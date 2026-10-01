<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number','customer_name','phone','email','address',
        'subtotal','shipping_cost','discount','total','coupon_code','status','notes'
    ];

    protected $casts = [
        'subtotal' => 'integer','shipping_cost' => 'integer','discount' => 'integer','total' => 'integer'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
