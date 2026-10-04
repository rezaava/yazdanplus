<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Datis2Order extends Model
{
    protected $table = 'datis2_orders';

    protected $fillable = [
        'name',
        'family',
        'mobile',
        'meli_code',
        'price',
        'final_price',
        'status',
    ];

    // رابطه با محصولات سفارش
    public function products(): HasMany
    {
        return $this->hasMany(Datis2ProductOrder::class, 'order_id');
    }

    // مجموع تعداد محصولات
    public function getTotalItemsAttribute(): int
    {
        return $this->products->sum('num');
    }

    // مجموع مبلغ با تخفیف
    public function getTotalPriceAttribute(): int
    {
        $total = 0;
        foreach ($this->products as $item) {
            $product = $item->product;
            if ($product) {
                $price = $product->price;
                if ($product->off_percent > 0) {
                    $price = $price - (($price * $product->off_percent) / 100);
                }
                $total += $price * $item->num;
            }
        }
        return $total;
    }
}