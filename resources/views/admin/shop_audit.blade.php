@extends('admin.layout.master')

@section('onvan')
حسابرسی
{{ $shop->name }}
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

    .table-primary {
        border: 3px solid black;
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
</style>
<link rel="stylesheet" href="{{ asset('dashboard/persian-datepicker.min.css') }}" />
@endsection
@section('back')
<a href="{{ url()->previous() }}" id="backButton" class="btn btn-outline-secondary btn-lg">
    <span>
        ←
    </span>
</a>
@endsection
@section('main')
<div class="container">
    <div class="row">
        <form action="{{ route('audit.excel', $shop->id) }}" method="POST" class="row g-3 mb-4">
            @csrf

            <div class="col-md-3">
                <label class="form-label">از تاریخ</label>
                <input type="text"
                    id="from_date"
                    name="from_date"
                    class="form-control"
                    placeholder="انتخاب تاریخ شروع"
                    required
                    autocomplete="off">
            </div>

            <div class="col-md-3">
                <label class="form-label">تا تاریخ</label>
                <input type="text"
                    id="to_date"
                    name="to_date"
                    class="form-control"
                    placeholder="انتخاب تاریخ پایان"
                    required
                    autocomplete="off">
            </div>

            <div class="col-md-3 pt-2">
                <!-- <form action="/shops/show_audit/{{ $shop->id }}" method="get"> -->

                <button type="button" class="btn  mt-4 w-100" id="showDataBtn" style="background-color: #FFD8D8;">
                    نمایش
                </button>

                <!-- </form> -->
            </div>
            <div class="col-md-3 pt-2">
                <button type="submit" class="btn mt-4 w-100" style="background-color: #ed3500;color: #f9f9f9;">
                    دانلود اکسل
                </button>
            </div>
        </form>

    </div>
</div>

<div id="tableContainer" class="mt-4">
    @if (Session::has('error'))
    <div class="alert alert-warning">
        {{ Session::get('error') }}
    </div>
    @endif
    <!-- جدول داده‌ها اینجا نمایش داده میشه -->
</div>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card text-center" style="background-color: #e0f7fa;">
                <div class="card-body">
                    <h5 class="card-title">کل فروش</h5>
                    <p class="card-text display-6" id="kol">{{number_format($value)}}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center" style="background-color: #e0f7fa;">
                <div class="card-body">
                    <h5 class="card-title">کل فروش با احتساب تخفیف</h5>
                    <p class="card-text display-6" id="kol_off">{{number_format($value-$value_off)}}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center" style="background-color: #fff9c4;">
                <div class="card-body">
                    <h5 class="card-title">پرداخت شده

                    </h5>
                    <p class="card-text display-6" id="pardakht">{{number_format($dadim)}}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center" style="background-color: #ffcdd2;">
                <div class="card-body">
                    <h5 class="card-title">مانده حساب</h5>
                    <p class="card-text display-6" id="mande">{{number_format($value-$value_off-$dadim)}}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="salesTable" class="table table-bordered table-striped table-hover ">
                    <thead class="table-primary">
                        <tr>
                            <th>تاریخ</th>
                            <th>ساعت</th>

                            <th>قیمت/ریال</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $totalAzMaMikhahad = 0;
                        @endphp
                        @foreach ($transactions as $transaction)
                        <tr class="text-center">
                            <td>
                                <h4>{{ $transaction->tarikh }}</h4>
                            </td>
                            <td>
                                <h4>{{ $transaction->saat }}</h4>
                            </td>

                            <td class="text-center">
                                <h4>{{ number_format($transaction->price) }}
                                    {{-- ({{ number_format($transaction->final_price) }}) --}}
                                </h4>
                            </td>
                            @php
                            $totalAzMaMikhahad += $transaction->price;
                            @endphp
                        </tr>
                        @endforeach


                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="salesTable2" class="table table-bordered table-striped table-hover ">
                    <thead class="table-primary">
                        <tr>
                            <th>شناسه</th>
                            <th>تاریخ</th>
                            <th>ساعت</th>
                            <th>نوع</th>
                            <th>قیمت/ریال</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $totalMadadim = 0;
                        @endphp
                        @foreach ($maDadim as $item)
                        <tr class="text-center">
                            <td>
                                {{ $item->id }}
                            </td>
                            <td>
                                <h4>{{ $item->tarikh }}</h4>
                            </td>
                            <td>
                                <h4>{{ $item->saat }}</h4>
                            </td>
                            <td>
                                <h5>
                                    {{$item->description}}

                                </h5>
                            </td>
                            <td>
                                <h4 style="text-align: center;">{{ number_format($item->value) }}</h4>
                            </td>
                            @php
                            $totalMadadim += $item->value;
                            @endphp
                        </tr>
                        @endforeach
                    </tbody>
                    <h4> مجموع پرداخت شده ها = <span style="color:rgb(4, 0, 255)">{{ number_format($totalMadadim) }}</span> ریال</h4>
                </table>
                <hr>
                <!-- <h3>
        خلاصه عملکرد
    </h3>
    <h4>مبلغی که از ما میخواهد = <span style="color:rgb(4, 0, 255)">{{ number_format($totalAzMaMikhahad) }}</span>
        ریال</h4>

    <h4>ما دادیم = <span style="color:rgb(4, 0, 255)">{{ number_format($totalMadadim) }}</span> ریال</h4>

    <h4>مانده حساب = <span style="color:rgb(4, 0, 255)">{{ number_format($totalAzMaMikhahad-$totalMadadim) }}</span> ریال</h4> -->

            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="salesTable3" class="table table-bordered table-striped table-hover ">
                    <thead class="table-primary">
                        <tr>
                            <th>تاریخ</th>
                            <th>نوع</th>
                            <th>قیمت/ریال</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $totalMadadimOff =0;
                        @endphp
                        @foreach ($maDadim_off as $item)
                        <tr class="text-center">
                            <td>
                                <h4>{{ $item->tarikh }}</h4>
                            </td>
                            <td>
                                <h5>
                                    {{$item->description}}

                                </h5>
                            </td>
                            <td>
                                <h4 style="text-align: center;">{{ number_format($item->value) }}</h4>
                            </td>
                            @php
                            $totalMadadim += $item->value;
                            $totalMadadimOff += $item->value;
                            @endphp
                        </tr>
                        @endforeach
                    </tbody>
                    <h4> مجموع تخفیف = <span style="color:rgb(4, 0, 255)">{{ number_format($value_off) }}</span> ریال</h4>
                </table>
                <hr>
                <!-- <h3>
        خلاصه عملکرد
    </h3>
    <h4> کل فروش = <span style="color:rgb(4, 0, 255)" id="kol2">{{ number_format($totalAzMaMikhahad) }}</span>
        ریال</h4>

    <h4> پرداخت شده با تخفیف  = <span style="color:rgb(4, 0, 255)" id="pardakht2">{{ number_format($totalMadadim) }}</span> ریال</h4>

    <h4>مانده حساب = <span style="color:rgb(4, 0, 255)" id="mande2">{{ number_format($totalAzMaMikhahad-$totalMadadim) }}</span> ریال</h4> -->
                <script>
                    // document.getElementById('kol').innerHTML=document.getElementById("kol2").innerHTML;
                    // document.getElementById('kol_off').innerHTML=document.getElementById("kol2").innerHTML;
                    // document.getElementById('pardakht').innerHTML=document.getElementById("pardakht2").innerHTML;
                    // document.getElementById('mande').innerHTML=document.getElementById("mande2").innerHTML;
                </script>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')


<!-- jQuery -->
<script src="{{ asset('dashboard/jquery.js') }}"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('dashboard/bootstrap.bundle.min.js') }}"></script>


