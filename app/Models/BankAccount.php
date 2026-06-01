<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\User;

class BankAccount extends Model
{
    use HasFactory;

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bankAccount()
    {
        return $this->hasMany(Transaction::class, 'bank_account_id');
    }

    public function toString()
    {
        return $this->shaba . '<br>به نام: ' . $this->owner . '<br>بانک: ' . $this->bank_name;
    }
}
