@extends('layout.master')
@section('title')
| فروشنده شو
@endsection
@section('style')
<style>
    /* کارت اصلی فرم */
    .vendor-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 15px;
        padding: 40px 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transition: 0.3s;
    }

    .vendor-card:hover {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
    }

    /* تیتر */
    .vendor-title {
        font-weight: 700;
        margin-bottom: 25px;
    }

    /* لیبل ها */
    .vendor-label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    /* اینپوت ها */
    .vendor-card .form-control {
        border-radius: 10px;
        border: 1px solid #ddd;
        padding: 12px;
        transition: 0.3s;
    }

    .vendor-card .form-control:focus {
        border-color: #e30613;
        box-shadow: 0 0 0 0.2rem rgba(227, 6, 19, 0.1);
    }

    /* دکمه */
    .register-button-in-become-vendor-form {
        border-radius: 10px;
        padding: 12px;
        font-weight: 600;
        transition: 0.3s;
    }

    .register-button-in-become-vendor-form:hover {
        transform: translateY(-2px);
    }

    body {
        padding-top: 100px;
    }
</style>
@endsection
@section('content')

<!-- Login Wrapper Area-->

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-md-10 col-lg-6">
            <div class="vendor-card">
              
                @if(Session::has('suc'))
            
                <div class="alert alert-success" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                        <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"></path>
                        <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"></path>
                    </svg>
                    همکاری با شما افتخار ماست. منتظر تماس از طرف تیم یزدان باشید...
                </div>
             
           
                @endif
                <h3 class="vendor-title text-center">فروشنده شوید</h3>

                <form action="register" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="vendor-label">
                            <i class="fa-solid fa-basket-shopping me-2 text-danger"></i>
                            نام فروشگاه <span class="text-danger">*</span>
                        </label>

                        <input class="form-control @error('name') is-invalid @enderror"
                            name="name"
                            type="text"
                            value="{{ old('name') }}">

                        @error('name')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>


                    <div class="mb-4">
                        <label class="vendor-label">
                            <i class="fa-solid fa-location-dot me-2 text-danger"></i>
                            آدرس <span class="text-danger">*</span>
                        </label>

                        <textarea class="form-control @error('address') is-invalid @enderror"
                            name="address"
                            rows="3">{{ old('address') }}</textarea>

                        @error('address')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>


                    <div class="mb-4">
                        <label class="vendor-label">
                            <i class="fa-solid fa-phone me-2 text-danger"></i>
                            شماره تلفن فروشگاه <span class="text-danger">*</span>
                        </label>

                        <input class="form-control @error('phone') is-invalid @enderror"
                            name="phone"
                            type="tel"
                            dir="rtl"
                            value="{{ old('phone') }}">

                        @error('phone')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>


                    <div class="mb-4">
                        <label class="vendor-label">
                            <i class="fa-solid fa-mobile me-2 text-danger"></i>
                            شماره موبایل <span class="text-danger">*</span>
                        </label>

                        <input class="form-control @error('mobile') is-invalid @enderror"
                            name="mobile"
                            type="tel"
                            dir="rtl"
                            value="{{ old('mobile') }}">

                        @error('mobile')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <button class="btn btn-danger w-100 register-button-in-become-vendor-form">
                        ارسال درخواست همکاری
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="/" class="text-dark">بازگشت به صفحه اصلی</a>
                </div>
            </div>
        </div>

    </div>
</div>


@endsection