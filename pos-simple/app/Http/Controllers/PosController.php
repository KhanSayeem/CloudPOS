<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->cart($request);
        $totals = $this->totals($cart);
        $q = (string) $request->get('q', '');
        $products = Product::active()
            ->when($q, fn($qry) => $qry->where('name', 'like', "%$q%")
                                       ->orWhere('sku', 'like', "%$q%")
                                       ->orWhere('barcode', 'like', "%$q%"))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('pos.index', compact('cart', 'totals', 'products', 'q'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'code' => 'nullable|string',
            'product_id' => 'nullable|integer|exists:products,id',
            'qty' => 'nullable|integer|min:1',
        ]);

        $qty = max(1, (int)($data['qty'] ?? 1));

        $product = null;
        if (!empty($data['product_id'])) {
            $product = Product::find($data['product_id']);
        } elseif (!empty($data['code'])) {
            $code = trim($data['code']);
            $product = Product::where('sku', $code)->orWhere('barcode', $code)->first();
        }

        if (!$product) {
            return back()->with('error', 'Product not found.');
        }

        if (!$product->status) {
            return back()->with('error', 'Product is inactive.');
        }

        $cart = $this->cart($request);
        $line = $cart[$product->id] ?? [
            'id' => $product->id,
            'sku' => $product->sku,
            'name' => $product->name,
            'price' => (float) $product->price,
            'qty' => 0,
            'discount' => 0,
            'stock' => (int) $product->stock,
        ];
        $line['qty'] += $qty;
        $line['stock'] = (int) $product->stock;

        // Enforce stock limit
        if ($line['qty'] > $product->stock) {
            $line['qty'] = (int) $product->stock;
        }
        $line['line_total'] = round($line['qty'] * $line['price'] - $line['discount'], 2);
        $cart[$product->id] = $line;
        $this->saveCart($request, $cart);

        return redirect()->route('pos.index');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);

        $cart = $this->cart($request);
        if (!isset($cart[$data['id']])) {
            return back();
        }

        $product = Product::find($data['id']);
        $qty = min($data['qty'], (int) $product->stock);
        $cart[$data['id']]['qty'] = $qty;
        $cart[$data['id']]['stock'] = (int) $product->stock;
        $cart[$data['id']]['line_total'] = round($qty * $cart[$data['id']]['price'] - ($cart[$data['id']]['discount'] ?? 0), 2);
        $this->saveCart($request, $cart);

        return redirect()->route('pos.index');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer',
        ]);
        $cart = $this->cart($request);
        unset($cart[$data['id']]);
        $this->saveCart($request, $cart);
        return redirect()->route('pos.index');
    }

    public function clear(Request $request)
    {
        $this->saveCart($request, []);
        return redirect()->route('pos.index');
    }

    public function checkout(Request $request)
    {
        $data = $request->validate([
            'method' => 'nullable|string',
        ]);

        $cart = $this->cart($request);
        if (empty($cart)) {
            return back()->with('error', 'Cart is empty.');
        }

        // Ensure stock availability
        $insufficient = [];
        foreach ($cart as $item) {
            $p = Product::find($item['id']);
            if (!$p || $item['qty'] > $p->stock) {
                $insufficient[] = $item['name'];
            }
        }
        if (!empty($insufficient)) {
            return back()->with('error', 'Insufficient stock for: '.implode(', ', $insufficient));
        }

        $totals = $this->totals($cart);
        $method = $data['method'] ?? 'cash';

        $saleId = DB::transaction(function () use ($request, $cart, $totals, $method) {
            $sale = Sale::create([
                'user_id' => $request->user()->id,
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
                'reference' => null,
                'notes' => null,
            ]);

            return $sale->id;
        });

        // Clear cart
        $this->saveCart($request, []);

        return redirect()->route('sales.show', $saleId)
            ->with('success', 'Sale completed.');
    }

    private function cart(Request $request): array
    {
        return $request->session()->get('cart', []);
    }

    private function saveCart(Request $request, array $cart): void
    {
        $request->session()->put('cart', $cart);
    }

    private function totals(array $cart): array
    {
        $subtotal = 0.0;
        foreach ($cart as $item) {
            $subtotal += (float) ($item['line_total'] ?? ($item['qty'] * $item['price']));
        }
        $discount = 0.0;
        $tax = 0.0;
        $total = round($subtotal - $discount + $tax, 2);
        return compact('subtotal', 'discount', 'tax', 'total');
    }
}
