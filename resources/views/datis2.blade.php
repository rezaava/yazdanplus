@extends('layout/master')

@section('title')
خرید محصولات داتیس 2
@endsection

@section('content')

<style>
    /* ---------- RESET & BASE ---------- */
    .datis-page {
        margin-top: 70px;
        direction: rtl;
        padding: 30px 0;
        font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    .datis-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 30px;
    }

    .datis-title {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .datis-title .badge-off {
        background: #dc2626;
        color: #fff;
        font-size: 18px;
        padding: 4px 14px;
        border-radius: 30px;
        font-weight: 600;
    }

    /* ---------- BUTTONS ---------- */
    .excel-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        border-radius: 50px;
        background: #dc2626;
        color: #fff !important;
        text-decoration: none !important;
        font-size: 14px;
        font-weight: 600;
        transition: 0.25s ease;
        border: none;
        box-shadow: 0 4px 14px rgba(220, 38, 38, 0.3);
        white-space: nowrap;
    }

    .excel-btn:hover {
        background: #b91c1c;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
        color: #fff !important;
    }

    .excel-btn i {
        font-size: 18px;
    }

    .buy-btn,
    .datis-buy-all-btn {
        border: none;
        border-radius: 50px;
        padding: 12px 32px;
        background: #dc2626;
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.25s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 14px rgba(220, 38, 38, 0.3);
        min-width: 140px;
    }

    .buy-btn:hover,
    .datis-buy-all-btn:hover {
        background: #b91c1c;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
        color: #fff;
    }

    /* ---------- USER FORM ---------- */
    .user-form {
        background: #f8fafc;
        padding: 20px 25px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        margin-bottom: 25px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
        justify-content: center;
    }

    .user-form .form-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .user-form .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
    }

    .user-form .form-group input {
        padding: 8px 12px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        transition: 0.2s;
        min-width: 140px;
    }

    .user-form .form-group input:focus {
        border-color: #dc2626;
        outline: none;
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.12);
    }

    .user-form .form-group input:disabled {
        background: #f1f5f9;
        cursor: not-allowed;
        opacity: 0.7;
    }

    @media (max-width: 768px) {
        .user-form {
            flex-direction: column;
            padding: 15px;
        }
        .user-form .form-group {
            width: 100%;
        }
        .user-form .form-group input {
            min-width: unset;
            width: 100%;
        }
    }

    /* ---------- TABLE ---------- */
    .datis-table-wrapper {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        border: 1px solid #f1f5f9;
    }

    .datis-table {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
        font-size: 15px;
    }

    .datis-table thead {
        background: #f8fafc;
        border-bottom: 2px solid #e9edf2;
    }

    .datis-table th {
        padding: 18px 12px;
        font-weight: 700;
        color: #1e293b;
        font-size: 14px;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .datis-table td {
        padding: 16px 12px;
        vertical-align: middle;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
    }

    .datis-table tbody tr {
        transition: 0.2s;
    }

    .datis-table tbody tr:hover {
        background-color: #fafcff;
    }

    .datis-table tbody tr:last-child td {
        border-bottom: none;
    }

    .product-name {
        font-weight: 600;
        color: #0f172a;
        font-size: 15px;
    }

    .product-price {
        font-weight: 700;
        font-size: 16px;
    }

    .product-price small {
        font-weight: 400;
        color: #64748b;
        font-size: 12px;
        margin-right: 4px;
    }

    /* ---------- PRICE WRAPPER (عمودی) ---------- */
    .price-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 2px;
        line-height: 1.4;
    }

    .original-price {
        color: #94a3b8;
        text-decoration: line-through;
        font-size: 14px;
        font-weight: 500;
        order: 1;
    }

    .discounted-price {
        color: #63d93b;
        font-size: 17px;
        font-weight: 700;
        order: 2;
    }

    .discounted-price small {
        font-size: 12px;
        color: #64748b;
        font-weight: 400;
    }

    /* ---------- INPUT COUNT ---------- */
    .count-input {
        width: 80px;
        text-align: center;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 6px;
        font-size: 15px;
        font-weight: 600;
        transition: 0.2s;
        background: #f8fafc;
        cursor: pointer;
    }

    .count-input:focus {
        border-color: #dc2626;
        outline: none;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.12);
    }

    .count-input::-webkit-outer-spin-button,
    .count-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .count-input[type="number"] {
        -moz-appearance: textfield;
    }

    .count-input:disabled {
        background: #f1f5f9;
        cursor: not-allowed;
        opacity: 0.7;
        border-color: #e2e8f0;
    }

    /* ---------- PURCHASED MESSAGE ---------- */
    .datis-purchased-message {
        margin-top: 25px;
        padding: 18px 25px;
        border-radius: 16px;
        background: #fef2f2;
        color: #991b1b;
        text-align: center;
        font-weight: 700;
        font-size: 17px;
        border: 1px solid #fca5a5;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .datis-purchased-message i {
        font-size: 24px;
        color: #dc2626;
    }

    /* ---------- BUY AREA ---------- */
    .datis-buy-area {
        text-align: center;
        margin-top: 30px;
    }

    /* ---------- PURCHASED SUMMARY ---------- */
    .purchased-summary {
        margin-top: 20px;
        padding: 20px 25px;
        background: #f8fafc;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        align-items: center;
        gap: 20px;
    }

    .summary-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }

    .summary-item .label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }

    .summary-item .value {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
    }

    .summary-item .value.green {
        color: #63d93b;
    }

    .summary-item .value.red {
        color: #dc2626;
    }

    .summary-item .value.blue {
        color: #3b82f6;
    }

    .summary-divider {
        width: 1px;
        height: 50px;
        background: #e2e8f0;
    }

    @media (max-width: 768px) {
        .purchased-summary {
            flex-direction: column;
            gap: 15px;
            padding: 15px 20px;
        }
        .summary-divider {
            width: 80%;
            height: 1px;
        }
        .summary-item .value {
            font-size: 18px;
        }
    }

    /* ---------- RESPONSIVE (Mobile) ---------- */
    @media (max-width: 768px) {
        .datis-page {
            padding: 15px 0;
            margin-top: 80px;
        }
        .datis-title {
            font-size: 17px;
            width: 100%;
            justify-content: center;
            display: contents;
        }
        .datis-title .badge-off {
            font-size: 15px;
            padding: 3px 12px;
        }
        .datis-header {
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }
        .excel-btn {
            padding: 8px 16px;
            font-size: 12px;
            gap: 6px;
            width: auto;
            justify-content: center;
        }
        .excel-btn i {
            font-size: 14px;
        }
        .datis-table-wrapper {
            border-radius: 14px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .datis-table {
            width: 100%;
            min-width: 0;
            font-size: 13px;
            table-layout: fixed;
        }
        .datis-table th,
        .datis-table td {
            padding: 10px 5px;
            white-space: normal;
            word-break: break-word;
        }
        .datis-table th:first-child,
        .datis-table td:first-child {
            width: 10%;
        }
        .datis-table th:nth-child(2),
        .datis-table td:nth-child(2) {
            width: 42%;
            white-space: normal;
            word-break: break-word;
            line-height: 1.7;
        }
        .datis-table th:nth-child(3),
        .datis-table td:nth-child(3) {
            width: 28%;
        }
        .datis-table th:nth-child(4),
        .datis-table td:nth-child(4) {
            width: 20%;
        }
        .product-name {
            font-size: 13px;
            line-height: 1.7;
            white-space: normal;
            word-break: break-word;
            overflow-wrap: anywhere;
        }
        .count-input {
            width: 55px;
            padding: 7px 3px;
            font-size: 13px;
        }
        .discounted-price {
            font-size: 13px;
        }
        .original-price {
            font-size: 10px;
        }
        .datis-table-wrapper {
            overflow-x: hidden;
        }
        .datis-buy-area {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .datis-buy-all-btn {
            padding: 12px 30px;
            font-size: 16px;
            min-width: 160px;
            width: auto;
        }
        .datis-purchased-message {
            font-size: 15px;
            padding: 14px 18px;
            flex-direction: row;
        }
        .datis-purchased-message i {
            font-size: 20px;
        }
    }

    @media (max-width: 400px) {
        .datis-table {
            width: 100%;
            min-width: 0;
            table-layout: fixed;
            font-size: 12px;
        }
        .datis-table th,
        .datis-table td {
            padding: 9px 4px;
            white-space: normal;
        }
        .datis-table th:first-child,
        .datis-table td:first-child {
            width: 9% !important;
        }
        .datis-table th:nth-child(2),
        .datis-table td:nth-child(2) {
            width: 43%;
        }
        .datis-table th:nth-child(3),
        .datis-table td:nth-child(3) {
            width: 28%;
        }
        .datis-table th:nth-child(4),
        .datis-table td:nth-child(4) {
            width: 20%;
        }
        .product-name {
            font-size: 12px;
            line-height: 1.6;
            white-space: normal;
            word-break: break-word;
        }
        .count-input {
            width: 52px;
            padding: 6px 2px;
            font-size: 12px;
        }
        .discounted-price {
            font-size: 12px;
        }
        .original-price {
            font-size: 10px;
        }
    }

    /* ---------- PURCHASE SUMMARY ---------- */
    .purchase-summary {
        padding: 20px 25px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        align-items: center;
        gap: 20px;
    }

    .summary-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        min-width: 140px;
    }

    .summary-item .label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }

    .summary-item .value {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
    }

    .summary-item .value.blue {
        color: #3b82f6;
    }

    .summary-item .value.red {
        color: #dc2626;
    }

    .summary-item .value.green {
        color: #63d93b;
    }

    .summary-item small {
        font-size: 12px;
        color: #64748b;
        font-weight: 400;
    }

    .summary-divider {
        width: 1px;
        height: 50px;
        background: #e2e8f0;
    }

    @media (max-width: 768px) {
        .purchase-summary {
            flex-direction: column;
            gap: 15px;
            padding: 18px;
        }
        .summary-divider {
            width: 80%;
            height: 1px;
        }
        .summary-item .value {
            font-size: 18px;
        }
    }

    /* ---------- MAIN TITLE (لینک تصویر) ---------- */
    .main-title {
        text-align: center;
        margin-bottom: 25px;
        padding: 10px 0;
    }

    .datis-price-image {
        width: 180px;
        height: 120px;
        object-fit: cover;
        border-radius: 16px;
        cursor: pointer;
        border: 3px solid #dc2626;
        box-shadow: 0 4px 20px rgba(220, 38, 38, 0.25);
        transition: all 0.3s ease;
    }

    .datis-price-image:hover {
        transform: scale(1.05) rotate(-1deg);
        box-shadow: 0 8px 35px rgba(220, 38, 38, 0.4);
        border-color: #b91c1c;
    }

    .image-caption {
        margin-top: 10px;
        color: #1e293b;
        font-size: 15px;
        font-weight: 600;
    }

    .image-caption small {
        display: block;
        color: #64748b;
        font-weight: 400;
        font-size: 13px;
        margin-top: 2px;
    }

    /* ---------- IMAGE MODAL ---------- */
    .datis-image-modal {
        display: none;
        position: fixed;
        z-index: 99999;
        inset: 0;
        background: rgba(0, 0, 0, 0.85);
        align-items: center;
        justify-content: center;
        padding: 30px;
    }

    .datis-image-modal.show {
        display: flex;
    }

    .datis-image-modal img {
        max-width: 95%;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 10px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
    }

    .datis-image-close {
        position: absolute;
        top: 20px;
        right: 30px;
        color: #fff;
        font-size: 35px;
        cursor: pointer;
        line-height: 1;
        transition: 0.2s;
    }

    .datis-image-close:hover {
        transform: scale(1.1);
        color: #fca5a5;
    }

    @media (max-width: 768px) {
        .datis-price-image {
            width: 140px;
            height: 95px;
        }
        .image-caption {
            font-size: 12px;
        }
        .datis-image-modal {
            padding: 15px;
        }
        .datis-image-modal img {
            max-width: 100%;
            max-height: 85vh;
        }
        .datis-image-close {
            top: 10px;
            right: 15px;
            font-size: 30px;
        }
    }

    /* ---------- TOAST NOTIFICATION ---------- */
    .toast-notification {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: #dc2626;
        color: #fff;
        padding: 14px 30px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        box-shadow: 0 8px 30px rgba(220, 38, 38, 0.4);
        z-index: 9999;
        opacity: 0;
        transition: all 0.4s ease;
        pointer-events: none;
        direction: rtl;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .toast-notification.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
        pointer-events: auto;
    }

    .toast-notification i {
        font-size: 22px;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
        direction: ltr;
        white-space: nowrap;
    }

    .quantity-btn {
        width: 28px;
        height: 30px;
        min-width: 28px;
        flex-shrink: 0;
        padding: 0;
        border: none;
        border-radius: 7px;
        background: #dc2626;
        color: #fff;
        font-size: 17px;
        font-weight: bold;
        line-height: 30px;
        text-align: center;
        cursor: pointer;
    }

    .quantity-control .count-input {
        width: 38px;
        min-width: 38px;
        height: 30px;
        padding: 3px;
        border-radius: 7px;
        font-size: 13px;
        text-align: center;
    }

    @media (max-width: 768px) {
        .quantity-control {
            gap: 2px;
        }
        .quantity-btn {
            width: 25px;
            min-width: 25px;
            height: 29px;
            line-height: 29px;
            font-size: 16px;
            border-radius: 6px;
        }
        .quantity-control .count-input {
            width: 32px;
            min-width: 32px;
            height: 29px;
            padding: 2px;
            font-size: 12px;
            border-radius: 6px;
        }
    }

    /* ---------- NOTICE BADGE ---------- */
    .notice-badge {
        display: inline-block;
        background: #fef2f2;
        color: #991b1b;
        font-size: 14px;
        padding: 8px 18px;
        border-radius: 12px;
        font-weight: 500;
        border: 1px solid #fca5a5;
        line-height: 1.8;
        text-align: center;
        max-width: 100%;
        margin-top: 5px;
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    }

    .notice-badge i {
        margin-left: 8px;
        color: #dc2626;
    }

    @media (max-width: 768px) {
        .notice-badge {
            font-size: 14px;
            padding: 6px 14px;
            line-height: 1.7;
            border-radius: 10px;
        }
    }

    @media (max-width: 400px) {
        .notice-badge {
            font-size: 13px;
            padding: 5px 10px;
            line-height: 1.6;
        }
    }
