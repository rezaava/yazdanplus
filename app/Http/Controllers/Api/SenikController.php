<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Condition;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\Jalalian;

class SenikController extends Controller
{
    public function store(Request $request): JsonResponse
    {


        $data=$request->all();
        $ruls = [
            'amount' => 'required|numeric|min:1',
            'mobile' => 'required|string',
            'date'   => 'nullable|string',
            'key'    => 'required|string',
        ];

        $validator=Validator::make($data,$ruls);

        if($validator->fails()){
            return response()->json(['status' => -1]);
        }


        // SENIK_API_KEY=K8xP4mZ9qL2vN7sR5tW3yH6cB1dF0gA
        // php artisan config:clear
        // if ($request->input('key') !== config('services.senik.key')) {
        //     return response()->json(['status' => 3], 401);
           
        // }
        
        // کلید امنیتی
        $senikApiKey = 'K8xP4mZ9qL2vN7sR5tW3yH6cB1dF0gA';

        if ($request->input('key') !== $senikApiKey) {
            return response()->json(['status' => 3], 401);
            
        }

        $amount = $request->input('amount');
        // $token  = $request->input('token');
        $mobile = $request->input('mobile');

        // اگر قبلا استفاده شده دیگه ثبت نکنه
        // $ex
        

        //  پیدا کردن کاربر  
        $user = User::where('mobile', $mobile)->first();
        if (!$user) {
            return response()->json(['status' => 0]);
        }

        //  بررسی اعتبار 
        if ($user->wallet < $amount) {
            return response()->json(['status' => 1]);
        }

    // ثبت خرید
$tk = 0;

DB::transaction(function () use ($user, $amount, &$tk) {

    $user->wallet = $user->wallet - $amount;
    $user->save();

    $order = new Order();
    $order->user_id     = $user->id;
    $order->shop_id     = 58;
    $order->price       = $amount;
    $order->final_price = $amount;
    $order->status      = 2;
    // $order->senik_token = $token;
    $order->save();

    $tk = $order->id;

    $condition = Condition::where('shop_id', $order->shop_id)->first();
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
});

return response()->json([
    'status' => 2,
    'code' => $tk
]);
      
    }
}