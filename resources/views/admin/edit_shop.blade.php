@extends('admin.layout.master')

@section('onvan')
ویرایش فروشگاه 
@endsection

@section('head')
<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
<style>
    /* =========================
   Admin Shop Edit Page
========================= */

.layout-px-spacing {
    background: #f4f6f9;
    min-height: 100vh;
    padding: 30px;
}

/* کارت اصلی */
.widget-content-area {
    background: #ffffff;
    border-radius: 20px;
    padding: 35px 40px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.06);
}

/* عنوان سکشن‌ها */
.widget-content-area p {
    font-weight: 700;
    margin-top: 30px;
    margin-bottom: 15px;
    color: #333;
    border-right: 4px solid #4361ee;
    padding-right: 10px;
}

/* فرم */
.form-group label {
    margin-top: 12px;
    font-weight: 500;
    font-size: 14px;
    color: #444;
}

/* اینپوت‌ها */
.form-control {
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    padding: 10px 14px;
    transition: 0.25s;
}

.form-control:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
}

/* چک‌باکس دسته‌بندی */
input[type="checkbox"] {
    margin-left: 8px;
    transform: scale(1.1);
}

/* رادیو */
input[type="radio"] {
    margin-left: 6px;
}

/* select2 */
.select2-container--default .select2-selection--single {
    height: 42px;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    padding-top: 6px;
}

.select2-container--default .select2-selection--single:focus {
    border-color: #4361ee;
}

/* بخش عکس‌ها */
.custom-file-input {
    border-radius: 10px;
}

.custom-file-label {
    border-radius: 10px;
}

