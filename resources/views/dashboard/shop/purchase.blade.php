@extends('layout/masterc')
@section('title')
| پرداخت
@endsection
@section('style')
<style>
    /* ===== Payment Page ===== */

    .login-wrapper {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa, #e4e9f2);
    }

    /* کارت اصلی */
    .col-10.col-md-6.col-lg-4 {
        background: #ffffff;
        padding: 30px 25px;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        transition: 0.3s ease;
    }

    .col-10.col-md-6.col-lg-4:hover {
        transform: translateY(-5px);
    }

    /* ===== Shop Info Box ===== */

    .purchase-page-shop-info {
        text-align: center;
        margin-bottom: 20px;
    }

    .payment-shop-image {
        width: 85px;
        height: 85px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #2d9f04;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    /* نام فروشگاه */
    .purchase-page-shopname {
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    .purchase-page-shopname h3 {
        font-weight: 700;
        font-size: 20px;
    }

    /* دکمه جزئیات */
    .collapse-btn-for-more-info {
        background: #f1f3f7;
        border-radius: 50px;
        border: none;
        padding: 4px 12px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: 0.2s;
    }

    .collapse-btn-for-more-info:hover {
        background: #e0e6ef;
    }

    /* اطلاعات بازشو */
    .purchase-collapse-info {
        flex-direction: column;
        gap: 6px;
        margin-top: 10px;
        font-size: 14px;
        color: #555;
    }

    /* ===== Form Styling ===== */

    .register-form label {
        font-weight: 600;
        font-size: 14px;

        text-align: right;
    }

    /* Input */
    .input-group {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .input-group-text {
        background: #f1f3f7;
        border: none;
    }

    .form-control {
        border: none !important;
        padding: 12px;
        font-size: 14px;
    }

    .form-control:focus {
        box-shadow: none !important;
        background: #f9fbff;
    }

    /* واحد پول */
    #rial-icon {
        font-weight: 600;
        color: #666;
    }

    /* تبدیل عدد به حروف */
    #result {
        font-size: 13px;
        color: #2d9f04;
        margin-top: 5px;
        display: block;
        min-height: 20px;
    }

    /* Radio Button Style */
    input[type="radio"] {
        accent-color: #2d9f04;
        margin-left: 5px;
    }

    /* دکمه پرداخت */
    .btn-theme {

        border: none;
        border-radius: 14px;
        padding: 14px;
        font-weight: 700;
        font-size: 15px;
        transition: 0.25s;
        box-shadow: 0 8px 20px rgba(45, 159, 4, 0.3);
    }

    .btn-theme:hover {

        transform: translateY(-2px);
    }

    /* لینک بازگشت */
    a {
        font-size: 13px;
        color: #666;
        text-decoration: none;
    }

    a:hover {
        color: #2d9f04;
    }

    /* پیام خطا */
    #error {
        background: #ffe5e8;
        padding: 8px;
        border-radius: 8px;
        font-size: 13px;
    }

    /* موبایل */
    @media (max-width: 576px) {
        .col-10.col-md-6.col-lg-4 {
            padding: 25px 18px;
        }

        .purchase-page-shopname h3 {
            font-size: 17px;
        }

        .btn-theme {
            padding: 12px;
        }
    }
</style>
@endsection
@section('content')

<div class="login-wrapper d-flex align-items-center justify-content-center text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10 col-md-6 col-lg-4">

                {{-- اطلاعات فروشگاه --}}
                <div class="purchase-page-shop-info">
                    <img src='{{ asset("$icon->address") }}' alt="logo" class="payment-shop-image">
                    <div class="d-flex purchase-page-shopname">
                        <h3 class="mt-3">{{ $shop->name }}</h3>
                        <button onclick="collapse()" class="btn collapse-btn-for-more-info" type="button">
                            <p>جزئیات</p>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                    </div>

                    <div class="w-100" id="collapse-more-info-content" style="max-height: 0px; overflow: hidden;">
                        <div class="d-flex purchase-collapse-info">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt"></i>
                                <p class="m-1">{{ $shop->address }}</p>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-telephone"></i>
                                <p class="m-1">{{ $shop->mobile }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- پیام‌های خطا --}}
                @if ($errors->any())
                @foreach ($errors->all() as $error)
                <p style='color:#9f0415;'>{{ $error }}</p>
                @endforeach
                @endif
                <p style='color:#9f0415; display:none;' id="error"></p>

                {{-- فرم پرداخت --}}
                <div class="register-form mt-3" id="checked">
                    @if ($user)
                    {{-- کاربر لاگین‌شده --}}
                    <form action="/factor/{{ $shop->slug_code }}" method="POST">
                        @csrf
                        @foreach ($conditions as $cc => $condition)
                        <div class="">
                            <input type="radio" id="month{{ $condition->id }}" value="{{ $condition->month }}" name="month" @if ($cc==0) checked @endif>
                            <label for="month{{ $condition->id }}">{{ $condition->month }}ماهه</label>
                        </div>
                        @endforeach
                        <br>
                        <label for="price_display" class="mb-3">مبلغ را وارد کنید:</label>
                        <div class="input-group text-start mb-4">
                            <span class="input-group-text"><i class="fa fa-money-bill"></i></span>
                            <input
                                type="text"
                                id="price_display"
                                class="form-control price-input"
                                autocomplete="off"
                                autofocus
                                placeholder="مبلغ را وارد کنید"
                                oninput="handlePriceInput(this)">

                            {{-- فیلد hidden برای ذخیره مقدار اصلی (بدون کاما) --}}
                            <input type="hidden" id="price_hidden" name="price" />
                        </div>
                        <span id="rial-icon">{{ $MONEY_SIGN }}</span>
                        <div><span id="result"></span></div>
                        <button class="btn btn-lg w-100 btn-theme" type="submit">محاسبه و پرداخت</button>
                        <br><br>
                        <a href="/shop/{{ $shop->slug_code }}">بازگشت به فروشگاه</a>
                    </form>
                    @else
                    {{-- کاربر مهمان --}}
                    <div><span id="result"></span></div>
                    <div id="veify_code_show"></div>
                    <a href="/login"><button class="btn btn-lg w-100 btn-theme" type="button">وارد شوید</button></a>
                    <br><br>
                    <a href="/shop/{{ $shop->slug_code }}">بازگشت به فروشگاه</a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

