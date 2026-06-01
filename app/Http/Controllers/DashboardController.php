<?php

namespace App\Http\Controllers;

use App\Exports\AuditExport;
use App\Exports\UsersTransExport;
use App\Models\Category;
use App\Models\CategoryShop;
use App\Models\Images;
use App\Models\Off;
use App\Models\Off_user;
use App\Models\Portal_transaction;
use App\Models\Portal_user;
use App\Models\Role;
use App\Models\ShopUser;
use App\Models\Slider;
use App\Models\Order;
use App\Models\UserLog;
use Carbon\Carbon;
use Couchbase\View;
use Faker\Provider\Company;
use Illuminate\Support\Composer;
use Illuminate\support\Facades\Auth;
use App\Models\BankAccount;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\Shop;
use App\Models\sms;
use App\Models\User;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use SoapClient;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ShopsReportExport;
use App\Exports\Shops;
use App\Exports\TransactionsExport;
use App\Exports\ShopReportExport;
use App\Exports\TasviehExport;
use App\Exports\ReportExport;
use App\Models\Condition;
use App\Models\Contract;
use App\Exports\OrderExport;
use App\Exports\ShopRizOrderExport;

class DashboardController extends Controller
{

    public function index(request $req)
    {

        $user_i = Auth::user();
        if ($user_i->hasRole('content_manager')) {
            return redirect('/admin/shops');
        } else if ($user_i->hasRole('admin')) {

            $orders = Order::whereIn('status',[2,7])->orderBy('id','desc')->get();
            $users_count = Order::whereIn('status',[2])->distinct('user_id')->count('user_id');
            $orders_count = Order::whereIn('status',[2])->count();
            $orders_prices = Order::where('status', '2')->get(['price']);
            $ghestAndOff = Transaction::whereIn('type', ['5', '18'])->sum('value');

            $all_price = 0;
        } else if ($user_i->hasRole('marketer') || $user_i->hasRole('shop_admin') || $user_i->hasRole('Employee_admin') ||  $user_i->hasRole('shop_user')) {
            $shop = Shop::getOwned($user_i->id, true)->pluck('id');
            $shop_n = Shop::getOwned($user_i->id, true)->first();
            $ghestAndOff = Transaction::whereIn('type', ['5', '18'])->whereIn('shop_id', $shop)->sum('value');
           
                // $orders = Order::whereIn('status', [2,7])->whereIn('shop_id', $shop)->orderBy('id', 'desc')->get();
                $orders = Order::where(function ($query) use ($shop_n) {
                    // وضعیت 2: همه رکوردها
                    $query->where('status', 2)
                          ->where('shop_id', $shop_n->id);
                    
                    // وضعیت 7: فقط 48 ساعت اخیر
                    $query->orWhere(function ($subQuery) use ($shop_n) {
                        $subQuery->where('status', 7)
                                 ->where('created_at', '>=', now()->subHours(24))
                                 ->where('shop_id', $shop_n->id);
                    });
                })->orderBy('id', 'desc')->get();
                // $orders_count = Order::whereIn('status', [2,7])->whereIn('shop_id', $shop)->orderBy('id', 'desc')->count();
                // برای شمارش هم دقیقاً همین شرط
                $orders_count = Order::where(function ($query) use ($shop_n) {
                    // وضعیت 2: همه رکوردها
                    $query->where('status', 2)
                          ->where('shop_id', $shop_n->id);
                    
                })->orderBy('id', 'desc')->count();
                $users_count = Order::whereIn('status',[2])->where('shop_id',$shop_n->id)->distinct('user_id')->count('user_id');
                // $orders = Order::whereIn('status', [2])->whereIn('shop_id', $shop)->orderBy('id', 'desc')->get();
                // $orders_count = Order::whereIn('status', [2])->whereIn('shop_id', $shop)->orderBy('id', 'desc')->count();
           
            // $all_price = null;
            $all_price = 0;
        } else {
            abort(403);
        }

        foreach ($orders as $order) {
            $condition = Condition::where('id', $order->condition_id)->first();
            $order['month'] = $condition->month;
            $user = User::find($order->user_id);
            $order['user'] = $user->fullname();
            $order['mobile'] = $this->hideMobile($user->mobile);
            $order['time'] = $this->convertToPersianTimeadmin($order->created_at->format('H:i:s'));
            $shop_order = $order->shop_order;
            $order['shop_order_name'] = ($shop_order == null) ? '' : $shop_order->name;
            $order['shop_order_id'] = ($shop_order == null) ? '' : $shop_order->id;
            $all_price += $order->price;
        }

        $mande = $all_price - $ghestAndOff;
        if ($user_i->hasRole('shop_admin') || $user_i->hasRole('shop_user')) {
            return view('admin.list_sale', compact('orders', 'all_price', 'orders_count', 'shop_n', 'mande','users_count'));
        } else {

            return view('admin.list_sale', compact('orders', 'all_price', 'orders_count', 'mande','users_count'));
        }
    }
    public function abc(){
       
            $orders = Order::where('status',2)->get();
            $orders_count = Order::where('status',2)->count();
            $orders_prices = Order::where('status', '2')->get(['price']);
            $users_count = Order::whereIn('status',[2])->distinct('user_id')->count('user_id');
            $ghestAndOff = Transaction::whereIn('type', ['5', '18'])->sum('value');

            $all_price = 0;
       

        foreach ($orders as $order) {
            $condition = Condition::where('id', $order->condition_id)->first();
            $order['month'] = $condition->month;
            $user = User::find($order->user_id);
            $order['user'] = $user->fullname();
            $order['mobile'] = $this->hideMobile($user->mobile);
            $order['time'] = $this->convertToPersianTimeadmin($order->created_at->format('H:i:s'));
            $shop_order = $order->shop_order;
            $order['shop_order_name'] = ($shop_order == null) ? '' : $shop_order->name;
            $order['shop_order_id'] = ($shop_order == null) ? '' : $shop_order->id;
            $all_price += $order->price;
        }

        $mande = $all_price - $ghestAndOff;

        return view('list_sale2', compact('orders', 'all_price', 'orders_count', 'mande','users_count'));
        
    }
    public function add_comment(Request $req,$id){
        $order=Order::find($id);
        $order->comment=$req->comment;
        $order->save();
        return back();
    }

    public function orderExcel(Request $request)
    {
        $from = $this->convertPersianNumber($request->from_date);
        $to   = $this->convertPersianNumber($request->to_date);

        if (!$from || !$to) {
            return back()->with('error', 'تاریخ‌ها الزامی هستند');
        }

        try {

            $separator = str_contains($from, '/') ? '/' : '-';

            [$fy, $fm, $fd] = array_map('intval', explode($separator, $from));
            [$ty, $tm, $td] = array_map('intval', explode($separator, $to));

            $fromDate = Jalalian::fromFormat(
                'Y/m/d',
                sprintf('%04d/%02d/%02d', $fy, $fm, $fd)
            )->toCarbon()->startOfDay();

            $toDate = Jalalian::fromFormat(
                'Y/m/d',
                sprintf('%04d/%02d/%02d', $ty, $tm, $td)
            )->toCarbon()->endOfDay();

            $fileName = "orders_{$fromDate->format('Y-m-d')}_to_{$toDate->format('Y-m-d')}.xlsx";

            return Excel::download(
                new OrderExport($fromDate, $toDate, Auth::user()),
                $fileName
            );
        } catch (\Exception $e) {
            return back()->with('error', 'فرمت تاریخ نادرست است');
        }
    }

    public function shops()
    {
        $user_i = Auth::user();
        if ($user_i->hasRole('admin') || $user_i->hasRole('content_manager')) {
            $shops = Shop::all();
            foreach ($shops as $shop) {
                $shop['isset'] = ShopUser::where('shop_id', $shop->id)->get();
                $shop_categorys = CategoryShop::where('shop_id', $shop->id)->get();
                if ($shop_categorys != '[]') {
                    $shop['category'] = $shop_categorys;
                } else {
                    $shop['cat'] = null;
                }
            }
        } else {
            $shops = Shop::where('user_id', $user_i->id)->orWhere('refferer_id', $user_i->id)->get();
        }
        return view('admin.list_shop', compact('shops'));
    }
    public function shop_user($id)
    {
        $shop = Shop::find($id);
        return view('admin.shop_pass', compact('shop'));
    }
    public function shop_pass_post(Request $request, $id)
    {
        $shop = Shop::find($id);
        $shop->password = $request->password;
        $shop->save();
        return back()->with('suc', 'ذخیره شد');
    }

