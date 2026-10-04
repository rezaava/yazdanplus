<?php

namespace App\Http\Controllers;

use App\Exports\ArdeExport;
use App\Exports\Datis2Export;
use App\Exports\DatisExport;
use App\Exports\HoneyExport;
use App\Models\Condition;
use App\Models\Contact;
use App\Models\Contract;
use App\Models\Datis2Order;
use App\Models\Datis2ProductOrder;
use App\Models\Shop;
use App\Models\Images;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Product_orders;
use App\Models\Products;
use App\Models\stock_forms;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ShopController extends Controller
{

    public function shop($slug_code, Request $route)
    {
        // $url = redirect()->intended()->getTargetUrl();
        // $currentUrl = $route->fullUrl();
        // dd(str_replace('service-worker.js', '', url()->previous()));

        $shop = Shop::where('slug_code', $slug_code)->first();
        if ($shop) {
            $slug_name = $shop->slug_name;
            $url = "$slug_code/$slug_name";
            return redirect("/shop/$url");
        } else {
            abort(404);
        }
    }
    public function shop1($slug_code, $slug_name)
    {
        $shop = Shop::where('slug_code', $slug_code)->first();
        $conditions = Condition::where('shop_id', $shop->id)->get();
        $user = Auth::user();
        $menu = Menu::where('shop_id', $shop->id)->first();
        if ($shop) {
            $icon = Images::where('id', $shop->icon_id)->first();
            if ($icon == null) {
                $icon = null;
            }
            $cover = Images::where('id', $shop->cover_id)->first();
            if ($cover == null) {
                $cover = null;
            }
            if ($user) {
                $profile = Images::where('id', $user->profpic_id)->first();
                if ($profile == null) {
                    $profile = null;
                }
            } else {
                $user = null;
                $profile = null;
            }
        } else {
            abort(404);
        }

        return view('dashboard.shop.vendor-shop', compact('shop', 'user', 'profile', 'conditions', 'icon', 'cover', 'menu'));
    }



    public function getMonthSummary(Request $request)
    {
        $monthYear = $request->input('month_year');
        $shopId = $request->input('shop_id'); // از فرم دریافت می‌کنیم

        if (!$shopId) {
            return response()->json([
                'success' => false,
                'message' => 'شناسه فروشگاه ارسال نشده است.'
            ]);
        }

        // بررسی فرمت ورودی تاریخ (مثلاً 1404-08)
        if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $monthYear)) {
            return response()->json([
                'success' => false,
                'message' => 'فرمت ماه و سال معتبر نیست. (مثلاً 1404-08)',
            ]);
        }

        try {
            [$year, $month] = explode('-', $monthYear);
            // Log::info('shoro1:'.$year);
            // Log::info('payan1:'.$month);
            $shoro = $date = (new Jalalian($year, $month, 24))->getFirstDayOfMonth();
            $akhar = $date = (new Jalalian($year, $month, 24))->getEndDayOfMonth();

            // Log::info('shoro2:'.$shoro);
            // Log::info('payan2:'.$akhar);
            // / تاریخ شمسی به صورت رشته
            $jalaliDateTime = $shoro;

            // جدا کردن تاریخ و ساعت
            [$jalaliDate, $time] = explode(' ', $jalaliDateTime);
            [$year, $month, $day] = explode('-', $jalaliDate);

            // ساخت شیء Jalalian
            // $jalali = new Jalalian((int)$year, (int)$month, (int)$day);
            $jalali = new Jalalian((int)$year, (int)$month, (int)$day);
            $jalali_shoro = $jalali;
            // تبدیل به Carbon (میلادی)
            $carbon_shoro = $jalali->toCarbon();



            // پایان

            // / تاریخ شمسی به صورت رشته
            $jalaliDateTime = $akhar;

            // جدا کردن تاریخ و ساعت
            [$jalaliDate, $time] = explode(' ', $jalaliDateTime);
            [$year, $month, $day] = explode('-', $jalaliDate);

            // ساخت شیء Jalalian
            $jalali = new Jalalian((int)$year, (int)$month, (int)$day, 23, 59, 59);

            // تبدیل به Carbon (میلادی)
            $carbon_akhar = $jalali->toCarbon();
            // Log::info('carbon111:' . $carbon_shoro);
            // Log::info('carbon222:' . $carbon_akhar);

            $startOfMonth = $carbon_shoro;
            $endOfMonth = $carbon_akhar;
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => 'خطا در تبدیل تاریخ']);
        }

        $shop = Shop::find($shopId);
        if ($shop->transaction_type == 2) {

            $cont = Contract::where('shop_id', $shop->id)->first();
            // Log::info('cond::' . $cont);

            // $threeMonthsAgo = $startOfMonth->copy()->subMonths($condition->month)->startOfMonth();
            $threeMonthsAgo  = $jalali_shoro->subMonths($cont->delay - 1)->getFirstDayOfMonth();
            $carbon_aval = $threeMonthsAgo->toCarbon();
            $threeMonthsAgo = $carbon_aval;

            $totalSales = \App\Models\Order::where('shop_id', $shopId)
                ->whereIn('status', [2])
                ->whereBetween('created_at', [$threeMonthsAgo, $endOfMonth])
                ->sum('price');
            Log::info('kol::' . $totalSales);
        } else {
            $totalSales = \App\Models\Order::where('shop_id', $shopId)
                ->whereIn('status', [2])
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('price');
        }


        $totalPayments = \App\Models\Transaction::where('shop_id', $shopId)
            ->whereIn('type', [5, 18])
            ->whereBetween('tarikh_ghest', [$startOfMonth, $endOfMonth])
            ->sum('fee');
        // Log::info('shoro:'.$startOfMonth);
        // Log::info('payan:'.$endOfMonth);
        // Log::info('shop:'.$shopId);
        // Log::info('kol:'.$totalPayments);
        // 🔹 محاسبه مانده
        $remaining = $totalSales - $totalPayments;
        // Log::info('mande:'.$remaining);
        $contract = Contract::where('shop_id', $shopId)
            ->where('delay', '>=', $request->delay)
            ->first();
        $fee = (int) str_replace(',', '', $remaining);
        $off_total = $contract ? $fee - (($fee * $contract->off) / 100)
            : $fee;
        //  Log::info('contract:'.$contract);
        //  Log::info('off:'.$off);
        //  Log::info('req:'.$request->delay);
        $off = $remaining - $off_total;

        if ($shop->transaction_type == 2) {
            $off_total = ($off_total / $cont->delay);
        }
        return response()->json([
            'success' => true,
            'total_sales' => $totalSales,
            'total_payments' => $totalPayments,
            'remaining' => $remaining,
            'off_total' => $off_total,
            'off' => $off
        ]);
    }
    public function listShop()
    {
        $shops = Shop::where('display', 1)->orderBy('id', 'desc')->get();

        return view('listShop', compact('shops'));
    }
    public function contract_test()
    {

        // $shops=Shop::get();
        $orders = Order::get();
        foreach ($orders as $order) {
            $contract = Contract::where('shop_id', $order->shop_id)->first();
            if ($contract) {

                $order->contract_id = $contract->id;
                $order->save();
            }
        }

        return 1111111111;
    }
    public function sms_mobile()
    {

        // $shops=Shop::get();
        $users = User::get();
        foreach ($users as $user) {
            $user->sms_mobile = $user->mobile;
            $user->save();
        }

        return 1111111111;
    }

    public function datis()
    {
        $products = Products::where('status', 1)->where('type',1)->get();

        $user = Auth::user();

        // اگر کاربر لاگین نکرده
        if (!$user) {
            return view('datis', [
                'products' => $products,
                'order' => null,
                'purchasedProducts' => collect(),
                'profile' => null,
                'user' => null,
            ]);
        }

        // اطلاعات پروفایل
        $profile = Images::where('id', $user->profpic_id)->first();

        // آخرین خرید کاربر از Datis
        $order = Order::where('user_id', $user->id)
            ->where('shop_id', 8)
            ->where('status', 10)->where('datis_turn',2)
            ->latest()
            ->first();

        // محصولات خریداری شده
        $purchasedProducts = collect();

        if ($order) {
            $purchasedProducts = Product_orders::with('product')
                ->where('order_id', $order->id)
                ->get();
        }

        return view('datis', compact(
            'products',
            'order',
            'purchasedProducts',
            'profile'
        ));
    }

    public function datisBuy(Request $req)
    {
        $req->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.count' => 'required|integer|min:0',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($req, $user) {

            // پیدا کردن سفارش قبلی
            $order = Order::where('user_id', $user->id)
                ->where('shop_id', 8)
                ->where('status', 10)->where('datis_turn',2)
                ->latest()
                ->first();

            // اگر سفارش قبلی وجود نداشت، سفارش جدید بساز
            if (!$order) {

                $order = new Order();

                $order->user_id = $user->id;
                $order->shop_id = 8;
                $order->status = 10;
                $order->datis_turn = 2;
                $order->price = 0;
                $order->final_price = 0;

                // خیلی مهم
                $order->save();
            }

            $totalPrice = 0;

            foreach ($req->products as $item) {

                $product = Products::findOrFail($item['product_id']);

                $count = (int) $item['count'];

                // قیمت با تخفیف
                $unitPrice = $product->price;

                if ($product->off_percent > 0) {

                    $unitPrice = $product->price -
                        (($product->price * $product->off_percent) / 100);
                }

                $totalPrice += $unitPrice * $count;


                // محصول قبلی در همین سفارش
                $productOrder = Product_orders::where('order_id', $order->id)
                    ->where('product_id', $product->id)
                    ->first();


                // اگر تعداد صفر شد
                if ($count == 0) {

                    if ($productOrder) {
                        $productOrder->delete();
                    }

                    continue;
                }


                // اگر قبلاً وجود داشته
                if ($productOrder) {

                    $productOrder->num = $count;
                    $productOrder->save();
                } else {

                    // محصول جدید
                    $productOrder = new Product_orders();

                    $productOrder->num = $count;

                    // Order حتماً قبلاً save شده
                    $productOrder->order_id = $order->id;

                    $productOrder->product_id = $product->id;

                    $productOrder->save();
                }
            }


            // آپدیت مبلغ سفارش
            $order->price = $totalPrice;
            $order->final_price = $totalPrice;

            $order->save();
        });


        return redirect()
            ->back()
            ->with('suc', 'خرید با موفقیت ثبت شد');
    }


    public function datisExcel()
    {
        return Excel::download(
            new DatisExport,
            'datis-orders.xlsx'
        );
    }

    public function datis_orders()
    {
        $user = Auth::user();
        $profile = Images::where('id', $user->profpic_id)->first();

        $orders = Order::with([
            'productOrders.product'
        ])
        ->where('user_id', $user->id)
        ->where('shop_id', 8)
        ->where('status', 10)
        ->latest()
        ->get();
    
        return view('datis_orders', compact('orders','profile'));
    }

    public function datis2()
    {
        $products = Products::where('status', 1)->get();

        $order = null;
        $purchasedProducts = collect();

        // دیگر اطلاعات کاربر از session نمی‌آید
        // فقط اگر کاربر لاگین کرده باشه برای پروفایل
        if (Auth::user()) {
            $user = Auth::user();
            $profile = Images::where('id', $user->profpic_id)->first();
        } else {
            $profile = null;
            $user = null;
        }

        return view('datis2', compact('products', 'order', 'purchasedProducts', 'profile', 'user'));
    }

    public function datisBuy2(Request $req)
    {
       // 1. اعتبارسنجی
$req->validate([
    'name' => 'required|string|max:100',
    'family' => 'required|string|max:100',
    'mobile' => 'required|string|size:11|regex:/^09[0-9]{9}$/',
    'meli_code' => 'required|string|size:10|regex:/^[0-9]{10}$/',
], [
    // پیام‌های اختصاصی
    'name.required' => 'وارد کردن نام الزامی است.',
    'name.string' => 'نام باید به صورت متن وارد شود.',
    'name.max' => 'نام نباید بیشتر از ۱۰۰ کاراکتر باشد.',

    'family.required' => 'وارد کردن نام خانوادگی الزامی است.',
    'family.string' => 'نام خانوادگی باید به صورت متن وارد شود.',
    'family.max' => 'نام خانوادگی نباید بیشتر از ۱۰۰ کاراکتر باشد.',

    'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
    'mobile.size' => 'شماره موبایل باید دقیقاً ۱۱ رقم باشد.',
    'mobile.regex' => 'شماره موبایل باید با 09 شروع شود و ۱۱ رقم باشد. مثال: 09123456789',

    'meli_code.required' => 'وارد کردن کد ملی الزامی است.',
    'meli_code.size' => 'کد ملی باید دقیقاً ۱۰ رقم باشد.',
    'meli_code.regex' => 'کد ملی باید فقط شامل اعداد باشد.',

 
]);
    
        $totalPrice = 0;
    
        DB::transaction(function () use ($req, &$totalPrice) {
    
            $order = Datis2Order::where('meli_code', $req->meli_code)
                ->where('mobile', $req->mobile)
                ->where('status', 10)
                ->latest()
                ->first();
    
            if (!$order) {
                $order = new Datis2Order();
                $order->name = $req->name;
                $order->family = $req->family;
                $order->mobile = $req->mobile;
                $order->meli_code = $req->meli_code;
                $order->status = 10;
                $order->datis_turn = 2;
                $order->price = 0;
                $order->final_price = 0;
                $order->save();
            }
    
            foreach ($req->products as $item) {
                $product = Products::findOrFail($item['product_id']);
                $count = (int) $item['count'];
    
                $unitPrice = $product->price;
                if ($product->off_percent > 0) {
                    $unitPrice = $product->price - (($product->price * $product->off_percent) / 100);
                }
    
                $totalPrice += $unitPrice * $count;
    
                $productOrder = Datis2ProductOrder::where('order_id', $order->id)
                    ->where('product_id', $product->id)
                    ->first();
    
                if ($count == 0) {
                    if ($productOrder) {
                        $productOrder->delete();
                    }
                    continue;
                }
    
                if ($productOrder) {
                    $productOrder->num = $count;
                    $productOrder->save();
                } else {
                    $productOrder = new Datis2ProductOrder();
                    $productOrder->num = $count;
                    $productOrder->order_id = $order->id;
                    $productOrder->product_id = $product->id;
                    $productOrder->save();
                }
            }
    
            $order->price = $totalPrice;
            $order->final_price = $totalPrice;
            $order->save();
        });
    
        // پیام موفقیت
        return redirect()
            ->back()
            ->with('suc', "✅ خرید شما با موفقیت ثبت شد.\n\nنام: {$req->name} {$req->family}\nشماره موبایل: {$req->mobile}\nکد ملی: {$req->meli_code}\n\nمجموع مبلغ: " . number_format($totalPrice) . " ریال");
    }

    public function datisExcel2()
{
    return Excel::download(new Datis2Export(), 'datis2_orders.xlsx');
}

