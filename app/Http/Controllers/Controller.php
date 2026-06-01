<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use App\Models\store;
use App\Models\Order;
use App\Models\sms;
use App\Models\Transaction;
use Illuminate\support\Facades\Auth;
use Hekmatinasser\Verta\Verta;
use SoapClient;


class Controller extends BaseController
{
    private $number = 10000000242564;

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function auth_user()
    {
        if (auth::user()) {
            $user = auth::user();
        } else {
            $user = null;
        }
        return $user;
    }

    public function referrer()
    {
        $random = "abcdefghijklmnopqrstuvwxyz1234567890";
        do {
            $get = substr(str_shuffle($random), 0, 4);
            $find_user = User::where('referrer', $get)->first();
        } while ($find_user);

        return $get;
    }

    public function code(): int
    {
        // $chars = "1234567890";
        //  return substr( str_shuffle($chars), 0, 6);
        return mt_rand(1000, 9999);
    }

    public function convertFa2EnNum($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

        $num = range(0, 9);
        return str_replace($persian, $num, $string);
    }

    public function convertAr2EnNum($string)
    {
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $num = range(0, 9);
        return str_replace($arabic, $num, $string);
    }

    public function convert2EnNum($string)
    {
        $result = $this->convertFa2EnNum($string);
        $result = $this->convertAr2EnNum($result);
        return $result;
    }

    public function coinlogin($id)
    {
        $transaction = new Transaction();
        $transaction->value = '250';
        $transaction->type = '9';
        $transaction->user_id = $id;
        $transaction->save();
    }

    public function coinmoaref($id)
    {
        $transaction = new Transaction();
        $transaction->value = '200';
        $transaction->type = '10';
        $transaction->user_id = $id;
        $transaction->save();
    }

    public function coinmoarefi($id)
    {
        $transaction = new Transaction();
        $transaction->value = '200';
        $transaction->type = '11';
        $transaction->user_id = $id;
        $transaction->save();
    }

    public function coinkhariduser($user_id, $value, $trans_id)
    {
        $transaction = new Transaction();
        $transaction->value = $value;
        $transaction->type = '7';
        $transaction->user_id = $user_id;
        $transaction->transaction_id = $trans_id;
        $transaction->save();
    }

    public function coinkharidazshop($shop_id, $value, $trans_id)
    {
        $transaction = new Transaction();
        $transaction->value = $value;
        $transaction->type = '8';
        $transaction->shop_id = $shop_id;
        $transaction->transaction_id = $trans_id;
        $transaction->save();
    }

    public function coinavlinkharid($id, $trans_id)
    {
        $transaction = new Transaction();
        $transaction->value += 1000;
        $transaction->type = '12';
        $transaction->user_id = $id;
        $transaction->transaction_id = $trans_id;
        $transaction->save();
    }

    public function cointomoney($value)
    {
        return $value * 500;
    }

    public function moneytocoin($value)
    {
        return $value / 500;
    }

    public function convertToPersianTime($time)
    {
        $vertaTime = new Verta($time);
        return $vertaTime->format('d F Y - H:i');
    }

    public function convertToPersianTimeadmin($time)
    {
        $vertaTime = new Verta($time);
        return $vertaTime->format('H:i  Y/m/d');
    }
    
    public function convertToPersianTimeadmin2($time)
    {
        $vertaTime = new Verta($time);
        return $vertaTime->format('Y/m/d');
    }

    public function hideMobile($mobile, $auth = null)
    {
        if ($auth == null) {
            $auth = Auth::user();
        }

        $currentUrl = url()->current(); // یا request()->url()
        if ($currentUrl === url('/drhadizade')) { // مقایسه با آدرس مورد نظر
            return $mobile;
        } elseif ($auth->hasRole('admin'))
            return $mobile;
        else
            return substr($mobile, 0, 4) . '***' . substr($mobile, 7, 10);
    }

    public function correctPrice($value)
    {
        $charsToRemove = array(",", "-", "،");
        return str_replace($charsToRemove, "", $value);
    }

    function convert($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }

    function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