</style>

<div class="container datis-page">
<!-- 
    <div class="row text-center">
        <span class="notice-badge">
            <i class="fa-solid fa-circle-exclamation"></i>
            در صورت وجود مشکل با پشتیبانی تماس بگیرید : 09930348151
        </span>
    </div> -->

    <div class="main-title">
        <img
            src="/img/datis/datis.jpg"
            alt="لیست قیمت محصولات صنایع غذایی نشاط آور یزد"
            class="datis-price-image"
            onclick="openDatisImage()">

        <div class="image-caption">
            📋 لیست قیمت محصولات
            <small>صنایع غذایی نشاط آور یزد</small>
        </div>
    </div>

    <!-- HEADER -->
    <div class="datis-header">
        <h3 class="datis-title">
            🛒 لیست محصولات
            <span class="badge-off">۲۰٪ تخفیف
                به همراه اقساط 4 ماهه کسر از حقوق
            </span>

            <span class="notice-badge">
                <i class="fa-solid fa-circle-exclamation"></i>
                توجه: اقساط ماهانهٔ محصولات داتیس از اعتبار نقدی یزدان‌پلاس کسر نخواهد شد و شما می‌توانید از اعتبار نقدی ماهانه خود نیز استفاده کنید.
            </span>

        </h3>
        @if (Auth::check() && Auth::user()->hasRole('admin'))
        <a href="{{ route('datis2.excel') }}" class="excel-btn">
            <i class="fa-solid fa-file-excel"></i>
            دانلود اکسل خریدها
        </a>
        @endif
    </div>

    @if(!$order)

    @if($errors->any())
