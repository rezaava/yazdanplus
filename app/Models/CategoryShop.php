<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class CategoryShop extends Model
{
    use HasFactory;
    public function category_shop()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
