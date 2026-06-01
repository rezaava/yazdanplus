<?php

namespace App\Models;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopUser extends Model
{
    use HasFactory;
    public function employee_user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function employee_shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
