@extends('admin.layout.master')

@section('onvan')
اضافه کردن فروشگاه
@endsection

@section('head')
<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
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
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
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
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    /* دکمه ذخیره */
    .btn-primary {
        border-radius: 14px;
        padding: 12px;
        font-weight: 700;
        font-size: 15px;
        background: linear-gradient(45deg, #4361ee, #3a0ca3);
        border: none;
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
        transition: 0.25s;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        background: linear-gradient(45deg, #3a0ca3, #240046);
    }

    /* فاصله بهتر بین سکشن‌ها */
    .form-group>br {
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
                       
                        <form method="post" action="/admin/shops/add" enctype="multipart/form-data">
                            @csrf

                            {{-- نام فروشگاه --}}
                            <div class="form-group">
                                <label for="name">نام فروشگاه</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    id="name" value="{{ old('name') }}">
                                @error('name')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- نام سئو --}}
                            <div class="form-group">
                                <label for="slug">نام سئو</label>
                                <input type="text" name="slug_name" class="form-control @error('slug_name') is-invalid @enderror"
                                    id="slug" value="{{ old('slug_name') }}">
                                @error('slug_name')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- تلفن --}}
                            <div class="form-group">
                                <label for="phone">تلفن ثابت</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" value="{{ old('phone') }}">
                                @error('phone')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- موبایل --}}
                            <div class="form-group">
                                <label for="mobile">شماره موبایل</label>
                                <input type="tel" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                                    id="mobile" value="{{ old('mobile') }}">
                                @error('mobile')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- پیش پرداخت --}}
                            <div class="form-group">
                                <label for="advance_payment">پیش پرداخت (%)</label>
                                <input type="number" name="advance_payment"
                                    class="form-control @error('advance_payment') is-invalid @enderror"
                                    id="advance_payment" value="{{ old('advance_payment') }}">
                                @error('advance_payment')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- تعداد اقساط --}}
                            <div class="form-group">
                                <label for="installments_number">تعداد اقساط</label>
                                <input type="number" name="installments_number"
                                    class="form-control @error('installments_number') is-invalid @enderror"
                                    id="installments_number" value="{{ old('installments_number') }}">
                                @error('installments_number')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- سود --}}
                            <div class="form-group">
                                <label for="profit">درصد سود ماهانه</label>
                                <input type="number" name="profit"
                                    class="form-control @error('profit') is-invalid @enderror"
                                    id="profit" value="{{ old('profit') }}">
                                @error('profit')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="off">تخفیف</label>
                                <input type="text" name="off"
                                    class="form-control @error('off') is-invalid @enderror"
                                    id="off" value="{{ old('off') }}">
                            </div>

                            {{-- آدرس --}}
                            <div class="form-group">
                                <label for="address">آدرس</label>
                                <textarea name="address"
                                    class="form-control @error('address') is-invalid @enderror"
                                    id="address" rows="3">{{ old('address') }}</textarea>
                                @error('address')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- توضیحات --}}
                            <div class="form-group">
                                <label for="description">توضیحات</label>
                                <textarea name="description"
                                    class="form-control @error('description') is-invalid @enderror"
                                    id="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- دسته بندی --}}
                            <p class="mt-4">دسته بندی</p>
                            @foreach ($categories as $category)
                            <div class="form-check">
                                <input type="checkbox"
                                    name="categories[]"
                                    value="{{ $category->id }}"
                                    class="form-check-input"
                                    id="category{{ $category->id }}"
                                    {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="category{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                            @endforeach

                            {{-- روند خرید --}}
                            <p class="mt-4">روند خرید</p>
                            <div class="form-check">
                                <input type="radio" name="sale_type" value="1"
                                    class="form-check-input"
                                    {{ old('sale_type', 1) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label">روند 1</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="sale_type" value="2"
                                    class="form-check-input"
                                    {{ old('sale_type') == 2 ? 'checked' : '' }}>
                                <label class="form-check-label">روند 2</label>
                            </div>

                            {{-- نمایش --}}
                            <p class="mt-4">نمایش در سایت</p>
                            <div class="form-check">
                                <input type="radio" name="display" value="1"
                                    class="form-check-input"
                                    {{ old('display', 1) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label">نمایش</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="display" value="0"
                                    class="form-check-input"
                                    {{ old('display') == 0 ? 'checked' : '' }}>
                                <label class="form-check-label">عدم نمایش</label>
                            </div>

                            {{-- مدیر فروشگاه --}}
                            <p class="mt-4">اطلاعات مدیر فروشگاه</p>

                            <div class="form-group">
                                <label>نام</label>
                                <input type="text" name="firstname"
                                    class="form-control @error('firstname') is-invalid @enderror"
                                    value="{{ old('firstname') }}">
                                @error('firstname')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>نام خانوادگی</label>
                                <input type="text" name="lastname"
                                    class="form-control @error('lastname') is-invalid @enderror"
                                    value="{{ old('lastname') }}">
                                @error('lastname')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>کد ملی</label>
                                <input type="text" name="nationalcode"
                                    class="form-control @error('nationalcode') is-invalid @enderror"
                                    value="{{ old('nationalcode') }}">
                                @error('nationalcode')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- عکس --}}
                            <p class="mt-4">عکس ها</p>

                            <div class="form-group">
                                <input type="file" name="cover"
                                    class="form-control @error('cover') is-invalid @enderror">
                                @error('cover')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="file" name="icon"
                                    class="form-control @error('icon') is-invalid @enderror">
                                @error('icon')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" style="width: 100%;background-color: #FFD8D8;" class="mt-4 btn btn-block">
                                    ذخیره
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function() {
        $("#country").select2();
    });
</script>
@endsection