@extends('layout/master')

@section('title')
فرم
@endsection

@section('content')
<style>
    .stock-page {
        direction: rtl;
        /* font-family: 'iran-md', sans-serif; */
        padding: 100px 15px 60px;
    }

    .stock-box {
        max-width: 850px;
        margin: 0 auto;
        background: #fff;
        border-radius: 18px;
        padding: 35px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    }

    .stock-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 25px;
        color: #222;
    }

    .stock-text {
        text-align: center;
        line-height: 2.2;
        font-size: 16px;
        color: #555;
        margin-bottom: 30px;
    }

    .stock-call-btn {
        display: block;
        margin: 0 auto 30px;
        border: none;
        background: #9f0415;
        color: #fff;
        padding: 12px 45px;
        border-radius: 10px;
        font-size: 16px;
        cursor: pointer;
        transition: .3s;
    }

    .stock-call-btn:hover {
        background: #7f0310;
        color: #fff;
    }

    .stock-form {
        display: none;
        border-top: 1px solid #eee;
        padding-top: 30px;
    }

    .stock-form.show {
        display: block;
    }

    .form-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
    }

    .type-box {
        background: #f8f8f8;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .type-title {
        font-weight: bold;
        margin-bottom: 15px;
    }

    .type-options {
        display: flex;
        flex-wrap: wrap;
        gap: 15px 25px;
    }

    .type-option {
        display: flex;
        align-items: center;
        gap: 7px;
        cursor: pointer;
    }

    .type-option input {
        width: 17px;
        height: 17px;
        cursor: pointer;
    }

    .stock-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #444;
    }

    .stock-input {
        width: 100%;
        height: 48px;
        border: 1px solid #ddd;
        border-radius: 9px;
        text-align: right;
        padding: 0 14px;
        outline: none;
        transition: .2s;
    }

    .stock-input:focus {
        border-color: #9f0415;
        box-shadow: 0 0 0 2px rgba(159, 4, 21, .08);
    }

    .stock-submit {
        width: 100%;
        height: 50px;
        border: none;
        border-radius: 10px;
        background: #9f0415;
        color: white;
        font-size: 16px;
        margin-top: 10px;
        cursor: pointer;
    }

    .stock-submit:hover {
        background: #7f0310;
    }

    @media (max-width: 576px) {
        .stock-page {
            padding-top: 80px;
        }

        .stock-box {
            padding: 25px 18px;
        }

        .stock-title {
            font-size: 20px;
        }

        .stock-text {
            font-size: 14px;
        }

        .type-options {
            flex-direction: column;
            gap: 12px;
        }
    }

    .stock-error {
        color: #d32f2f;
        font-size: 13px;
        margin-top: 6px;
        padding-right: 4px;
    }

    .stock-input.is-invalid {
        border-color: #d32f2f;
    }

    .stock-input.is-invalid:focus {
        box-shadow: 0 0 0 2px rgba(211, 47, 47, .12);
    }

    .stock-eligible {
    max-width: 850px;
    margin: 30px auto 0;
    background: #fff;
    border-radius: 18px;
    padding: 30px 35px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
}

.stock-eligible-title {
    font-size: 18px;
    font-weight: bold;
    color: #9f0415;
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f0f0f0;
}

.stock-eligible-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.stock-eligible-list li {
    position: relative;
    padding: 10px 30px 10px 0;
    line-height: 1.9;
    font-size: 15px;
    color: #444;
    border-bottom: 1px dashed #eee;
}

.stock-eligible-list li:last-child {
    border-bottom: none;
}

/* دایره قرمز جلوی هر آیتم */
.stock-eligible-list li::before {
    content: '';
    position: absolute;
    right: 0;
    top: 19px;
    width: 12px;
    height: 12px;
    border: 2px solid #9f0415;
    border-radius: 50%;
    box-sizing: border-box;
}

/* نسخه موبایل */
@media (max-width: 576px) {
    .stock-eligible {
        padding: 22px 18px;
        margin-top: 20px;
    }

    .stock-eligible-title {
        font-size: 16px;
    }

    .stock-eligible-list li {
        font-size: 14px;
        padding: 8px 26px 8px 0;
    }

    .stock-eligible-list li::before {
        top: 16px;
        width: 10px;
        height: 10px;
    }
}
</style>


