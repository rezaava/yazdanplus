<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\User;
use App\Models\Transaction;

class Images extends Model
{
    use HasFactory;

    public function shopcover()
    {
        return $this->hasOne(Shop::class, 'cover_id');
    }

    public function shopicon()
    {
        return $this->hasOne(Shop::class, 'icon_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
    public function profile(){
        return $this->belongsTo(User::class, 'profpic_id');
    }
    public function icon(){
        return $this->belongsTo(User::class, 'icon_id');
    }
    public function receipts()
    {
        return $this->hasMany(Transaction::class, 'receipt');
    }
}
