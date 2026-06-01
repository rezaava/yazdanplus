@extends('layout.master')
@section('title')
| کویناتو
@endsection
@section('coin')
active
@endsection
@section('style')
<style>
    /* ===============================
   Coin Transactions Page
================================ */

.page-content-wrapper {
    background: #f4f6f9;
    min-height: 50vh;
}

/* کارت هر تراکنش */
.list-group-item {
    background: #ffffff;
    border-radius: 18px !important;
    margin-bottom: 12px;
    padding: 18px 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    transition: 0.3s;
}

.list-group-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.08);
}

/* آیکن گرد */
.noti-icon-success {
    width: 45px;
    height: 45px;
    min-width: 45px;
    border-radius: 50%;
    background: linear-gradient(45deg,#2d9f04,#1b7c02);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 15px;
    box-shadow: 0 5px 15px rgba(45,159,4,0.3);
}

/* متن تراکنش */
.noti-info h6 {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 4px;
}

/* مقدار کوین */
.rial {
    font-weight: 700;
    font-size: 17px !important;
    color: #2d9f04 !important;
}

/* تاریخ */
.noti-info h6:last-child {
    font-size: 12px;
    color: #888;
}

/* اگر بعداً برداشت اضافه کردی */
.order-danger {
    color: #e53935 !important;
}

/* pagination حرفه‌ای */
.pagination {
    gap: 6px;
}

.pagination .page-item .page-link {
    border-radius: 10px !important;
    border: none;
    color: #555;
    box-shadow: 0 3px 8px rgba(0,0,0,0.05);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(45deg,#2d9f04,#1b7c02);
    border: none;
    color: white;
}

.pagination .page-item .page-link:hover {
    background: #e9f7e9;
}

/* موبایل */
@media (max-width:768px){
    .list-group-item {
        padding: 15px;
    }

    .rial {
        font-size: 15px !important;
    }

    .noti-icon-success {
        width: 38px;
        height: 38px;
    }
}

</style>    
@endsection
@section('content')
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
<!-- Page Content Wrapper-->
<div class="page-content-wrapper">
<br>

    <div class="container">
        <!-- Section Heading-->
        <div class="section-heading d-flex align-items-center pt-3 justify-content-between rtl-flex-d-row-r">
        </div>
        <!-- Notifications Area-->
        <div class="notification-area pb-2">
            <div class="list-group">
                @foreach ($transactions as $transaction)
                <!-- Single Notification--><a class="list-group-item d-flex align-items-center border-0"href="#">
                    <span class="noti-icon-success">
                        <i class="fa-solid fa-arrow-up" style="color: white;"></i>
                    </span>
                    <div class="noti-info">
                        @if ($transaction->type == '12')
                        <h6 class="mb-0 order-success" > کویناتو اولین خرید شما <span class="rial order-success "
                                style="color:#212529;font-size:16px;">{{ $transaction->value}}+ کویناتو</span>
                        </h6>
                        @elseif ($transaction->type == '7')
                        <h6 class="mb-0 order-success"> کویناتو خرید شما <span class="rial" style="color:#212529;font-size:16px;">{{
                                $transaction->value}}+ کویناتو</span>
                        </h6>
                        @elseif ($transaction->type == '8')
                        <h6 class="mb-0 order-success"> کویناتو خرید از فروشگاه <span class="rial"
                                style="color:#212529;font-size:16px;">{{ $transaction->value}}+ کویناتو</span></h6>
                        @elseif ($transaction->type == '9')
                        <h6 class="mb-0 order-success">کویناتو ورود به سیستم <span class="rial"
                                style="color:#212529;font-size:16px;">{{ $transaction->value}}+ کویناتو</span></h6>
                        @elseif ($transaction->type == '10')
                        <h6 class="mb-0 order-success"> کویناتو ثبت معرف <span class="rial" style="color:#212529;font-size:16px;">{{
                                $transaction->value}}+ کویناتو</span></h6>
                        @elseif ($transaction->type == '11')
                        <h6 class="mb-0 order-success"> کویناتو ثبت زیر مجموعه<span class="rial"
                                style="color:#212529;font-size:16px;">{{ $transaction->value}}+ کویناتو</span></h6>
                        @endif
                        <h6 class="mb-0">
                            {{$transaction["time"]}}
                        </h6>
                    </div>
                </a>
                <script>
                    elements = document.getElementsByClassName('rial');
                    for (index = 0; index < elements.length; index++) {
                        elements[index].innerHTML = separate(elements[index].innerHTML);
                    }
                </script>
                @endforeach


            <!-- pagination -->
            <div class="container mt-4 ">
               <ul class="pagination justify-content-center">
                 {!! $transactions->links() !!}
               </ul>
             </div>

            </div>
        </div>
    </div>
</div>
@endsection
