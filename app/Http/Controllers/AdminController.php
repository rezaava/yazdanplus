<?php

namespace App\Http\Controllers;

use App\Models\User;
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

            // فرض: ستون A = ملی, B = فامیل, C = اسم, D = موبایل
            $meli = $row[0] ?? null;
            $last_name = $row[1] ?? null;
            $first_name = $row[2] ?? null;
            $mobile = $row[3] ?? null;

            // اگر موبایل خالی بود، از این ردیف رد شو
            if (empty($mobile)) {
                continue;
            }

            // 4. شرط: اگر شماره موبایل قبلاً در دیتابیس وجود دارد، ذخیره نکن
            $exists = User::where('mobile', $mobile)->first();

            if ($exists) {
               
                $exists->adad=0;
                //ba har exceel
                $exists->wallet=100000000;
                $exists->init_wallet=100000000;
                $exists->type=2;
    
                
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
            $user->wallet=1;
            $user->init_wallet=1;
            $user->type=1;


            $user->save();
            $num+=1;
           
        }

        return back()->with('suc', 'ایمپورت فایل با موفقیت انجام شد
         تعداد ذخیره '.$num.'');
    }
}