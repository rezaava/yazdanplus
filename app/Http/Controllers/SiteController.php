<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shop;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class SiteController extends Controller
{
    
    public function index(){
        $user=Auth::user();
        if ($user->hasRole('shop_admin')) {
            $shop=Shop::where('user_id',$user->id)->first();
            $order_count=Order::where('shop_id',$shop->id)->where('status',2)->count();
            $orders_price=Order::where('shop_id',$shop->id)->where('status',2)->sum('price');
             $tasvie=Transaction::where('shop_id',$shop->id)->where('type',5)->sum('value');
             $trasn_offs=Transaction::where('shop_id',$shop->id)->where('type',18)->sum('value');
             $bestankar=$orders_price-$tasvie - $trasn_offs;
             $date=Jalalian::now();
            return view('admin.dashborad',compact('user','shop','order_count','bestankar','date'));
        }else{

            return view('admin.dashborad');
        }

    }
}
