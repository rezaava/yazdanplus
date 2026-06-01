@extends('admin.layout.master')

@section('onvan')
ارسال پیام
@endsection

@section('head')
<style>
    /* =========================
   Admin Send Message Page
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

/* عنوان کاربر */
.message-user-title {
    background: linear-gradient(45deg,#4361ee,#3a0ca3);
    color: white;
    padding: 15px;
    border-radius: 14px;
    text-align: center;
    font-weight: 600;
    margin-bottom: 25px;
    box-shadow: 0 8px 20px rgba(67,97,238,0.3);
}

/* textarea حرفه‌ای */
#text {
    border-radius: 15px;
    border: 1px solid #e0e0e0;
    padding: 15px;
    font-size: 14px;
    resize: none;
    transition: 0.25s;
    min-height: 180px;
}

#text:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
    outline: none;
}

/* شمارنده کاراکتر */
.char-counter {
    text-align: left;
    font-size: 12px;
    color: #888;
    margin-top: 5px;
}

/* دکمه ارسال */
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

/* خطاها */
.alert-error {
    background: #ffe5e5;
    color: #b00020;
    padding: 10px 15px;
    border-radius: 10px;
    margin-bottom: 10px;
    font-size: 13px;
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


                        @foreach($errors->all() as $error)
                        <p style='color:#9f0415;'>{{$error}}</p>
                        @endforeach
                        <form action="/admin/users/send-sms/{{$user->id}}" method="post">
                            @csrf
                            <div class="form-group">
                                <center>
                                    <label for="text">متن پیام به کاربر :
                                        @if ($user->name == null || $user->family == null )
                                        {{ substr($user->mobile,0,9).'****'}}
                                        @else
                                        {{$user->name}} {{$user->family}}
                                        @endif
                                    </label>
                                    <br>
                                    <textarea name="text" id="text" cols="30" rows="10" style="width: 100%">کاربر گرامی @if ($user->name != null || $user->family != null ){{$user->name}} {{$user->family}}@endif:</textarea>
                                </center>
                                <br>
                                <button type="submit" class="mt-4 btn btn-block btn-primary">ذخیره</button>
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