<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Images;
use App\Models\User;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Off;
use App\Models\sms;
use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Carbon;
use App\Models\BankAccount;
use App\Models\Condition;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Str;
use Redirect;
use SoapClient;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class BuyController extends Controller
{
    public function buy($slug_code)
    {
        $shop = Shop::where('slug_code', $slug_code)->first();
        if ($shop) {
            $slug_name = $shop->slug_name;
            $url = "$slug_code/$slug_name";
            return redirect("/buy/$url");
        } else {
            abort(404);
        }
    }

    public function buy1($slug_code, $slug_name = null)
    {
        $conditions = Condition::where('shop_id', $slug_code)->get();
        $shop = Shop::where('slug_code', $slug_code)->first();
        $user = $this->auth_user();
        if ($shop) {
            if ($user) {
                $profile = Images::where('id', $user->profpic_id)->first();
                $icon = Images::where('id', $shop->icon_id)->first();
                if ($profile == null) {
                    $profile == null;
                }
                if ($shop == null) {
                    $shop = null;
                }
                if ($icon == null) {
                    $icon = null;
                }
            } else {
                $icon = Images::where('id', $shop->icon_id)->first();
                if ($icon == null) {
                    $icon = null;
                }
                if ($shop == null) {
                    $shop = null;
                }
                $user = null;
                $profile = null;
            }
            return view('dashboard.shop.purchase', compact('shop', 'user', 'profile', 'icon', 'conditions'));
        } else {
            abort(404);
        }
    }
    public function payment_details(request $requ)
    {
        $users = Auth::user();
        $order = Order::where('id', $requ->order_id)->first();

        $chargeWalletTransaction = Transaction::where('order_id', $requ->order_id)
            ->where('type', '1')
            ->first();

        $payValue = $order->getPayValue();

        $payFromBank = $chargeWalletTransaction ? $chargeWalletTransaction->value : 0;

        $payFromWallet = $payValue - $payFromBank;

        if ($order) {
            if ($order->user_id == $users->id) {
                $profit = $order->getTotalProfit();
                $price_off = $order->getPayValue();
                $off = $order->user_off;
                $price = $order->price;
                $shop_buy = Shop::find($order->shop_id);
                $find_order = Order::where('status', '2')->where('id', $order->id)->first();
                $trans = Transaction::where('order_id', $find_order->id)->where('type', '2')->first();
                $time = $this->convertToPersianTime($order->created_at);

                return view('payment-details', compact('time', 'order', 'profit', 'price_off', 'payFromBank', 'off', 'price', 'payFromWallet', 'shop_buy'));
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function payment_show($order_id, $month)
    {
        $order = Order::where('id', $order_id)->first();
        $user = Auth::user();
        $wallet = $user->wallet;
        if ($order) {
            if ($user) {
                if ($order->user_id == $user->id) {
                    $shop = Shop::where('id', $order->shop_id)->first();
                    $condition = Condition::where('shop_id', $order->shop_id)->where('month', $month)->first();

                    $fee = $condition->percent;

                    $pishpardakht = $condition->advance_payment;

                    $flag = $order->final_price;
                    // return $fee/100 *( $order->price - $pishpardakht) *($shop->installments_number +1);
                    $priceAll = ceil($order->price + ($fee / 100 * ($order->price ) * ($condition->month + 1)));
                    
                    if ($priceAll < $pishpardakht) {
                        return redirect()->back()->withErrors(['price' => "مبلغ فعلی کمتر از مبلغ پیش پرداخت است پس نیاز به قسط بندی نیست"]);
                    }

                    $icon = Images::where('id', $shop->icon_id)->first();
                    $payAll = ceil(($priceAll - $condition->advance_payment));
                    $mablagh_pishpardakht = (($order->final_price *$pishpardakht)/100);
                    $baghimande = $priceAll - $mablagh_pishpardakht;
                    $payMonth = ceil(($priceAll) / $condition->month);

                    return view('payment', compact('order','fee','pishpardakht', 'baghimande', 'shop', 'payMonth', 'icon', 'wallet', 'payAll', 'condition'));
                } else {
                    abort(404);
                }
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }
    public function factor($slug_code, request $req)
    {


        if (Auth::user()) {
            $req->price = $this->convert2EnNum(strval($req->price));
            $data = array(
                "price" => $req->price,
            );
            $validator = Validator::make($data, [
                'price' => 'required|numeric|min:100000'
            ], [
                'price.required' => 'لطفا مبلغ را وارد کنید',
                'price.numeric' => 'لطفا مبلغ را به صورت عددی وارد کنید',
                'price.min' => 'مبلغ باید بیش از 100 هزار ریال باشد'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
            $user = Auth::user();
            $shop = Shop::where('slug_code', $slug_code)->first();
            $condition = Condition::where('shop_id', $shop->id)->where('month', $req->month)->first();
            $order = new Order();
            $order->user_id = $user->id;
            $order->condition_id = $condition->id;
            $order->shop_id = $shop->id;
            $order->final_price = $req->price;
            $order->price = $req->price * (100 - $condition->advance_payment)/100;
            $order->off = $shop->off;
            $order->transaction_type = $shop->transaction_type;
            $order->Purchase_type = $req->Purchase_type;
            $order->user_off = $shop->user_off;
            $order->status = '1';
            $order->save();
            $url = "$order->id";
            $month = $req->month;
            return redirect("/payment_show/$url/$month");
        }
      
    }
    public function payment($id, $month, Request $request)
    {
        $order = Order::where('id', $id)->first();
        $shop = Shop::find($order->shop_id);
        $user = User::where('id', $order->user_id)->first();
        $condition = Condition::where('shop_id', $order->shop_id)->where('month', $month)->first();
        $fee = $condition->percent;
        $pishpardakht = $condition->advance_payment * $order->final_price / 100;
        $flag = $order->final_price;
        $priceAll = ceil($order->price + ($fee / 100 * ($order->price ) * ($condition->month + 1)));
        $payAll = ceil(($priceAll - $pishpardakht));
        $baghimande = $priceAll - $pishpardakht;
        $payMonth = ceil(($priceAll) / $condition->month);

        if ($order->status != '1') { // 1 = motazere pardakht
            abort('403');
        }
        $payValue = $order->getPayValue();
        $payAll = ceil(($payValue - $pishpardakht));

        if ($condition->advance_payment == 0) {

            if ($user->wallet < $baghimande && $shop->more_sale ==0) {
                return redirect('/wallet');
            }
            // $user->wallet -= $baghimande;
            // $user->save();
            if ($user->wallet >= $baghimande || $shop->more_sale ==1) {
                $code = rand(1111, 9999);
                if ($order->shop_id == 8) {
                    $order->sms_code = 1111;
                }else{
                    $order->sms_code = $code;
                }
                $order->status = 7;
                $order->save();
                $tarikh = Jalalian::now();
                for ($i = 0; $i < $condition->month; $i++) {
                    $tarikh = $tarikh->addMonths(1); // اضافه کردن ماه شمسی
                    $transaction = new Transaction();
                    $transaction->value = $payMonth;
                    $transaction->order_id = $order->id;
                    $transaction->user_id = $user->id;
                    $transaction->shop_id = $order->shop_id;
                    $transaction->type = '15';
                    $transaction->fee = $payMonth;
                    // تبدیل به Carbon برای ذخیره در دیتابیس
                    $transaction->tarikh_ghest = $tarikh->toCarbon();
                    $transaction->save();
                }
                

                    // $matn='کد تایید خرید یزدان پلاس از فروشگاه '.$shop->name.' به مبلغ '.number_format($order->price).'ریال:
                    //     '. $code;
                    //     SmsController::sendSms(
                    //         $user,
                    //         SmsTypes::LOGIN_VERIFY_CODE,
                    //         $matn,
                    //         $user->mobile
                    //     );
                    
        

                return redirect('/orders');
            }
        } else {
            if ($user->wallet < $baghimande) {
                return redirect('/wallet');
            }
          
            // if ($request->hasFile('image')) {
            //     $file = $request->file('image');
            //     $file_name = time() . '.' . $file->getClientOriginalExtension();
            //     $destination_path = 'files/order/advance_payment_Image';
            //     $file->move($destination_path, $file_name);
            //     $order->image = $destination_path . '/' . $file_name;
            // }
            $code = rand(1111, 9999);
            if ($order->shop_id == 8) {
                $order->sms_code = 1111;
            }else{
                $order->sms_code = $code;
            }
            $order->status = 7;
            $order->save();
            $tarikh = Jalalian::now();
            for ($i = 0; $i < $condition->month; $i++) {
                $tarikh = $tarikh->addMonths(1); // اضافه کردن ماه شمسی
                $transaction = new Transaction();
                $transaction->value = $payMonth;
                $transaction->order_id = $order->id;
                $transaction->user_id = $user->id;
                $transaction->shop_id = $order->shop_id;
                $transaction->type = '15';
                $transaction->fee = $payMonth;
                // تبدیل به Carbon برای ذخیره در دیتابیس
                $transaction->tarikh_ghest = $tarikh->toCarbon();
                $transaction->save();
            }

           

            
                    // $matn='کد تایید خرید یزدان پلاس از فروشگاه '.$shop->name.' به مبلغ '.number_format($order->price).'ریال:
                    //     '. $code;
                    //     SmsController::sendSms(
                    //         $user,
                    //         SmsTypes::LOGIN_VERIFY_CODE,
                    //         $matn,
                    //         $user->mobile
                    //     );

                

            return redirect('/orders');
        }
    }
    public function show_details($id, $time)
    {
        $transactions = Transaction::where('order_id', $id)
            ->get();

        $order = Order::find($id);
        $count = $transactions->count();

        foreach ($transactions as $transaction) {
            // تقسیم قیمت بر تعداد اقساط
            // $transaction->price = $count > 0 ? $order->price / $count : 0;

            // تبدیل تاریخ قسط به شمسی و اضافه کردن به آبجکت
            $transaction->tarikh_ghest_shamsi = Jalalian::fromDateTime($transaction->tarikh_ghest)->format('Y/m/d');
        }

        session()->flash('transactions', $transactions);

        return redirect('/orders');
    }
    public function taeedOrder($id, $month)
    {
        $order = Order::where('id', $id)->first();
        $shop = Shop::find($order->shop_id);
        $user = User::where('id', $order->user_id)->first();
        $order->status = 8;
        $order->save();

        $condition = Condition::where('shop_id', $order->shop_id)->where('month', $month)->first();
        $fee = $condition->percent;
        $pishpardakht = $condition->advance_payment;
        $flag = $order->price;
        $priceAll = ceil($flag + ($fee / 100 * ($order->price - $pishpardakht) * ($condition->month + 1)));
        $payAll = ceil(($priceAll - $condition->advance_payment));
        $baghimande = $priceAll - $pishpardakht;
        $payMonth = ceil(($baghimande) / $condition->month);


        $tarikh = Jalalian::now();
        for ($i = 0; $i < $condition->month; $i++) {
            $tarikh = $tarikh->addMonths(1); // اضافه کردن ماه شمسی
            $transaction = new Transaction();
            $transaction->order_id = $order->id;
            $transaction->user_id = $user->id;
            $transaction->shop_id = $order->shop_id;
            $transaction->type = '15';
            $transaction->fee = $payMonth;
            $transaction->value = $payMonth;
            $transaction->tarikh_ghest = $tarikh->toCarbon();
            $transaction->save();
        }
        return redirect()->back();
    }

    public function cancelOrder($id)
    {
        $order = Order::where('id', $id)->first();
        $order->status = 9;
        $order->save();

        return redirect()->back();
    }
}