/* باکس تصویر */
.widget-content-area img {
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* دکمه ذخیره */
.btn-primary {
    border-radius: 14px;
    padding: 12px;
    font-weight: 700;
    font-size: 15px;
    background: linear-gradient(45deg,#4361ee,#3a0ca3);
    border: none;
    box-shadow: 0 10px 25px rgba(67,97,238,0.3);
    transition: 0.25s;
}

.btn-primary:hover {
    transform: translateY(-3px);
    background: linear-gradient(45deg,#3a0ca3,#240046);
}

/* فاصله بهتر بین سکشن‌ها */
.form-group > br {
    display: none;
}

/* ریسپانسیو */
@media (max-width: 768px) {
    .widget-content-area {
        padding: 25px 20px;
    }

    .layout-px-spacing {
        padding: 15px;
    }
}

</style>
@endsection

@section('main')
<div class="layout-px-spacing">

    <div class="row layout-top-spacing" id="cancel-row">
        <div id="basic" class="col-lg-12 layout-spacing">
            <div class="widget-content widget-content-area">

                <div class="row">
                    <div class="col-lg-6 col-12 mx-auto">
                        <form method="post" @php $user=\Illuminate\Support\Facades\Auth::user(); @endphp
                            @if ($user->hasRole('admin') || $user->hasRole('content')) action="/admin/shops/edit/{{ $shop->id }}" @endif
                            enctype="multipart/form-data">
                            @CSRF

                            <div class="form-group">
                                @if (Auth::user()->hasRole('admin'))
                                <label for="name">نام فروشگاه</label>
                                <input type="text" name="name" class="form-control" id="name"
                                    value="{{ $shop->name }}">
                                <label for="slug">نام سئو</label>
                                <input type="text" name="slug_name" value="{{ $shop->slug_name }}"
                                    class="form-control" id="slug">
                                <label for="phone">تلفن ثابت</label>
                                <input type="text" class="form-control" name="phone" id="phone"
                                    value="{{ $shop->telephone }}">
                                <label for="mobile">شماره موبایل</label>
                                <input type="text" class="form-control" name="mobile" id="mobile"
                                    value="{{ $shop->mobile }}">
                                {{-- <label for="off">تخفیف کل</label>
                                        <input type="text" class="form-control" name="off" id="off"
                                            value="{{ $shop->off }}">
                                <label for="user-off">تخفیف کاربر</label>
                                <input type="text" class="form-control" name="user_off" id="user-off" --}}
                                    {{-- value="{{ $shop->user_off }}"> --}}
                                <label for="advance_payment">پیش پرداخت</label>
                                <input type="number" class="form-control"
                                    value="{{ $shop->advance_payment }}" name="advance_payment" id="advance_payment"
                                    placeholder="مبلغ پیش پرداخت">
                                <label for="installments_number">تعداداقساط</label>
                                <input type="number" class="form-control"
                                    value="{{ $shop->installments_number }}" name="installments_number"
                                    id="installments_number" placeholder="تعداد ماه اقساط">
                                    
                                <label for="profit">درصد سود ماهانه</label>
                                <input type="number" class="form-control" value="{{ $shop->profit }} " name="profit"
                                    id="profit" placeholder="سود ماهانه روی اقساط">

                                <label for="off">تخفیف</label>
                                <input type="text" class="form-control" value="{{ $shop->off }} " name="off"
                                    id="off" placeholder="تخفیف">


                                <label for="address">آدرس</label>
                                <textarea class="form-control" id="address" name="address" rows="3"> {{ $shop->address }}</textarea>
                                @endif
                                @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketer'))
                                <label for="description">توضیحات</label>
                                <textarea class="form-control" id="description" name="description" rows="3"> {{ $shop->decription }}</textarea>
                                @endif
                                @if (Auth::user()->hasRole('admin'))
                                <br>
                                <br>
                                <p>دسته بندی</p>
                                @foreach ($categories as $category)
                                <label for="category{{ $category->id }}">{{ $category->name }}</label>
                                <input type="checkbox" name="{{ $category->id }}" value="{{ $category->id }}"
                                    id="category{{ $category->id }}"
                                    @foreach ($find_categorys as $find_category) @if ($find_category->category_id == $category->id)
                                checked
                                @endif @endforeach>
                                <br>
                                @endforeach
                                <br>
                                <br>
                                <p>نمایش در سایت</p>
                                <label for="display_1">نمایش</label>
                                <input type="radio" @if ($shop->display == '1') checked @endif
                                name="display" id="display_1" value="1">
                                <br>
                                <label for="display_0">عدم نمایش</label>
                                <input type="radio" @if ($shop->display == '0') checked @endif
                                name="display" id="display_0" value="0">
                                <br>
                                <br>
                                <br>
                                <P>اطلاعات بازار یاب</P>

                                <select id="country" name="marketer" class="form-control">
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        @if ($shop->refferer_id == $user->id) selected @endif>
                                        {{ $user->fullname() }}
                                    </option>
                                    @endforeach
                                </select>
                                <br><br><br>

                                <p>اطلاعات مدیر فروشگاه (طبق قرارداد)</p>
                                <label for="firstname">نام</label>
                                <input type="text" class="form-control" name="firstname" id="off"
                                    value="{{ $shopuser->name }}">
                                <label for="lastname">نام خانوادگی</label>
                                <input type="text" class="form-control" name="lastname" id="off"
                                    value="{{ $shopuser->family }}">
                                <label for="nationalcode">کد ملی</label>
                                <input type="text" class="form-control" name="nationalcode" id="off"
                                    value="{{ $shopuser->nationalcode }}">

                                <br>
                                <br>
                                @endif
                                @if (Auth::user()->hasRole('content_manager') || Auth::user()->hasRole('admin'))
                                <br>
                                <p>عکس ها</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>کاور فعلی</p>
                                        <div style="background-color: gray; border: 1px solid black;">
                                            <img src='{{ asset($shop->cover->address) }}'
                                                alt="{{ $shop->name }}" class="d-block"
                                                style="width: 100%; height: 200px; margin: auto; object-fit: contain;">
                                        </div>
                                        <br>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="cover"
                                                id="cover">
                                            <label class="custom-file-label" for="cover">تغییر کاور</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <p>آیکون (لوگو) فعلی</p>
                                        <div style="background-color: gray; border: 1px solid black;">
                                            <img src='{{ asset($shop->icon->address) }}'
                                                alt="{{ $shop->name }}" class="d-block"
                                                style="width: 100%; height: 200px; margin: auto; object-fit: contain;">
                                        </div>
                                        <br>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="icon"
                                                id="icon">
                                            <label class="custom-file-label" for="icon">تغییر آیکون
                                                (لوگو)</label>
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <br>
                                @endif
                                @if (Auth::user()->hasRole('admin'))
                                <br>
                                <br>
                                <br>
                                <p>اطلاعات حساب بانکی (طبق قرارداد)</p>
                                <label for="user-off">شبا</label>
                                <input type="text" class="form-control" name="shaba" id="user-off"
                                    value="@if ($bank) {{ $bank->shaba }} @endif" maxlength="24">

                                <label for="user-off">صاحب</label>
                                <input type="text" class="form-control" name="owner" id="user-off"
                                    value="@if ($bank) {{ $bank->owner }} @endif">

                                <label for="user-off">بانک</label>
                                <input type="text" class="form-control" name="bank" id="user-off"
                                    value="@if ($bank) {{ $bank->bank_name }} @endif">
                                {{-- @if ($user->hasRole('admin') || $user->hasRole('content')) --}}
                                @endif


                                <br>
                                <br>
                                <br> 

                                <div class="form-group">
                                <button type="submit" style="width: 100%;background-color: #FFD8D8;" class="mt-4 btn btn-block">
                                    ذخیره
                                </button>
                            </div>
                                {{-- @endif --}}
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
<script>
    $(function() {
        $("#country").select2();
    });
</script>
@endsection