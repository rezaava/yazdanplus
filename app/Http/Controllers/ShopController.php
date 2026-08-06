<?php

namespace App\Http\Controllers;

use App\Models\Condition;
use App\Models\Contact;
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
use App\Models\User;
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
            $jalali_shoro=$jalali;
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
            // Log::info('carbon111:' . $carbon_shoro);
            // Log::info('carbon222:' . $carbon_akhar);

            $startOfMonth = $carbon_shoro;
            $endOfMonth = $carbon_akhar;
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => 'خطا در تبدیل تاریخ']);
        }

        $shop=Shop::find($shopId);
        if($shop->transaction_type == 2){
            $cont=Contract::where('shop_id',$shop->id)->first();
            // Log::info('cond::' . $cont);

            // $threeMonthsAgo = $startOfMonth->copy()->subMonths($condition->month)->startOfMonth();
            $threeMonthsAgo  = $jalali_shoro->subMonths($cont->delay-1)->getFirstDayOfMonth();
            $carbon_aval = $threeMonthsAgo->toCarbon();
            $threeMonthsAgo=$carbon_aval;
            // Log::info('shoro:' . $startOfMonth);
            // Log::info('shoro::' . $threeMonthsAgo);
            // Log::info('payan::' . $endOfMonth);
// [2026-07-18 20:37:52] local.INFO: carbon222:2026-04-21 00:00:00  
// [2026-07-18 20:37:52] local.INFO: carbon222:2025-11-01 00:00:00 

            $totalSales = \App\Models\Order::where('shop_id', $shopId)
            ->whereIn('status', [2])
            ->whereBetween('created_at', [$threeMonthsAgo, $endOfMonth])
                ->sum('price');
                // Log::info('kol::' . $totalSales);

        }else{
            $totalSales = \App\Models\Order::where('shop_id', $shopId)
                ->whereIn('status', [2])
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('price');
        }

        
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
        ->where('delay','>=', $request->delay)
        ->first();
        $fee = (int) str_replace(',', '', $remaining);
        $off = $contract ? $fee - (($fee * $contract->off) / 100)
        : $fee;
        //  Log::info('contract:'.$contract);
        //  Log::info('off:'.$off);
        //  Log::info('req:'.$request->delay);

        return response()->json([
            'success' => true,
            'total_sales' => $totalSales,
            'total_payments' => $totalPayments,
            'remaining' => $remaining,
            'off' => $off
        ]);
    }
    public function listShop(){
        $shops=Shop::where('display',1)->orderBy('id','desc')->get();
        
        return view('listShop',compact('shops'));
    }
    public function contract_test(){

        // $shops=Shop::get();
        $orders=Order::get();
        foreach($orders as $order){
            $contract=Contract::where('shop_id',$order->shop_id)->first();
            if($contract){

                $order->contract_id=$contract->id;
                $order->save();
            }
        }

        return 1111111111;
    }
    public function sms_mobile(){

        // $shops=Shop::get();
        $users=User::get();
        foreach($users as $user){
            $user->sms_mobile = $user->mobile;
            $user->save();
        }

        return 1111111111;
    }

}
