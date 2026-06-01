<?php

namespace App\Models;

use App\Models\BankAccount;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use App\Models\Transaction;
//use App\Models\Order;
//use App\Models\BankAccount;

class Shop extends Model
{
    use HasFactory;

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'shop_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function marketer()
    {
        return $this->belongsTo(User::class, 'refferer_id');
    }


    public function order()
    {
        return $this->hasMany(Order::class, 'order_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_shops');
    }

    public function bankaccount()
    {
        return $this->hasMany(BankAccount::class, 'bankaccount_id');
    }


    public function cover()
    {
        return $this->belongsTo(Images::class, 'cover_id');
    }

    public function icon()
    {
        return $this->belongsTo(Images::class, 'icon_id');
    }

    public function shop()
    {
        return $this->hasMany(Shop::class, 'shop_id');
    }


    public static function getOwned($userId, $addMarketedShops = false)
    {
        $finalShops = array();

        $shopsOwned = Shop::where('user_id', $userId)->get();
        $shopsEmployed = Shop::whereIn('id', ShopUser::where('user_id', $userId)->pluck('shop_id'))->get();
        $finalShops = $shopsOwned->merge($shopsEmployed);

        if ($addMarketedShops) {
            $shopsMarketed = Shop::where('refferer_id', $userId)->get();
            $finalShops = $finalShops->merge($shopsMarketed);
        }

        return $finalShops;
    }
}
