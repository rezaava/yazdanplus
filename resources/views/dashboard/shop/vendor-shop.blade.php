@extends('layout.master')

@section('title')
| {{ $shop->name }}
@endsection

@section('content')
<style>
    /* ====== Vendor Shop Page ====== */

    .page-content-vendor-shop {
        background: #f4f6f9;
        padding-bottom: 50px;
    }

    /* کاور فروشگاه */
    .page-content-vendor-shop img[alt^="تصویر کاور"] {
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
        transition: 0.3s ease;
    }

    .page-content-vendor-shop img[alt^="تصویر کاور"]:hover {
        transform: scale(1.02);
    }

    /* کارت اطلاعات فروشگاه */
    .vendor-profile-dtails-wraper {
        background: #fff;
        padding: 20px;
        border-radius: 18px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        transition: 0.3s ease;
    }

    .vendor-profile-dtails-wraper:hover {
        transform: translateY(-4px);
    }

    /* آیکن فروشگاه */
    .vendor-profile-dtails-wraper img {
        width: 90%;
        object-fit: cover;
        border-radius: 50% !important;
        border: 3px solid #2d9f04;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* نام فروشگاه */
    .vendor-name-info-dtails-wraper h4 {
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 8px;
    }

    /* متن اطلاعات */
    .payment-shop-info-text {
        font-size: 14px;
        color: #555;
    }

    /* کارت توضیحات */
    .card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        margin-top: 25px;
    }

    .card-body {
        padding: 25px;
    }

    /* عنوان گالری */
    .about-content-wrap h6 {
        font-weight: 700;
        margin-bottom: 15px;
    }

    /* دکمه‌ها */
    .btn-theme {

        border: none;
        border-radius: 12px;
        padding: 12px;
        font-weight: 600;
        transition: 0.25s;
    }

    .btn-theme:hover {

        transform: translateY(-2px);
    }

    /* دکمه نمایش منو */
    .contact-btn-wrap .btn-info {
        background: #fff;
        color: #dc3545;
        border: 2px solid #dc3545;
        border-radius: 50px;
        padding: 10px 0;
        font-weight: 600;
        transition: 0.25s;
    }

    .contact-btn-wrap .btn-info:hover {
        background: #dc3545;
        color: #fff;
    }

    /* مودال پیشرفته‌تر */
    .modal-white-red .modal-content {
        border-radius: 20px !important;
        overflow: hidden;
    }

    .modal-image {
        border-radius: 16px;
    }

    /* انیمیشن نرم مودال */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
        transform: translateY(20px);
    }

    .modal.show .modal-dialog {
        transform: translateY(0);
    }

    /* ریسپانسیو */
    @media (max-width: 768px) {
        .vendor-profile-dtails-wraper {
            flex-direction: column;
            text-align: center;
            align-items: center !important;
        }

        .icon {
            width: 25% !important;
        }

        .vendor-name-info-dtails-wraper {
            align-items: center !important;
            text-align: center;
            margin-top: 15px;
        }

        .contact-btn-wrap .btn-info {
            width: 100% !important;
        }
    }

    /*  SALE */
    .discount-badge {
        /* position: absolute; */
        top: -15px;
        right: 20px;
        background: linear-gradient(135deg, #ff0000, #e092cf);
        color: white;
        padding: 8px 18px;
        border-radius: 50px;
        font-weight: 800;
        z-index: 20;
        box-shadow: 0 8px 20px rgba(255, 8, 68, 0.4);
        display: flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(5px);
        border: 2px solid rgba(255, 255, 255, 0.8);
        letter-spacing: 1px;
        animation: zoomInOut 1.2s ease-in-out infinite;
        width: max-content;
    }

    /* انیمیشن زوم این و زوم اوت */
    @keyframes zoomInOut {
        0% {
            transform: scale(0.9);
            opacity: 0.7;
        }

        40% {
            transform: scale(1.03);
            opacity: 1;
        }

        100% {
            transform: scale(0.9);
            opacity: 0.7;
        }
    }

    .discount-badge {
        padding: 6px 14px;
        font-size: 16px;
        top: -12px;
        right: 15px;
    }

    .discount-icon {
        font-size: 16px;
        display: inline-block;
        animation: rotate 2s linear infinite;
    }
</style>

<!-- Modal -->
<div class="modal fade modal-white-red" id="photoTextModal" tabindex="-1" role="dialog"
    aria-labelledby="photoTextModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="photoTextModalLabel">منو</h5>
                <button type="button" class="close ml-0" data-dismiss="modal" aria-label="بستن">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-0">
                <div class="modal-image-container">
                    @if(!empty($menu) && $menu->image)
                    <img class="modal-image" src="{{ asset($menu->image) }}" alt="تصویر منو">
                    @endif
                </div>
                <div class="modal-text">
                    <h4 class="text-dark mb-3">متن</h4>
                    @if(!empty($menu) && $menu->text)
                    <p>{!! $menu->text !!}</p>
                    @endif
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-red btn-lg" data-dismiss="modal">تایید</button>
            </div>

        </div>
    </div>
</div>

<div class="page-content-vendor-shop" style="min-height: 100px;">
    <!-- Vendor Details Wrap -->
    <div class="container">
        <div class="row" style="align-items: center; flex-direction: row-reverse;">
            <div class="col-md-8">
                <!-- shop image -->
                <br><br>
                @if(!empty($cover) && $cover->address)
                <img src="{{ asset($cover->address) }}" alt="تصویر کاور فروشگاه {{ $shop->name }}"
                    style="box-shadow: 0 0 6px #aaa; border-radius: 6px; margin: auto; aspect-ratio: 16/9; width: 100%;">
                @endif
            </div>
            <div class="col-md-4">
                <div class="w-100 vendor-profile-dtails-wraper">
                    <!-- Vendor Profile-->
                    <div class="row">
                        <div class="col-md-4">
                            @if(!empty($icon) && $icon->address)
                            <img style="border-radius: 50px;width: 100%;" class="icon" src="{{ asset($icon->address) }}" alt="{{ $shop->name }}">
                            @endif
                        </div>

                        <div class="col-md-8">
                            <div class="" style="position: relative;margin-top: 20px;">
                                <h4 style="color: #333; position: relative;">{{ $shop->name }}</h4>
                            </div>

                            <div class="d-flex align-items-center mt-2 payment-svg-size">
                                <svg xmlns="http://www.w3.org/2000/svg" style="min-width: 16px;" width="16" height="16"
                                    fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                                    <path
                                        d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
                                </svg>
                                <p class="m-1 payment-shop-info-text">
                                    {{ $shop->telephone ?? '...' }}
                                </p>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <!-- <div class="d-flex vendor-name-info-dtails-wraper" style="flex-direction: column;"> -->

                        <div class="d-flex align-items-center payment-svg-size">
                            <svg xmlns="http://www.w3.org/2000/svg" style="min-width: 16px;" width="16" height="16"
                                fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                <path
                                    d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z" />
                                <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                            </svg>
                            <p class="m-1 payment-shop-info-text">{{ $shop->address }}</p>
                        </div>
                        <div class="d-flex align-items-center payment-svg-size">
                            <p class="m-1 payment-shop-info-text">{!! $shop->decription !!}</p>
                        </div>
                        <div class="" style="position: relative; flex-direction: column; margin-top: 12px; align-items: flex-start;">
                            <h4 style="color: #333; position: relative;">
                                شرایط
                                خرید
                            </h4>
                        </div>
                        <div class="d-flex align-items-center payment-svg-size">
                            <div class="row">
                                @foreach ($conditions as $condition)
                                <div class="col-md-9">
                                    <p class="m-1 payment-shop-info-text">
                                      
                                        {{ $condition->month }}
                                        ماهه
                                        @if ($condition->percent > 0)
                                        {{ $condition->percent }}
                                        درصد کارمزد
                                        @endif
                                        @if ($condition->advance_payment > 0)
                                        {{ $condition->advance_payment }}
                                        درصد پیش پرداخت
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    @if ($shop->off_title)             
                                    <div class="discount-badge">   
                                        {{ $shop->off_title }}
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content pt-3" id="vendorTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="container" style="width: 70%;">
                    <!-- @if (!empty($menu) && ($menu->image || $menu->text))
                    <div class="text-center contact-btn-wrap">
                        <button class="btn btn-info d-block" style="width:60%; margin: 0 auto;" data-toggle="modal"
                            data-target="#photoTextModal">
                            نمایش منو
                        </button>
                    </div>
                    @endif -->
                    @if ($shop->sale_type == 1)
                        
                    <div class="card">
                        <div class="card-body about-content-wrap dir-rtl">
                            <h6>به {{ $shop->name }} خوش آمدید.</h6>
                            <p>{{ $shop->description ?? '' }}</p>
                            <div class="contact-btn-wrap text-center">
                                <a class="btn btn-theme w-100" href="/buy/{{ $shop->slug_code }}">
                                    <i class="fa-solid fa-shopping-cart me-2"></i> درخواست خرید
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection