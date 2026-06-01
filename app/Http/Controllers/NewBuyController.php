<?php

namespace App\Http\Controllers;

use App\Models\Condition;
use App\Models\Order;
use App\Models\Shop;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Morilog\Jalali\Jalalian;

class NewBuyController extends Controller
{
    public function new_buy(){
        $user=Auth::user();
        $shop=Shop::where('user_id',$user->id)->first();
        return view('admin.buy',compact('shop','user'));
    }
    public function post_new_buy(Request $request, $id)
{
    $shop = Shop::find($id);
    $mobile = $request->mobile;
    $karbar = User::where('mobile', $mobile)->first();
    $price = $request->price;
    
    // چک کردن وجود کاربر
    if(!$karbar) {
        return response()->json([
            'success' => false,
            'message' => 'کاربری با این شماره موبایل یافت نشد'
        ], 404);
    }
    
    // چک کردن موجودی کیف پول
    if($price > $karbar->wallet) {
        return response()->json([
            'success' => false,
            'message' => 'موجودی کیف پول کاربر کافی نیست',
        ], 400);
    }
    
    // اگر همه چی اوکی بود
    // کم کردن از کیف پول
   
    $condition = Condition::where('shop_id', $shop->id)->first();
    $order = new Order();
    $order->user_id = $karbar->id;
    $order->seller_id = Auth::user()->id;
    $order->condition_id = $condition->id;
    $order->shop_id = $shop->id;
    $order->final_price = $price;
    $order->price = $price * (100 - $condition->advance_payment)/100;
    $order->off = $shop->off;
    $order->status = '7';
    $order->save();


    $fee = $condition->percent;
    $pishpardakht = $condition->advance_payment * $order->final_price / 100;
    $flag = $order->final_price;
    $priceAll = ceil($order->price + ($fee / 100 * ($order->price ) * ($condition->month + 1)));
    $payAll = ceil(($priceAll - $pishpardakht));
    $baghimande = $priceAll - $pishpardakht;
    $payMonth = ceil(($priceAll) / $condition->month);
    // $payValue = $order->getPayValue();
    // $payAll = ceil(($payValue - $pishpardakht));


    if ($condition->advance_payment == 0) {

       
            $code = rand(1111, 9999);
            $order->sms_code = $code;
            $order->status = 7;
            $order->save();
            $tarikh = Jalalian::now();
            for ($i = 0; $i < $condition->month; $i++) {
                $tarikh = $tarikh->addMonths(1); // اضافه کردن ماه شمسی
                $transaction = new Transaction();
                $transaction->value = $payMonth;
                $transaction->order_id = $order->id;
                $transaction->user_id = $karbar->id;
                $transaction->shop_id = $order->shop_id;
                $transaction->type = '15';
                $transaction->fee = $payMonth;
                // تبدیل به Carbon برای ذخیره در دیتابیس
                $transaction->tarikh_ghest = $tarikh->toCarbon();
                $transaction->save();
            }

            $matn='کد تایید خرید یزدان پلاس از فروشگاه '.$shop->name.' به مبلغ '.number_format($order->price).'ریال:
                '. $code;
                SmsController::sendSms(
                    $karbar,
                    SmsTypes::LOGIN_VERIFY_CODE,
                    $matn,
                    $karbar->mobile
                );
        
    } else {
       
        $code = rand(1111, 9999);
        $order->sms_code = $code;
        $order->status = 7;
        $order->save();
        $tarikh = Jalalian::now();
        for ($i = 0; $i < $condition->month; $i++) {
            $tarikh = $tarikh->addMonths(1); // اضافه کردن ماه شمسی
            $transaction = new Transaction();
            $transaction->value = $payMonth;
            $transaction->order_id = $order->id;
            $transaction->user_id = $karbar->id;
            $transaction->shop_id = $order->shop_id;
            $transaction->type = '15';
            $transaction->fee = $payMonth;
            // تبدیل به Carbon برای ذخیره در دیتابیس
            $transaction->tarikh_ghest = $tarikh->toCarbon();
            $transaction->save();
        }
        
                $matn='کد تایید خرید یزدان پلاس از فروشگاه '.$shop->name.' به مبلغ '.number_format($order->price).'ریال:
                    '. $code;
                    SmsController::sendSms(
                        $karbar,
                        SmsTypes::LOGIN_VERIFY_CODE,
                        $matn,
                        $karbar->mobile
                    );

    }

    
    return response()->json([
        'success' => true,
        'order_id' => $order->id,     
        'karbar' => $karbar,
        'price' => $price,
        // 'created_at' => $purchase->created_at->format('Y/m/d H:i')
    ]);
}


public function verifyCode(Request $request, $orderId)
{
    $order = Order::find($orderId);
    $user =User::find($order->user_id);

    if(!$order) {
        return response()->json([
            'success' => false,
            'message' => 'سفارش یافت نشد'
        ], 404);
    }
    
    if($order->sms_code != $request->code) {
        return response()->json([
            'success' => false,
            'message' => 'کد تایید اشتباه است'
        ], 422);
    }
    
    $transactions_value = Transaction::where('order_id', $order->id)->sum('value');
    
    if($transactions_value > $user->wallet) {
        return response()->json([
            'success' => false,
            'message' => 'عدم موجودی کافی'
        ], 422);
    }
    
    DB::beginTransaction();
    try {
        $user->wallet -= $transactions_value;
        $user->save();
        $order->status = 2;
        $order->save();
        DB::commit();
        
        // $admin = User::find(6);
        // $matn = 'سفارش ' . $order->id . ' تایید شد';
        // SmsController::sendSms($admin, SmsTypes::LOGIN_VERIFY_CODE, $matn, $admin->mobile);
        
        return response()->json([
            'success' => true,
            'message' => 'کد تایید شد.'
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'خطا در ثبت نهایی'
        ], 500);
    }
}
}
