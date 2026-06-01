@extends('admin.layout.master')

@section('onvan')
لیست فروش
@endsection

@section('head')
<style>
    /* فونت و چینش */
    table {
        font-family: iran-md;
        text-align: center;
        border-collapse: collapse;
        width: 100%;
    }

    /* هدر جدول */
    thead.table-primary th {
        background-color: #ed3500;
        color: #fff;
        font-weight: 600;
        padding: 12px 8px;
        text-align: center !important;
        border: 2px solid #ed3500;
        font-size: 1.2rem;
    }

    /* ردیف‌ها */
    tbody tr {
        background-color: #f9f9f9;
        transition: background-color 0.3s;
    }

    tbody tr:nth-child(even) {
        background-color: #eef2f7;
    }

    tbody tr:hover {
        background-color: #d9e6ff;
    }

    tbody td {
        padding: 10px 8px;
        vertical-align: middle;
    }

    .page-link:first-child {
        margin-left: 0 !important;
    }

    .page-link {
        background-color: rgb(255, 255, 255) !important;
        color: #000000 !important;
        border: none !important;
        border-radius: 50% !important;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 8px;
        font-size: 14px;
    }

    /* نسخه کمرنگ برای غیرفعال‌ها */
    .paginate_button.disabled .page-link {
        background-color: #333 !important;
        color: #888 !important;
        opacity: 0.5 !important;
        cursor: not-allowed !important;
    }


    /* ستون وضعیت موفق/ناموفق */
    .status-success {
        color: #28a745;
        font-weight: 600;
    }

    .status-failed {
        color: #dc3545;
        font-weight: 600;
    }

    .status-pending {
        color: #ffc107;
        font-weight: 600;
    }

    /* ستون تسویه */
    .settled {
        color: #007bff;
        font-weight: 600;
    }

    .not-settled {
        color: #6c757d;
        font-weight: 600;
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
        background-color: #ed3500 !important;
        color: #fff;
        border-radius: 10px;
        padding: 10px;
        transition: 0.3s;
        margin-top: 27px;
    }

    .btn_red:hover {
        background-color: #ed3500 !important;
        color: #fff;
    }
</style>


<link href="{{ asset('dashboard/persian-datepicker.min.css') }}" rel="stylesheet">
@endsection
{{-- 
@section('back')
<a href="{{ url()->previous() }}" id="backButton" class="btn btn-outline btn-lg">
    <span>
        ←
    </span>
</a>
@endsection   --}}
@section('main')
@if (Auth::user()->hasRole('shop_admin') || Auth::user()->hasRole('admin'))
<div class="container p-2" style="box-shadow: 1px 1px 28px rgb(183, 188, 188);">
    <div class="row">
        @if (Auth::user()->hasRole('shop_admin') || Auth::user()->hasRole('shop_user'))
        <div class="col-md-6 mb-2">
            <input type="text" class="form-control" value="نام فروشگاه : {{ $shop_n->name }} " readonly>
        </div>
        @endif
        <div class="col-md-6 mb-2">
            <input type="text" class="form-control" value="تعداد خرید ها : {{ $orders_count }}" readonly>
        </div>
        <div class="col-md-6">
            <input type="text" class="form-control" value="کل : {{ number_format($all_price) }}" readonly>
        </div>
        <div class="col-md-6">
            <input type="text" class="form-control" value="مانده : {{ number_format($mande) }}" readonly>
        </div>
        <div class="col-md-6">
            <input type="text" class="form-control" value="تعداد خریدار : {{ number_format($users_count) }}" readonly>
        </div>
    </div>
</div>
@endif



