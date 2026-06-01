@extends('admin.layout.master')

@section('onvan')

@endsection

@section('head')
<style>
    /* فونت و چینش */
    table {
        font-family: iran-md;
        text-align: center !important;
        border-collapse: collapse;
        width: 100%;
    }

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
        font-size: 0.75rem;
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


    .datepicker-container {
        position: relative;
        max-width: 300px;
        margin-bottom: 20px;
    }

    .datepicker-box {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border: 1px solid #ccc;
        z-index: 100;
        display: none;
        width: 100%;
        padding: 10px;
        border-radius: 4px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .days-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        text-align: center;
    }

    .day,
    .weekday {
        padding: 6px;
        border-radius: 4px;
        cursor: pointer;
    }

    .weekday {
        font-weight: bold;
        background: #f1f1f1;
    }

    .day:hover {
        background: #007bff;
        color: white;
    }

    .selected {
        background: #007bff;
        color: white;
    }
</style>

@endsection

@section('main')

<div class="container mt-4">

    <h4>اقساط کاربر: {{ $user->name }} {{ $user->family }}</h4>
    <p>از تاریخ {{ $fromShamsi }} تا {{ $toShamsi }}</p>

    <table id="salesTable" class="table table-bordered table-striped table-hover ">
        <thead class="table-primary">
            <tr>
                <th>نام</th>
                <th>فامیل</th>
                <th>موبایل</th>
                <th>مبلغ</th>
                <th>تاریخ قسط</th>
                <th>وضعیت</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($transactions as $trx)
            <tr>
                <td>{{ $user->name ?? '---' }}</td>
                <td>{{ $user->family ?? '---' }}</td>
                <td>{{ $user->mobile ?? '---' }}</td>
                <td>{{ number_format($trx->value) . ' ریال' ?? '---' }}</td>
                <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($trx->tarikh_ghest)->format('Y/m/d') }}</td>
                <td>
                    @switch($trx->type)
                    @case(15)
                    قسط
                    @break

                    @case(17)
                    رد قسط
                    @break

                    @case(18)
                    قسط پرداخت شده
                    @break

                    @case(19)
                    قسط پرداخت نشده
                    @break

                    @default
                    ---
                    @endswitch
                </td>
            </tr>
            @empty
           
            @endforelse
        </tbody>
    </table>
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
@endsection