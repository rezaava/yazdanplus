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

    .table.dataTable>thead>tr>th{
        border-bottom: none;
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

@endsection

@section('main')


<div class="container p-2" style="box-shadow: 1px 1px 28px rgb(183, 188, 188);">
    <div class="row">
        <div class="col-md-6 mb-2">
            <input type="text" class="form-control" value="تعداد شرکت کننده : {{ $user_count }}" readonly>
        </div>
    </div>
</div>


<div class="container mt-4">

    <div class="table-responsive">

        <table id="salesTable" class="table table-bordered table-striped table-hover">

            <thead class="table-primary">
                <tr>
                    <th>سؤال</th>
                    <th>گزینه</th>
                    <th>تعداد پاسخ</th>
                </tr>
            </thead>

            <tbody>

{{-- سوال 1 --}}
<tr>
    <td>قیمت تقریبی تلفن همراه موردنظر</td>
    <td>کمتر از ۵۰ میلیون تومان</td>
    <td class="text-center">{{ $priceRange['under_50'] ?? 0 }}</td>
</tr>

<tr>
    <td>قیمت تقریبی تلفن همراه موردنظر</td>
    <td>۵۰ تا ۸۰ میلیون تومان</td>
    <td class="text-center" >{{ $priceRange['50_80'] ?? 0 }}</td>
</tr>

<tr>
    <td>قیمت تقریبی تلفن همراه موردنظر</td>
    <td>۸۰ تا ۱۲۰ میلیون تومان</td>
    <td class="text-center">{{ $priceRange['80_120'] ?? 0 }}</td>
</tr>

<tr>
    <td>قیمت تقریبی تلفن همراه موردنظر</td>
    <td>۱۲۰ تا ۱۵۰ میلیون تومان</td>
    <td class="text-center">{{ $priceRange['120_150'] ?? 0 }}</td>
</tr>

<tr>
    <td>قیمت تقریبی تلفن همراه موردنظر</td>
    <td>بیشتر از ۱۵۰ میلیون تومان</td>
    <td class="text-center">{{ $priceRange['over_150'] ?? 0 }}</td>
</tr>

<tr>
    <td>قیمت تقریبی تلفن همراه موردنظر</td>
    <td>هنوز مبلغ مشخصی ندارم</td>
    <td class="text-center">{{ $priceRange['undecided'] ?? 0 }}</td>
</tr>


{{-- سوال 2 --}}
<tr>
    <td>مبلغ مناسب کسر ماهانه از حقوق</td>
    <td>کمتر از ۵ میلیون تومان</td>
    <td class="text-center">{{ $monthlyPayment['under_5'] ?? 0 }}</td>
</tr>

<tr>
    <td>مبلغ مناسب کسر ماهانه از حقوق</td>
    <td>۵ تا ۸ میلیون تومان</td>
    <td class="text-center">{{ $monthlyPayment['5_8'] ?? 0 }}</td>
</tr>

<tr>
    <td>مبلغ مناسب کسر ماهانه از حقوق</td>
    <td>۸ تا ۱۲ میلیون تومان</td>
    <td class="text-center">{{ $monthlyPayment['8_12'] ?? 0 }}</td>
</tr>

<tr>
    <td>مبلغ مناسب کسر ماهانه از حقوق</td>
    <td>۱۲ تا ۲۰ میلیون تومان</td>
    <td class="text-center">{{ $monthlyPayment['12_20'] ?? 0 }}</td>
</tr>

<tr>
    <td>مبلغ مناسب کسر ماهانه از حقوق</td>
    <td>بیشتر از ۲۰ میلیون تومان</td>
    <td class="text-center">{{ $monthlyPayment['over_20'] ?? 0 }}</td>
</tr>


{{-- سوال 3 --}}
<tr>
    <td>دوره بازپرداخت موردنظر</td>
    <td>۶ ماه</td>
    <td class="text-center">{{ $term['6'] ?? 0 }}</td>
</tr>

<tr>
    <td>دوره بازپرداخت موردنظر</td>
    <td>۱۲ ماه</td>
    <td class="text-center">{{ $term['12'] ?? 0 }}</td>
</tr>

<tr>
    <td>دوره بازپرداخت موردنظر</td>
    <td>۱۸ ماه</td>
    <td class="text-center">{{ $term['18'] ?? 0 }}</td>
</tr>

<tr>
    <td>دوره بازپرداخت موردنظر</td>
    <td>۲۴ ماه</td>
    <td class="text-center">{{ $term['24'] ?? 0 }}</td>
</tr>

<tr>
    <td>دوره بازپرداخت موردنظر</td>
    <td>۳۶ ماه</td>
    <td class="text-center">{{ $term['36'] ?? 0 }}</td>
</tr>

<tr>
    <td>دوره بازپرداخت موردنظر</td>
    <td>به مبلغ قسط بستگی دارد</td>
    <td class="text-center">{{ $term['depends'] ?? 0 }}</td>
</tr>


{{-- سوال 4 --}}
<tr>
    <td>میزان پیش‌پرداخت</td>
    <td>ترجیح می‌دهم بدون پیش‌پرداخت باشد</td>
    <td class="text-center">{{ $downPayment['none'] ?? 0 }}</td>
</tr>

<tr>
    <td>میزان پیش‌پرداخت</td>
    <td>تا ۲۵٪ قیمت گوشی</td>
    <td class="text-center">{{ $downPayment['under_25'] ?? 0 }}</td>
</tr>

<tr>
    <td>میزان پیش‌پرداخت</td>
    <td>بین ۲۵٪ تا ۵۰٪ قیمت گوشی</td>
    <td class="text-center">{{ $downPayment['25_50'] ?? 0 }}</td>
</tr>

<tr>
    <td>میزان پیش‌پرداخت</td>
    <td>بیشتر از ۵۰٪ قیمت گوشی</td>
    <td class="text-center">{{ $downPayment['over_50'] ?? 0 }}</td>
</tr>

<tr>
    <td>میزان پیش‌پرداخت</td>
    <td>به قیمت و شرایط اقساط بستگی دارد</td>
    <td class="text-center">{{ $downPayment['depends'] ?? 0 }}</td>
</tr>

</tbody>

        </table>

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
            "pageLength": 50
        });
    });
</script>


@endsection