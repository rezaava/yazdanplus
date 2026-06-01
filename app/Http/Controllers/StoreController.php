<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Images;
use Illuminate\Support\Facades\Validator;
use App\Models\Beshop;
use App\Models\user;
use Illuminate\Support\Facades\Auth;


class StoreController extends Controller
{
    public function store(){
        $user=Auth::user();
        if ($user) {
            $profile=Images::where('id',$user->profpic_id)->first();
            if (!$profile) {
                $profile=null;
            }
        }else {
            $profile=null;
        }
        return view('become-vendor',compact('user','profile'));
    }
    public function register(request $req){

        $validator = Validator::make(request()->all() , [
            'name'=>'required',
            'address'=>'required',
            'phone'=>'required|numeric|digits:11',
            'mobile'=>'required|numeric|digits:11',
        ],[
            'name.required' => 'نام الزامی است',
            'address.required' => 'آدرس الزامی است',
            'phone.required' => 'شماره تلفن فروشگاه الزامی است',
            'phone.numeric' => 'شماره تلفن فروشگاه عددی باشد',
            'phone.digits' => 'شماره تلفن فروشگاه 11 رقم باشد',
            'mobile.required' => 'شماره موبایل الزامی است',
            'mobile.numeric' => 'شماره موبایل عددی باشد',
            'mobile.digits' => 'شماره موبایل 11 رقم باشد',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
            $register=new Beshop();
            $register->name=$req->name;
            $register->address=$req->address;
            $register->telephone=$req->phone;
            $register->mobile=$req->mobile;
            $register->save();
            $valid='همکاری با شما افتخار ماست. منتظر تماس از طرف تیم آنتو باشید ...';
            return redirect('/shop-registration')->with('suc',$valid);
        }
        
}