public function stock()
{
    if (Auth::user()) {
        $user = Auth::user();
        $profile = Images::where('id', $user->profpic_id)->first();
    } else {
        $profile = null;
        $user = null;
    }
    return view('stock_form',compact('user','profile'));
}

public function stock_submit(Request $req)
{
    $validated = $req->validate([
        'type' => 'required|in:1,2,3,4,5,6',
        'name' => 'required|string|max:255',
        'family' => 'required|string|max:255',
        'mobile' => ['required', 'regex:/^09[0-9]{9}$/'],
        'national_code' => ['required', 'regex:/^[0-9]{10}$/'],
    ], [
        'type.required' => 'لطفاً وضعیت همکاری خود را انتخاب کنید.',
        'type.in' => 'وضعیت همکاری انتخاب شده معتبر نیست.',

        'name.required' => 'وارد کردن نام الزامی است.',
        'name.max' => 'نام نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

        'family.required' => 'وارد کردن نام خانوادگی الزامی است.',
        'family.max' => 'نام خانوادگی نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

        'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
        'mobile.regex' => 'شماره موبایل باید ۱۱ رقم و با ۰۹ شروع شود.',

        'national_code.required' => 'وارد کردن کد ملی الزامی است.',
        'national_code.regex' => 'کد ملی باید ۱۰ رقم عددی باشد.',
    ]);


    // بررسی تکراری بودن کد ملی
    $exists = stock_forms::where(
        'national_code',
        $validated['national_code']
    )->exists();

    if ($exists) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'با این کد ملی قبلاً درخواست ثبت شده است.');
    }


    $stock = new stock_forms();

    $stock->name = $validated['name'];
    $stock->family = $validated['family'];
    $stock->mobile = $validated['mobile'];
    $stock->national_code = $validated['national_code'];
    $stock->type = $validated['type'];

    $stock->save();


    return redirect()
        ->back()
        ->with('suc', 'درخواست شما با موفقیت ثبت شد.');
}

