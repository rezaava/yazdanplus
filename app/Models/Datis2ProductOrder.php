<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Datis2ProductOrder extends Model
{
    protected $table = 'datis2_product_orders';

    protected $fillable = [
        'product_id',
        'order_id',
        'num',
    ];

    // رابطه با سفارش
    public function order(): BelongsTo
    {
        return $this->belongsTo(Datis2Order::class, 'order_id');
    }

    // رابطه با محصول
    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    // قیمت واحد با تخفیف
    public function getUnitPriceAttribute(): float
    {
        $product = $this->product;
        if (!$product) {
            return 0;
        }

        $price = $product->price;
        if ($product->off_percent > 0) {
            $price = $price - (($price * $product->off_percent) / 100);
        }
        return $price;
    }

    // قیمت کل این محصول (تعداد * قیمت واحد)
    public function getTotalPriceAttribute(): float
    {
        return $this->unit_price * $this->num;
    }
}