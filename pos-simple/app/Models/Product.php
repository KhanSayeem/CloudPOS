<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // If your table is `products`, you can omit this.
    // protected $table = 'products';

    protected $fillable = [
        'sku',
        'barcode',
        'name',
        'description',
        'price',
        'stock',
        'status',
    ];

    protected $casts = [
        'price'  => 'decimal:2',
        'status' => 'boolean',
    ];

    /* ---------- Relationships (optional but useful) ---------- */
    // Example if you create SaleItem model:
    // public function saleItems()
    // {
    //     return $this->hasMany(SaleItem::class);
    // }

    /* ---------- Helpers ---------- */
    public function inStock(): bool
    {
        return (int) $this->stock > 0 && $this->status === true;
    }
}
