<?php

namespace App\Http\Controllers;

use Illuminate\support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Images;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Hekmatinasser\Verta\Verta;
use Morilog\Jalali\Jalalian;

class AccountController extends Controller
{
    public function profile()
    {
        $user=Auth::user();
        $images = Images::get();
        if ($user) {
            $profile = Images::where('id', $user->profpic_id)->first();
        }
        if ($user->referrer_id) {
            $refferal = User::where('id', $user->referrer_id)->first()->referrer;
            $user['refferal'] = $refferal;
        }
        return view('user.profile', compact('user', 'profile'));
    }
    public function see_profile()
    {

        $user=Auth::user();
       
            $images = Images::get();
            if ($user) {
                $profile = Images::where('id', $user->profpic_id)->first();
                return view('user.edit-profile', compact('user', 'profile'));
            }else{
                return view('user.edit-profile', compact('user'));
            }
        

    }
    public function edit_profile(request $req)
    {
        $validator = Validator::make(
            request()->all(),
            [
                'name' => 'required',
                'family' => 'required',
                'fromDate' => 'required',
                // 'date_of_birth' => 'required|Date',
                'nationalcode' => 'required|numeric|digits:10',

            ],
            [
                // null msg
                'name.required' => 'نام خود را کامل کنید',
                'family.required' => 'فامیلی خود را کامل کنید',
                'fromDate.required' => 'تاریخ تولد خود را کامل کنید',
                'nationalcode.required' => 'کد ملی خود را کامل کنید',
                // 'name.alpha' => 'نوع نام باید متن باشد',
                // 'family.alpha' => 'نوع فامیلی باید متن باشد',
                // 'date_of_birth.Date' => 'نوع سال تولد باید از نوع دیت باشد',
                'nationalcode.numeric' => 'نوع کد ملی باید عددی باشد',
                'nationalcode.digits' => 'فیلد کد ملی باید ده رقم باشد',
               

            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user=Auth::user();
        $edit_user = User::where('id', $user->id)->first();
        $randomize = $user->id;
        


        if ($req->hasFile('pic')) {
            $image = $req->file('pic');
            $slide_image = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = 'img/profile';
            $image->move($destinationPath, $slide_image);
            $image = new Images();
            $image->address = 'img/profile/' . $slide_image;
            $image->save();
            $edit_user->profpic_id = $image->id;
            } 
      

        
        $edit_user->name = $req->name;
        $edit_user->family = $req->family;
        $fromMiladiDate = Jalalian::fromFormat('Y/m/d', $req->fromDate)->toCarbon()->format('Y-m-d');
        $edit_user->date_of_birth = $fromMiladiDate;
        $edit_user->nationalcode = $req->nationalcode;
        // $edit_user->coin+=200;
        $edit_user->save();
        return redirect('/profile')->withErrors('success');

    }

    // public function edit_pic(){
    //     $user = Auth::user();
    //     $profile = Images::where('id', $user->profpic_id)->first();
    //     return view('user.edit-pic',compact('user','profile'));
    // }
    // public function edit_pic_post(request $req){
    //     $user=User::where('id',Auth::user()->id)->first();
    //     if ($req->hasFile('pic')) {
    //         $image = $req->file('pic');
    //         $slide_image = time() . '.' . $image->getClientOriginalExtension();
    //         $destinationPath = 'img/profile';
    //         $image->move($destinationPath, $slide_image);
    //         $image1 = new Images();
    //         $image1->address = 'img/profile/' . $slide_image;
    //         $image1->save();
    //         $user->profpic_id = $image1->id;
    //         } 

    //         $user->save();
    //         return redirect('/');
    // }


    public function referrer2()
    {
        $user=Auth::user();
        if ($user->referrer_id==null) {
            $profile = Images::where('id', $user->profpic_id)->first();

            return view("user.referrer", compact("user", "profile"));
        }else {
            return redirect('user.profile');
        }

    }
    public function check_referrer2(request $req)
    {
        $validator = Validator::make(request()->all(), [
            'referrer' => 'required|string|min:4|max:4'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $user=Auth::user();
        if ($user->referrer == $req->referrer) {
            return redirect()->back()->withErrors('repetition');
        } else {
            $ref_user = User::where('referrer', $req->referrer)->first();
            if ($ref_user) {
                $user->referrer_id = $ref_user->id;

                $user->coin += '200';
                $ref_user->coin += '200';

                $this->coinmoaref($user->id);
                $this->coinmoarefi($ref_user->id);

                $ref_user->save();
                $user->save();
                return redirect('/profile');
            } else {
                return redirect()->back()->withErrors('notfound');
            }
        }
    }

    public function wallet()
    {

        $user=Auth::user();
        $transactions = Transaction::where('user_id', $user->id)->
            where('type', '<=', '2 ')
            ->orderBy('id', 'DESC')
            ->paginate(8);

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
            $transaction['time'] = $this->convertToPersianTime($transaction->created_at);
        }
        $profile = Images::where('id', $user->profpic_id)->first();
        if ($profile == null) {
            $profile = null;
        }
        return view('wallet-details', compact('transactions', 'user', 'profile'));
    }
    
}