<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="salesTable" class="table table-bordered table-striped table-hover ">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 5%;">شناسه</th>
                            <th>نام فروشگاه</th>
                            <th>خریدار</th>
                            <th>مبلغ
                                (ریال)
                            </th>

                            <!-- <th>کد تخفیف</th> -->
                            <!-- <th>رفته به درگاه</th> -->
                            <th>وضعیت</th>

                            <th>تاریخ</th>
                            <th>ساعت</th>
                        
                            <th>کد خرید</th>
                            


                            @if (Auth::user()->hasRole('admin') && Auth::user()->hasRole('shop_user'))
                            <th>جزئیات سفارش</th>
                            @endif
                            <th>کامنت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $price_user = 0;
                        $price_shop = 0;
                        @endphp
                        @foreach ($orders as $order)
                        <tr class="text-center">
                            <td style="text-align: center;">{{ $order->id }}</td>
                            <td>
                                <a
                                    href="/shop/{{ $order['shop_order_id'] }}" style="text-decoration: none;">{{ $order['shop_order_name'] }}</a>
                            </td>
                            <td dir="ltr">
                                <span style="direction: ltr">{{ $order['mobile'] }}</span>
                                <br>
                                {{ $order['user'] }}
                            </td>
                            <td class="text-center rial">{{ number_format($order->price) }}
                                {{-- ({{ number_format($order->final_price) }}) --}}
                            </td>

                            <!-- <td>
                    @if ($order->off_id)
                    {{ $order->getOffCode() }}
                    <br>
                    تاثیر:
                    <span class="rial">{{ $order->getOffValue() }}</span>
                    @endif
                </td> -->

                            <!-- <td>
                @if ($order->authority != null)
                <div style="color: green;font-size: 20px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                        <path
                        d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z">
                    </path>
                        </svg>
                    </div>
                    @else
                    <div>
                        <p style="color:red;font-size: 20px;">&times;</p>
                    </div>
                    @endif
                </td> -->
                            <td>
                                @if ($order->status == 2)
                                <p class="text-success">
                                    پرداخت موفق
                                </p>
                                @elseif ($order->status == 7)
                                <p class="text-warning">
                                درانتظار تایید  
                                </p>
                                    @endif
                            </td>

                            <td>{{ jdate($order->created_at)->format('Y/m/d') }}</td>
                            <td>{{ jdate($order->created_at)->format('H:i') }}</td>

                         
                            <td>{{ $order->sms_code }}</td>
                           
                            <!-- <td>
                    @if ($order->status == 8)
                    تایید شده
                    @elseif ($order->status == 7)
                    <a href="/admin/taeed-order/{{ $order->id }}/{{ $order->month }}"
                    class="btn btn-outline-info">تایید</a>
                    <a href="/admin/rad-order/{{ $order->id }}/{{ $order->month }}"
                    class="btn btn-outline-danger">رد </a>
                    @elseif ($order->status == 9)
                    رد شده
                    @else
                    پیش پرداخت ندارد
                    @endif
                    
                </td> -->
                            <!-- <td>
                    @if(!empty($order->image))
                    <a href="{{ asset($order->image) }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset($order->image) }}" alt="توضیح عکس" width="80">
                    </a>
                    @else
                    <span class="text-muted">----</span>
                    @endif
                </td> -->
                            @if (Auth::user()->hasRole('admin') && Auth::user()->hasRole('shop_user'))
                            <td><a href="/admin/order/details/{{ $order->id }}" class="btn" style="background-color: #ffc900;">نمایش
                                    جزئیات</a></td>
                            @endif
                            <td>
                                <form action="/admin/add_comment/{{ $order->id }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-8">

                                            <textarea name="comment" cols="10" rows="1" class="form-control">{{ $order->comment }}</textarea>
                                        </div>
                                        <div class="col-md-4">
                                            
                                            <button class="btn ml-1" style="background-color: #FFD8D8;color: black;" type="submit">ذخیره</button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="rial" style="text-align: right;">جمع کل پرداخت موفق: {{ number_format($all_price) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@if (Auth::user()->hasRole('shop_admin') || Auth::user()->hasRole('admin'))
<div class="container">
    <div class="row">

        <form action="{{ route('order.excel') }}" method="POST" class="row g-3">
            @csrf

            <div class="col-md-3">
                <label for="from_date" class="form-label">
                    از تاریخ
                </label>
                <input type="text"
                    id="from_date"
                    name="from_date"
                    class="form-control"
                    placeholder="انتخاب تاریخ شروع"
                    required
                    autocomplete="off">
            </div>

            <div class="col-md-3">
                <label for="to_date" class="form-label">
                    تا تاریخ
                </label>
                <input type="text"
                    id="to_date"
                    name="to_date"
                    class="form-control"
                    placeholder="انتخاب تاریخ پایان"
                    required
                    autocomplete="off">
            </div>

            <div class="col-md-3">
                <button type="submit"
                    class="btn btn_red">
                    دریافت گزارش
                </button>
            </div>

        </form>

    </div>
</div>
@endif

<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- دکمه بستن -->
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                <img id="modalImage"
                    src=""
                    class="img-fluid rounded"
                    style="cursor:pointer;width: 50%;"
                    data-bs-dismiss="modal">
            </div>

        </div>
    </div>
</div>
@endsection

@section('script')



<!-- jQuery -->
<script src="{{ asset('dashboard/bootstrap.bundle.min.js') }}"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('dashboard/jquery.js') }}"></script>

<!-- DataTables JS + Bootstrap integration -->
<script src="{{ asset('dashboard/datatables.min.js') }}"></script>
<!-- <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script> -->


<script>
    $(document).ready(function() {
        $('#salesTable').DataTable({
            "order": [[0, 'desc']],  // مرتب‌سازی بر اساس ستون اول (0) به صورت نزولی (desc)

            "pagingType": "full_numbers",
            "oLanguage": {
                "oPaginate": {

                    "sLast": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                    "sFirst": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>'

                },
                "sInfo": "نمایش صفحه _PAGE_  از _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "جستجو کنید...",
                "sLengthMenu": "نتایج :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 10
        });
    });
</script>
<script>
    $(function() {
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

<script>
    function showImage(src) {
        document.getElementById('modalImage').src = src;
    }
</script>

<script src="{{ asset('dashboard/persian-date.min.js') }}"></script>
<script src="{{ asset('dashboard/persian-datepicker.min.js') }}"></script>
@endsection