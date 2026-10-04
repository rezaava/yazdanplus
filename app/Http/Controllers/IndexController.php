<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Shop;
use App\Models\CategoryShop;
use App\Models\Images;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Faq;
use App\Models\Slider;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Condition_v;
use App\Models\Condition_vige;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class IndexController extends Controller
{
    public function index(request $req)
    {

        if (isset($req->search)) {
            $shops = Shop::where('name', 'like', "%$req->search%")
                ->where('display', '1')
                ->paginate(18);
            foreach ($shops as $shop) {
                $icon = Images::find($shop->icon_id)->address;
                $shop['icon'] = $icon;
                $cover = Images::find($shop->cover_id)->address;
                $shop['cover'] = $cover;
            }
        } elseif (isset($req->cat)) {
            $shops_id = CategoryShop::where('category_id', $req->cat)->pluck('shop_id');
            $shops = Shop::orderBy('id', 'DESC')
                ->whereIn('id', $shops_id)
                ->where('display', '1')
                ->paginate(18);
            foreach ($shops as $shop) {
                $icon = Images::find($shop->icon_id)->address;
                $shop['icon'] = $icon;
                $cover = Images::find($shop->cover_id)->address;
                $shop['cover'] = $cover;
            }
        } else {
            $shops = Shop::orderBy('id', 'DESC')
                ->where('display', '1')
                ->paginate(9);
            foreach ($shops as $shop) {
                $icon = Images::find($shop->icon_id)->address;
                $shop['icon'] = $icon;
                $cover = Images::find($shop->cover_id)->address;
                $shop['cover'] = $cover;
            }
        }
        $user = Auth::user();
        $images = Images::get();
        if ($user) {
            $profile = Images::where('id', $user->profpic_id)->first();
            if ($profile == null) {
                $profile = null;
            }
            if ($shops == null) {
                $shops = null;
            }
        } else {
            if ($shops == null) {
                $shops = null;
            }
            $user = null;
            $profile = null;
        }
        $categories = Category::where('display', 1) ->orderBy('sort', 'asc')->get();
        foreach ($categories as $category) {
            $img = Images::find($category->icon_id)->address;
            $category['image'] = $img;
        }
        $all = Images::find(22)->address;
        $sliders = Slider::where('display', '1')->get();
        foreach ($sliders as $slide) {
            $slide['aks'] = Images::find($slide->image);
        }
        $all_user = User::count();
        $all_shop = Shop::count();

        $shop_test=Shop::find(8);

        // $order_last_success = Order::where('status', 2)->orderBy('id', 'DESC')->first();
        // $transation_last = Transaction::where('order_id', $order_last_success->id)->get();
        // $transation_last_time = $this->convertToPersianTimeadmin($transation_last[0]->created_at);
        // return view('home', compact('all', 'shops', 'user', 'profile', 'sliders', 'images', 'categories', 'all_shop', 'all_user', 'transation_last', 'transation_last_time'));
        return view('home', compact('all','shops', 'user', 'profile','shop_test','sliders', 'images', 'categories', 'all_shop', 'all_user'));
    }
    public function contact()
    {
        $user = Auth::user();
        $images = Images::get();
        if ($user) {
            $profile = Images::where('id', $user->profpic_id)->first();
            if (!$profile) {
                $profile = null;
            }
        } else {
            $profile = null;
        }

           

        return view('contact', compact('user','profile'));
    }
    public function contacts()
    {   
        $contacts=Contact::get();
        return view('admin.new.contacts', compact('contacts'));
    }
    public function about()
    {
        $user = Auth::user();
        $images = Images::get();
        if ($user) {
            $profile = Images::where('id', $user->profpic_id)->first();
            if (!$profile) {
                $profile = null;
            }
        } else {
            $profile = null;
        }
        return view('about-us', compact('user', 'profile'));
    }
    public function send_contact(Request $req)
    {
        $user = Auth::user();
        
        if ($user) {
            $validator = Validator::make(request()->all(), [
                'name' => 'required',
                'family' => 'required',
                'text' => 'required|string',
            ], [
                'name.required' => 'نام الزامی است',
                'family.required' => 'فامیل الزامی است',
                'text.required' => 'پیام خود را بنویسید',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $contact = new Contact;
            $contact->name = $req->name;
            $contact->family = $req->family;
            $contact->text = $req->text;
            $contact->mobile = $user->mobile;
            $contact->save();
            
        } else {
            $validator = Validator::make(request()->all(), [
                'name' => 'required',
                'family' => 'required',
                'text' => 'required|string',
                'mobile' => 'required|numeric|digits:11',
            ], [
                'name.required' => 'نام الزامی است',
                'family.required' => 'فامیل الزامی است',
                'text.required' => 'پیام خود را بنویسید',
                'mobile.required' => 'موبایل الزامی است',
                'mobile.numeric' => 'موبایل عددی باشد',
                'mobile.digits' => 'موبایل 11 رقم باشه',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $contact = new Contact;
            $contact->name = $req->name;
            $contact->family = $req->family;
            $contact->text = $req->text;
            $contact->mobile = $req->mobile;
            $contact->save();
        }
        
        // ✅ پیام موفقیت با with()
        return redirect()->back()->with('success', 'نظر شما با موفقیت ثبت شد. از ارتباط شما سپاسگزاریم 🙏');
    }
    public function sharayet()
    {
        $user = Auth::user();
        $images = Images::get();
        if ($user) {
            $profile = Images::where('id', $user->profpic_id)->first();
            if (!$profile) {
                $profile = null;
            }
        } else {
            $profile = null;
        }
        return view('sharayet', compact('user', 'profile'));
    }
    public function sharayet_post(request $req)
    {
        $user = Auth::user();
        $condition_v = new Condition_vige();
        $condition_v->sharayet = $req->sharayet;
        $condition_v->user_id = $user->id;
        $condition_v->save();
        return redirect()->back()->with('suc', 'با موفقیت ارسال شد');
    }
}