public function Arde()
{
    // فقط محصولات ارده
    $products = Products::where('status', 1)
        ->where('type', 2)
        ->get();

    $user = Auth::user();

    // اگر کاربر لاگین نکرده
    if (!$user) {
        return view('buy_product.arde', [
            'products' => $products,
            'order' => null,
            'purchasedProducts' => collect(),
            'profile' => null,
            'user' => null,
        ]);
    }

    // اطلاعات پروفایل
    $profile = Images::where('id', $user->profpic_id)->first();

    // آخرین خرید محصولات ارده
    $order = Order::where('user_id', $user->id)
        ->where('shop_id', 8)
        ->where('status', 11)
        ->where('datis_turn', 1)
        ->latest()
        ->first();

    // محصولات خریداری شده
    $purchasedProducts = collect();

    if ($order) {
        $purchasedProducts = Product_orders::with('product')
            ->where('order_id', $order->id)
            ->get();
    }

    return view('buy_product.arde', compact(
        'products',
        'order',
        'purchasedProducts',
        'profile',
        'user'
    ));
}

public function ardeBuy(Request $req)
{
    $req->validate([
        'products' => 'required|array',
        'products.*.product_id' => 'required|integer|exists:products,id',
        'products.*.count' => 'required|integer|min:0',
    ]);

    $user = Auth::user();

    DB::transaction(function () use ($req, $user) {

        // پیدا کردن سفارش قبلی ارده
        $order = Order::where('user_id', $user->id)
            ->where('shop_id', 8)
            ->where('status', 11)
            ->where('datis_turn', 1)
            ->first();

        // اگر سفارش قبلی وجود نداشت، سفارش جدید بساز
        if (!$order) {

            $order = new Order();

            $order->user_id = $user->id;
            $order->shop_id = 8;
            $order->status = 11;
            $order->datis_turn = 1;
            $order->price = 0;
            $order->final_price = 0;

            $order->save();
        }

        $totalPrice = 0;

        foreach ($req->products as $item) {

            $product = Products::where('type', 2)
                ->findOrFail($item['product_id']);

            $count = (int) $item['count'];

            // قیمت با تخفیف
            $unitPrice = $product->price;

            if ($product->off_percent > 0) {

                $unitPrice = $product->price -
                    (($product->price * $product->off_percent) / 100);
            }

            $totalPrice += $unitPrice * $count;

            // محصول قبلی در همین سفارش
            $productOrder = Product_orders::where('order_id', $order->id)
                ->where('product_id', $product->id)
                ->first();

            // اگر تعداد صفر شد
            if ($count == 0) {

                if ($productOrder) {
                    $productOrder->delete();
                }

                continue;
            }

            // اگر قبلاً وجود داشته
            if ($productOrder) {

                $productOrder->num = $count;
                $productOrder->save();

            } else {

                // محصول جدید
                $productOrder = new Product_orders();

                $productOrder->num = $count;
                $productOrder->order_id = $order->id;
                $productOrder->product_id = $product->id;

                $productOrder->save();
            }
        }

        // آپدیت مبلغ سفارش
        $order->price = $totalPrice;
        $order->final_price = $totalPrice;

        $order->save();
    });

    return redirect()
        ->back()
        ->with('suc', 'خرید با موفقیت ثبت شد');
}


