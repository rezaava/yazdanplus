<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\pagination\paginator;
use Illuminate\support\Facades\Auth;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;
use App\Http\ViewComposers\AdminComposer; // اضافه کنید

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
      // در AppServiceProvider.php
      public function boot()
      {
          Schema::defaultStringLength(191);
          paginator::usebootstrap();
  
          // فقط برای ویوهای مشخص شده View Composer را اعمال کن
          view()->composer(
              ['home', 'orders','contact','about-us','become-vendor','datis','datis2','arde','datis_orders','stock_form','dashboard.shop.purchase','dashboard.shop.vendor-shop','payment','reportForm','wallet-details','user.edit-profile','user.profile','user.referrer','show_reportForm'], // لیست ویوهایی که به این داده نیاز دارند
              function ($view) {
                  $user = Auth::user();
  
                  // بررسی کنید آیا کاربر لاگین کرده است
                  if (!$user) {
                      // اگر کاربر لاگین نکرده، مقدار پیش‌فرض را ارسال کنید یا کلاً ارسال نکنید
                      // $view->with('totalMonthlySum', 0);
                      return; // یا به سادگی از این ویو کامپوزر خارج شوید
                  }
  
                  // --- باقی کد محاسبه ---
                  $today = Jalalian::now();
                  $currentYear = $today->getYear();
                  $currentMonth = $today->getMonth();
                  $formattedMonth = sprintf("%02d", $currentMonth);
  
                  $startOfMonthJalali = Jalalian::fromFormat('Y-m-d', $currentYear . '-' . $formattedMonth . '-01');
                  $daysInMonth = $startOfMonthJalali->getMonthDays();
                  $endOfMonthJalali = Jalalian::fromFormat('Y-m-d', $currentYear . '-' . $formattedMonth . '-' . $daysInMonth);
  
                  $startOfMonthCarbon = $startOfMonthJalali->toCarbon()->startOfDay();
                  $endOfMonthCarbon = $endOfMonthJalali->toCarbon()->endOfDay();
  
                  // فرض می‌کنیم شما مدل Transaction و ستون value را دارید
                  // اگر نام ستون یا مدل متفاوت است، آن را اصلاح کنید
                  $totalMonthlySum = Transaction::where('user_id', $user->id)
                      ->whereBetween('tarikh_ghest', [
                          $startOfMonthCarbon->format('Y-m-d H:i:s'),
                          $endOfMonthCarbon->format('Y-m-d H:i:s')
                      ])
                      ->sum('value'); // نام ستون price بود یا value؟
  
                  // ارسال متغیرها
                  $view->with('MONEY_SIGN', 'ریال')
                       ->with('user', $user)
                       ->with('COLOR_ACTIVE', '#25d5e4')
                       ->with('totalMonthlySum', $totalMonthlySum); // ارسال متغیر محاسبه شده
              }
          );



          view()->composer(
            ['buy_product.*'], // لیست ویوهایی که به این داده نیاز دارند
            function ($view) {
                $user = Auth::user();

                // بررسی کنید آیا کاربر لاگین کرده است
                if (!$user) {
                    // اگر کاربر لاگین نکرده، مقدار پیش‌فرض را ارسال کنید یا کلاً ارسال نکنید
                    // $view->with('totalMonthlySum', 0);
                    return; // یا به سادگی از این ویو کامپوزر خارج شوید
                }

                // --- باقی کد محاسبه ---
                $today = Jalalian::now();
                $currentYear = $today->getYear();
                $currentMonth = $today->getMonth();
                $formattedMonth = sprintf("%02d", $currentMonth);

                $startOfMonthJalali = Jalalian::fromFormat('Y-m-d', $currentYear . '-' . $formattedMonth . '-01');
                $daysInMonth = $startOfMonthJalali->getMonthDays();
                $endOfMonthJalali = Jalalian::fromFormat('Y-m-d', $currentYear . '-' . $formattedMonth . '-' . $daysInMonth);

                $startOfMonthCarbon = $startOfMonthJalali->toCarbon()->startOfDay();
                $endOfMonthCarbon = $endOfMonthJalali->toCarbon()->endOfDay();

                // فرض می‌کنیم شما مدل Transaction و ستون value را دارید
                // اگر نام ستون یا مدل متفاوت است، آن را اصلاح کنید
                $totalMonthlySum = Transaction::where('user_id', $user->id)
                    ->whereBetween('tarikh_ghest', [
                        $startOfMonthCarbon->format('Y-m-d H:i:s'),
                        $endOfMonthCarbon->format('Y-m-d H:i:s')
                    ])
                    ->sum('value'); // نام ستون price بود یا value؟

                // ارسال متغیرها
                $view->with('MONEY_SIGN', 'ریال')
                     ->with('user', $user)
                     ->with('COLOR_ACTIVE', '#25d5e4')
                     ->with('totalMonthlySum', $totalMonthlySum); // ارسال متغیر محاسبه شده
            }
        );

               // --- View Composer جدید برای پنل مدیریت ---
        // این متغیرها به تمام ویوهایی که نامشان با 'admin.' یا 'dashboard.admin.' شروع می‌شود ارسال می‌گردد
        view()->composer([
            'admin.*',           // تمام ویوهای داخل پوشه admin
        ], AdminComposer::class);
        
        // یا اگر می‌خواهید فقط برای چند صفحه خاص مدیریت:
        // view()->composer([
        //     'admin.dashboard',
        //     'admin.orders',
        //     'admin.users',
        //     'admin.settings',
        // ], AdminComposer::class);
      }
}
