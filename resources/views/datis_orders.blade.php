@extends('layout.master')

@section('title')
خرید های داتیس
@endsection

@section('style')
<style>
    body {
        direction: rtl;
        background: #f7f9fa;
        font-family: "IRANSans", sans-serif;
        /* مطمئن شوید فونت IRANSans در پروژه شما موجود است */
    }

    .report-card {
        
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        border: none;
    }

    .report-title {
        font-weight: 700;
    }

    .form-label {
        font-weight: 600;
    }

    .btn_red {
        background-color: #ff2130 !important;
        color: #fff;
        border-radius: 10px;
        padding: 10px;
        transition: 0.3s;
    }

    .btn_red:hover {
        background-color: #9f0415 !important;
        color: #fff;
    }

    /* --- استایل‌های اضافه شده برای جدول --- */
    .table-container {
        margin-top: 20px;
        overflow-x: auto;
        /* برای نمایش بهتر در موبایل */
    }

    .styled-table {
        width: 100%;
        border-collapse: collapse;
        /* حذف فواصل بین سلول‌ها */
        margin: 25px 0;
        font-size: 0.9em;
        font-family: "IRANSans", sans-serif;
        min-width: 400px;
        /* حداقل عرض برای جلوگیری از فشرده شدن بیش از حد */
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
        /* گوشه‌های گرد برای کل جدول */
        overflow: hidden;
        /* برای اعمال گوشه‌های گرد روی محتوا */
    }

    .styled-table thead tr {
        background-color: #009879;
        /* رنگ هدر */
        color: #ffffff;
        text-align: center;
        /* وسط چین کردن متن سرستون‌ها */
        font-weight: bold;
    }

    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
        /* فاصله داخلی سلول‌ها */
        text-align: center;
        /* وسط چین کردن محتوای سلول‌ها */
    }

    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
        /* خط جداکننده بین ردیف‌ها */
    }

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
        /* رنگ پس‌زمینه ردیف‌های زوج */
    }

    .styled-table tbody tr:nth-of-type(odd) {
        background-color: #ffffff;
        /* رنگ پس‌زمینه ردیف‌های فرد */
    }

    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #009879;
        /* خط ضخیم‌تر برای آخرین ردیف */
    }

    .styled-table tbody tr:hover {
        background-color: #e0f7fa;
        /* تغییر رنگ در حالت هاور */
        color: #009879;
        /* تغییر رنگ متن در حالت هاور */
      
        /* نشانگر موس به صورت دست */
    }

    /* استایل برای ردیف‌های هایلایت شده (مثلا اگر بخواهید ماه خاصی را هایلایت کنید) */
    .highlighted-row {
        background-color: #ffecb3 !important;
        /* رنگ زرد ملایم */
        font-weight: bold;
    }
    .gardesh{
        text-align: center;margin-top: 135px;margin-bottom: 20px;
    }
    #backButton {
            left: 5px;
            z-index: 1000;
            position: fixed;
            top: 100px;
            border-radius: 50%;
            border: 2px solid;
            border-color: #f43f5e;
            width: 50px;
            /* یا هر سایز دلخواه */
            height: 50px;
            /* باید با عرض یکی باشد */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            /* حذف padding اضافه */
        }

        /* برای اطمینان از اینکه آیکون در مرکز قرار می‌گیرد */
        #backButton span {
            color: #f43f5e;
            font-size: 40px;
            /* سایز آیکون */
        }
    </style>
<link href="{{ asset('dashboard/persian-datepicker.min.css') }}" rel="stylesheet">
@endsection


@section('content')

<div class="container">
    <div class="row justify-content-center">

        <!-- <a href="/" id="backButton" class="btn btn-outline btn-lg">
            <span>←</span>
        </a> -->

        <div class="col-lg-10 col-md-11 mt-4 ">

            <div class="card report-card p-4 ">

                <h4 class="report-title text-center mb-4">
                    خرید های داتیس
                </h4>

                <div class="table-container">

                    <table class="styled-table">

                        <thead>
                            <tr>
                                <th>ردیف</th>
                                <th>شماره سفارش</th>
                                <th>تاریخ خرید</th>
                                <th>محصولات خریداری شده</th>
                                <th>مبلغ خرید</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($orders as $key => $order)

                                <tr>

                                    {{-- ردیف --}}
                                    <td>
                                        {{ $key + 1 }}
                                    </td>

                                    {{-- شماره سفارش --}}
                                    <td>
                                        {{ $order->id }}
                                    </td>

                                    {{-- تاریخ خرید --}}
                                    <td>
                                        {{ verta($order->created_at)->format('Y/m/d') }}
                                    </td>

                                    {{-- محصولات --}}
                                    <td style="text-align: right;">

                                        @foreach ($order->productOrders as $productOrder)

                                            @if ($productOrder->product && $productOrder->num > 0)

                                                <div style="margin-bottom: 8px;">
                                                    ☐
                                                    {{ $productOrder->product->name }}
                                                    <strong>
                                                        ({{ $productOrder->num }} عدد)
                                                    </strong>
                                                </div>

                                            @endif

                                        @endforeach

                                    </td>

                                    {{-- مبلغ --}}
                                    <td>
                                        {{ number_format($order->final_price ?? $order->price ?? 0) }}
                                        تومان
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5">
                                        هنوز خریدی از داتیس ثبت نکرده‌اید.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>
</div>

@endsection


@section('scripts')
 
<script src="{{ asset('dashboard/jquery.js') }}"></script>
<script src="{{ asset('dashboard/persian-date.min.js') }}"></script>
<script src="{{ asset('dashboard/persian-datepicker.min.js') }}"></script>

<script>
    $(function() {
        // اگر قالب تاریخ در controller آماده شود، بهتر است
        // اما اگر از این کتابخانه استفاده می‌کنید، این کد درست است
        $("#from_date").pDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: false
        });

        $("#to_date").pDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: false
        });
    });
</script>
@endsection