public function ardeExcel()
{
    return Excel::download(
        new ArdeExport,
        'arde-orders.xlsx'
    );
}

public function honeyExcel()
{
    return Excel::download(
        new HoneyExport,
        'honey-orders.xlsx'
    );
}

public function Honey()
{
    $products = Products::where('status', 1)
        ->where('type', 3)
        ->get();

    $user = Auth::user();

    if (!$user) {
        return view('buy_product.honey', [
            'products' => $products,
            'order' => null,
            'purchasedProducts' => collect(),
            'profile' => null,
            'user' => null,
        ]);
    }

    $profile = Images::where('id', $user->profpic_id)->first();

    $order = Order::where('user_id', $user->id)
        ->where('shop_id', 8)
        ->where('status', 12)
        ->where('datis_turn', 1)
        ->latest()
        ->first();

    $purchasedProducts = collect();

    if ($order) {
        $purchasedProducts = Product_orders::with('product')
            ->where('order_id', $order->id)
            ->get();
    }

    return view('buy_product.honey', compact(
        'products',
        'order',
        'purchasedProducts',
        'profile',
        'user'
    ));
}

public function honeyBuy(Request $req)
{
    $req->validate([
        'products' => 'required|array',
        'products.*.product_id' => 'required|integer|exists:products,id',
        'products.*.count' => 'required|integer|min:0',
    ]);

    $user = Auth::user();

    DB::transaction(function () use ($req, $user) {

        $order = Order::where('user_id', $user->id)
            ->where('shop_id', 8)
            ->where('status', 12)
            ->where('datis_turn', 1)
            ->latest()
            ->first();

        if (!$order) {
            $order = new Order();
            $order->user_id = $user->id;
            $order->shop_id = 8;
            $order->status = 12;
            $order->datis_turn = 1;
            $order->price = 0;
            $order->final_price = 0;
            $order->save();
        }

        $totalPrice = 0;

        foreach ($req->products as $item) {

            $product = Products::where('type', 3)
                ->findOrFail($item['product_id']);

            $count = (int) $item['count'];

            $unitPrice = $product->price;

            if ($product->off_percent > 0) {
                $unitPrice = $product->price -
                    (($product->price * $product->off_percent) / 100);
            }

            $totalPrice += $unitPrice * $count;

            $productOrder = Product_orders::where('order_id', $order->id)
                ->where('product_id', $product->id)
                ->first();

            if ($count == 0) {

                if ($productOrder) {
                    $productOrder->delete();
                }

                continue;
            }

            if ($productOrder) {

                $productOrder->num = $count;
                $productOrder->save();

            } else {

                $productOrder = new Product_orders();
                $productOrder->num = $count;
                $productOrder->order_id = $order->id;
                $productOrder->product_id = $product->id;
                $productOrder->save();
            }
        }

        $order->price = $totalPrice;
        $order->final_price = $totalPrice;
        $order->save();
    });

    return redirect()
        ->back()
        ->with('suc', 'خرید عسل با موفقیت ثبت شد');
}

