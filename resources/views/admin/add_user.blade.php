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
        margin-bottom: 20px;
        padding-bottom: 8px;
        border-bottom: 2px solid #eee;
        color: #333;
    }

    .form-group label {
        margin-top: 14px;
        font-weight: 500;
        font-size: 14px;
        color: #444;
    }

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

    .is-invalid {
        border-color: #dc3545 !important;
    }

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

    .select2-container .select2-selection--single {
        height: 45px !important;
        border-radius: 12px !important;
        border: 1px solid #e0e0e0 !important;
        padding: 8px 12px !important;
    }

    @media (max-width:768px) {
        .widget-content-area {
            padding: 25px 20px;
        }
    }
</style>
@endsection

@section('onvan')
افزودن کاربر
@endsection

@section('main')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
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
                        <form action="/admin/users/add/user" method="post" onsubmit="myFunction()">
                            @csrf

                            <div class="section-title" style="font-weight: bolder;font-size: larger;">اطلاعات کاربر جدید</div>

                            {{-- نام --}}
                            <div class="form-group">
                                <label for="name">نام</label>
                                <input type="text"
                                    name="name"
                                    id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}">
                                @error('name')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- نام خانوادگی --}}
                            <div class="form-group">
                                <label for="family">نام خانوادگی</label>
                                <input type="text"
                                    name="family"
                                    id="family"
                                    class="form-control @error('family') is-invalid @enderror"
                                    value="{{ old('family') }}">
                                @error('family')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- موبایل --}}
                            <div class="form-group">
                                <label for="mobile">موبایل</label>
                                <input type="tel"
                                    name="mobile"
                                    id="mobile"
                                    maxlength="11"
                                    class="form-control @error('mobile') is-invalid @enderror"
                                    value="{{ old('mobile') }}">
                                @error('mobile')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- کد ملی --}}
                            <div class="form-group">
                                <label for="nationalcode">کد ملی</label>
                                <input type="tel"
                                    name="nationalcode"
                                    id="nationalcode"
                                    maxlength="10"
                                    class="form-control @error('nationalcode') is-invalid @enderror"
                                    value="{{ old('nationalcode') }}">
                                @error('nationalcode')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- تاریخ تولد --}}
                            <div class="form-group">
                                <label for="birth_day">تاریخ تولد</label>
                                <input type="text"
                                    name="birth_day"
                                    id="birth_day"
                                    placeholder="1402/02/01"
                                    class="form-control @error('birth_day') is-invalid @enderror"
                                    value="{{ old('birth_day') }}">
                                @error('birth_day')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- کوین --}}
                            <div class="form-group">
                                <label for="coin">کوین</label>
                                <input type="tel"
                                    name="coin"
                                    id="coin"
                                    onkeyup="format(this)"
                                    class="form-control @error('coin') is-invalid @enderror"
                                    value="{{ old('coin') }}">
                                @error('coin')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- نقش --}}
                            <div class="form-group">
                                <label for="role">انتخاب نقش</label>
                                <select id="role"
                                    name="role"
                                    class="w-100 @error('role') is-invalid @enderror">
                                    <option value="">انتخاب کنید</option>
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ $role->description }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('role')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="mt-4 btn btn-block" style="background-color: #FFD8D8;">
                                ذخیره
                            </button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

<script>
    $(function() {
        $("#role").select2({
            placeholder: "انتخاب نقش کاربر",
            dir: "rtl",
            width: "100%"
        });
    });

    function myFunction() {
        var price = document.getElementById('coin').value;
        var realPrice = price.replaceAll(",", "");
        document.getElementById('coin').value = realPrice;
    }
</script>
@endsection