@extends('admin.layout.master')

@section('head')
<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->

<style>
    .layout-px-spacing {
        background: #f4f6f9;
        min-height: 100vh;
        padding: 30px;
    }

    .widget-content-area {
        background: #ffffff;
        border-radius: 20px;
        padding: 35px 40px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.06);
    }

    .section-title {
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 25px;
        padding-bottom: 8px;
        border-bottom: 2px solid #eee;
        color: #333;
    }

    .form-group label {
        font-weight: 500;
        font-size: 14px;
        margin-bottom: 6px;
        color: #444;
    }

    /* select */
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

    /* دکمه */
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
    }

    /* نمایش خطا */
    .error-box {
        background: #fff0f0;
        border: 1px solid #ffcccc;
        color: #c40000;
        padding: 10px 15px;
        border-radius: 12px;
        margin-bottom: 15px;
        font-size: 13px;
    }
</style>
@endsection
@section('onvan')
افزودن کارمند
@endsection

@section('main')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing" id="cancel-row">
        <div id="basic" class="col-lg-12 layout-spacing">
            <div class="widget-content widget-content-area">

                <div class="row">
                    <div class="col-lg-6 col-12 mx-auto">
                       
                        @if(Session::has('suc'))
                        <div class="alert alert-success" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"></path>
                                <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"></path>
                            </svg>
                            {{ Session::get('suc') }}
                        </div>
                        @endif
                        <form method="post" action="/admin/users/employee/add">
                            @CSRF
                            <div class="section-title" style="font-weight: bolder;font-size: larger;">انتساب کارمند به فروشگاه</div>

                            <div class="form-group">
                                <label for="shop">فروشگاه</label>
                                <select id="shop" name="shop" class="form-control">
                                <option value="">انتخاب کنید</option>
                                    @foreach ($shops as $shop)
                                    <option value="{{ $shop->id }}" {{ old('shop') == $shop->id ? 'selected' : '' }}>
                                        {{$shop->name}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('shop')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                                <br>
                                <label for="country">کاربر</label>
                                <select id="country" name="user" class="form-control">
                                <option value="">انتخاب کنید</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('shop') == $shop->id ? 'selected' : '' }}>
                                        {{$user->mobile}}{{ $user->fullname() }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('user')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                                <button type="submit" class="mt-4 btn btn-block" style="background-color: #FFD8D8;">ذخیره</button>
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
<!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

<script>
    $(function() {
        $('#shop').select2({
            placeholder: "انتخاب فروشگاه",
            dir: "rtl",
            width: "100%"
        });

        $('#country').select2({
            placeholder: "انتخاب کاربر",
            dir: "rtl",
            width: "100%"
        });
    });
</script>

@endsection