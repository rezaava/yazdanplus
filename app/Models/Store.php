<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\BankAccount;

class Store extends Model
{
    use HasFactory;
    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'store_id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'order_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_stores');
    }

    public function bankaccount()
    {
        return $this->hasMany(BankAccount::class, 'bankaccount_id');
    }

    public function cover()
    {
        return $this->hasMany(Images::class);
    }
}