public function Aroosha()
{
    $products = Products::where('status', 1)
        ->where('type', 5)
        ->get();

    $user = Auth::user();

    if (!$user) {
        return view('buy_product.aroosha', [
            'products' => $products,
            'order' => null,
            'purchasedProducts' => collect(),
            'profile' => null,
            'user' => null,
        ]);
    }

    $profile = Images::where('id', $user->profpic_id)->first();

    $order = Order::where('user_id', $user->id)
        ->where('shop_id', 8)
        ->where('status', 14)
        ->where('datis_turn', 1)
        ->latest()
        ->first();

    $purchasedProducts = collect();

    if ($order) {
        $purchasedProducts = Product_orders::with('product')
            ->where('order_id', $order->id)
            ->get();
    }

    return view('buy_product.aroosha', compact(
        'products',
        'order',
        'purchasedProducts',
        'profile',
        'user'
    ));
}

public function arooshaBuy(Request $req)
{
    $req->validate([
        'products' => 'required|array',
        'products.*.product_id' => 'required|integer|exists:products,id',
        'products.*.count' => 'required|integer|min:0',
    ]);

    $user = Auth::user();

    DB::transaction(function () use ($req, $user) {

        $order = Order::where('user_id', $user->id)
            ->where('shop_id', 8)
            ->where('status', 14)
            ->where('datis_turn', 1)
            ->latest()
            ->first();

        if (!$order) {
            $order = new Order();
            $order->user_id = $user->id;
            $order->shop_id = 8;
            $order->status = 14;
            $order->datis_turn = 1;
            $order->price = 0;
            $order->final_price = 0;
            $order->save();
        }

        $totalPrice = 0;

        foreach ($req->products as $item) {

            $product = Products::where('type', 5)
                ->findOrFail($item['product_id']);

            $count = (int) $item['count'];

            $unitPrice = $product->price;

            if ($product->off_percent > 0) {
                $unitPrice = $product->price -
                    (($product->price * $product->off_percent) / 100);
            }

            $totalPrice += $unitPrice * $count;

            $productOrder = Product_orders::where('order_id', $order->id)
                ->where('product_id', $product->id)
                ->first();

            if ($count == 0) {

                if ($productOrder) {
                    $productOrder->delete();
                }

                continue;
            }

            if ($productOrder) {

                $productOrder->num = $count;
                $productOrder->save();

            } else {

                $productOrder = new Product_orders();
                $productOrder->num = $count;
                $productOrder->order_id = $order->id;
                $productOrder->product_id = $product->id;
                $productOrder->save();
            }
        }

        $order->price = $totalPrice;
        $order->final_price = $totalPrice;
        $order->save();
    });

    return redirect()
        ->back()
        ->with('suc', 'خرید محصولات آروشا با موفقیت ثبت شد');
}


