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
use Morilog\Jalali\Jalalian;

class AdminController extends Controller
{
    public function import_excel(){
        return view('admin.import_excel');
    }
    public function import_excel_post(Request $request)
    {
        
        $request->validate([
            'excel' => 'required|file|mimes:xlsx,xls,csv',
        ]);
        

        
        $file = $request->file('excel');
        
        $rows = Excel::toArray([], $file); 
      

        $data = $rows[0] ?? []; 
            $num=0;
        
        foreach ($data as $index => $row) {

            
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

    
    public function orders_test(Request $request)
    {

$settlementMonth = $request->get(
    'settlement_month',
    Jalalian::now()->getMonth()
);
        $selectedMonth = $request->get('month', Jalalian::now()->getMonth());

        $ekhtelaf = $settlementMonth - $selectedMonth;
        // return $ekhtelaf;
    
        $year = Jalalian::now()->getYear();
    
        // روز اول ماه انتخاب شده
        $startJalali = new Jalalian($year, $selectedMonth, 1);
    
        // تعداد روزهای ماه انتخاب شده
        $daysInMonth = $startJalali->getMonthDays();
    
        // روز آخر ماه انتخاب شده
        $endJalali = new Jalalian(
            $year,
            $selectedMonth,
            $daysInMonth
        );
    
        // تبدیل به تاریخ میلادی
        $startDate = $startJalali->toCarbon()->startOfDay();
        $endDate = $endJalali->toCarbon()->endOfDay();
    
        $shops = Shop::where('display', 1)->get();
    
        foreach ($shops as $shop) {
    
            $total = Order::where('status', 2)
                ->where('shop_id', $shop->id)
                ->whereBetween('created_at', [
                    $startDate,
                    $endDate
                ])
                ->sum('price');
    
            $shop['order_m'] = $total;
    
            $condition = Condition::where('shop_id', $shop->id)->first();
    
            $shop['month'] = $condition->month ?? 0;
    
            if ($condition && $condition->month < $ekhtelaf) {

                $maah = 0;
            
            } elseif($condition && $condition->month >= $ekhtelaf) {
            
                $maah = $condition && $condition->month > 0
                    ? $total / $condition->month
                    : 0;
            }
            
            $shop['price'] = $maah;
        }
    
        $months = [
            1 => 'فروردین',
            2 => 'اردیبهشت',
            3 => 'خرداد',
            4 => 'تیر',
            5 => 'مرداد',
            6 => 'شهریور',
            7 => 'مهر',
            8 => 'آبان',
            9 => 'آذر',
            10 => 'دی',
            11 => 'بهمن',
            12 => 'اسفند',
        ];
    
        return view('admin.new.orders_test', compact(
            'shops',
            'months',
            'selectedMonth',
            'settlementMonth'
        ));
    }
    // if($condition->month < $ekhtelaf)
    //     $maah=0;
    //     else
    //     $maah=$total/$condition;
    // $shop['price']=$maah;
}