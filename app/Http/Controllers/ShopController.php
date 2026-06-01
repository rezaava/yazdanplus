<?php

namespace App\Http\Controllers;

use App\Models\Condition;
use App\Models\Contract;
use App\Models\Shop;
use App\Models\Images;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

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
        $conditions=Condition::where('shop_id',$shop->id)->get();
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

        return view('dashboard.shop.vendor-shop', compact('shop', 'user', 'profile','conditions','icon', 'cover', 'menu'));
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
            // تبدیل به Carbon (میلادی)
            $carbon_shoro = $jalali->toCarbon();



            // پایان

            // / تاریخ شمسی به صورت رشته
            $jalaliDateTime = $akhar;

            // جدا کردن تاریخ و ساعت
            [$jalaliDate, $time] = explode(' ', $jalaliDateTime);
            [$year, $month, $day] = explode('-', $jalaliDate);

            // ساخت شیء Jalalian
            $jalali = new Jalalian((int)$year, (int)$month, (int)$day,23,59,59);

            // تبدیل به Carbon (میلادی)
            $carbon_akhar = $jalali->toCarbon();
            Log::info('carbon111:' . $carbon_shoro);
            Log::info('carbon222:' . $carbon_akhar);

            $startOfMonth = $carbon_shoro;
            $endOfMonth = $carbon_akhar;
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => 'خطا در تبدیل تاریخ']);
        }

        // 🔹 جمع فروش‌ها (status = 2 یا 8) برای همین فروشگاه
        $totalSales = \App\Models\Order::where('shop_id', $shopId)
            ->whereIn('status', [2])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('price');

        // 🔹 جمع پرداختی‌ها (type = 5) برای همین فروشگاه
        $totalPayments = \App\Models\Transaction::where('shop_id', $shopId)
            ->whereIn('type',[5,18])
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
        ->where('delay', $request->delay)
        ->first();
        $fee = (int) str_replace(',', '', $remaining);
        $off = $contract
        ? $fee - (($fee * $contract->off) / 100)
        : 0;
        return response()->json([
            'success' => true,
            'total_sales' => $totalSales,
            'total_payments' => $totalPayments,
            'remaining' => $remaining,
            'off' => $off
        ]);
    }
}
