<?php

namespace App\Models;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Order;
use App\Models\Images;

class Transaction extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'tarikh_ghest' => 'datetime',
    ];
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'tasvie_transaction_id');
    }
    public function receipts()
    {
        return $this->belongsTo(Images::class, 'receipt');
    }

    public function gettype()
    {
        switch ($this->type) {
            case 1:
                return " شارژ کیف پول";
            case 2:
                return "خرید";
            case 3:
                return "واریز به فروشگاه";
            case 4:
                return "پورسانت";
            case 5:
                return "تسویه فروشگاه";
            case 6:
                return "واریز از بانک";
            case 7:
                return "کوین خرید کاربر";
            case 8:
                return "کوین خرید از فروشگاه";
            case 9:
                return "کوین ورود به سیستم";
            case 10:
                return "کوین معرف کاربر";
            case 11:
                return "کوین کاربر معرفی شده";
            case 12:
                return "کوین اولین خرید";
            case 13:
                return "شارژ کیف پول (ادمین)";
            case 14:
                return "تایید پیش پرداخت";
            case 15:
                return "قسط";
            case 16:
                return "رد پیش پرداخت";
            case 17:
                return "رد قسط";
            case 18:
                return "قسط پرداخت شده";
            case 19:
                return "قسط پرداخت نشده";
        }
    }

    public function getTypeClass()
    {
        switch ($this->type) {
            case 1:
                return "text-body";
            case 2:
                return "text-success";
            case 3:
                return "text-info";
            case 4:
                return "text-info";
            case 5:
                return "text-info";
            case 6:
                return "text-info";
            case 7:
                return "text-gold";
            case 8:
                return "text-gold";
            case 9:
                return "text-gold";
            case 10:
                return "text-gold";
            case 11:
                return "text-gold";
            case 12:
                return "text-gold";
            case 13:
                return "text-body";
        }
    }

    public function getTasvieType()
    {
        switch ($this->tasvie_type) {
            case 1:
                return "دستگاه";
            case 2:
                return "کارت به کارت";
            case 3:
                return "همراه بانک";
            case 4:
                return "سایر";
        }
    }


    // public function getFormattedValue()
    // {
    //     return number_format($this->value).''.;
    // }


    /*
    public static function chargeWallet($value) {
        $transaction = new transaction();
        $transaction->user_id = $user->id;
        $transaction->value = $ghabele_pardakht;
        $transaction->type = '1';
        $transaction->save();
    }

    public static function chargeWallet($value) {
        $transaction = new transaction {
            user_id = $user->id,
            value = $ghabele_pardakht,
            type = '1',
        };
        $transaction->save();
    }
    */
    public function ShowItem()
    {
        switch ($this->type) {
            case 7:
                return '<h6 class="mb-0 order-success"> کویناتو خرید شما <span class="rial" style="color:#212529;font-size:16px;">' . $this->value . '+ کویناتو</span></h6>';
            case 8:
                return '<h6 class="mb-0 order-success"> کویناتو خرید از فروشگاه <span class="rial"style="color:#212529;font-size:16px;">' . $this->value . '+ کویناتو</span></h6>';
            case 9:
                return '<h6 class="mb-0 order-success">کویناتو ورود به سیستم <span class="rial"style="color:#212529;font-size:16px;">' . $this->value . '+ کویناتو</span></h6>';
            case 10:
                return '<h6 class="mb-0 order-success"> کویناتو ثبت معرف <span class="rial" style="color:#212529;font-size:16px;">' . $this->value . '+ کویناتو</span></h6>';
            case 11:
                return '<h6 class="mb-0 order-success"> کویناتو ثبت زیر مجموعه<span class="rial"style="color:#212529;font-size:16px;">' . $this->value . '+ کویناتو</span></h6>';
            case 12:
                return '<h6 class="mb-0 order-success" > کویناتو اولین خرید شما <span class="rial order-success "style="color:#212529;font-size:16px;">' . $this->value . '+ کویناتو</span></h6>';
        }
    }
}
