@extends('layout.master2')

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

    .sidebar {
        z-index: 0 !important;
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
        background-color: #ff256d !important;
        color: #fff;
        border-radius: 10px;
        padding: 10px;
        transition: 0.3s;
        margin-top: 27px;
    }

    .btn_red:hover {
        background-color: #9f0415 !important;
        color: #fff;
    }
</style>
@endsection



@section('main')

<div class="container p-2" style="box-shadow: 1px 1px 28px rgb(183, 188, 188);">
    <div class="row">

        <div class="col-md-6 mb-2">
            <input type="text" class="form-control" value="تعداد فروشگاه فعال  : {{ $shops_count }}" readonly>
        </div>
        <div class="col-md-6 mb-2">
            <input type="text" class="form-control" value="تعداد کاربران فعال  : {{ $users_count }}" readonly>
        </div>
        <div class="col-md-6 mb-2">
            <input type="text" class="form-control" value="تعداد هیئت علمی فعال  : {{ $elmi_count }}" readonly>
        </div>
        <div class="col-md-6 mb-2">
            <input type="text" class="form-control" value="تعداد کارمند فعال  : {{ $karmand_count }}" readonly>
        </div>
        <div class="col-md-6 mb-2">
            <input type="text" class="form-control" value="تعداد هیئت علمی بازنشسته  فعال  : {{ $elmi_b_count }}" readonly>
        </div>
        <div class="col-md-6 mb-2">
            <input type="text" class="form-control" value="تعداد کارمند بازنشسته فعال  : {{ $karmand_b_count }}" readonly>
        </div>
        <div class="col-md-6 mb-2">
            <input type="text" class="form-control" value="مجموع خرید هیئت علمی  : {{ number_format($elmi_order) }}" readonly>
        </div>
        <div class="col-md-6 mb-2">
            <input type="text" class="form-control" value="مجموع خرید کارمندان  : {{ number_format($karmand_order) }}" readonly>
        </div>
        <div class="col-md-6 mb-2">
            <input type="text" class="form-control" value="مجموع خرید هیئت علمی بازنشسته : {{ number_format($elmi_b_order) }}" readonly>
        </div>
        <div class="col-md-6 mb-2">
            <input type="text" class="form-control" value="مجموع خرید کارمندان بازنشسته  : {{ number_format($karmand_b_order) }}" readonly>
        </div>
    </div>
</div>


<div class="container mt-3">
    <form method="GET" action="{{ url()->current() }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">فیلتر بر اساس ماه</label>
            <select name="month" class="form-control" onchange="this.form.submit()">
                <option value="">همه ماه‌ها</option>
                @foreach($persianMonths as $num => $name)
                    <option value="{{ $num }}" {{ (string)$selectedMonth === (string)$num ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
        @if($selectedMonth)
        <div class="col-md-2">
            <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-100">حذف فیلتر</a>
        </div>
        @endif
    </form>
</div>




<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="salesTable" class="table table-bordered table-striped table-hover ">
                    <thead class="table-primary">
                        <tr>
                            <th>نام</th>
                            <th>فامیل</th>
                            <th>وضعیت</th>
                            <th>موبایل</th>
                            <th>موجودی
                                (ریال)
                            </th>
                            <th>تعداد خرید</th>
                            <th>مبلغ خرید</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        @if ($user->orders_count > 0)
                            
                        <tr class="text-center">
                            <td>
                                {{ $user->name }}
                            </td>
                            <td style="text-align: center;">{{ $user->family }}</td>
                            <td style="text-align: center;">
                            @switch($user->type)
                                @case(1)
                                    هیئت علمی
                                    @break
                            
                                @case(2)
                                     کارمند
                                    @break
                                @case(3)
                                     هیئت علمی بازنشسته
                                    @break
                                @case(4)
                                     کارمند بازنشسته
                                    @break
                            
                                @default
                                ....
                                    
                            @endswitch
                                
                            </td>
                            <td style="text-align: center;">{{ $user->mobile }}</td>
                            <td style="text-align: center;">{{ $user->wallet }}</td>
                            <td class="text-center">
                                <span>{{ $user->orders_count }}</span>
                            </td>
                            <td class="text-center rial">
                                {{ number_format($user->orders_sum) }}
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- <div class="container">
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
</div> -->


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