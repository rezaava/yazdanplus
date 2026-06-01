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
    }

    .report-card {
        margin-top: 140px;
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
</style>
@endsection


@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card report-card p-4">

                <h4 class="text-center mb-4 report-title">
                    ادیت عکس پروفایل
                </h4>

                <form action="/edit-pic" method="POST" class="row g-3" enctype="multipart/form-data">
                    @csrf

                <div class="col-md-12">
                <input type="file" name="pic">
                </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn_red w-100 mt-2">
                            ذخیره 
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

@endsection


@section('scripts')

@endsection
