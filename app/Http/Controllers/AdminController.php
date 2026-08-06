<?php

namespace App\Http\Controllers;

use App\Models\Condition;
use App\Models\Order;
use App\Models\Shop;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function import_excel(){
        return view('admin.import_excel');
    }
    public function import_excel_post(Request $request)
    {
        // 1. اعتبارسنجی فایل
        $request->validate([
            'excel' => 'required|file|mimes:xlsx,xls,csv',
        ]);
        

        // 2. گرفتن فایل
        $file = $request->file('excel');
        // 3. خواندن محتوای اکسل
        $rows = Excel::toArray([], $file); 
        // خروجی چیزی شبیه:
        // $rows[0] = آرایه ردیف‌های شیت اول

        $data = $rows[0] ?? []; // فقط شیت اول
            $num=0;
        // اگر ردیف اول هدر است و نباید ذخیره شود می‌تونی از اندیس 1 شروع کنی
        foreach ($data as $index => $row) {

            // اگر سطر اول هدر است، این را فعال کن:
             if ($index == 0) continue;

             // نام =c   نام خانوادگی =d  کدملی=f  موبایل = f
             $first_name = $row[2] ?? null;
             $last_name = $row[3] ?? null;
             $meli = $row[5] ?? null;
             $mobile = $row[6] ?? null;
             
             // فرض: ستون A = ملی, B = فامیل, C = اسم, D = موبایل
            // $meli = $row[0] ?? null;
            // $last_name = $row[1] ?? null;
            // $first_name = $row[2] ?? null;
            // $mobile = $row[3] ?? null;

            // اگر موبایل خالی بود، از این ردیف رد شو
            if (empty($mobile)) {
                continue;
            }   

            // 4. شرط: اگر شماره موبایل قبلاً در دیتابیس وجود دارد، ذخیره نکن
            $exists = User::where('mobile', $mobile)->first();

            if ($exists) {
               $wallet= $exists->wallet;
                
                $transaction=new Transaction();
                $transaction->user_id=$exists->id;
                $transaction->value=$exists->wallet;
                $transaction->description='شارژ '.$exists->name.' '.$exists->family.'از'.$wallet.'به'.'50000000'.'شارژ شد';
                $transaction->type=21;
                $transaction->save();
                
                $exists->adad=0;
                //ba har exceel
                $exists->wallet=50000000;
                $exists->init_wallet=50000000;
                $exists->type=4;
                $exists->save();
                continue;
            }
            // 5. ذخیره در دیتابیس
            $user=new User();
            $user->name=$first_name;
            $user->family=$last_name;
            $user->mobile=$mobile;
            $user->nationalcode=$meli;
            $user->adad=0;
            $user->active=1;
            // ba har exceel
            $user->wallet=50000000;
            $user->init_wallet=50000000;
            $user->type=4;


            $user->save();
            $num+=1;
           
        }

        return back()->with('suc', 'ایمپورت فایل با موفقیت انجام شد
         تعداد ذخیره '.$num.'');
    }
    public function b_order(){
        $order=Order::where('status',2)->pluck('id');
        $user_trans = Transaction::where('type',15)
        ->whereBetween('tarikh_ghest', ['2026-07-23 00:00:00',
        '2026-08-22 23:59:59'])
        ->whereIn('order_id',$order)
        ->sum('value');
        return $user_trans;
        $transactions=Transaction::whereBetween('tarikh_ghest', [
            '2026-07-23 00:00:00',
                '2026-08-22 23:59:59'
        ])->where('type',15)->sum('value');
        return $transactions;
        // $shops_id=Shop::where('display',1)->pluck('id');
        
    //     $startDate = Carbon::parse('2026-04-21')->startOfDay();
    // $endDate = Carbon::parse('2026-05-21')->endOfDay();
    
    //     $orders = Order::whereIn('shop_id', $shops_id)
    // ->whereBetween('created_at', ['2026-04-21', '2026-05-22'])
    // ->get();
    
    $condition=Condition::where('month','>',0)->pluck('shop_id');
    $shops_id=Shop::whereIn('id',$condition)->where('display',1)->pluck('id');
    $total = Order::where('status',2)->whereBetween('created_at', [
        '2026-06-22 00:00:00',
            '2026-07-22 23:59:59'
    ])->whereIn('shop_id',$shops_id)->get();
    $jam=0;
        foreach($total as $order){
            $cond=Condition::where('shop_id',$order->shop_id)->first();
            $x=$order->price / $cond->month;
            $jam+=$x;
        }


        // $condition=Condition::where('month','>',0)->pluck('shop_id');
        // $shops_id=Shop::whereIn('id',$condition)->where('display',1)->pluck('id');
        // $total = Order::where('status',2)->whereBetween('created_at', [
        //     '2026-05-22 00:00:00',
        //     '2026-06-21 23:59:59'
        // ])->get();
            // foreach($total as $order){
            //     $cond=Condition::where('shop_id',$order->shop_id)->first();
            //     $x=$order->price / $cond->month;
            //     $jam+=$x;
            // }
return $jam;

    }
}