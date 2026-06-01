@extends('layout.master')

@section('title')
گزارش تراکنش‌ها
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
        cursor: pointer;
        /* نشانگر موس به صورت دست */
    }

    /* استایل برای ردیف‌های هایلایت شده (مثلا اگر بخواهید ماه خاصی را هایلایت کنید) */
    .highlighted-row {
        background-color: #ffecb3 !important;
        /* رنگ زرد ملایم */
        font-weight: bold;
    }

    .gardesh {
        text-align: center;
        margin-top: 135px;
        margin-bottom: 20px;
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

@endsection


@section('content')


<div class="container">
    <div class="row justify-content-center">
        <a href="/reportform" id="backButton" class="btn btn-outline btn-lg ">
            <span>
                ←
            </span>
        </a>
        <div class="col-lg-6 col-md-8">
            <h3 class="gardesh">گردش حساب</h3>
            <div class="card report-card p-4">



                {{-- شروع بخش جدول --}}
                <div class="col-md-12 table-container">
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>ردیف</th>
                                <th>فروشگاه</th>
                                <th>بستانکار</th>
                                <th>تاریخ</th>
                               
                                
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $r => $transaction)
                            <tr>

                                <td>{{ $r + 1 }}</td>
                                <td>{{ $transaction->shop->name }}</td>

                                <td>{{ number_format($transaction->value) }}</td>
                                <td>
                                 {{ $transaction->tarikh }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3">هیچ قسطی برای نمایش وجود ندارد.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- پایان بخش جدول --}}


            </div>

        </div>
    </div>
</div>

@endsection


@section('scripts')
<script src="{{ asset('dashboard/jquery.js') }}"></script>
@endsection