    public function downloadMonthlyInstallmentReport(Request $request, $yearMonth)
    {
        try {

            $persianYearMonth = str_replace('-', '/', $yearMonth);


            list($year, $month) = explode('/', $persianYearMonth);

            // اطمینان از اینکه سال و ماه به صورت عددی و صحیح هستند
            $currentYear = (int) $year;
            $currentMonth = (int) $month;

            // فرمت دهی ماه برای اطمینان از دو رقمی بودن (مثلا 01, 02, ..., 12)
            $formattedMonth = sprintf("%02d", $currentMonth);


            $startOfMonthJalali = Jalalian::fromFormat('Y-m-d', $currentYear . '-' . $formattedMonth . '-01');

            $daysInMonth = $startOfMonthJalali->getMonthDays();

            $endOfMonthJalali = Jalalian::fromFormat('Y-m-d', $currentYear . '-' . $formattedMonth . '-' . $daysInMonth);


            $startOfMonthCarbon = $startOfMonthJalali->toCarbon()->startOfDay();
            $endOfMonthCarbon = $endOfMonthJalali->toCarbon()->endOfDay();


            $fileName = "monthly_installments_{$year}-{$formattedMonth}.xlsx";

            // 5. دریافت ID کاربر لاگین شده
            $userId = Auth::id();


            return Excel::download(
                new TransactionsExport($startOfMonthCarbon, $endOfMonthCarbon, $userId),
                $fileName
            );
        } catch (\Exception $e) {

            return back()->withErrors(['report_error' => 'دریافت گزارش ماهانه برای تاریخ ' . $yearMonth . ' با خطا مواجه شد. لطفاً فرمت تاریخ را بررسی کنید (مثلاً YYYY-MM).']);
        }
    }


    public function show_reportForm(Request $request, $yearMonth)
    {
        $persianYearMonth = str_replace('-', '/', $yearMonth);


        list($year, $month) = explode('/', $persianYearMonth);

        // اطمینان از اینکه سال و ماه به صورت عددی و صحیح هستند
        $currentYear = (int) $year;
        $currentMonth = (int) $month;

        // فرمت دهی ماه برای اطمینان از دو رقمی بودن (مثلا 01, 02, ..., 12)
        $formattedMonth = sprintf("%02d", $currentMonth);


        $startOfMonthJalali = Jalalian::fromFormat('Y-m-d', $currentYear . '-' . $formattedMonth . '-01');

        $daysInMonth = $startOfMonthJalali->getMonthDays();

        $endOfMonthJalali = Jalalian::fromFormat('Y-m-d', $currentYear . '-' . $formattedMonth . '-' . $daysInMonth);


        $startOfMonthCarbon = $startOfMonthJalali->toCarbon()->startOfDay();
        $endOfMonthCarbon = $endOfMonthJalali->toCarbon()->endOfDay();
        $userId = Auth::id();

        $ordersId=Order::where('user_id' , $userId)->whereIn('status',['2','8'])->pluck('id');
        
        // 1. دریافت تراکنش‌ها و تبدیل تاریخ‌ها به شمسی

        $transactions = Transaction::with('shop')
            ->whereIn('order_id', $ordersId) // از userId که از Auth گرفتیم استفاده می‌کنیم
            ->whereBetween('tarikh_ghest', [$startOfMonthCarbon, $endOfMonthCarbon])
            ->orderBy('tarikh_ghest', 'asc')
            ->get();

        foreach ($transactions as $transaction) {
            $transaction['tarikh'] = \Morilog\Jalali\Jalalian::fromDateTime($transaction->tarikh_ghest)->format('Y/m/d');
        }

        // $transactions_sum = Transaction::where('user_id', $userId)
        //     ->whereBetween('tarikh_ghest', [$startOfMonthCarbon, $endOfMonthCarbon])
        //     ->sum('value');

        $user = Auth::user();
        $profile = Images::where('id', $user->profpic_id)->first();
        return view('show_reportForm', compact('transactions', 'user', 'profile'));
    }

    public function reportForm()
    {
        $user = Auth::user();
        $profile = Images::where('id', $user->profpic_id)->first();

        $ordersId=Order::where('user_id' , $user->id)->whereIn('status',['2','8'])->pluck('id');
        
        // 1. دریافت تراکنش‌ها و تبدیل تاریخ‌ها به شمسی
        $transactions = Transaction::whereIn('order_id',$ordersId)
            ->orderBy('tarikh_ghest', 'asc')
            ->get();

        // 2. گروه‌بندی تراکنش‌ها بر اساس ماه شمسی و محاسبه مجموع مبالغ
        $monthlyInstallments = $transactions->map(function ($transaction) {
            // تبدیل تاریخ میلادی به شمسی و استخراج سال و ماه
            $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->tarikh_ghest);
            $jalali = Jalalian::fromCarbon($carbonDate);
            $yearMonth = $jalali->format('Y/m'); // فرمت YYYY/MM برای گروه‌بندی

            return [
                'year_month' => $yearMonth,
                'month_name' => $jalali->format('%B %Y'), // نام کامل ماه برای نمایش (اختیاری)
                'value' => (int) $transaction->value, // تبدیل به عدد صحیح برای جمع زدن
            ];
        })->groupBy('year_month')->map(function ($group, $yearMonth) {
            // محاسبه مجموع مبلغ برای هر ماه
            $totalValue = $group->sum('value');
            // شما می‌توانید از $group->first()['month_name'] هم برای نام ماه استفاده کنید اگر بخواهید
            return [
                'year_month_display' => $yearMonth, // نمایش ماه به فرمت YYYY/MM
                'total_amount' => $totalValue,
            ];
        })->values(); // تبدیل مجموعه گروه‌بندی شده به آرایه معمولی