<div style="background: #fef2f2; border: 1px solid #fca5a5; border-radius: 12px; padding: 15px 20px; margin-bottom: 20px; color: #991b1b;">
    <ul style="margin: 0; padding-right: 20px;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

    <form action="{{ route('datis2.buy') }}" method="POST" id="datis-buy-form">
        @csrf

        <div class="user-form">
    <div class="form-group">
        <label for="name">نام</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="100">
    </div>
    <div class="form-group">
        <label for="family">نام خانوادگی</label>
        <input type="text" id="family" name="family" value="{{ old('family') }}" required maxlength="100">
    </div>
    <div class="form-group">
        <label for="mobile">شماره موبایل</label>
        <input type="text" id="mobile" name="mobile" value="{{ old('mobile') }}" required 
               maxlength="11" minlength="11"
               placeholder="مثال: 09123456789"
               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)">
    </div>
    <div class="form-group">
        <label for="meli_code">کد ملی</label>
        <input type="text" id="meli_code" name="meli_code" value="{{ old('meli_code') }}" required 
               maxlength="10" minlength="10"
               placeholder="مثال: 1234567890"
               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
    </div>
</div>
        <div class="datis-table-wrapper">
            <table class="datis-table">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>اسم محصول</th>
                        <th>قیمت</th>
                        <th>تعداد</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $key => $product)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td class="product-name">{{ $product->name }}</td>
                        <td class="product-price">
                            <div class="price-wrapper">
                                <span class="original-price">
                                    {{ number_format($product->price) }}
                                </span>
                                <span class="discounted-price">
                                    {{ number_format($product->price * ((100-$product->off_percent)/100)) }}
                                    <small>ریال</small>
                                </span>
                            </div>
                        </td>
                        <td>
                            <input type="hidden" name="products[{{ $product->id }}][product_id]" value="{{ $product->id }}">

                            <div class="quantity-control">
                                <button type="button" class="quantity-btn minus-btn">−</button>
                                <input
                                    type="number"
                                    name="products[{{ $product->id }}][count]"
                                    class="count-input product-count"
                                    value="0"
                                    min="0"
                                    readonly
                                    data-price="{{ $product->price }}"
                                    data-off="{{ $product->off_percent }}">
                                <button type="button" class="quantity-btn plus-btn">+</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 40px 0; color: #94a3b8; font-weight: 500;">
                            🚫 محصولی موجود نیست.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="purchase-summary">
                <div class="summary-item">
                    <span class="label">📦 تعداد کل</span>
                    <span class="value blue" id="totalItems">0</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-item">
                    <span class="label">🛍️ محصولات انتخاب شده</span>
                    <span class="value" id="selectedProducts">0</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-item">
                    <span class="label">💰 مبلغ کل</span>
                    <span class="value red">
                        <span id="totalPrice">0</span>
                        <small>ریال</small>
                    </span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-item">
                    <span class="label">📅 مبلغ هر قسط</span>
                    <span class="value green">
                        <span id="installmentPrice">0</span>
                        <small>ریال</small>
                    </span>
                </div>
            </div>
        </div>

        @if($products->count() > 0)
        <div class="datis-buy-area">
            <button type="submit" class="datis-buy-all-btn">
                <i class="fa-solid fa-cart-shopping"></i>
                ثبت خرید
            </button>
        </div>
        @endif

    </form>

    @else
    <!-- حالت خرید شده -->
    <div class="datis-table-wrapper">
        <table class="datis-table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>اسم محصول</th>
                    <th>قیمت</th>
                    <th>تعداد</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $key => $product)
                @php
                    $purchasedProduct = $purchasedProducts->firstWhere('product_id', $product->id);
                    $count = $purchasedProduct ? $purchasedProduct->num : 0;
                    $price = $product->price * ((100 - $product->off_percent) / 100);
                @endphp
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td class="product-name">{{ $product->name }}</td>
                    <td class="product-price">
                        <div class="price-wrapper">
                            <span class="discounted-price">
                                {{ number_format($price) }}
                                <small>ریال</small>
                            </span>
                            <span class="original-price">
                                {{ number_format($product->price) }}
                            </span>
                        </div>
                    </td>
                    <td><strong>{{ $count }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="purchased-summary">
        <div class="summary-item">
            <span class="label">📦 تعداد کل محصولات</span>
            <span class="value blue">{{ number_format($totalItems ?? 0) }}</span>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <span class="label">💰 جمع مبلغ کل</span>
            <span class="value red">{{ number_format($totalPrice ?? 0) }} <small style="font-size:14px;color:#64748b;">ریال</small></span>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <span class="label">📅 مبلغ هر قسط (۴ ماهه)</span>
            <span class="value green">{{ number_format(($totalPrice ?? 0) / 4) }} <small style="font-size:14px;color:#64748b;">ریال</small></span>
        </div>
    </div>

    <div class="datis-purchased-message">
        <i class="fa-solid fa-circle-check"></i>
        خرید شما با موفقیت ثبت شده است.
    </div>
    @endif

</div>

<!-- TOAST NOTIFICATION -->
<div id="loginToast" class="toast-notification">
    <i class="fa-solid fa-triangle-exclamation"></i>
    لطفاً تمام فیلدهای اطلاعات کاربری را پر کنید.
</div>

<!-- IMAGE MODAL -->
<div id="datisImageModal" class="datis-image-modal" onclick="closeDatisImage()">
    <span class="datis-image-close" onclick="closeDatisImage(event)">&times;</span>
    <img src="/img/datis/datis.jpg" alt="لیست قیمت محصولات" onclick="event.stopPropagation()">
</div>

@endsection

@section('scripts')

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('suc'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: '✅ خرید موفق',
            html: `{!! nl2br(e(session('suc'))) !!}`,
            confirmButtonText: 'باشه',
            confirmButtonColor: '#dc2626',
            timer: 6000,
            timerProgressBar: true
        });
    });
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {

    const inputs = document.querySelectorAll('.product-count');

    const totalItems = document.getElementById('totalItems');
    const selectedProducts = document.getElementById('selectedProducts');
    const totalPrice = document.getElementById('totalPrice');
    const installmentPrice = document.getElementById('installmentPrice');

    function updateSummary() {
        let totalCount = 0;
        let totalAmount = 0;
        let selectedCount = 0;

        inputs.forEach(function(input) {
            let count = parseInt(input.value) || 0;
            let price = parseFloat(input.dataset.price) || 0;
            let off = parseFloat(input.dataset.off) || 0;
            let discountedPrice = price - (price * off / 100);

            if (count > 0) {
                selectedCount++;
            }
            totalCount += count;
            totalAmount += discountedPrice * count;
        });

        let installment = totalAmount / 4;

        totalItems.innerText = totalCount.toLocaleString('fa-IR');
        selectedProducts.innerText = selectedCount.toLocaleString('fa-IR');
        totalPrice.innerText = Math.round(totalAmount).toLocaleString('fa-IR');
        installmentPrice.innerText = Math.round(installment).toLocaleString('fa-IR');
    }

    inputs.forEach(function(input) {
        input.addEventListener('input', updateSummary);
        input.addEventListener('change', updateSummary);
    });

    updateSummary();

    // دکمه‌های + و -
    document.querySelectorAll('.quantity-control').forEach(function(control) {
        const minusBtn = control.querySelector('.minus-btn');
        const plusBtn = control.querySelector('.plus-btn');
        const input = control.querySelector('.count-input');

        plusBtn.addEventListener('click', function() {
            let value = parseInt(input.value) || 0;
            input.value = value + 1;
            input.dispatchEvent(new Event('input', { bubbles: true }));
        });

        minusBtn.addEventListener('click', function() {
            let value = parseInt(input.value) || 0;
            if (value > 0) {
                input.value = value - 1;
                input.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });
    });

    // جلوگیری از Enter
    const buyForm = document.getElementById('datis-buy-form');
    if (buyForm) {
        buyForm.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

        // تأیید قبل از ثبت خرید
        buyForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // بررسی اطلاعات کاربر
            const name = document.getElementById('name').value.trim();
            const family = document.getElementById('family').value.trim();
            const mobile = document.getElementById('mobile').value.trim();
            const meli_code = document.getElementById('meli_code').value.trim();

            if (!name || !family || !mobile || !meli_code) {
                const toast = document.getElementById('loginToast');
                toast.classList.add('show');
                setTimeout(function() {
                    toast.classList.remove('show');
                }, 3000);
                return;
            }

            // بررسی انتخاب محصول
            let hasSelection = false;
            inputs.forEach(function(input) {
                if (parseInt(input.value) > 0) {
                    hasSelection = true;
                }
            });

            if (!hasSelection) {
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️ هیچ محصولی انتخاب نشده',
                    text: 'لطفاً حداقل یک محصول را انتخاب کنید.',
                    confirmButtonText: 'باشه',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }

            // نمایش پیام تأیید
            Swal.fire({
                title: 'آیا مطمئن هستید؟',
                text: 'پس از ثبت درخواست، امکان ویرایش وجود ندارد، در صورت نهایی بودن انتخاب های خود، تایید کنید.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: '✅ بله، ثبت کن',
                cancelButtonText: '❌ نه، منصرف شدم'
            }).then((result) => {
                if (result.isConfirmed) {
                    buyForm.submit();
                }
            });
        });
    }

});

// Modal functions
function openDatisImage() {
    document.getElementById('datisImageModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeDatisImage(event) {
    if (event) {
        event.stopPropagation();
    }
    document.getElementById('datisImageModal').classList.remove('show');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDatisImage();
    }
});
</script>

@endsection