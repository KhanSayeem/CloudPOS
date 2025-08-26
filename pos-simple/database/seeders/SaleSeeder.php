<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Sale, SaleItem, Payment, Product, User};

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $cashier = User::whereHas('roles', fn($q) => $q->where('name', 'Cashier'))->first();
        if (!$cashier) return;

        for ($i = 0; $i < 5; $i++) {
            $picked = Product::inRandomOrder()->take(3)->get();
            if ($picked->isEmpty()) break;

            $sale = Sale::create([
                'user_id' => $cashier->id,
                'subtotal' => 0,
                'discount_total' => 0,
                'tax_total' => 0,
                'total' => 0,
                'status' => 'completed',
            ]);

            $subtotal = 0;

            foreach ($picked as $p) {
                $qty = rand(1, 3);
                $line = $p->price * $qty;
                $subtotal += $line;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $p->id,
                    'qty' => $qty,
                    'unit_price' => $p->price,
                    'discount' => 0,
                    'line_total' => $line,
                ]);

                // keep stock consistent
                $p->decrement('stock', min($qty, $p->stock));
            }

            $sale->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);

            Payment::create([
                'sale_id' => $sale->id,
                'method' => 'cash',
                'amount' => $subtotal,
            ]);
        }
    }
}
