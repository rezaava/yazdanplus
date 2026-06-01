@extends('admin.layout.master')

@section('onvan')
داشبورد
@endsection

@section('main')

@if (Auth::user()->hasRole('shop_admin'))

<div class="container p-4 rounded shadow-lg" style="background:#eef2f7;">

    {{-- دکمه‌ها --}}
    <div class="d-flex justify-content-start gap-2 mb-3">
        <a class="btn  px-4" href="/admin/shops/audit/{{ $shop->id }}" style="background-color: #FFD8D8;">
            حسابرسی
        </a>

        <a class="btn px-4" href="/admin/shops/report_g/{{ $shop->id }}" style="background-color: #ed3500;color: #eef2f7;">
            گزارش
        </a>

        <!-- <a class="btn px-4" href="/admin/list/sale/{{ $shop->id }}" style="background-color: #ff256d;">
            لیست فروش
        </a> -->

        {{-- اگر خواستی فعال شود --}}
        {{-- 
        <a class="btn btn-info px-4" href="/admin/shops/bill/{{ $shop->id }}">
            حساب کل
        </a>
        --}}
    </div>

    {{-- اطلاعات --}}
    <div class="row g-3">

        <div class="col-md-3">
            <label class="form-label fw-bold">نام</label>
            <div class="form-control bg-white">{{ $user->name }} {{ $user->family }}</div>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">نام فروشگاه</label>
            <div class="form-control bg-white">{{ $shop->name }}</div>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">تعداد خریدها</label>
            <div class="form-control bg-white">{{ $order_count }}</div>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">بستانکار</label>
            <div class="form-control bg-white">{{ number_format($bestankar) }}</div>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">تاریخ</label>
            <div class="form-control bg-white">{{ $date }}</div>
        </div>

    </div>
</div>

@endif

@endsection
