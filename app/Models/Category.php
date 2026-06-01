<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\CategoryShop;


class Category extends Model
{
    use HasFactory;

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'category_shops');
    }
    public function icon()
    {
        return $this->belongsTo(Images::class, 'icon_id');
    }
    public function category_shop()
    {
        return $this->hasMany(CategoryShop::class, 'category_id');
    }
}
