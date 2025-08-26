<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use App\Models\Product;

class Cart extends Component
{
    public $search = '';
    public $items = []; // product_id => ['name','price','qty','discount']

    public function add($productId)
    {
        $p = Product::where('status', true)->findOrFail($productId);
        $this->items[$productId] = $this->items[$productId] ?? [
            'name' => $p->name, 'price' => (float)$p->price, 'qty' => 0, 'discount' => 0
        ];
        $this->items[$productId]['qty']++;
    }

    public function updateQty($productId, $qty)
    {
        if (!isset($this->items[$productId])) return;
        $this->items[$productId]['qty'] = max(1, (int)$qty);
    }

    public function remove($productId){ unset($this->items[$productId]); }

    public function totals(): array
    {
        $subtotal = 0;
        foreach ($this->items as $row) {
            $line = ($row['price'] * $row['qty']) - ($row['discount'] ?? 0);
            $subtotal += $line;
        }
        $tax = round($subtotal * 0.00, 2); // hook to settings later
        $total = $subtotal + $tax;
        return compact('subtotal','tax','total');
    }

    public function render()
    {
        $products = strlen($this->search) > 0
            ? Product::where('status', true)
                ->where(function($q){
                    $q->where('name','like',"%{$this->search}%")
                      ->orWhere('sku','like',"%{$this->search}%")
                      ->orWhere('barcode','like',"%{$this->search}%");
                })
                ->limit(10)->get()
            : collect();

        $totals = $this->totals();
        return view('livewire.pos.cart', compact('products','totals'));
    }
}