<div class="stock-page">

    <div class="stock-box mt-2">

        <div class="stock-title">
        فراخوان افزایش سرمایه و توسعه جامعه سهام‌داران صندوق رفاه دانشگاه یزد
        </div>

        <hr>

        <div class="stock-text">
            شرکت سهامی خاص توسعه‌گران آرتان پویا یزد (تاپ)
            <br>
            شماره ثبت: ۲۵۵۲۳
            <br>
            با هدف توسعه فعالیت‌های اقتصادی شرکت، فراهم نمودن امکان
            سهامدار شدن اعضای جامعه دانشگاه یزد و تقویت سامانه یزدان‌پلاس
        </div>

        <button type="button" class="stock-call-btn" id="stockCallBtn">
            شرکت در فراخوان
        </button>


        <div class="stock-form" id="stockForm">

            <div class="form-title">
                اطلاعات متقاضی
            </div>

            <form action="{{ route('stock.submit') }}" method="POST">

                @csrf

                {{-- نوع همکاری --}}
                <div class="type-box">

                    <div class="type-title">
                        وضعیت همکاری خود را انتخاب کنید:
                    </div>

                    <div class="type-options">

                        {{-- عضو هیأت علمی --}}
                        <label class="type-option">
                            <input type="radio"
                                name="type"
                                value="1"
                                {{ old('type', $user->type ?? '') == 1 ? 'checked' : '' }}>
                            <span>اعضای هیات علمی دانشگاه یزد</span>
                        </label>


                        {{-- کارمند --}}
                        <label class="type-option">
                            <input type="radio"
                                name="type"
                                value="2"
                                {{ old('type', $user->type ?? '') == 2 ? 'checked' : '' }}>
                            <span>️کارکنان رسمی، پیمانی، قراردادی و سایر کارکنان واجد شرایط دانشگاه یزد</span>
                        </label>


                        {{-- عضو هیأت علمی بازنشسته --}}
                        <label class="type-option">
                            <input type="radio"
                                name="type"
                                value="3"
                                {{ old('type', $user->type ?? '') == 3 ? 'checked' : '' }}>
                            <span>بازنشستگان دانشگاه یزد</span>
                        </label>


                        {{-- کارمند بازنشسته --}}
                        <label class="type-option">
                            <input type="radio"
                                name="type"
                                value="4"
                                {{ old('type', $user->type ?? '') == 4 ? 'checked' : '' }}>
                            <span>️نیروهای شرکتی و شاغلان شرکت‌های طرف قرارداد دانشگاه یزد</span>
                        </label>


                        {{-- نیروی شرکتی یا قراردادی --}}
                        <label class="type-option">
                            <input type="radio"
                                name="type"
                                value="5"
                                {{ old('type', $user->type ?? '') == 5 ? 'checked' : '' }}>
                            <span>پردیس فناوری و اعضای واجد شرایط سایر مجموعه‌ها و واحدهای وابسته به دانشگاه یزد</span>
                        </label>

                        <label class="type-option">
                            <input type="radio"
                                name="type"
                                value="6"
                                {{ old('type', $user->type ?? '') == 6 ? 'checked' : '' }}>
                            <span>️سایر اشخاص</span>
                        </label>

                    </div>

                    @error('type')
                        <div class="stock-error">{{ $message }}</div>
                    @enderror

                </div>


                <div class="row">

                    {{-- نام --}}
                    <div class="col-md-6 mb-3">

                        <label class="stock-label">
                            نام
                        </label>

                        <input type="text"
                            name="name"
                            class="stock-input @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name ?? '') }}"
                            placeholder="نام خود را وارد کنید">
                        @error('name')
                        <div class="stock-error">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- نام خانوادگی --}}
                    <div class="col-md-6 mb-3">

                        <label class="stock-label">
                            نام خانوادگی
                        </label>

                        <input type="text"
                            name="family"
                            class="stock-input @error('family') is-invalid @enderror"
                            value="{{ old('family', $user->family ?? '') }}"
                            placeholder="نام خانوادگی خود را وارد کنید">
                        @error('family')
                        <div class="stock-error">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- موبایل --}}
                    <div class="col-md-6 mb-3">

                        <label class="stock-label">
                            شماره موبایل
                        </label>

                        <input type="text"
                            name="mobile"
                            class="stock-input @error('mobile') is-invalid @enderror"
                            value="{{ old('mobile', $user->mobile ?? '') }}"
                            placeholder="شماره موبایل خود را وارد کنید"
                            dir="ltr">
                        @error('mobile')
                        <div class="stock-error">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- کد ملی --}}
                    <div class="col-md-6 mb-3">

                        <label class="stock-label">
                            کد ملی
                        </label>

                        <input type="text"
                            name="national_code"
                            class="stock-input @error('national_code') is-invalid @enderror"
                            value="{{ old('nationalcode', $user->nationalcode ?? '') }}"
                            placeholder="کد ملی خود را وارد کنید"
                            dir="ltr">
                        @error('national_code')
                        <div class="stock-error">{{ $message }}</div>
                        @enderror

                    </div>

                </div>


                <button type="submit" class="stock-submit">
                    ثبت درخواست
                </button>

            </form>

        </div>

    </div>


    <div class="stock-eligible">

    <div class="stock-eligible-title">
        افراد واجد شرایط:
    </div>

    <ul class="stock-eligible-list">
        <li>اعضای هیات علمی دانشگاه یزد؛</li>
        <li>کارکنان رسمی، پیمانی، قراردادی و سایر کارکنان واجد شرایط دانشگاه یزد؛</li>
        <li>بازنشستگان دانشگاه یزد؛</li>
        <li>نیروهای شرکتی و شاغلان شرکت‌های طرف قرارداد دانشگاه یزد؛</li>
        <li>پردیس فناوری و اعضای واجد شرایط سایر مجموعه‌ها و واحدهای وابسته به دانشگاه یزد؛</li>
        <li>سایر اشخاص</li>
    </ul>

</div>

</div>

@endsection

@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('stockForm');
        const btn  = document.getElementById('stockCallBtn');

        // اگه خطای ولیدیشن وجود داره، فرم رو باز نگه دار
        @if($errors->any())
            form.classList.add('show');
            btn.innerText = 'بستن فرم';
        @endif

        // فقط یک بار listener اضافه می‌شه
        btn.addEventListener('click', function () {
            form.classList.toggle('show');
            this.innerText = form.classList.contains('show')
                ? 'بستن فرم'
                : 'شرکت در فراخوان';
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


{{-- پیام موفقیت --}}
@if (session('suc'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'موفق',
        text: @json(session('suc')),
        confirmButtonText: 'تأیید',
        confirmButtonColor: '#9f0415',
        timer: 3000,
        timerProgressBar: true,
    });
</script>
@endif


{{-- پیام خطا --}}
@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'خطا',
        text: @json(session('error')),
        confirmButtonText: 'تأیید',
        confirmButtonColor: '#9f0415',
    });
</script>
@endif

@endsection