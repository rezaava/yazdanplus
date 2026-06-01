<?php

namespace App\Http\ViewComposers;

use App\Models\Shop;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AdminComposer
{
    public function compose(View $view)
    {
        // متغیرهای عمومی برای کل پنل مدیریت
        $user = Auth::user();
        $sale_type=1;
        if($user->hasRole('shop_admin')){
            $shop=Shop::where('user_id',$user->id)->first();
            $sale_type=$shop->sale_type;
        }elseif($user->hasRole('admin')){
            $sale_type=3;
        }
      
        
        // ارسال متغیرها به تمام ویوهای مدیریت
        $view->with([
            'sale_type' => $sale_type,
        ]);
    }
}