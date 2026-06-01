@extends('layout/masterc')
@section('title')
| ورود و ثبت نام
@endsection
@section('content')
<!-- Login Wrapper Area-->
<div class="login-wrapper d-flex align-items-center justify-content-center text-center">
    <!-- Background Shape-->
    <div class="background-shape"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10 col-md-6 col-lg-4"><img class="big-logo" src="/img/core-img/aneto-logo2.png" alt="logo">
                <!-- Register Form-->
                <div class="register-form mt-5">
                    @if($errors->any())
                        @if($errors->has('mobile'))
                            <div class="text-danger">{{ $errors->first('name') }}</div>
                        @endif
                    @endif
                    @if($errors->any())

                        @foreach($errors->all() as $error)
                            @if ($error=='ban')
                                اکانت شما به دلیل تعداد درخواست بیش از حد مجاز مسدود شده است
                                <br>
                                <a href="tel:09964945144">برای تماس با پشتیبانی کلیک کنید</a>
                                <br>
                                <br>
                            @else
                                <p style='color:#9f0415;'>{{$error}}</p>
                            @endif
                        @endforeach
                    @endif
                    <form action="/verified-code" method="POST">
                        @csrf
                        <label for="username" class="mb-3">شماره موبایل خود را وارد کنید</label>
                        <div class="input-group text-start mb-4">
                            <span class="input-group-text"><i class="m-5px fa-solid fa-phone"></i></span>
                            <input class="form-control" id="mobile"
                                name="mobile" minlength="0" maxlength="11" type="tel" placeholder="مثال : 09964945144 "
                                autofocus dir="rtl">
                        </div>
                        <button class="btn btn-lg w-100" type="submit">ورود</button>
                    </form>
                </div>
                <!-- Login Meta-->
                <!-- <div class="login-meta-data"><a class="forgot-password d-block mt-3 mb-1" href="forget-password.html">رمز ورود را فراموش کرده اید؟</a>
              <p class="mb-0">حساب نداشتید؟<a class="mx-1" href="register.html">اکنون ثبت نام کنید</a></p>
            </div> -->
                <!-- View As Guest-->
                <div class="view-as-guest mt-3"><a class="btn" href="/">مشاهده به عنوان مهمان</a></div>
            </div>
        </div>
    </div>
</div>
@endsection
