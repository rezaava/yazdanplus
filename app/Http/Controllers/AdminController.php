<?php

namespace App\Http\Controllers;

use App\Exports\ExcelUsers;
use App\Models\Condition;
use App\Models\Order;
use App\Models\Shop;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    $rows = Excel::toArray([], $request->file('excel'));
    $data = $rows[0] ?? [];

    $created = 0;
    $existing = 0;
    $invalid = [];

    DB::transaction(function () use ($data, &$created, &$existing, &$invalid) {
        $seen = []; // موبایل‌های تکراری داخل خود فایل

        foreach ($data as $index => $row) {
            if ($index == 0) continue; // هدر

            // ستون‌ها: A کدملی | B نام خانوادگی | C نام | D موبایل | E مبلغ
            $meli       = str_pad($this->digits($row[0] ?? ''), 10, '0', STR_PAD_LEFT);
            $last_name  = trim((string)($row[1] ?? ''));
            $first_name = trim((string)($row[2] ?? ''));
            $mobile     = $this->normalizeMobile($row[3] ?? '');
            $amount     = (int) $this->digits($row[4] ?? '');

            // ردیف خالی یا ردیف «مجموع» (موبایل ندارد)
            if ($mobile === '') continue;

            // موبایل یا مبلغ نامعتبر
            if (!preg_match('/^09\d{9}$/', $mobile) || $amount <= 0) {
                $invalid[] = $index + 1; // شماره ردیف در اکسل
                continue;
            }

            // موبایل تکراری داخل همین فایل
            if (isset($seen[$mobile])) {
                $existing++;
                continue;
            }
            $seen[$mobile] = true;

            // کاربر قبلی: کاری باهاش نداریم
            if (User::where('mobile', $mobile)->exists()) {
                $existing++;
                continue;
            }

            $user = new User();
            $user->name = $first_name;
            $user->family = $last_name;
            $user->mobile = $mobile;
            $user->nationalcode = $meli;
            $user->adad = 0;
            $user->active = 1;
            $user->wallet = $amount;
            $user->init_wallet = $amount;
            $user->type = 4;
            $user->save();

            $created++;
        }
    });

    $msg = "ایمپورت انجام شد. کاربر جدید: $created ، ردشده (قبلاً وجود داشت یا تکراری بود): $existing";
    if ($invalid) {
        $msg .= ' | ردیف‌های نامعتبر: ' . implode('، ', $invalid);
    }

    return back()->with('suc', $msg);
}

// تبدیل ارقام فارسی/عربی به انگلیسی و حذف هر چیز غیر عددی
private function digits($v): string
{
    $v = str_replace(
        ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹','٠','١','٢','٣','٤','٥','٦','٧','٨','٩'],
        [0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9],
        (string)$v
    );
    return preg_replace('/\D/', '', $v);
}

// خروجی: 09xxxxxxxxx
private function normalizeMobile($v): string
{
    $v = $this->digits($v);
    if ($v === '') return '';
    if (str_starts_with($v, '98')) return '0' . substr($v, 2);
    if (strlen($v) === 10 && $v[0] === '9') return '0' . $v; // صفر اولش حذف شده
    return $v;
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


    public function excel_user()
{
    $users = User::where('type', 1)
        ->orderBy('id', 'desc')
        ->get();

    return view('admin.partials.excel_user', compact('users'));
}

public function excel_user_download()
{
    return Excel::download(
        new ExcelUsers(),
        'users.xlsx'
    );
}

}