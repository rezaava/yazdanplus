@extends('admin.layout.master')

@section('onvan')
ویرایش کاربر
@endsection

@section('head')
<style>

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
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.06);
    }

    /* سکشن تیتر */
    .section-title {
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 20px;
        padding-bottom: 8px;
        border-bottom: 2px solid #eee;
        color: #333;
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
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    /* دکمه اصلی */
    .btn-primary {
        border-radius: 14px;
        padding: 12px;
        font-weight: 700;
        font-size: 14px;
        background: linear-gradient(45deg, #4361ee, #3a0ca3);
        border: none;
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
        transition: 0.25s;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        background: linear-gradient(45deg, #3a0ca3, #240046);
    }

    /* کارت کیف پول */
    .wallet-box {
        background: #f8f9ff;
        padding: 25px;
        border-radius: 16px;
        margin-top: 30px;
        border: 1px solid #e4e8ff;
    }

    /* دکمه خطر برای عملیات مالی */
    .btn-wallet {
        background: linear-gradient(45deg, #2d9f04, #1b7c02);
        box-shadow: 0 10px 25px rgba(45, 159, 4, 0.3);
    }

    .btn-wallet:hover {
        background: linear-gradient(45deg, #1b7c02, #145a01);
    }

    /* ریسپانسیو */
    @media (max-width:768px) {
        .widget-content-area {
            padding: 25px 20px;
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
                        <div class="section-title">اطلاعات کاربر</div>

                        <form action="/admin/users/user-edit/{{$user->id}}" method="post">
                            @csrf

                            <div class="form-group">
                                <label for="name">نام</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{$user->name}}">
                                @error('name')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                                <label for="name">نام خانوادگی</label>
                                <input type="text" name="family" class="form-control" id="family" value="{{$user->family}}">
                                @error('family')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                                <label for="name">موبایل</label>
                                <input type="tel" name="mobile" class="form-control" id="mobile" value="{{$user->mobile}}">
                                @error('mobile')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                                <!-- <label for="nationalcode">کد ملی</label>
                                <input type="tel" name="nationalcode" class="form-control" id="nationalcode" value="{{$user->nationalcode}}"> -->

                                <label for="name">تاریخ تولد</label>
                                <input type="text" name="date" class="form-control" id="date" value="{{$user->date_of_birth}}">

                                <label for="name">کوین</label>
                                <input type="tel" name="coin" class="form-control" id="coin" value="{{$user->coin}}">

                                <button type="submit" class="mt-4 btn btn-block btn-primary">ذخیره</button>
                            </div>
                        </form>
                        <br><br>
                        <hr>
                        <form action="/admin/chrge-wallet/{{$user->id}}" method="post">
                            @csrf
                            <div class="wallet-box">
                                <div class="section-title">مدیریت کیف پول</div>

                                <label for="wallet">مبلغ شارژ کاربر</label>
                                <input type="text" name="wallet" class="form-control" id="wallet" value="{{$user->wallet}}">
                                @error('wallet')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                                <button type="submit" class="mt-4 btn btn-block btn-primary btn-wallet">
                                    ذخیره تغییرات کیف پول
                                </button>
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

@endsection