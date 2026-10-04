@extends('layout/masterc')
@section('title')
فاکتور
@endsection
@section('style')
<style>
    .page-content-wrapper {
        background: #f4f6f9;
        padding: 40px 0;
    }

    /* کارت اطلاعات فروشگاه */
    .payment-shop-info-on-screen {
        background: #ffffff;
        padding: 25px;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        margin-bottom: 20px;
        transition: 0.3s;
    }

    .payment-shop-info-on-screen:hover {
        transform: translateY(-5px);
    }

    /* تصویر فروشگاه */
    .payment-shop-image {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #2d9f04;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    /* نام فروشگاه */
    .payment-name-discount-align h2 {
        font-weight: 700;
        font-size: 22px;
        margin-top: 15px;
    }

    /* اطلاعات تماس */
    .payment-shop-info-text {
        font-size: 14px;
        color: #555;
    }



    .payment-bill-info-box {
        background: #ffffff;
        padding: 35px 30px;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.07);
    }

    /* عنوان فاکتور */
    .payment-bill-title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 25px;
        text-align: center;
        color: #333;
        border-bottom: 2px dashed #ddd;
        padding-bottom: 10px;
    }

    /* هر ردیف مبلغ */
    .payment-bill-elements {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 15px;
    }

    .payment-bill-elements:last-child {
        border-bottom: none;
    }

    /* قیمت‌ها */
    .price {
        font-weight: 600;
        color: #222;
    }

    /* درصد کارمزد */
    .payment-discount-calculating {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* جداکننده */
    hr {
        opacity: 0.2;
    }

    /* مبلغ نهایی برجسته */
    .payment-final-price {
        font-size: 18px;
        font-weight: 700;
        color: #2d9f04;
    }

    /* =========================
   دکمه پرداخت
========================= */

    .payment-submit-btn {
        width: 100%;
        margin-top: 20px;
        padding: 14px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 15px;
        background: linear-gradient(45deg, #2d9f04, #1b7c02);
        border: none;
        box-shadow: 0 10px 25px rgba(45, 159, 4, 0.3);
        transition: 0.25s;
    }

    .payment-submit-btn:hover {
        transform: translateY(-3px);
        background: linear-gradient(45deg, #1b7c02, #145a01);
    }

    /* دکمه غیر فعال */
    .payment-submit-btn:disabled {
        background: #cccccc !important;
        box-shadow: none;
        cursor: not-allowed;
    }

    /* =========================
   فایل آپلود
========================= */

    input[type="file"] {
        border-radius: 12px;
        padding: 10px;
        border: 1px solid #ddd;
    }

    /* =========================
   Collapse details
========================= */

    #collapse-more-info-content {
        overflow: hidden;
        transition: max-height 0.4s ease;
    }

    /* =========================
   لینک‌ها
========================= */

    a {
        text-decoration: none;
        font-size: 14px;
        transition: 0.2s;
    }

    a:hover {
        color: #2d9f04 !important;
    }

    /* =========================
   Mobile Responsive
========================= */

    @media (max-width: 768px) {

        .payment-bill-info-box {
            padding: 25px 20px;
        }

        .payment-bill-elements {
            font-size: 14px;
        }

        .payment-shop-info-on-screen {
            text-align: center;
        }

        .payment-name-discount-align h2 {
            font-size: 18px;
        }

        .payment-shop-image {
            height: 90px;
        }
    }
</style>
@endsection
@section('content')

<div class="page-content-wrapper">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-5">
                <!-- ------------shop information------------ -->
                <div class="payment-shop-info-on-screen">
                    <!-- ------------shop image------------ -->
                    <div class="d-flex payment-set-center-on-large">
                        <img src='{{ asset("$icon->address") }}' class="payment-shop-image" alt="shop-image">
                    </div>
                    <div>
                        <div class="d-flex payment-set-center-on-large">
                            <div class="d-flex payment-name-discount-align">
                                <!-- ------------shop name------------ -->
                                <a href="#" class="mt-3">
                                    <h2>{{ $shop->name }}</h2>
                                </a>
                                <!--<div class="discount-box" style="width: fit-content;height: fit-content;">-->
                                <!-- ------------shop discount percent------------ -->
                                <!-- </div>-->
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-2 payment-svg-size">
                            <svg xmlns="http://www.w3.org/2000/svg" style="min-width: 16px;" width="16"
                                height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                <path
                                    d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z" />
                                <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                            </svg>
                            <!-- ------------shop location------------ -->
                            <p class="m-1 payment-shop-info-text">{{ $shop->address }}</p>
                        </div>
                        <div class="d-flex align-items-center payment-svg-size">
                            <svg xmlns="http://www.w3.org/2000/svg" style="min-width: 16px;" width="16"
                                height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                                <path
                                    d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
                            </svg>
                            <!-- ------------shop number------------ -->
                            <p class="m-1 payment-shop-info-text">{{ $shop->mobile }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="payment-bill-info-box">
                    <p class="payment-bill-title">
                        صورت حساب
                        ({{ $order->id }}#)
                    </p>
                    <p class="payment-bill-title">
                        اعتبار: {{ Auth::user()->wallet }}
                    </p>
                    @if ($errors->any())
                    @foreach ($errors->all() as $error)
                    <p class="text-center rial">{!! $error !!}</p>
                    @endforeach
                    @endif

                    <div class="payment-bill-elements mt-5">
                        <p>
                            مبلغ کل :
                        </p>
                        <script>
                            function separate(Number) {
                                Number += '';
                                Number = Number.replace(',', '');
                                x = Number.split('.');
                                y = x[0];
                                z = x.length > 1 ? '.' + x[1] : '';
                                var rgx = /(\d+)(\d{3})/;
                                while (rgx.test(y))
                                    y = y.replace(rgx, '$1' + ',' + '$2');
                                return y + z;
                            }
                        </script>
                        <p>
                            <span class="price">{{ $order->final_price}}</span> {{ $MONEY_SIGN }}
                        </p>
                    </div>
                    <div class="payment-bill-elements">
                        <p>
                            کارمزد :
                        </p>
                        <div class="payment-discount-calculating">
                            <p class="ms-3">
                                <span class="price">{{ $fee }}</span> درصد
                            </p>
                        </div>
                    </div>

                    @if ($order->canPay())
                    <hr style="background-color: black;width:100%;margin-top: 0;margin-bottom: 1rem;">

                    @if ($condition->advance_payment != 0)
                    <div class="payment-bill-elements">
                        <p>
                            درصد پیش پرداخت :
                        </p>
                        <div class="payment-discount-calculating">
                            <p class="ms-3">
                                <span class="price">{{ $pishpardakht ?? '' }}%</span>

                            </p>
                        </div>
                    </div>
                    @endif
                    <!-- <div class="payment-bill-elements">
    <p>
        مجموع اقساط :
    </p>
    <div class="payment-discount-calculating">
        <p class="ms-3">
            <span class="price">{{ $baghimande }}</span> {{ $MONEY_SIGN }}
        </p>
    </div>
</div> -->
                    <div class="payment-bill-elements">
                        <p>
                            تعداد اقساط :
                        </p>
                        <div class="payment-discount-calculating">
                            <p class="ms-3">
                                <span class="price">{{ $condition->month ?? '' }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="payment-bill-elements">
                        <p>
                            مبلغ هر قسط :
                        </p>
                        <div class="payment-discount-calculating">
                            <p class="ms-3">
                                <span class="price">{{ $payMonth }}</span> {{ $MONEY_SIGN }}
                            </p>
                        </div>
                    </div>
                    <hr style="background-color: black;width:100%;margin-top: 0;margin-bottom: 1rem;">
                    @else
                    <div class="payment-bill-elements">
                        <p class="payment-final-price-title">
                            مبلغ پرداخت شده :
                            <button onclick="collapse()" class="btn collapse-btn-for-more-info" type="button"
                                id="coll  apse-more-info-btn">
                                <p>جزئیات</p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                </svg>
                            </button>
                        </p>
                        <p class="payment-final-price">
                            <span class="price">{{ $order->getPayValue() }}</span> {{ $MONEY_SIGN }}
                        </p>
                    </div>
                    <div class="w-100" id="collapse-more-info-content">
                        <div class="payment-bill-elements">
                            <p>
                                پرداختی از کیف پول :
                            </p>
                            <p>
                                <span class="price">{{ $payFromWallet }}</span> {{ $MONEY_SIGN }}
                            </p>
                        </div>
                        <div class="payment-bill-elements">
                            <p>
                                پرداختی از درگاه :
                            </p>
                            <p>
                                <span class="price">{{ $payFromBank }}</span> {{ $MONEY_SIGN }}
                            </p>
                        </div>
                    </div>
                    <script>
                        let showCollapseContent = 0;

                        function collapse() {
                            if (showCollapseContent == 0) {
                                document.getElementById('collapse-more-info-content').style.maxHeight = '200px';
                                document.getElementById('collapse-more-info-content').style.transition = 'max-height .5s ease-out';
                                showCollapseContent = 1;
                            } else if (showCollapseContent == 1) {
                                document.getElementById('collapse-more-info-content').style.maxHeight = '0px';
                                document.getElementById('collapse-more-info-content').style.transition = 'max-height .4s ease-out';
                                showCollapseContent = 0;
                            }
                        }
                    </script>
                    @endif

                    <form class="w-100" action="/payment/{{ $order->id }}/{{ $condition->month }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if ($order->canPay())
                        @if (1||$condition->advance_payment != 0)
                        @if ($condition->advance_payment != 0)

                        <!-- <div class="col-md-4 mb-3">
        <label for="image" class="form-label">عکس فیش واریز پیش پرداخت :</label>
        <input type="file" class="form-control" id="image" name="image"
            required>
        @error('image')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div> -->
                        @endif
                        <button class="btn btn-theme payment-submit-btn price"
                            @if ($wallet < $payAll && $shop->more_sale ==0 ) disabled @endif type="submit"
                            data-animation="fadeInUp" data-delay="500ms" data-duration="1000ms">

                            @if ($order->getPayValue() == 0)
                            تکمیل پرداخت
                            @else
                            @if ($condition->advance_payment == 0)
                            @if ($wallet >= $payAll || $shop->more_sale ==1)
                            خرید نهایی

                            @else

                            اعتبار کافی برای خرید ندارید
                            برای افزایش اعتبار با شماره مدیریت تماس بگیرید
                            @endif
                            @else
                            خرید
                            @endif
                            @endif
                        </button>
                        @endif

                        @endif
                    </form>
                    <br>
                    <div class="container row" style="text-align: center">
                        <div class="col-6">
                            <a href="/buy/{{ $shop->slug_code }}" style="color: black;">
                                @if ($order->canPay())
                                بازگشت به مرحله قبل
                                @else
                                خرید مجدد از این فروشگاه
                                @endif
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="/" style="color: black">بازگشت به صفحه اصلی</a>

                        </div>
                    </div>


                </div>
                <script>
                    function separate(Number) {
                        Number += '';
                        Number = Number.replace(',', '');
                        x = Number.split('.');
                        y = x[0];
                        z = x.length > 1 ? '.' + x[1] : '';
                        var rgx = /(\d+)(\d{3})/;
                        while (rgx.test(y))
                            y = y.replace(rgx, '$1' + ',' + '$2');
                        return y + z;
                    }

                    const prices = document.getElementsByClassName("price");
                    for (let i = 0; i < prices.length; i++) {
                        var item = prices[i];
                        item.innerHTML = separate(item.innerHTML);
                    }
                </script>
            </div>
        </div>
    </div>
</div>
@if ($errors->has('price'))
<div class="alert alert-danger">
    {{ $errors->first('price') }}
</div>
@endif
@endsection