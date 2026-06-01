@extends('layout/masterc')
@section('title')
| پرداخت موفق
@endsection
@section('content')
<div class="align-payment-box">
    <div class="container">

        <div class="">
            <div class="payment-success-details">
                <div class="alert alert-success d-flex" style="justify-content: center; align-items: center; flex-direction: row;" role="alert">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor"
                        class="bi bi-check2-circle" viewBox="0 0 16 16">
                        <path
                            d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z" />
                        <path
                            d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z" />
                    </svg>
                    </div>
                    <div>پرداخت با موفقیت انجام شد</div>
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
                </script>
                <div class="payment-bill-info-box">
                    <p class="payment-bill-title">صورت حساب</p>
                    <div class="payment-bill-elements">
                        <p>
                            نام فروشگاه :
                        </p>
                        <a href="/shop/{{$shop_buy->slug_code}}"
                            style="color: black"><span>{{$shop_buy->name}}</span></a>
                    </div>
                    <div class="payment-bill-elements">
                        <p>
                            مبلغ تراکنش :
                        </p>
                        <p>
                            <span class="price">{{$price}}</span> {{$MONEY_SIGN}}
                        </p>
                    </div>
                    {{-- <div class="payment-bill-elements">
                        <p>
                            تخفیف آنتو :
                        </p>
                        <p>
                            <span id='price_2'>{{$profit}}</span> {{$MONEY_SIGN}}
                        </p>
                    </div> --}}
                    <div class="payment-bill-elements">
                        <p>
                            زمان خرید :
                        </p>
                        <p>
                            <span id='time'>{{$time}}</span>
                        </p>
                    </div>

                    {{-- <div class="payment-bill-elements">
                        <p>
                            تخفیف شما :
                        </p>
                        <div class="payment-discount-calculating">
                            <p class="payment-discount-percent"><span>{{$off}}</span>% -</p>
                            <p class="ms-3">
                                <span>{{$price}}</span> bONEY_SIGN}}
                            </p>
                        </div>
                    </div> --}}
                    <hr style="background-color: black;width:100%;margin-top: 1rem;margin-bottom: 1rem;">
                    <div class="payment-bill-elements">
                        <p>
                            سود شما از این خرید :
                        </p>
                        <p>
                            <button onclick="collapse()" class="btn collapse-btn-for-more-info"
                                type="button" id="coll  apse-more-info-btn">
                                <p>جزئیات</p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-chevron-down" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                </svg>
                            </button>
                            <span class="price">{{$profit}}</span> {{$MONEY_SIGN}}
                        </p>
                    </div>
                    <div class="w-100" id="collapse-more-info-content">
                        <div class="payment-bill-elements">
                            <p>
                                مبلغ تراکنش :
                            </p>
                            <p>
                                <span class="price">{{$price}}</span> {{$MONEY_SIGN}}
                            </p>
                        </div>
                        <div class="payment-bill-elements">
                            <p>
                                تخفیف آنتو :
                            </p>
                            <p>
                                <span class="price">{{$profit}}</span> {{$MONEY_SIGN}}
                            </p>
                        </div>
                        @if($order->off_id !=null)
                            <div class="payment-bill-elements">
                                <p>
                                    کد تخفیف :
                                    <span class="bg-success text-white rounded p-1">{{ $order->offs->code }}</span>
                                </p>
                                <p>
                                    <span class="price">{{$order->offs->getOffEffectForOrder($order) }}</span> {{$MONEY_SIGN}}
                                </p>
                            </div>
                        @endif
                        <div class="payment-bill-elements">
                            <p>
                                مبلغ کل پرداخت شده :
                            </p>
                            <p>
                                <span class="price">{{$price_off}}</span> {{$MONEY_SIGN}}
                            </p>
                        </div>
                        <div class="payment-bill-elements">
                            <p>
                                پرداختی از کیف پول :
                            </p>
                            <p>
                                <span class="price">{{$payFromWallet}}</span> {{$MONEY_SIGN}}
                            </p>
                        </div>
                        <div class="payment-bill-elements">
                            <p>
                                پرداختی از درگاه :
                            </p>
                            <p>
                                <span class="price">{{$payFromBank}}</span> {{$MONEY_SIGN}}
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

                    <p style="color: rgb(146, 0, 0);">این صفحه را فروشنده نشان دهید</p>
                    <a class="btn btn-danger" href="/">بازگشت به صفحه اصلی</a>
                </div>
            </div>
            <script>
                    const prices = document.getElementsByClassName("price");
                    for (let i = 0; i < prices.length; i++) {
                        var item = prices[i];
                        item.innerHTML = separate(item.innerHTML);
                    }
            </script>
        </div>
    </div>
</div>
@endsection