<!-- DataTables JS + Bootstrap integration -->
<script src="{{ asset('dashboard/datatables.min.js') }}"></script>
<!-- <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script> -->
<script>
    document.getElementById('showDataBtn').addEventListener('click', function() {
        let from_date = document.getElementById('from_date').value;
        let to_date = document.getElementById('to_date').value;
        let shop_id = "{{ $shop->id }}";

        if (to_date < from_date) {
            alert('لطفاً تاریخ‌ها را درست وارد کنید');
            return;
        }

        if (!from_date || !to_date) {
            alert('لطفاً تاریخ‌ها را کامل وارد کنید');
            return;
        }

        fetch(`/shops/${shop_id}/audit-preview`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    from_date,
                    to_date
                })
            })
            .then(res => res.text())
            .then(html => document.getElementById('tableContainer').innerHTML = html)
            .catch(err => console.error(err));
    });
</script>



<script>
    $(document).ready(function() {
        $('#salesTable').DataTable({
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
    $(document).ready(function() {
        $('#salesTable2').DataTable({
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
    $(document).ready(function() {
        $('#salesTable3').DataTable({
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

<script src="{{ asset('dashboard/persian-date.min.js') }}"></script>
<script src="{{ asset('dashboard/persian-datepicker.min.js') }}"></script>
@endsection