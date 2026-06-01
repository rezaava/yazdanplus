<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Images;
use App\Models\Shop;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Hekmatinasser\Verta\Verta;
use App\Exports\UserInstallmentsExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;

class TransactionController extends Controller
{
    public function show_orders()
    {
        $priceAll = 0;
        $user = Auth::user();
        // $orders = Order::where('user_id', $user->id)->whereIn('status', ['2','7'])
        //     ->orderBy('id', 'DESC')
        //     ->paginate(10);
        $orders = Order::where(function ($query) use ($user) {
            // وضعیت 2: همه رکوردها
            $query->where('status', 2)
                  ->where('user_id', $user->id);
            
            // وضعیت 7: فقط 48 ساعت اخیر
            $query->orWhere(function ($subQuery) use ($user) {
                $subQuery->where('status', 7)
                         ->where('created_at', '>=', now()->subHours(48))
                         ->where('user_id', $user->id);
            });
        })->orderBy('id', 'desc')->paginate(10);


        $profile = Images::where('id', $user->profpic_id)->first();
        if ($profile == null) {
            $profile = null;
        }
        foreach ($orders as $order) {

            $priceAll = 0;
            $order['transaction_count'] = Transaction::where('order_id', $order->id)->count();
            $order['transaction_count_month'] = Transaction::where('tarikh_ghest', '>=', Carbon::now())
                ->where('order_id', $order->id)
                ->count();

            $transactions = Transaction::where('order_id', $order->id)->get();
            foreach ($transactions as $transaction) {
                $mablagh = $transaction->value;
                $priceAll += $transaction->value;
            }
            $order['mablagh'] = $mablagh;
            $order['priceAll'] = $priceAll;
            $order['mande'] = $order->mablagh * $order->transaction_count_month;
            $shop = Shop::where('id', $order->shop_id)->first();
            if ($shop) {
                $order['shop'] = $shop->name;
            } else {
                $order['shop'] = '';
            }
            $order['time'] = $this->convertToPersianTime(
                $order->status == '2' ? $order->updated_at : $order->created_at
            );
        }


        return view('orders', compact('user', 'profile', 'orders'));
    }
    public function taeed_code(Request $req, $id)
    {
        $order = Order::find($id);
        $user = Auth::user();

        // اگر پیدا نشد
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'سفارش یافت نشد.'
            ], 404);
        }



        $transactions_value = Transaction::where('order_id', $order->id)->sum('value');


        if ($transactions_value > $user->wallet) {

            return response()->json([
                'success' => false,
                'message' => 'عدم موجودی کافی'
            ], 422);
        } else {

            // چک کردن کد
            if ($order->sms_code == $req->code) {
                DB::beginTransaction();
                try {
                    $user->wallet -= $transactions_value;
                    $user->save();
                    $order->status = 2;
                    $order->save();
                    DB::commit();

                    $user2 = User::find(6);
                    $matn = 'yazdan';
                    SmsController::sendSms(
                        $user2,
                        SmsTypes::LOGIN_VERIFY_CODE,
                        $matn,
                        $user2->mobile
                    );

                    return response()->json([
                        'success' => true,
                        'message' => 'کد تایید شد.'
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error('خطا : ');
                }
            }


            // کد اشتباه
            return response()->json([
                'success' => false,
                'message' => 'کد اشتباه است.'
            ], 422);
        }
    }

    public function coin()
    {
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)->where('type', '>=', '7')->where('type', '<=', '12')
            ->orderBy('id', 'DESC')
            ->paginate(6);

        foreach ($transactions as $transaction) {

            $shop = Shop::where('id', $transaction->shop_id)->first();
            if ($shop) {
                $transaction['shop'] = $shop->name;
                $transaction['price'] = Order::where('id', $transaction->order_id)
                    ->first()
                    ->price;
            } else {
                $transaction['shop'] = '';
            }
            //    if($transaction->type=='4'){
            //        $order=Order::where('id',$transaction->order_id)->first();
            //        $porsant_user=User::where('id',$order->user_id)->first();
            //        $transaction['porsant_user']=$porsant_user->name;
            //    }

            $transaction['time'] = $this->convertToPersianTime($transaction->created_at);
        }

        $profile = Images::where('id', $user->profpic_id)->first();
        if ($profile == null) {
            $profile = null;
        }
        return view('coins', compact('transactions', 'user', 'profile'));
    }

    public function exportUserInstallmentsExcel(Request $request)
    {
        $userId = $request->query('user');
        $fromShamsi = $request->query('from');
        $toShamsi = $request->query('to');

        // تبدیل شمسی به میلادی
        $fromMiladi = Jalalian::fromFormat('Y/m/d', $fromShamsi)->toCarbon()->startOfDay()->format('Y-m-d H:i:s');
        $toMiladi = Jalalian::fromFormat('Y/m/d', $toShamsi)->toCarbon()->endOfDay()->format('Y-m-d H:i:s');

        return Excel::download(new UserInstallmentsExport($userId, $fromMiladi, $toMiladi), 'user_installments.xlsx');
    }
    public function ghest_payment(Request $request, Transaction $Transaction)
    {
        // return $request;
        $Transaction->type = $request->payment ?? 19;
        $Transaction->save();
        return back()->with('success', 'وضعیت نوبت با موفقیت به‌روزرسانی شد.');
    }
    public function mablagh_ghest()
    {
        try {
            $today = Jalalian::now();
            $currentYear = $today->getYear();
            $currentMonth = $today->getMonth();
            $formattedMonth = sprintf("%02d", $currentMonth);

            $startOfMonthJalali = Jalalian::fromFormat('Y-m-d', $currentYear . '-' . $formattedMonth . '-01');
            $daysInMonth = $startOfMonthJalali->getMonthDays();
            $endOfMonthJalali = Jalalian::fromFormat('Y-m-d', $currentYear . '-' . $formattedMonth . '-' . $daysInMonth);

            $startOfMonthCarbon = $startOfMonthJalali->toCarbon()->startOfDay();
            $endOfMonthCarbon = $endOfMonthJalali->toCarbon()->endOfDay();
            // 3. تعیین نام فایل
            // از تاریخ شمسی برای نام فایل استفاده می‌کنیم
            $fileName = "monthly_installments_{$startOfMonthCarbon->format('Y-m-d')}_to_{$endOfMonthCarbon->format('Y-m-d')}.xlsx";

            // 4. دریافت ID کاربر لاگین شده
            $userId = Auth::id();

            // 5. ارسال تاریخ‌ها و ID کاربر به Export Class
            // اطمینان حاصل کنید که TransactionsExport این تاریخ‌ها را به درستی دریافت می‌کند
            return Excel::download(
                new TransactionsExport($startOfMonthCarbon, $endOfMonthCarbon, $userId),
                $fileName
            );
        } catch (\Exception $e) {
            // در صورت بروز خطا، پیام مناسب را برگردانید
            // بهتر است به جای JSON، یک redirect با پیغام خطا داشته باشید
            return back()->withErrors(['error' => 'دریافت گزارش با خطا مواجه شد: ' . $e->getMessage()]);
        }
    }
}