{{-- اسکریپت‌ها --}}
<script>
    function collapse() {
        const content = document.getElementById('collapse-more-info-content');
        content.style.maxHeight = content.style.maxHeight === '0px' ? '100px' : '0px';
    }

    // تابع اصلی برای مدیریت ورودی
    function handlePriceInput(inputElement) {
        // حذف همه کاراکترهای غیر عددی
        let rawValue = inputElement.value.replace(/[^0-9]/g, '');
        
        // ذخیره مقدار خام در فیلد hidden (برای ارسال به سرور)
        document.getElementById('price_hidden').value = rawValue;
        
        // تبدیل به فرمت سه رقم سه رقم با کاما
        if (rawValue !== '') {
            // اضافه کردن کاما بین هر سه رقم
            inputElement.value = Number(rawValue).toLocaleString('en-US');
        } else {
            inputElement.value = '';
        }
        
        // تبدیل عدد به حروف
        convertToPersianText(rawValue);
    }

    // تابع تبدیل عدد به حروف فارسی
    function numberToPersianWords(num) {
        if (num === 0 || num === '0') return "صفر";
        
        const numStr = num.toString();
        if (numStr === '') return "";
        
        const number = parseInt(numStr, 10);
        if (isNaN(number)) return "";
        
        const units = ["", "یک", "دو", "سه", "چهار", "پنج", "شش", "هفت", "هشت", "نه"];
        const teens = ["ده", "یازده", "دوازده", "سیزده", "چهارده", "پانزده", "شانزده", "هفده", "هجده", "نوزده"];
        const tens = ["", "ده", "بیست", "سی", "چهل", "پنجاه", "شصت", "هفتاد", "هشتاد", "نود"];
        const hundreds = ["", "صد", "دویست", "سیصد", "چهارصد", "پانصد", "ششصد", "هفتصد", "هشتصد", "نهصد"];
        const scales = ["", "هزار", "میلیون", "میلیارد", "بیلیون", "تریلیون"];

        function convertLessThanOneThousand(n) {
            if (n === 0) return "";
            
            let result = "";
            const hundred = Math.floor(n / 100);
            const remainder = n % 100;
            
            if (hundred > 0) {
                result += hundreds[hundred];
                if (remainder > 0) result += " و ";
            }
            
            if (remainder > 0) {
                if (remainder < 10) {
                    result += units[remainder];
                } else if (remainder < 20) {
                    result += teens[remainder - 10];
                } else {
                    const ten = Math.floor(remainder / 10);
                    const unit = remainder % 10;
                    result += tens[ten];
                    if (unit > 0) result += " و " + units[unit];
                }
            }
            
            return result;
        }

        let result = "";
        let scaleIndex = 0;
        let tempNum = number;
        
        while (tempNum > 0) {
            const chunk = tempNum % 1000;
            if (chunk !== 0) {
                let chunkWords = convertLessThanOneThousand(chunk);
                if (scaleIndex > 0) {
                    chunkWords += " " + scales[scaleIndex];
                }
                if (result !== "") {
                    result = chunkWords + " و " + result;
                } else {
                    result = chunkWords;
                }
            }
            tempNum = Math.floor(tempNum / 1000);
            scaleIndex++;
        }
        
        return result;
    }

    // تابع تبدیل و نمایش به حروف
    function convertToPersianText(rawValue) {
        const resultElement = document.getElementById("result");
        
        if (!rawValue || rawValue === '') {
            resultElement.textContent = "";
            return;
        }
        
        const persianWords = numberToPersianWords(rawValue);
        if (persianWords) {
            resultElement.textContent = `${persianWords} ریال`;
        } else {
            resultElement.textContent = "لطفاً عدد معتبر وارد کنید";
        }
    }

    // برای پشتیبانی از چسباندن متن (paste)
    document.getElementById('price_display')?.addEventListener('paste', function(e) {
        setTimeout(() => {
            handlePriceInput(this);
        }, 10);
    });

    function showError(msg) {
        const el = document.getElementById('error');
        if (el) {
            el.textContent = msg;
            el.style.display = 'block';
        }
    }
</script>
@endsection