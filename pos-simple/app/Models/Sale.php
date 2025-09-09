<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'user_id','customer_id','subtotal','discount_total','tax_total','total','status','notes'
    ];

    public function items(){ return $this->hasMany(SaleItem::class); }
    public function payments(){ return $this->hasMany(Payment::class); }
    public function user(){ return $this->belongsTo(User::class); }
    public function customer(){ return $this->belongsTo(Customer::class); }
}