public function Kerem()
{
    $products = Products::where('status', 1)
        ->where('type', 4)
        ->get();

    $user = Auth::user();

    if (!$user) {
        return view('buy_product.Kerem', [
            'products' => $products,
            'order' => null,
            'purchasedProducts' => collect(),
            'profile' => null,
            'user' => null,
        ]);
    }

    $profile = Images::where('id', $user->profpic_id)->first();

    $order = Order::where('user_id', $user->id)
        ->where('shop_id', 8)
        ->where('status', 13)
        ->where('datis_turn', 1)
        ->latest()
        ->first();

    $purchasedProducts = collect();

    if ($order) {
        $purchasedProducts = Product_orders::with('product')
            ->where('order_id', $order->id)
            ->get();
    }

    return view('buy_product.Kerem', compact(
        'products',
        'order',
        'purchasedProducts',
        'profile',
        'user'
    ));
}

public function keremBuy(Request $req)
{
    $req->validate([
        'products' => 'required|array',
        'products.*.product_id' => 'required|integer|exists:products,id',
        'products.*.count' => 'required|integer|min:0',
    ]);

    $user = Auth::user();

    DB::transaction(function () use ($req, $user) {

        $order = Order::where('user_id', $user->id)
            ->where('shop_id', 8)
            ->where('status', 13)
            ->where('datis_turn', 1)
            ->latest()
            ->first();

        if (!$order) {
            $order = new Order();
            $order->user_id = $user->id;
            $order->shop_id = 8;
            $order->status = 13;
            $order->datis_turn = 1;
            $order->price = 0;
            $order->final_price = 0;
            $order->save();
        }

        $totalPrice = 0;

        foreach ($req->products as $item) {

            $product = Products::where('type', 4)
                ->findOrFail($item['product_id']);

            $count = (int) $item['count'];

            $unitPrice = $product->price;

            if ($product->off_percent > 0) {
                $unitPrice = $product->price -
                    (($product->price * $product->off_percent) / 100);
            }

            $totalPrice += $unitPrice * $count;

            $productOrder = Product_orders::where('order_id', $order->id)
                ->where('product_id', $product->id)
                ->first();

            if ($count == 0) {

                if ($productOrder) {
                    $productOrder->delete();
                }

                continue;
            }

            if ($productOrder) {

                $productOrder->num = $count;
                $productOrder->save();

            } else {

                $productOrder = new Product_orders();
                $productOrder->num = $count;
                $productOrder->order_id = $order->id;
                $productOrder->product_id = $product->id;
                $productOrder->save();
            }
        }

        $order->price = $totalPrice;
        $order->final_price = $totalPrice;
        $order->save();
    });

    return redirect()
        ->back()
        ->with('suc', 'خرید محصولات  با موفقیت ثبت شد');
}

}
