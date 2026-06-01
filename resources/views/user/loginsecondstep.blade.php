@extends('layout/masterc')
@section('title')
| کد تایید
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
                        <br>
                    @endforeach
                    @endif
                    <form action="/verify-check" method="POST">
                        @csrf
                        <label for="username" class="mb-3">کد تایید را وارد کنید</label>
                        <div class="input-group text-start mb-4">
                            <span class="input-group-text"><i class="m-5px fa-solid fa-check-circle"></i></span>
                             <input type="text" value='{{$mobile}}' name='mobile' style='display:none;'>
                            <input class="form-control" id="verifiedCode" name="verifiedCode" type="tel" maxlength="4"
                            autofocus placeholder="کد تایید" dir="rtl">

                        </div>

                        <button class="btn btn-lg w-100" type="submit">ورود</button>
                    </form>
                </div>
                <!-- Login Meta-->
                <div class="login-meta-data">
                    @if ($errors->has('mobile'))
                    <p class="mb-0 mt-4">کد تایید به<span class="mx-1">{{$errors->first('mobile')}}</span>ارسال شد</p>
                    @else
                    <p class="mb-0 mt-4">کد تایید به<span class="mx-1">{{$mobile}}</span>ارسال شد</p>
                    @endif
                    <div class="row" id='get'>
                        <p class="col-6 mt-3 mb-1">
                            <!-- countdown -->
                            دریافت مجدد کد تایید
                            <span id="timer"></span>
                            <script>
                                // اگر زمان ذخیره شده در localStorage وجود داشته باشد، آن را بخوانید
                                var startTime = localStorage.getItem('timerStartTime');

                                // اگر زمان ذخیره شده وجود نداشته یا 1 دقیقه به پایان رسیده باشد، یک دقیقه جدید تنظیم کنید
                                if (!startTime || (Date.now() - startTime) >= 180000) {
                                    startTime = Date.now();
                                    localStorage.setItem('timerStartTime', startTime);
                                }

                                // تابع برای به روزرسانی زمان باقی‌مانده
                                function updateTimer() {
                                    var currentTime = Date.now();
                                    var elapsedTime = currentTime - startTime;
                                    var remainingTime = 180000 - elapsedTime;

                                    if (remainingTime <= 0) {
                                        // زمان به پایان رسیده است
                                        document.getElementById('get').innerHTML = '<a class="col-6 mt-3 mb-1" href="/verified_code/{{$mobile}}">دریافت مجدد کد تایید</a><a class="col-6 mt-3 mb-1" href="/change-phone/{{$mobile}}">تغییر شماره موبایل</a>';
                                        // document.getElementById('timer').innerHTML = '0:00';

                                    } else {
                                        // نمایش زمان باقی‌مانده به کاربر
                                        var minutes = Math.floor(remainingTime / 180000);
                                        var seconds = Math.floor((remainingTime % 180000) / 1000);
                                        document.getElementById('timer').textContent = minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
                                    }
                                }

                                // فراخوانی تابع به روزرسانی هر ثانیه
                                setInterval(updateTimer, 1000);

                                // نمایش زمان اولیه
                                updateTimer();
                            </script>

                        </p>
                        <a class="col-6 mt-3 mb-1" href="/change-phone/{{$mobile}}">تغییر شماره موبایل</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
