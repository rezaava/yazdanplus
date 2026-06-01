<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;
use App\Models\Shop;
use App\Models\User;
use App\Models\Off;
use Illuminate\support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes; // اضافه کردن این خط

class Order extends Model
{
    use SoftDeletes; // استفاده از Trait
    use HasFactory;

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'transaction_id');
    }

    public function shop_order()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
    public function offs()
    {
        return $this->belongsTo(Off::class, 'off_id');
    }


    public function user()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function shop()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    //
    public function getuservalue()
    {
        return floor($this->price - (($this->price * $this->user_off) / 100));
    }

    public function getshopvalue()
    {
        return floor($this->price - (($this->price * $this->off) / 100));
    }
    //
    public function getProfit()
    {
        return floor(($this->price / 100) * $this->user_off);
    }

    public function getPoorsant()
    {
        return floor(($this->price / 100)); // poorsant is 1% of order price
    }

    public function getOffCode()
    {
        return $this->off_id ? (Off::find($this->off_id)->code ?: null) : null;
    }

    public function getOffValue()
    {
        if ($this->off_id == null)
            return 0;

        $off = Off::find($this->off_id);
        if (!$off)
            return 0;

        return $off->getOffEffectForOrder($this);
    }

    public function getPayValue($calculateOff = true)
    {
        
        return floor(
            $this->price - $this->getProfit() -
            (($calculateOff) ? $this->getOffValue() : 0)
        );
    }


    public function getTotalProfit()
    {
        return $this->price - $this->getPayValue();
    }



    // public function getShopValue()
    // {
    //     return floor($this->price - $this->getProfit());
    // }

    public function canPay()
    {
        return $this->status == 1 || $this->status == 3;
    }


    public function getmobileuser()
    {
        $user_auth = Auth::user();
        $user_order = User::find($this->user_id);
        if ($user_auth->hasRole('admin')) {
            return $user_order->mobile;
        } else {
            return $this->hideMobile($user_order->mobile);
        }
    }


    public function hideMobile($mobile)
    {
        return substr($mobile, 0, 4) . '***' . substr($mobile, 7, 10);
    }


    public function getStatus()
    {
        switch ($this->status) {
            case 1:
                return "منتظر پرداخت";
            case 2:
                return "پرداخت موفق";
            case 3:
                return "پرداخت ناموفق";
            case 4:
                return "پرداخت با کارت";
            case 5:
                return "لغو شده";
            case 6:
                return "شارژ کیف پول";
        }
    }

    public function getStatusClass()
    {
        switch ($this->status) {
            case 1:
                return "text-warning";
            case 2:
                return "text-success";
            case 3:
                return "text-error";
            case 4:
                return "text-success";
            case 5:
                return "text-muted";
            case 6:
                return "text-success";
        }
    }
    public function getStatus_tasvie()
    {
        if($this->status == 2 || $this->status == 4)
            switch ($this->status_tasvie) {
                case 0:
                    return "تسویه نشده";
                case 1:
                    return "تسویه توسط درگاه";
                case 2:
                    return "تسویه توسط سیستم";
                case 3:
                    return "تسویه توسط ادمین";
            }
        else
            return "---";
    }
    public function getStatus_tasvieClass()
    {
        switch ($this->status_tasvie) {
            case 0:
                return "danger";
            case 1:
                return "success";
            case 8:
            case 2:
                return "success";
            case 3:
                return "success";
        }
    }

    //blade
    public  function  ShowClassTopIconStatus()
    {
        switch ($this->status) {
            case 1:
                return "noti-icon-warning";
            case 8:
            case 2:
                return "noti-icon-success";
            case 3:
                return "noti-icon";
            case 5:
                return "noti-icon-cancel";
        }
    }
    public  function  ShowClassBodyIconStatus()
    {
        switch ($this->status) {
            case 1:
                return "noti-icon-warning";
            case 8:
            case 2:
                return "noti-icon-success";
            case 3:
                return "noti-icon";
            case 5:
                return "noti-icon-cancel";
        }
    }
    public  function  ShowIconStatus()
    {
        switch ($this->status) {
            case 1:
                return "fa-solid fa-hourglass";
            case 8:
            case 2:
                return "fa-solid fa-check";
            case 3:
                return "fa-solid fa-warning";
            case 5:
                return "fa-solid fa-xmark";
        }
    }
    public function ShowTextStatus()
    {
        switch ($this->status) {
            case 1:
                return "  منتظر پرداخت از";
            case 8:
            case 2:
                return "پرداخت موفق";
            case 3:
                return "پرداخت ناموفق";
            case 4:
                return " پرداخت با کارت";
            case 5:
                return "کنسل شده";
        }
    }
    public function ShowClassTextStatus()
    {
        switch ($this->status) {
            case 1:
                return "order-Warning";
            case 2:
            case 8:
                return "order-success";
            case 3:
                return "order-danger";
            case 5:
                return "order-cancel";
        }
    }
    public function link_payment_show()
    {
        if ($this->status == 1 || $this->status == 2){
            switch ($this->status) {
                case 1:
                    return "/payment_show/".$this->id;
                case 2:
                    return "/payment_show/".$this->id;
            }
        }else{
            return "#";
        }
    }
    public function CheckIconStatus()
    {
        $show = '<span class="'.$this->ShowClassBodyIconStatus().'"><i class="text-light '.$this->ShowIconStatus().' " style="color: white;"></i></span>';

        switch ($this->status){
            case 1:
                return $show;
            case 2:
            case 8:
                return $show;
            case 3:
                return $show;
            case 5:
                return $show;
        }
    }

}
