@extends('layout/master')
@section('title')
| سوابق خرید
@endsection
@section('style')
<style>
    /* ====== Order Page Styling ====== */

    .page-content-wrapper {
        background: #f4f6f9;

        padding-bottom: 40px;
    }

    /* کارت هر سفارش */
    .list-group-item {
        background: #ffffff;
        border-radius: 14px !important;
        margin-bottom: 0 !important;
        padding: 18px 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.25s ease;
    }

    .list-group-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    /* آیکن وضعیت */
    .noti-icon-warning,
    .noti-icon-success,
    .noti-icon,
    .noti-icon-cancel {
        width: 45px;

        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 15px;
        flex-shrink: 0;
    }

    .noti-icon-warning {
        background: linear-gradient(45deg, #ffb300, #ff8f00);
    }

    .noti-icon-success {
        background: linear-gradient(45deg, #00c853, #009624);
    }

    .noti-icon-cancel {
        background: linear-gradient(45deg, #e53935, #b71c1c);
    }

    .noti-icon {
        background: linear-gradient(45deg, #ff7043, #e64a19);
    }

    /* متن داخل کارت */
    .noti-info h6 {
        font-weight: 700;
        margin-bottom: 8px;
        font-size: 16px;
    }

    .noti-info span {
        display: block;
        margin-bottom: 4px;
        font-size: 14px;
        color: #555;
    }

    /* رنگ تیتر بر اساس وضعیت */
    .order-success {
        color: #00c853;
    }

    .order-danger {
        color: #e53935;
    }

    .order-warning,
    .order-Warning {
        color: #ff9800;
    }

    .order-cancel {
        color: #b71c1c;
    }

    /* قیمت‌ها */
    .price {
        font-weight: 600;
        color: #212529;
    }

    /* Pagination */
    .pagination {
        gap: 6px;
    }

    .pagination .page-item .page-link {
        border-radius: 10px !important;
        border: none;
        padding: 8px 14px;
        background: #ffffff;
        color: #333;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        transition: 0.2s;
    }

    .pagination .page-item.active .page-link {
        background: #2d9f04;
        color: #fff;
    }

    .pagination .page-link:hover {
        background: #2d9f04;
        color: #fff;
    }
    /* موبایل */
    @media (max-width: 576px) {
        .list-group-item {
            padding: 14px 12px;
        }

        .noti-info h6 {
            font-size: 14px;
        }

        .noti-info span {
            font-size: 13px;
        }

        .noti-icon-warning,
        .noti-icon-success,
        .noti-icon,
        .noti-icon-cancel {
            width: 38px;
            height: 38px;
        }
    }
    #backButton {
            left: 5px;
            z-index: 1000;
            position: fixed;
            top: 90px;
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

@section('order')
active
@endsection
@section('content')
<!-- Page Content Wrapper-->
<div class="page-content-wrapper">
    <br>
    <div class="container">

    <a href="/" id="backButton" class="btn btn-outline btn-lg ">
            <span>
                ←
            </span>
        </a>
        <!-- Section Heading-->
        <div class="section-heading d-flex align-items-center pt-3 justify-content-between rtl-flex-d-row-r">
        </div>
        <!-- Notifications Area-->
        <div class="notification-area pb-2">
            <div class="list-group">

                @foreach ($orders as $order)
                <!-- Single Notification-->
                <span class="list-group-item d-flex align-items-center border-0 mt-2"
                    href="#">
                    <span class="{{ $order->ShowClassTopIconStatus() }}">

                        {!! $order->CheckIconStatus() !!}
                    </span>
                    <div class="noti-info" style="width: 100%;">
                        @switch($order->status)
                        @case(1)
                        <h6 class="{{ $order->ShowClassTextStatus() }}">{{ $order->ShowTextStatus() }}
                            {{ $order['shop'] }}
                        </h6>
                        <span class="rial" style="color:#212529;font-size:16px;">مبلغ :{{ $order->final_price }}
                            {{ $MONEY_SIGN }}</span>
                        @break

                        @case(7)
                        <h6 class="order-warning">

                            در انتظار تایید پرداخت {{ $order['shop'] }}
                        </h6>
                        <span class="rial" style="color:#212529;font-size:15px;">
                            مبلغ :{{ number_format($order->final_price) }} {{ $MONEY_SIGN }}
                        </span>
                        @break
                        @case(2)
                        @case(8)
                        <h6 class="{{ $order->ShowClassTextStatus() }}">{{ $order->ShowTextStatus() }}
                            {{ $order['shop'] }} 
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="rial" style="color:#212529;font-size:15px;">مبلغ کل
                                    :{{ number_format($order->final_price) }}{{ $MONEY_SIGN }}</span>
                            </div>

                            @if ($order->off_id != null)
                            <span class="rial" style="color:#212529;font-size:15px;"> کد تخفیف
                                :{{ $order->offs->code }} (تاثیر : {{ $order->getOffValue() }})</span>
                            @endif
                            @break


                            @endswitch
                            <div class="col-md-6">
                                <span style="color:#212529;font-size:13px;">{{ $order['time'] }}</span>
                            </div>
                            @if ($order->status == 7)

                            <div class="mt-2 p-3 rounded shadow-sm" id="confirmation-{{$order->id}}"
                                style="background:#f8f9fa; border:1px solid #e0e0e0;">
                                {{-- فرم را با یک ID مشخص می‌کنیم تا راحت‌تر با جاوااسکریپت انتخاب شود --}}
                                <form id="verifyCodeForm-{{ $order->id }}" class="d-flex">
                                    @csrf {{-- اگر CSRF token نیاز است، حتماً باشد --}}
                                    <input type="text"
                                        class="form-control me-2"
                                        name="code"
                                        placeholder="کد تایید را وارد کنید"
                                        style="font-size:14px; border-radius:6px;">
                                    {{-- دکمه submit را به type button تغییر می‌دهیم --}}
                                    <button type="button"
                                        data-order-id="{{ $order->id }}" {{-- برای پاس دادن ID سفارش به جاوااسکریپت --}}
                                        class="btn btn-primary submit-verify-code"
                                        style="border-radius:6px;">
                                        ثبت
                                    </button>
                                </form>
                                {{-- محلی برای نمایش پیام موفقیت یا خطا --}}
                                <div id="verifyMessage-{{ $order->id }}" class="mt-2"></div>
                            </div>

                            @endif
                        </div>
                    </div>
                </span>
                <div class="row">
                    <div class="p-2 mb-2 rounded"
                    style="background:#eef5ff; border:1px solid #d0e2ff; font-size:14px;">
                    تعداد {{ $order->transaction_count_month }}
                    از {{ $order->transaction_count }} قسط 
                     مبلغ هر قسط: {{ number_format($order->mablagh) ?? 'نامشخص' }}
                    باقی مانده : {{ number_format($order->mande) }}
                </div>
                
            </div>
                @endforeach

                <!-- pagination -->
                <div class="container mt-4 ">
                    <ul class="pagination justify-content-center">
                        {!! $orders->links() !!}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
  $(document).ready(function() {
    // Event listener برای همه دکمه‌های "ثبت کد"
    $('.submit-verify-code').on('click', function(e) {
        e.preventDefault(); // جلوگیری از رفتار پیش‌فرض دکمه

        var button = $(this); // خود دکمه‌ای که کلیک شده
        var form = button.closest('form'); // فرم والد این دکمه
        var orderId = button.data('order-id'); // گرفتن ID سفارش از data attribute
        var codeInput = form.find('input[name="code"]'); // فیلد ورودی کد
        var verifyMessageDiv = $('#verifyMessage-' + orderId); // Div پیام‌ها

        var code = codeInput.val().trim(); // گرفتن مقدار کد و حذف فاصله‌های اضافه

        // اطمینان از اینکه فیلد کد خالی نباشه
        if (code === '') {
            verifyMessageDiv.html('<div class="alert alert-danger">لطفاً کد تایید را وارد کنید.</div>');
            return; // توقف اجرای تابع
        }

        // پاک کردن پیام‌های قبلی
        verifyMessageDiv.html('');
        button.prop('disabled', true).text('در حال بررسی...'); // غیرفعال کردن دکمه

        // ارسال درخواست AJAX
        $.ajax({
            url: '/taeed_code/' + orderId, // آدرس URL که در Blade تعریف شده بود
            type: 'POST', // متد POST
            data: {
                _token: form.find('input[name="_token"]').val(), // ارسال CSRF token
                code: code // ارسال کد تایید
            },
            success: function(response) {
                // اگر درخواست موفق بود
                if (response.success) {
                    // نمایش پیام موفقیت
                    verifyMessageDiv.html('<div class="alert alert-success">' + response.message + '</div>');
                    window.location.reload();


                } else {
                    // نمایش پیام خطا از سمت سرور
                    verifyMessageDiv.html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function(xhr) {
                // اگر خطای سرور رخ داد (مثلاً 500 Internal Server Error)
                let errorMessage = 'خطا در ارتباط با سرور. لطفاً دوباره تلاش کنید.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                verifyMessageDiv.html('<div class="alert alert-danger">' + errorMessage + '</div>');
            },
            complete: function() {
                // در هر صورت (موفقیت یا خطا)، دکمه را به حالت اول برگردان
                button.prop('disabled', false).text('ثبت');
            }
        });
    });
});
 
</script> 


@endsection