        // 3. ارسال داده‌های تجمیع شده به ویو
        return view('reportForm', compact('user', 'profile', 'monthlyInstallments'));
    }

    public function reportOfDate(Request $request)
    {
        $fromShamsi = $this->convertPersianNumber($request->from_date);
        $toShamsi   = $this->convertPersianNumber($request->to_date);

        try {
            if (empty($fromShamsi) || empty($toShamsi)) {
                throw new \Exception('تاریخ‌ها نباید خالی باشند.');
            }

            // تشخیص جداکننده به صورت خودکار
            $separator = str_contains($fromShamsi, '/') ? '/' : '-';

            $fromParts = explode($separator, $fromShamsi);
            $toParts = explode($separator, $toShamsi);

            if (count($fromParts) !== 3 || count($toParts) !== 3) {
                throw new \Exception('فرمت تاریخ نادرست است.');
            }

            [$fy, $fm, $fd] = array_map('intval', $fromParts);
            [$ty, $tm, $td] = array_map('intval', $toParts);

            if ($fy < 1300 || $fy > 1600 || $ty < 1300 || $ty > 1600) {
                throw new \Exception('مقدار سال شمسی معتبر نیست.');
            }

            $fromMiladi = Jalalian::fromFormat('Y/m/d', sprintf('%04d/%02d/%02d', $fy, $fm, $fd))
                ->toCarbon()
                ->startOfDay();

            $toMiladi = Jalalian::fromFormat('Y/m/d', sprintf('%04d/%02d/%02d', $ty, $tm, $td))
                ->toCarbon()
                ->endOfDay();

            $fileName = "transactions_{$fromMiladi->format('Y-m-d')}_to_{$toMiladi->format('Y-m-d')}.xlsx";

            return Excel::download(
                new TransactionsExport($fromMiladi, $toMiladi, Auth::id()),
                $fileName
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'تبدیل تاریخ با خطا مواجه شد.',
                'message' => $e->getMessage(),
                'debug_from' => $fromShamsi,
                'debug_to' => $toShamsi
            ], 400);
        }
    }
    // ✅ تابع کمکی برای تبدیل اعداد فارسی به انگلیسی
    private function convertPersianNumber($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($persian, $english, $string);
    }

    public function users()
    {
        $users = User::get();
        foreach ($users as $user) {
            $user['shop'] = Shop::where('user_id', $user->id)->get();
            $user['referrer'] = User::where('id', $user->referrer_id)->first();
        }
        return view('admin.list_user', compact('users'));
    }

    public function userAudit($id)
    {
        $aghsat = Transaction::where('user_id', $id)->whereIn('type', [18, 19])->get();
        // $tedadGhest = Shop::where('user_id',$id)->first()->installments_number ;
        foreach ($aghsat as $ghest) {
            if ($ghest->order_id != null) {
                $ghest['price'] = (Order::where('id', $ghest->order_id)->first()->price);
            } else {
                $ghest['price'] = 0;
            }
        }

        $maDadim = Transaction::where('shop_id', $id)->where('type', 5)->get();

        foreach ($maDadim as $priceItem) {
            if ($priceItem->order_id != null) {
                $priceItem['price'] = (Order::where('id', $priceItem->order_id)->first()->price);
            } else {
                $priceItem['price'] = 0;
            }
        }

        return view('admin.user_finance', compact('aghsat', 'maDadim'));
    }

    public function performance($id)
    {
        $user = User::find($id);
        $orders_count=Order::where('user_id' , $user->id)->whereIn('status',['2'])->count();
        $orders_sum=Order::where('user_id' , $user->id)->whereIn('status',['2'])->sum('price');
        $ordersId=Order::where('user_id' , $user->id)->whereIn('status',['2'])->pluck('id');
        
        // 1. دریافت تراکنش‌ها و تبدیل تاریخ‌ها به شمسی
        $transactions = Transaction::whereIn('order_id',$ordersId)
            ->orderBy('tarikh_ghest', 'asc')
            ->get();

        // 2. گروه‌بندی تراکنش‌ها بر اساس ماه شمسی و محاسبه مجموع مبالغ
        $monthlyInstallments = $transactions->map(function ($transaction) {
            // تبدیل تاریخ میلادی به شمسی و استخراج سال و ماه
            $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->tarikh_ghest);
            $jalali = Jalalian::fromCarbon($carbonDate);
            $yearMonth = $jalali->format('Y/m'); // فرمت YYYY/MM برای گروه‌بندی

            return [
                'year_month' => $yearMonth,
                'month_name' => $jalali->format('%B %Y'), // نام کامل ماه برای نمایش (اختیاری)
                'value' => (int) $transaction->value, // تبدیل به عدد صحیح برای جمع زدن
            ];
        })->groupBy('year_month')->map(function ($group, $yearMonth) {
            // محاسبه مجموع مبلغ برای هر ماه
            $totalValue = $group->sum('value');
            // شما می‌توانید از $group->first()['month_name'] هم برای نام ماه استفاده کنید اگر بخواهید
            return [
                'year_month_display' => $yearMonth, // نمایش ماه به فرمت YYYY/MM
                'total_amount' => $totalValue,
            ];
        })->values(); // تبدیل مجموعه گروه‌بندی شده به آرایه معمولی




        if ($user) {
            return view('admin.user_performance', compact('user','monthlyInstallments','orders_count','orders_sum'));
        } else {
            abort(404);
        }
    }
    public function useredit($id)
    {
        $user = User::find($id);
        if ($user) {
            return view('admin.edit_user', compact('user'));
        } else {
            abort(404);
        }
    }
    public function userupdate(request $req, $id)
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'family' => 'required',
            'mobile' => 'required|numeric',
        ], [
            'name.required' => 'نام باید وارد شود',
            'family.required' => 'فامیل باید وارد شود',
            'mobile.required' => 'موبایل باید وارد شود',
            'mobile.numeric' => 'موبایل باید عددی باشد',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $user = User::find($id);
        if ($user) {
            $user->name = $req->name;
            $user->family = $req->family;
            $user->mobile = $req->mobile;
            $user->date_of_birth = $req->date;
            // $user->coin = $req->coin;
            $user->save();
            return redirect()->back();
        } else {
            abort('404');
        }
    }

    public function user_sms($id)
    {
        $user = User::find($id);
        if ($user) {
            return view('admin.sms_user', compact('user'));
        } else {
            abort(404);
        }
    }
    public function send_sms(request $request, $id)
    {
        $user = User::find($id);
        $user_i = Auth::user();
        if ($user) {
            $client = new SoapClient("http://185.237.85.55/smsWebService.asmx?wsdl");
            $client->__SoapCall('sendSingleSMS', [array("username" => "ramin", "password" => "R@min10300", 'domain' => 'sms.smsnegar', "messageBody" => "$request->text", "recipientNumber" => "$user->mobile", "senderNumber" => $this->number)]);
            $sms = new sms();
            $sms->user_from_id = $user_i->id;
            $sms->user_to_id = $user->id;
            $sms->text = $request->text;
            $sms->number = $this->number;
            $sms->type = '0';
            $sms->save();
            return redirect()->back();
        } else {
            abort(404);
        }
    }

    public function add_employee()
    {
        $users = User::get();
        $shops = Shop::get();
        return view('admin.add_employee', compact('users', 'shops'));
    }

    public function add_employee_post(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user' => 'required|exists:users,id',
                'shop' => 'required|exists:shops,id',
            ],
            [
                'user.required' => 'کاربر انتخاب نشده است.',
                'user.exists' => 'کاربر معتبر نیست.',
                'shop.required' => 'فروشگاه انتخاب نشده است.',
                'shop.exists' => 'فروشگاه معتبر نیست.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // گرفتن کاربر انتخاب شده
        $employee_user = User::find($request->user);

        if (!$employee_user) {
            return redirect()->back()->withErrors('کاربر وجود ندارد!');
        }

        // فقط اگر نقش نداشت اضافه کن
        if (!$employee_user->hasRole('Employee_admin')) {
            $employee_user->addRole('Employee_admin');
        }

        // جلوگیری از ثبت تکراری فروشگاه
        $exists = ShopUser::where('user_id', $employee_user->id)
            ->where('shop_id', $request->shop)
            ->exists();

        if (!$exists) {
            $employee_shop = new ShopUser();
            $employee_shop->user_id = $employee_user->id;
            $employee_shop->shop_id = $request->shop;
            $employee_shop->save();
        }

        return redirect()->back()->with('suc', 'کارمند با موفقیت ثبت شد.');
    }

    public function add_user()
    {
        $roles = Role::get();
        return view('admin.add_user', compact('roles'));
    }

    public function add_user_post(request $request)
    {
        $validator = Validator::make(
            request()->all(),
            [
                'name' => 'required|string|max:100',
                'family' => 'required|string|max:100',
                'mobile' => 'required|numeric|digits:11|unique:users,mobile',
                'nationalcode' => 'required|numeric|digits:10|unique:users,nationalcode',
                'birth_day' => 'required|date_format:Y/m/d',
                'coin' => 'nullable|numeric|min:0',
                'role' => 'required|exists:roles,name',
            ],
            [
                'name.required' => 'وارد کردن نام الزامی است.',
                'name.string' => 'نام وارد شده معتبر نیست.',
                'name.max' => 'نام نمی‌تواند بیشتر از ۱۰۰ کاراکتر باشد.',

                'family.required' => 'وارد کردن نام خانوادگی الزامی است.',
                'family.string' => 'نام خانوادگی معتبر نیست.',
                'family.max' => 'نام خانوادگی نمی‌تواند بیشتر از ۱۰۰ کاراکتر باشد.',

                'mobile.required' => 'شماره موبایل الزامی است.',
                'mobile.numeric' => 'شماره موبایل باید فقط شامل عدد باشد.',
                'mobile.digits' => 'شماره موبایل باید دقیقاً ۱۱ رقم باشد.',
                'mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است.',

                'nationalcode.required' => 'کد ملی الزامی است.',
                'nationalcode.numeric' => 'کد ملی باید فقط شامل عدد باشد.',
                'nationalcode.digits' => 'کد ملی باید دقیقاً ۱۰ رقم باشد.',
                'nationalcode.unique' => 'این کد ملی قبلاً ثبت شده است.',

                'birth_day.required' => 'تاریخ تولد الزامی است.',
                'birth_day.date_format' => 'فرمت تاریخ باید به صورت 1402/02/01 باشد.',

                'coin.numeric' => 'مقدار کوین باید عددی باشد.',
                'coin.min' => 'مقدار کوین نمی‌تواند منفی باشد.',

                'role.required' => 'انتخاب نقش کاربر الزامی است.',
                'role.exists' => 'نقش انتخاب شده معتبر نیست.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $user_i = Auth::user();
        //new user
        $user = new User();
        $user->name = $request->name;
        $user->family = $request->family;
        $user->mobile = $request->mobile;
        $user->coin = $request->coin;
        if ($request->birth_day) {
            $birth_day = explode("/", $request->birth_day);
            $birth_day = (new Jalalian($this->convert($birth_day[0]), $this->convert($birth_day[1]), $this->convert($birth_day[2]), 0, 0, 0))->toCarbon()->toDateTimeString();
            $user->date_of_birth = $birth_day;
        }
        if ($request->nationalcode) {
            $user->nationalcode = $request->nationalcode;
        }
        $user->adad = 0;
        $user->coin = $request->coin;
        $user->active = 1;
        $user->profpic_id = 1;
        $user->referrer_id = $user_i->id;
        $user->save();
        if ($request->role == null) {
            $user->addRole('user');
        } elseif ($request->role == 'admin' || $request->role == 'user' || $request->role == 'marketer' || $request->role == 'content_manager' || $request->role == 'shop_admin') {
            if ($request->role != 'user') {
                $user->addRole('user');
            }
            $user->addRole('' . $request->role . '');
        } else {
            $user->addRole('user');
        }
        return redirect()->back()->with('suc', 'کاربر با موفقیت ذخیره شد');
    }
    public function up_role($id, $role)
    {
        $user = User::find($id);
        if ($user) {
            $user->save();
            $user->addRole($role);
            return redirect()->back();
        } else {
            abort(404);
        }
    }

    public function down_role($id, $role)
    {
        $user = User::find($id);
        if ($user) {
            $user->save();
            $user->removeRole($role);
            return redirect()->back();
        } else {
            abort(404);
        }
    }

    public function exportReportExcel(Request $request, $shopId)
    {
        $from = $request->input('from_date');
        $to = $request->input('to_date');

        // همون داده‌های گزارش را دوباره بساز
        $controller = new self();
        $report = $controller->generateReportData($shopId, $from, $to);

        // خروجی Excel
        return Excel::download(new ReportExport($report), 'گزارش-فروشگاه.xlsx');
    }


    // تابع مشترک برای تولید داده گزارش (مثل reportG)
    private function generateReportData($shopId, $from, $to)
    {
        $report = [];

        if (
            !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $from) ||
            !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $to)
        ) {
            return collect([]);
        }

        [$fromYear, $fromMonth] = explode('-', $from);
        [$toYear, $toMonth] = explode('-', $to);

        $fromYear = (int)$fromYear;
        $fromMonth = (int)$fromMonth;
        $toYear = (int)$toYear;
        $toMonth = (int)$toMonth;

        // ✅ ایجاد Jalalian واقعی برای محدوده‌ی شروع و پایان
        $start = new \Morilog\Jalali\Jalalian($fromYear, $fromMonth, 1);
        $end = new \Morilog\Jalali\Jalalian($toYear, $toMonth, 1);

        $sumSales = 0;
        $sumPayments = 0;
        $sumRemaining = 0;
        $sumPaymentsOff = 0;

        // ✅ حلقه تا وقتی ماه فعلی کمتر یا مساوی ماه انتهایی است
        while (
            $start->getYear() < $end->getYear() ||
            ($start->getYear() == $end->getYear() && $start->getMonth() <= $end->getMonth())
        ) {
            $monthStr = $start->format('Y-m');

            $startOfMonth = $start->toCarbon();
            $endOfMonth = $start->getEndDayOfMonth()->toCarbon()->setTime(23, 59, 59);
            $totalSales = \App\Models\Order::where('shop_id', $shopId)
                ->whereIn('status', [2])
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('price');

            $totalPayments = \App\Models\Transaction::where('shop_id', $shopId)
                ->where('type', 5)
                ->whereBetween('tarikh_ghest', [$startOfMonth, $endOfMonth])
                ->sum('fee');
            $totalPayments_off = \App\Models\Transaction::where('shop_id', $shopId)
                ->where('type', 18)
                ->whereBetween('tarikh_ghest', [$startOfMonth, $endOfMonth])
                ->sum('fee');


            $remaining = $totalSales - $totalPayments - $totalPayments_off;

            $report[] = [
                'ماه' => $monthStr,
                'کل فروش' => number_format($totalSales),
                'کل تسویه' => number_format($totalPayments),
                'باقی مانده' => number_format($remaining),
                'تخفیف' => number_format($totalPayments_off),
            ];

            $sumSales += $totalSales;
            $sumPayments += $totalPayments;
            $sumPaymentsOff += $totalPayments_off;
            $sumRemaining += $remaining;

            // ✅ اضافه کردن دقیق یک ماه شمسی
            $start = $start->addMonths(1);
        }

        // ✅ ردیف جمع کل
        $report[] = [
            'ماه' => 'جمع کل',
            'کل فروش' => number_format($sumSales),
            'کل تسویه' => number_format($sumPayments),
            'تخفیف' => number_format($sumPaymentsOff),
            'باقی مانده' => number_format($sumRemaining),
        ];

        return collect($report);
    }

    public function reportG(Request $request, $shopId)
    {
        $report = [];

        if ($request->filled(['from_date', 'to_date'])) {
            $from = $request->input('from_date');
            $to = $request->input('to_date');

            // اعتبارسنجی فرمت
            if (
                !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $from) ||
                !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $to)
            ) {
                return back()->with('error', 'فرمت تاریخ باید مثل 1404-01 باشد.');
            }

            [$fromYear, $fromMonth] = explode('-', $from);
            [$toYear, $toMonth] = explode('-', $to);

            $fromYear = (int)$fromYear;
            $fromMonth = (int)$fromMonth;
            $toYear = (int)$toYear;
            $toMonth = (int)$toMonth;
            if ($fromMonth > $toMonth) {
                return redirect()->back()->with('error', 'تاریخ شروع و پایان را اشتباه وارد کرید');
            }

            try {
                $start = new \Morilog\Jalali\Jalalian($fromYear, $fromMonth, 1);
                $end = new \Morilog\Jalali\Jalalian($toYear, $toMonth, 1);
            } catch (\Exception $e) {
                return back()->with('error', 'خطا در تبدیل تاریخ.');
            }

            $sumSales = 0;
            $sumPayments = 0;
            $sumRemaining = 0;
            $sumPaymentsOff = 0;

            // ✅ حلقه دقیق بر اساس ماه و سال شمسی
            while (
                $start->getYear() < $end->getYear() ||
                ($start->getYear() == $end->getYear() && $start->getMonth() <= $end->getMonth())
            ) {
                $monthStr = $start->format('Y-m');

                $startOfMonth = $start->toCarbon();
                $endOfMonth = $start->getEndDayOfMonth()->toCarbon()->setTime(23, 59, 59);

                $totalSales = \App\Models\Order::where('shop_id', $shopId)
                    ->whereIn('status', [2])
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->sum('price');

                $totalPayments = \App\Models\Transaction::where('shop_id', $shopId)
                    ->where('type', 5)
                    ->whereBetween('tarikh_ghest', [$startOfMonth, $endOfMonth])
                    ->sum('fee');
                $totalPayments_off = \App\Models\Transaction::where('shop_id', $shopId)
                    ->where('type', 18)
                    ->whereBetween('tarikh_ghest', [$startOfMonth, $endOfMonth])
                    ->sum('fee');

                $remaining = $totalSales - $totalPayments - $totalPayments_off;

                $report[] = [
                    'month' => $monthStr,
                    'total_sales' => $totalSales,
                    'total_payments' => $totalPayments,
                    'total_payments_off' => $totalPayments_off,
                    'remaining' => $remaining,
                ];

                $sumSales += $totalSales;
                $sumPayments += $totalPayments;
                $sumPaymentsOff += $totalPayments_off;

                $sumRemaining += $remaining;

                // ⬅️ افزایش دقیق یک ماه شمسی
                $start = $start->addMonths(1);
            }

            // ✅ ردیف جمع کل
            $report[] = [
                'month' => 'جمع کل',
                'total_sales' => $sumSales,
                'total_payments' => $sumPayments,
                'total_payments_off' => $sumPaymentsOff,

                'remaining' => $sumRemaining,
            ];
        }

        return view('admin.report_g', [
            'shop' => \App\Models\Shop::find($shopId),
            'report' => $report,
        ]);
    }

    public function conditions($id)
    {
        $conditions = Condition::where('shop_id', $id)->get();
        $shop = Shop::find($id);
        return view('admin.conditions', compact('conditions', 'shop'));
    }
    public function conditions_post(Request $req, $id)
    {
        $condition = new Condition();
        $condition->month = $req->month;
        $condition->percent = $req->percent;
        $condition->advance_payment = $req->advance_payment;
        $condition->shop_id = $id;
        $condition->save();
        return back()->with('success', 'قرارداد با موفقیت ثبت شد');
    }
    public function conditions_delete($id)
    {
        $condition = Condition::find($id);
        $condition->delete();
        return back();
    }
    public function role()
    {
        $role = new Role();
        $role->name = 'user';
        $role->display_name = 'user';
        $role->description = 'user';
        $role->save();

        $role = new Role();
        $role->name = 'admin';
        $role->display_name = 'admin';
        $role->description = 'admin';
        $role->save();

        $role = new Role();
        $role->name = 'marketer';
        $role->display_name = 'marketer';
        $role->description = 'marketer';
        $role->save();

        $role = new Role();
        $role->name = 'content_manager';
        $role->display_name = 'محتوا';
        $role->description = 'content';
        $role->save();

        $role = new Role();
        $role->name = 'shop_admin';
        $role->display_name = 'shop_admin';
        $role->description = 'shop_admin';
        $role->save();

        $role = new Role();
        $role->name = 'Employee_admin';
        $role->display_name = 'Employee_admin';
        $role->description = 'Employee_admin';
        $role->save();
    }

    public function shopsAdd()
    {
        $categories = Category::get();

        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'marketer');
        })->get();

        return view('admin.add_shop', compact('categories', 'users'));
    }

    public function shopsAddPost(Request $request)
    {

        $validator = Validator::make(
            request()->all(),
            [
                'name' => 'required',
                'slug_name' => 'required',
                'mobile' => 'required|numeric|digits:11',
                'address' => 'required',
                'description' => 'required',
                'display' => 'required|min:0|max:1',
                'firstname' => 'required',
                'advance_payment' => 'required',
                'profit' => 'required',
                'installments_number' => 'required',
                'lastname' => 'required',
                'nationalcode' => 'required|numeric|digits:10',
            ],
            [
                'name.required' => 'نام فروشگاه الزامی است.',

                'slug_name.required' => 'نام سئو الزامی است.',

                'mobile.required' => 'شماره موبایل الزامی است.',
                'mobile.numeric' => 'شماره موبایل باید فقط شامل عدد باشد.',
                'mobile.digits' => 'شماره موبایل باید دقیقاً ۱۱ رقم باشد.',

                'address.required' => 'آدرس الزامی است.',

                'description.required' => 'وارد کردن توضیحات الزامی است.',

                'display.required' => 'وضعیت نمایش فروشگاه را مشخص کنید.',
                'display.min' => 'مقدار نمایش نامعتبر است.',
                'display.max' => 'مقدار نمایش نامعتبر است.',

                'firstname.required' => 'نام مدیر فروشگاه الزامی است.',

                'lastname.required' => 'نام خانوادگی مدیر فروشگاه الزامی است.',

                'advance_payment.required' => 'درصد پیش پرداخت را وارد کنید.',

                'profit.required' => 'درصد سود ماهانه را وارد کنید.',

                'installments_number.required' => 'تعداد اقساط را وارد کنید.',

                'nationalcode.required' => 'کد ملی الزامی است.',
                'nationalcode.numeric' => 'کد ملی باید فقط شامل عدد باشد.',
                'nationalcode.digits' => 'کد ملی باید دقیقاً ۱۰ رقم باشد.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user = Auth::user();
        $admin = User::where('mobile', $request->mobile)->first();

        if (!$admin) {
            $admin = new User();
            $admin->profpic_id = '1';
            $admin->wallet = 0;
            $admin->mobile = $request->mobile;

            $admin->name = $request->firstname;
            $admin->family = $request->lastname;
            $admin->nationalcode = $request->nationalcode;


            $admin->active = '1';
            $admin->adad = '0';
            //nyyy
            $admin->coin = '250';
            //eyyy

            $admin->save();
            $admin->addRole('shop_admin');
        } else {
            $admin->name = $request->firstname;
            $admin->family = $request->lastname;
            $admin->nationalcode = $request->nationalcode;
            $admin->save();
        }


        $shop_user = new User();
        $shop_user->profpic_id = '1';
        $shop_user->wallet = 0;
        $shop_user->name = 'ادمین ' . $request->name;
        $mobile = $request->mobile;
        if (substr($mobile, 0, 1) == '0') {
            $mobile = '9' . substr($mobile, 1);
        }
        $shop_user->mobile = $mobile;
        $shop_user->active = '1';
        $shop_user->adad = '0';
        $shop_user->save();
        $shop_user->addRole('shop_user');


        $shop = new Shop();
        $shop->name = $request->name;
        $shop->user_id = $admin->id;
        $shop->mobile = $request->mobile;
        $shop->telephone = $request->phone;
        $shop->address = $request->address;
        $shop->sale_type = $request->sale_type;
        $shop->decription = $request->description;
        $shop->refferer_id = $user->id;
        $shop->off_title = $request->off;
        $shop->user_off = $request->user_off;
        $shop->slug_name = $request->slug_name;
        $shop->slug_code = $request->slug_name;
        $shop->display = $request->display;
        $shop->refferer_id = $user->id;
        $shop->profit = $request->profit;
        $shop->installments_number = $request->installments_number;
        $shop->advance_payment = $request->advance_payment;


        if ($request->hasFile('cover')) {
            $image = $request->file('cover');
            $slide_image = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = 'img/cover';
            $image->move($destinationPath, $slide_image);
            $image = new Images();
            $image->address = 'img/cover/' . $slide_image;
            $image->save();
            $shop->cover_id = $image->id;
        } else {
            $shop->cover_id = 3; // default cover
        }

        if ($request->hasFile('icon')) {
            $image = $request->file('icon');
            $slide_image = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = 'img/icon';
            $image->move($destinationPath, $slide_image);
            $image = new Images();
            $image->address = 'img/icon/' . $slide_image;
            $image->save();
            $shop->icon_id = $image->id;
        } else {
            $shop->icon_id = 2; // default icon
        }

        $shop->save();

        $shop->slug_code = $shop->id;
        $shop->save();
        $categories = Category::get();
        foreach ($categories as $category) {
            $id = $category->id;
            if (isset($request->$id)) {
                $category_shop = new CategoryShop();
                $category_shop->category_id = $id;
                $category_shop->shop_id = $shop->id;
                $category_shop->save();
            }
        }

        $shop_u = new ShopUser();
        $shop_u->shop_id = $shop->id;
        $shop_u->user_id = $shop_user->id;
        $shop_u->save();





        $condition = new Condition();
        $condition->month = $request->installments_number;
        $condition->percent = $request->profit;
        $condition->advance_payment = $request->advance_payment;
        $condition->shop_id = $shop->id;
        $condition->save();
        return redirect('/admin/shops/');
    }

    public function shopsEdit($id)
    {
        $categories = Category::get();
        $shop = Shop::find($id);
        $find_categorys = CategoryShop::where('shop_id', $id)->get();
        $shopuser = User::find($shop->user_id);
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'marketer');
        })->get();
        $bank = BankAccount::where('shop_id', $id)->where('status', '1')->first();
        return view('admin.edit_shop', compact('shop', 'shopuser', 'categories', 'find_categorys', 'users', 'bank'));
    }

    public function shopsEditPost(Request $request, $id)
    {
        $user = Auth::user();
        $shop = Shop::find($id);
        $shopuser = User::find($shop->user_id);
        if ($user->hasRole('admin')) {
            $shop->name = $request->name;
            $shop->mobile = $request->mobile;
            $shop->telephone = $request->phone;
            $shop->address = $request->address;
            $shop->decription = $request->description;
            $shop->off = $request->off;
            // $shop->user_off = $request->user_off;
            $shop->display = $request->display;
            $shop->refferer_id = $request->marketer;
            $shop->slug_name = $request->slug_name;
            $shop->profit = $request->profit;
            $shop->installments_number = $request->installments_number;
            $shop->advance_payment = $request->advance_payment;
            $shopuser->name = $request->firstname;
            $shopuser->family = $request->lastname;
            $shopuser->nationalcode = $request->nationalcode;
            $shopuser->save();
        }
        if ($user->hasRole('content_manager') || $user->hasRole('admin')) {
            if ($request->hasFile('cover')) {
                $image = $request->file('cover');
                $slide_image = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = 'img/cover';
                $image->move($destinationPath, $slide_image);
                $image = new Images();
                $image->address = 'img/cover/' . $slide_image;
                $image->save();
                $shop->cover_id = $image->id;
            }

            if ($request->hasFile('icon')) {
                $image = $request->file('icon');
                $slide_image = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = 'img/icon';
                $image->move($destinationPath, $slide_image);
                $image = new Images();
                $image->address = 'img/icon/' . $slide_image;
                $image->save();
                $shop->icon_id = $image->id;
            }
        }

        $shop->save();
        if ($user->hasRole('admin')) {
            $shop->slug_code = $shop->id;
        }
        $shop->save();
        if ($user->hasRole('admin')) {
            $deletes = CategoryShop::where('shop_id', $shop->id)->get();
            foreach ($deletes as $delete) {
                $delete->delete();
            }
            $categories = Category::get();
            foreach ($categories as $category) {
                $id = $category->id;
                if (isset($request->$id)) {
                    $category_shop = new CategoryShop();
                    $category_shop->category_id = $id;
                    $category_shop->shop_id = $shop->id;
                    $category_shop->save();
                }
            }
            if (isset($request->shaba)) {
                $ba = BankAccount::where('shop_id', $shop->id)->where('status', '1')->first();
                if (!$ba) {
                    $ba = new BankAccount();
                }
                $ba->shaba = trim(strtolower($request->shaba), 'ir');
                $ba->bank_name = $request->bank;
                $ba->owner = $request->owner;
                $ba->shop_id = $shop->id;
                $ba->status = 1;
                $ba->save();
            }
        }
        return redirect()->back();
    }
 
    public function tasvie_riz_order(Request $req, $id)
    {
        $monthYear = $req->query('month_year');
    
        if (!preg_match('#^\d{4}-(0[1-9]|1[0-2])$#', $monthYear)) {
            return redirect()->back()->with('error', 'فرمت تاریخ باید مثل 1404-01 باشد.');
        }
    
        [$fromYear, $fromMonth] = explode('-', $monthYear);
    
        $fromYear = (int)$fromYear;
        $fromMonth = (int)$fromMonth;
    
        $start = new \Morilog\Jalali\Jalalian($fromYear, $fromMonth, 1);
        
        // تبدیل ماه شمسی به بازه زمانی میلادی
        $startOfMonth = $start->toCarbon(); // روز اول ماه
        $endOfMonth = $start->getEndDayOfMonth()->toCarbon()->setTime(23, 59, 59); // روز آخر ماه
        
        // گرفتن اطلاعات فروشگاه
        $shop = \App\Models\Shop::find($id);
        
        if (!$shop) {
            return redirect()->back()->with('error', 'فروشگاه یافت نشد');
        }
        
        $fileName = "ریز_خرید_{$shop->name}_{$fromYear}_{$fromMonth}.xlsx";
    
        return Excel::download(
            new ShopRizOrderExport($startOfMonth, $endOfMonth, $id, $shop->name),
            $fileName
        );
    }

    public function form_tasvie($id)
    {
        $shop = Shop::find($id);
        return view('admin.form_tasvie', compact('shop'));
    }
    public function factor_tasvie(Request $request)
    {
        // تبدیل اعداد فارسی
        $convertToEnglish = function ($string) {
            $persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            return str_replace($persianDigits, $englishDigits, $string);
        };

        $tasvie_shop = new Transaction();

        // مبلغ
        $fee = (int) str_replace(',', '', $request->price);

        // قرارداد
        $contract = Contract::where('shop_id', $request->id)
            ->where('delay', $request->delay)
            ->first();

        $tasvie_shop->shop_id  = $request->id;
        $tasvie_shop->type     = 5;
        $tasvie_shop->fee      = $fee;
        $tasvie_shop->value    = $fee;
        $tasvie_shop->darsad   = $request->darsad;
        $tasvie_shop->description = $request->description;
        // محاسبه تخفیف
        $tasvie_shop->fee_off = $contract
            ? $fee - (($fee * $contract->off) / 100)
            : $fee;

        // tarikh_ghest ← از ماه/سال با روز 01
        if ($request->month_year) {
            $monthYear = $convertToEnglish($request->month_year); // 2025-07
            $tarikh = $monthYear . '-01'; // 2025-07-01
            $tasvie_shop->tarikh_ghest = \Hekmatinasser\Verta\Verta::parse($tarikh)->toCarbon()->startOfDay();
        }

        // zaman_pardakht ← تاریخ پرداخت
        if ($request->date) {
            $datePay = $convertToEnglish($request->date);
            $tasvie_shop->zaman_pardakht = \Hekmatinasser\Verta\Verta::parse($datePay)->toCarbon()->startOfDay();
        }

        $tasvie_shop->save();
        $desc_id = $tasvie_shop->id;


        if ($contract) {
            $tasvie_shop = new Transaction();

            // مبلغ
            $fee = (int) str_replace(',', '', $request->price);

            // قرارداد

            $tasvie_shop->shop_id  = $request->id;
            $tasvie_shop->type     = 18;
            $tasvie_shop->description = 'تخفیف برای تسویه با شناسه ' . $desc_id . '';
            $tasvie_shop->fee      = (($fee * $contract->off) / 100);
            $tasvie_shop->value    = (($fee * $contract->off) / 100);
            $tasvie_shop->darsad   = $request->darsad;

            // محاسبه تخفیف
            $tasvie_shop->fee_off = $contract
                ? $fee - (($fee * $contract->off) / 100)
                : $fee;

            // tarikh_ghest ← از ماه/سال با روز 01
            if ($request->month_year) {
                $monthYear = $convertToEnglish($request->month_year); // 2025-07
                $tarikh = $monthYear . '-01'; // 2025-07-01
                $tasvie_shop->tarikh_ghest = \Hekmatinasser\Verta\Verta::parse($tarikh)->toCarbon()->startOfDay();
            }

            // zaman_pardakht ← تاریخ پرداخت
            if ($request->date) {
                $datePay = $convertToEnglish($request->date);
                $tasvie_shop->zaman_pardakht = \Hekmatinasser\Verta\Verta::parse($datePay)->toCarbon()->startOfDay();
            }

            $tasvie_shop->save();
        }
        return redirect()->back()->with('suc', 'با موفقیت ثبت شد');
    }
    public function audit($id)
    {

        $value = 0;
        $transactions = Order::where('shop_id', $id)->where('status', 2)->get();
        foreach ($transactions as $order) {
            $dateTime = \Morilog\Jalali\Jalalian::fromDateTime($order->created_at);
            $order['tarikh'] = $dateTime->format('Y/m/d'); // تاریخ شمسی
            $order['saat'] = $dateTime->format('H:i');    // ساعت به صورت 24 ساعته
            $value += $order->price;
        }
        $shop = Shop::find($id);

        $maDadim = Transaction::where('shop_id', $id)->where('type', 5)->get();
        $maDadim_off = Transaction::where('shop_id', $id)->where('type', 18)->get();
        $value_off = 0;
        foreach ($maDadim_off as $maDadim_of) {
            $value_off += $maDadim_of->value;
        }
        $mande = $value - $value_off;
        $dadim = 0;
        foreach ($maDadim as $priceItem) {

            
            $dateTime = \Morilog\Jalali\Jalalian::fromDateTime($priceItem->created_at);
            $priceItem['tarikh'] = $dateTime->format('Y/m/d'); // تاریخ شمسی
            $priceItem['saat'] = $dateTime->format('H:i');    // ساعت به صورت 24 ساعته

            $dadim += $priceItem->value;
            if ($priceItem->order_id != null) {
                $priceItem['price'] = (Order::where('id', $priceItem->order_id)->first()->price);
            } else {
                $priceItem['price'] = 0;
            }
        }

        foreach ($maDadim_off as $priceItem) {

            $dateTime = \Morilog\Jalali\Jalalian::fromDateTime($priceItem->created_at);
            $priceItem['tarikh'] = $dateTime->format('Y/m/d'); // تاریخ شمسی
            $priceItem['saat'] = $dateTime->format('H:i');    // ساعت به صورت 24 ساعته


            if ($priceItem->order_id != null) {
                $priceItem['price'] = (Order::where('id', $priceItem->order_id)->first()->price);
            } else {
                $priceItem['price'] = 0;
            }
        }
        return view('admin.shop_audit', compact('transactions', 'maDadim', 'maDadim_off', 'shop', 'value', 'dadim', 'value_off', 'mande'));
    }



    public function auditExcel(Request $request, $id)
    {
        if ($request->to_date < $request->from_date) {
            return redirect()->back()->with('error', 'تاریخ ها را درست وارد کنید');
        }
        $from = $this->convertPersianNumber($request->from_date);
        $to   = $this->convertPersianNumber($request->to_date);

        $separator = '/';

        [$fy, $fm, $fd] = explode($separator, $from);
        [$ty, $tm, $td] = explode($separator, $to);

        $fromDate = Jalalian::fromFormat('Y/m/d', "$fy/$fm/$fd")
            ->toCarbon()->startOfDay();

        $toDate = Jalalian::fromFormat('Y/m/d', "$ty/$tm/$td")
            ->toCarbon()->endOfDay();

        return Excel::download(
            new AuditExport($id, $fromDate, $toDate),
            "audit.xlsx"
        );
    }
    public function auditPreview(Request $request, $id)
    {
        // if ($request->to_date < $request->from_date) {
        //     return redirect()->back()->withErrors(['error' => 'تاریخ ها را درست وارد کنید']);
        // }
        $from = $this->convertPersianNumber($request->from_date);
        $to   = $this->convertPersianNumber($request->to_date);
        $separator = '/';

        [$fy, $fm, $fd] = explode($separator, $from);
        [$ty, $tm, $td] = explode($separator, $to);

        $fromDate = Jalalian::fromFormat('Y/m/d', "$fy/$fm/$fd")
            ->toCarbon()->startOfDay();

        $toDate = Jalalian::fromFormat('Y/m/d', "$ty/$tm/$td")
            ->toCarbon()->endOfDay();

        // داده‌هایی مشابه اکسل:


        $installments = Order::where('shop_id', $id)
            ->where('status', 2)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get();

        $settlements =  Transaction::where('shop_id', $id)
            ->where('type', 5)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get();

        $discounts = Transaction::where('shop_id', $id)
            ->where('type', 18)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get();

        return view('admin.partials.audit_preview', compact(
            'installments',
            'settlements',
            'discounts'
        ));
    }


    public function bill($id)
    {
        $user = Auth::user();
        $shop = Shop::findOrFail($id);


        // 1. دریافت تراکنش‌ها و تبدیل تاریخ‌ها به شمسی
        $transactions = Transaction::where('type', '5')
            ->orderBy('tarikh_ghest', 'asc')->where('shop_id', $shop->id)
            ->get();

        // 2. گروه‌بندی تراکنش‌ها بر اساس ماه شمسی و محاسبه مجموع مبالغ
        $monthlyInstallments = $transactions->map(function ($transaction) {
            // تبدیل تاریخ میلادی به شمسی و استخراج سال و ماه
            $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->tarikh_ghest);
            $jalali = Jalalian::fromCarbon($carbonDate);
            $yearMonth = $jalali->format('Y/m'); // فرمت YYYY/MM برای گروه‌بندی

            return [
                'year_month' => $yearMonth,
                'month_name' => $jalali->format('%B %Y'), // نام کامل ماه برای نمایش (اختیاری)
                'value' => (int) $transaction->value, // تبدیل به عدد صحیح برای جمع زدن
            ];
        })->groupBy('year_month')->map(function ($group, $yearMonth) {
            // محاسبه مجموع مبلغ برای هر ماه
            $totalValue = $group->sum('value');
            // شما می‌توانید از $group->first()['month_name'] هم برای نام ماه استفاده کنید اگر بخواهید
            return [
                'year_month_display' => $yearMonth, // نمایش ماه به فرمت YYYY/MM
                'total_amount' => $totalValue,
            ];
        })->values(); // تبدیل مجموعه گروه‌بندی شده به آرایه معمولی



        // $currentMonth = Carbon::now()->month; 
        // $months = range(12, $currentMonth + 12); 
        // $data = [];

        // foreach ($months as $m) {
        //     $month = ($m > 12) ? $m - 12 : $m; 
        //     $year = ($m > 12) ? Carbon::now()->year : Carbon::now()->year - 1;

        //     $sales = Order::where('shop_id', $shop->id)
        //         ->whereYear('created_at', $year)
        //         ->whereMonth('created_at', $month)
        //         ->sum('price'); 

        //     $settlements = Transaction::where('shop_id', $shop->id)
        //         ->whereYear('created_at', $year)
        //         ->whereMonth('created_at', $month)
        //         ->sum('value');

        //         $settlements_off = Transaction::where('shop_id', $shop->id)->where('type','18')
        //         ->whereYear('created_at', $year)
        //         ->whereMonth('created_at', $month)
        //         ->sum('value');

        //     $data[] = [
        //         'month' => $month,
        //         'year' => $year,
        //         'sales' => $sales,
        //         'settlements_off' => $settlements_off,
        //         'settlements' => $settlements
        //     ];
        // }

        return view('admin.bill', compact('shop', 'monthlyInstallments'));
    }

    public function chrgewallet(request $req, $id)
    {
        $validator = Validator::make(request()->all(), [
            'wallet' => 'required|numeric',
        ], [
            'wallet.required' => 'رقم شارژ را وارد کنید',
            'wallet.numeric' => ' شارژ را عددی وارد کنید',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } {
            $user = User::find($id);
            if ($user) {
                $transaction=new Transaction();
                $transaction->user_id=$user->id;
                $transaction->value=$req->wallet;
                $transaction->description='شارژ '.$user->name.' '.$user->family.'از'.$user->wallet.'به'.$req->wallet.'شارژ شد';
                $transaction->type=20;
                $transaction->save();
                $user->wallet = $req->wallet;
                $user->init_wallet = $req->wallet;
                $user->save();
                return redirect()->back();
            } else {
                abort(404);
            }
        }
    }

    public function dateOfTasvieh()
    {
        return view('admin.dateOfTasvieh');
    }

    public function excelTasvieha(Request $request)
    {
        if ($request->toDate < $request->fromDate) {
            return redirect()->back()->withErrors(['error' => 'تاریخ ها را درست وارد کنید']);
        }

        $fromMiladiDate = Jalalian::fromFormat('Y/m/d', $request->fromDate)->toCarbon()->format('Y-m-d');
        $toMiladiDate = Jalalian::fromFormat('Y/m/d', $request->toDate)->toCarbon()->format('Y-m-d');

        return Excel::download(new TasviehExport($fromMiladiDate, $toMiladiDate), 'tasvieh.xlsx');
    }

    public function users_trans(){
        return view('admin.users_trans');
    }
    public function users_trans_excel(Request $request)
    {
        $from = $request->input('from_date');
    
        $data = $this->generateUsersTransData($from);

    
        // اگه خطایی برگشت داده باشه
        if ($data instanceof \Illuminate\Http\RedirectResponse) {
            return $data;
        }

        

        return Excel::download(new UsersTransExport($data), 'لیست-اقساط-کاربران-' . $from . '.xlsx');
    }
    
    // تابع تولید داده
    private function generateUsersTransData($from)
    {
        // اعتبارسنجی فرمت
        if (!preg_match('#^\d{4}-(0[1-9]|1[0-2])$#', $from)) {
            return redirect()->back()->with('error', 'فرمت تاریخ باید مثل 1404-01 باشد.');
        }
    
        [$fromYear, $fromMonth] = explode('-', $from);
    
        $fromYear = (int)$fromYear;
        $fromMonth = (int)$fromMonth;
    
        try {
            $start = new \Morilog\Jalali\Jalalian($fromYear, $fromMonth, 1);
            
            // تبدیل ماه شمسی به بازه زمانی میلادی
            $startOfMonth = $start->toCarbon(); // روز اول ماه
            $endOfMonth = $start->getEndDayOfMonth()->toCarbon()->setTime(23, 59, 59); // روز آخر ماه
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطا در تبدیل تاریخ.');
        }

        
            $users=User::whereIn('type',[1,2])->get();
        // محاسبه مجموع value برای هر کاربر
        foreach ($users as $user) {
            $order=Order::where('user_id',$user->id)->where('status',2)->pluck('id');
            $user_trans = Transaction::where('user_id', $user->id)->where('type',15)
            ->whereBetween('tarikh_ghest', [$startOfMonth, $endOfMonth])
            ->whereIn('order_id',$order)
            ->sum('value');
            $user['trans'] = $user_trans;
        }
    
        $data = collect();
        foreach ($users as $user) {
            $data->push([
                'نام' => $user->name ?? '---',
                'فامیل' => $user->family ?? '---',
                'موبایل' => $user->mobile ?? '---',
                'کد ملی' => $user->nationalcode ?? '---',
                'جمع قسط این ماه' => $user->trans,
                'شارژ اولیه' => $user->init_wallet,
                'نوع' => $user->type,

            ]);
        }
    
        return $data;
    }
    
    
    public function reportUser(Request $request)
    {
        $fromShamsi = $request->input('from_date'); // مثال: 1402/01/01
        $toShamsi = $request->input('to_date');   // مثال: 1402/02/01

        // تبدیل شمسی به میلادی با Jalalian
        $fromMiladi = Jalalian::fromFormat('Y/m/d', $fromShamsi)->toCarbon()->startOfDay();
        $toMiladi = Jalalian::fromFormat('Y/m/d', $toShamsi)->toCarbon()->endOfDay();

        $users = User::get();
        foreach ($users as $user) {
            $transactions = Transaction::where('user_id', $user->id)
                ->where('tarikh_ghest', '>=', $fromMiladi)
                ->where('tarikh_ghest', '<=', $toMiladi)->sum('value');
            $user['jam'] = $transactions;
        }

        $reports = $users;

        return view('admin.user_report', compact('reports'));
    }

    public function showUserTransactions(Request $request, $userId)
    {
        $fromShamsi = $request->input('from_date');
        $toShamsi = $request->input('to_date');

        $fromMiladi = Jalalian::fromFormat('Y/m/d', $fromShamsi)->toCarbon()->startOfDay();
        $toMiladi = Jalalian::fromFormat('Y/m/d', $toShamsi)->toCarbon()->endOfDay();

        $user = User::findOrFail($userId);

        $transactions = DB::table('transactions')
            ->leftJoin('orders', 'transactions.order_id', '=', 'orders.id')
            ->where('transactions.user_id', $userId)
            ->whereBetween('transactions.tarikh_ghest', [$fromMiladi, $toMiladi])
            ->select('transactions.*', 'orders.price as order_price')
            ->get();

        return view('admin.user_transactions', compact('user', 'transactions', 'fromShamsi', 'toShamsi'));
    }
    public function order_details($id)
    {
        $order = Order::find($id);
        if ($order) {
            $user = User::find($order->user_id);
            $order['user'] = $user->fullname();
            $order['mobile'] = $this->hideMobile($user->mobile);
            $order['time'] = $this->convertToPersianTimeadmin($order->updated_at);
            $Transactions = Transaction::where('order_id', $order->id)->get();
            foreach ($Transactions as $Transaction) {
                $Transaction['mobile'] = $this->hideMobile($Transaction->user->mobile);
                $Transaction->tarikh_shamsi = Jalalian::fromCarbon($Transaction->tarikh_ghest)
                    ->format('Y/m/d H:i');
            }
            return view('admin.order_details', compact('order', 'Transactions'));
        } else {
            abort(404);
        }
    }
    public function tasvie_order($id)
    {
        $order = Order::find($id);
        if ($order && $order->status == 2 && $order->status_tasvie == 0) {
            $user = User::find($order->user_id);
            return view('admin.tasvie_order', compact('order', 'user'));
        } else {
            abort(404);
        }
    }

    public function save_order_tasvie(request $request, $id)
    {
        $order = Order::find($id);
        $user = Auth::user();
        if ($order && $order->status == 2 && $order->status_tasvie == 0) {
            $validator = Validator::make(
                request()->all(),
                [
                    'traking_code' => 'required|numeric',
                    'price' => 'required|numeric',
                    'fee' => 'required|numeric',
                    'Money_deposit' => 'required|numeric|min:1|max:4',
                ],
                [
                    'Money_deposit.min' => 'مقدار روش واریز پول نا معنبر است',
                    'Money_deposit.max' => 'مقدار روش واریز پول نا معنبر است',
                ]
            );
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
            // $shop = Shop::find($order->shop_id);
            // $shop->wallet -= $request->price;
            // $shop->save();

            $tasvie_shop = new Transaction();
            $tasvie_shop->order_id = $order->id;
            $tasvie_shop->user_id = $user->id;
            $tasvie_shop->shop_id = $order->shop_id;
            $tasvie_shop->value = $order->price;
            $tasvie_shop->type = '5';
            $tasvie_shop->tasvie_type = $request->Money_deposit;
            $tasvie_shop->traking_code = $request->traking_code;
            $tasvie_shop->fee = $request->fee;
            $tasvie_shop->fee_type = "Merchant";
            if ($request->receipt != null) {
                $image = $request->receipt;
                $receipt_image = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = 'img/receipts';
                $image->move($destinationPath, $receipt_image);
                $image = new Images();
                $image->address = 'img/receipts/' . $receipt_image;
                $image->save();
                $tasvie_shop->receipt = $image->id;
            }
            $tasvie_shop->save();

            $order->status_tasvie = '3';
            $order->tasvie_transaction_id = $tasvie_shop->id;
            $order->save();

            return redirect('/admin/list/sale');
        } else {
            abort(404);
        }
    }
    public function user_show($id)
    {
        $user = User::find($id);
        if ($user) {
            $shop = Shop::where('user_id', $user->id)->get();
            $banks = BankAccount::where('user_id', $user->id)->get();
            $orders = Order::where('user_id', $user->id)->get();
            $Transactions = Transaction::where('user_id', $user->id)->get();
            foreach ($orders as $order) {
                $user = User::find($order->user_id);
                $order['user'] = $user->fullname();
                $order['mobile'] = $this->hideMobile($user->mobile);
                $order['time'] = $this->convertToPersianTimeadmin($order->created_at);
                $shop_order = $order->shop_order;
                $order['shop_order_name'] = ($shop_order == null) ? '' : $shop_order->name;
            }
            $roles = Auth::user()->roles()->get();
            return view('admin.info_user', compact('user', 'roles', 'shop', 'banks', 'orders', 'Transactions'));
        } else {
            abort(404);
        }
    }
}
