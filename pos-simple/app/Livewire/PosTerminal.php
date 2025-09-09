<?php

namespace App\Livewire;

use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PosTerminal extends Component
{
    use WithPagination;

    public string $q = '';
    public string $code = '';
    public int $qty = 1;
    public string $method = 'cash';

    // Pricing config
    public float $taxRate = 0.07; // 7%
    public float $shippingFlat = 5.00; // flat shipping below threshold
    public float $freeShippingThreshold = 100.00;
    public float $minOrderThreshold = 50.00; // for readiness indicator

    // Promo code state
    public string $promoCode = '';
    public ?array $appliedPromo = null; // ['code' => 'SAVE10', 'type' => 'percent'|'amount'|'shipping', 'value' => 10]

    protected $queryString = [
        'q' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->qty = 1;
    }

    public function updatingQ(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::active()
            ->when($this->q, function ($qry) {
                $q = $this->q;
                $qry->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%")
                        ->orWhere('barcode', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->paginate(12);

        $cart = $this->cart();
        $totals = $this->totals($cart);

        return view('livewire.pos-terminal', compact('products', 'cart', 'totals'));
    }

    public function addByCode(): void
    {
        $code = trim($this->code);
        if ($code === '') return;
        $product = Product::where('sku', $code)->orWhere('barcode', $code)->first();
        if (!$product) {
            $this->dispatch('toast', type: 'error', message: 'Product not found');
            return;
        }
        $this->addProduct($product->id, $this->qty);
        $this->code = '';
        $this->qty = 1;
    }

    public function addProduct(int $productId, int $qty = 1): void
    {
        $product = Product::find($productId);
        if (!$product || !$product->status) return;

        $cart = $this->cart();
        $line = $cart[$productId] ?? [
            'id' => $product->id,
            'sku' => $product->sku,
            'name' => $product->name,
            'price' => (float)$product->price,
            'qty' => 0,
            'discount' => 0,
            'stock' => (int)$product->stock,
        ];
        $line['qty'] = min($line['qty'] + max(1, $qty), (int)$product->stock);
        $line['stock'] = (int)$product->stock;
        $line['line_total'] = round($line['qty'] * $line['price'] - $line['discount'], 2);
        $cart[$productId] = $line;
        $this->saveCart($cart);
    }

    public function setQty(int $productId, int $qty): void
    {
        $cart = $this->cart();
        if (!isset($cart[$productId])) return;
        $product = Product::find($productId);
        if (!$product) return;
        $qty = max(1, min($qty, (int)$product->stock));
        $cart[$productId]['qty'] = $qty;
        $cart[$productId]['stock'] = (int)$product->stock;
        $cart[$productId]['line_total'] = round($qty * $cart[$productId]['price'] - ($cart[$productId]['discount'] ?? 0), 2);
        $this->saveCart($cart);
    }

    public function increment(int $productId): void
    {
        $cart = $this->cart();
        if (!isset($cart[$productId])) return;
        $this->setQty($productId, $cart[$productId]['qty'] + 1);
    }

    public function decrement(int $productId): void
    {
        $cart = $this->cart();
        if (!isset($cart[$productId])) return;
        $this->setQty($productId, max(1, $cart[$productId]['qty'] - 1));
    }

    public function remove(int $productId): void
    {
        $cart = $this->cart();
        unset($cart[$productId]);
        $this->saveCart($cart);
    }

    public function clear(): void
    {
        $this->saveCart([]);
        $this->appliedPromo = null;
        $this->promoCode = '';
    }

    public function checkout()
    {
        $cart = $this->cart();
        if (empty($cart)) return;

        // Validate stock
        foreach ($cart as $item) {
            $p = Product::find($item['id']);
            if (!$p || $item['qty'] > $p->stock) {
                $this->dispatch('toast', type: 'error', message: 'Insufficient stock for '.$item['name']);
                return;
            }
        }

        $totals = $this->totals($cart);
        $method = $this->method ?: 'cash';

        $saleId = DB::transaction(function () use ($cart, $totals, $method) {
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'customer_id' => null,
                'subtotal' => $totals['subtotal'],
                'discount_total' => 0,
                'tax_total' => 0,
                'total' => $totals['total'],
                'status' => 'completed',
            ]);

            foreach ($cart as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['price'],
                    'discount' => $item['discount'] ?? 0,
                    'line_total' => $item['line_total'],
                ]);
                Product::where('id', $item['id'])->decrement('stock', $item['qty']);
            }

            Payment::create([
                'sale_id' => $sale->id,
                'method' => $method,
                'amount' => $totals['total'],
            ]);

            return $sale->id;
        });

        $this->saveCart([]);
        $this->appliedPromo = null;
        $this->promoCode = '';
        return redirect()->route('sales.show', $saleId);
    }

    public function applyPromo(): void
    {
        $code = strtoupper(trim($this->promoCode));
        if ($code === '') { $this->appliedPromo = null; return; }

        // Simple demo promo set
        $catalog = [
            'SAVE10' => ['type' => 'percent', 'value' => 10],      // 10% off
            'TAKE5'  => ['type' => 'amount',  'value' => 5],       // $5 off
            'FREESHIP' => ['type' => 'shipping', 'value' => 0],    // free shipping
        ];

        if (!isset($catalog[$code])) {
            $this->appliedPromo = null;
            $this->dispatch('toast', type: 'error', message: 'Invalid promo code');
            return;
        }
        $this->appliedPromo = array_merge(['code' => $code], $catalog[$code]);
        $this->dispatch('toast', type: 'success', message: 'Promo applied');
    }

    private function cart(): array
    {
        return session()->get('cart', []);
    }

    private function saveCart(array $cart): void
    {
        session()->put('cart', $cart);
        $this->dispatch('$refresh');
    }

    private function totals(array $cart): array
    {
        $subtotal = 0.0;
        foreach ($cart as $item) {
            $subtotal += (float) ($item['line_total'] ?? ($item['qty'] * $item['price']));
        }
        $discount = 0.0;

        // Promo discount
        if ($this->appliedPromo) {
            if ($this->appliedPromo['type'] === 'percent') {
                $discount += round($subtotal * ($this->appliedPromo['value'] / 100), 2);
            } elseif ($this->appliedPromo['type'] === 'amount') {
                $discount += min($subtotal, (float) $this->appliedPromo['value']);
            }
        }

        // Shipping
        $shipping = 0.0;
        if ($subtotal > 0 && $subtotal < $this->freeShippingThreshold) {
            $shipping = $this->shippingFlat;
        }
        if ($this->appliedPromo && $this->appliedPromo['type'] === 'shipping') {
            $shipping = 0.0;
        }

        // Tax applied to subtotal - discount (not including shipping)
        $taxable = max(0, $subtotal - $discount);
        $tax = round($taxable * $this->taxRate, 2);

        $total = round($taxable + $tax + $shipping, 2);
        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'shipping' => round($shipping, 2),
            'tax' => $tax,
            'total' => $total,
            'progress' => $this->minOrderThreshold > 0 ? min(100, (int) floor(($subtotal / $this->minOrderThreshold) * 100)) : 100,
        ];
    }
}
