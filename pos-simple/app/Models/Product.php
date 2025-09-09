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
        'image',
        'price',
        'cost_price',
        'stock',
        'min_stock',
        'max_stock',
        'supplier',
        'status',
        'category_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'status' => 'boolean',
    ];

    /* ---------- Relationships ---------- */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /* ---------- Helpers ---------- */
    public function inStock(): bool
    {
        return (int) $this->stock > 0 && $this->status === true;
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    public function getMarginAttribute(): float
    {
        if (!$this->cost_price || $this->cost_price <= 0) {
            return 0;
        }
        return (($this->price - $this->cost_price) / $this->cost_price) * 100;
    }

    public function getProfitAttribute(): float
    {
        return $this->price - ($this->cost_price ?? 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock <= min_stock');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
