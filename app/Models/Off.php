<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\User;
use App\Models\Order;

class Off extends Model
{

    use HasFactory;


    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function orders()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }


    public function GetStatus()
    {
        switch ($this->status) {
            case 0:
                return 'غیرفعال';
            case 1:
                return 'فعال';
        }
    }

    public function GetStatusClass()
    {
        switch ($this->status) {
            case 0:
                return 'text-dark';
            case 1:
                return 'text-success';
        }
    }

    public function GetStatusUser()
    {
        switch ($this->user_status) {
            case 0:
                return 'عمومی';
            case 1:
                return 'خصوصی';
        }
    }

    public function GetStatusUserClass()
    {
        switch ($this->user_status) {
            case 1:
                return 'text-dark';
            case 0:
                return 'text-success';
        }
    }


    public function submitOff(Order $order, Off $off, User $user_i)
    {
        $offEffect = $off->getOffEffectForOrder($order);

        $off->qty--;
        if ($off->now_price == null) { // not used this off until now
            $off->now_price = 0;
        }
        $off->now_price += $offEffect;
        $off->save();

//        echo 'order' . $order . '<br><br><br>';
//        echo 'offEffect' . $offEffect . '<br><br><br>';
//        echo 'off' . $off . '<br><br><br>';

        if ($off->user_status == '1') { // todo : is private
//            echo 'is private' . '<br><br><br>';
            $off_user = Off_user::where('off_id', $off->id)
                ->where('user_id', $user_i->id)
                ->first();

            if ($off_user) {
                if ($off_user->max_price != null) {
                    if ($off_user->now_price == null) { // not used this off until now
                        $off_user->now_price = 0;
                    }

                    $off_user->now_price += $offEffect;
                    $off_user->save();
                }
//                echo 'off_user' . $off_user . '<br><br><br>';
            } else {
                // todo: show error
            }
        }
    }

    public function getOffEffectForOrder(Order $order)
    {
        if (
            ($this->price == null && $this->percent == null)
            || ($this->price != null && $this->percent != null)
        ) {
            return 0;
        }
        $offValue = 0;
        if ($this->price != null)
            $offValue = $this->price;
        if ($this->percent != null)
            $offValue = $order->price * $this->percent / 100;
        $orderPayValueWithoutOff = $order->getPayValue(false);
        $payValueAfterOff = max($orderPayValueWithoutOff - $offValue, 0);
        $offEffect = $orderPayValueWithoutOff - $payValueAfterOff;

        return $offEffect;
    }
}
