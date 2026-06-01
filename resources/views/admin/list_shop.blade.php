@extends('admin.layout.master')

@section('onvan')
لیست فروشگاه ها
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
</style>
@endsection

@section('main')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="salesTable" class="table table-bordered table-striped table-hover ">
                    <thead class="table-primary">
                        <tr>
                            <th>شناسه</th>
                            <th>نام</th>

                            <th>موبایل</th>
                            <th>نمایش</th>
                            @if (Auth::user()->hasRole('shop_admin') || Auth::user()->hasRole('admin'))
                            <th class="text-center">ادمین</th>

                            @endif
                            @if (Auth::user()->hasRole('admin'))

                            <th class="text-center">ویرایش</th>

                            <th class="text-center">تسویه ها</th>
                            @endif
                            <th class="text-center">حسابرسی</th>
                            <th class="text-center">گزارش</th>
                            @if (Auth::user()->hasRole('admin'))
                            <th class="text-center">قرارداد</th>
                            <th class="text-center">شرایط</th>
                            @endif
                            <th class="text-center">حساب کل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shops as $shop)
                        <tr class="text-center">
                            <td class="text-center">{{ $shop->id }}</td>
                            <td><a href="/shop/{{ $shop->slug_code }}" style="text-decoration: none;">{{ $shop->name }}</a></td>
                            <td>{{ $shop->mobile }}</td>
                            <td>
                                @if ($shop->display == '1')
                                <div style="color: green;font-size: 20px;">✔</div>
                                @else
                                <p style="color:red;font-size: 20px;">&times;</p>
                                @endif
                            </td>
                            @if (Auth::user()->hasRole('shop_admin') || Auth::user()->hasRole('admin'))
                            <td class="text-center">
                                <a href="/admin/shop_pass/{{ $shop->id }}" class="btn" style="background-color: #FFD8D8 ;">ادمین</a>
                            </td>
                            @endif
                            @if (Auth::user()->hasRole('admin'))
                            <td class="text-center">
                                <a href="/admin/shops/edit/{{ $shop->id }}" class="btn" style="background-color: #FFFCFB;">ویرایش</a>
                            </td>
                            <td class="text-center">
                                <a href="/admin/shops/form_tasvie/{{ $shop->id }}" class="btn" style="background-color:#FFD8D8;"> تسویه</a>
                            </td>
                            @endif


                            <td class="text-center">
                                <a class="btn" style="background-color:#FFFCFB ;"
                                    href="/admin/shops/audit/{{ $shop->id }}">حسابرسی</a>
                                <!-- Example single danger button -->
                            </td>
                            <td class="text-center">
                                <a class="btn" style="background-color: #FFD8D8;"
                                    href="/admin/shops/report_g/{{ $shop->id }}">گزارش</a>
                                <!-- Example single danger button -->
                            </td>
                            @if (Auth::user()->hasRole('admin'))
                            <td class="text-center">
                                <a class="btn" style="background-color: #FFFCFB;"
                                    href="/admin/shops/{{ $shop->id }}/contracts">قرارداد</a>
                                <!-- Example single danger button -->
                            </td>
                            <td class="text-center">
                                <a class="btn" style="background-color: #FFD8D8;"
                                    href="/admin/shops/conditions/{{ $shop->id }}">شرایط</a>
                                <!-- Example single danger button -->
                            </td>
                            @endif
                            <td class="text-center">
                                <a class="btn" style="background-color:#FFFCFB ;font-size: 15px;padding-right: 8px;padding-left: 8px;"
                                    href="/admin/shops/bill/{{ $shop->id }}">حساب کل</a>
                                <!-- Example single danger button -->
                            </td>
                        </tr>


                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const priceInput = document.getElementById('priceInput{{ $shop->id }}');
                                const percentInput = document.getElementById('percentInput{{ $shop->id }}');
                                const calculatedDiv = document.getElementById('calculatedAmount{{ $shop->id }}');

                                function parsePrice(value) {
                                    if (!value) return 0;
                                    return parseFloat(value.replace(/,/g, '')) || 0;
                                }

                                function formatNumber(num) {
                                    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                }
                                priceInput.addEventListener('input', function() {
                                    let cursorPos = this.selectionStart;
                                    let originalLength = this.value.length;
                                    let value = this.value.replace(/,/g, '').replace(/[^\d]/g, '');
                                    if (value === '') {
                                        this.value = '';
                                        calculatedDiv.textContent = '';
                                        return;
                                    }
                                    this.value = formatNumber(value);
                                    let newLength = this.value.length;
                                    cursorPos = cursorPos + (newLength - originalLength);
                                    this.setSelectionRange(cursorPos, cursorPos);
                                    calculateAndShow();
                                });
                                percentInput.addEventListener('input', function() {
                                    calculateAndShow();
                                });

                                function calculateAndShow() {
                                    let price = parsePrice(priceInput.value);
                                    let percent = parseFloat(percentInput.value);
                                    if (isNaN(percent) || percent < 0) {
                                        percent = 0;
                                    }
                                    if (percent > 100) {
                                        percent = 100;
                                        percentInput.value = 100;
                                    }
                                    if (price === 0 || percent === 0) {
                                        calculatedDiv.textContent = '';
                                        return;
                                    }
                                    let result = (price * percent) / 100;

                                    calculatedDiv.innerHTML =
                                        `سهم فروشگاه: ${formatNumber((price - result).toFixed(0))} ریال<br>` +
                                        `سهم سیستم: ${formatNumber(result.toFixed(0))} ریال`;
                                }
                            });
                        </script>
                        @endforeach
                    </tbody>
                </table>
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