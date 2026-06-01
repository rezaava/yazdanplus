@extends('admin.layout.master')

@section('onvan')
اطلاعات
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
        background-color: #1b55e2;
        color: #fff;
        font-weight: 600;
        padding: 12px 8px;
        text-align: center;
        border-bottom: 2px solid #0f3bbd;
        font-size: 0.55rem;
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

    <table id="usersTable" class="table table-bordered table-striped table-hover ">
        <thead class="table-primary">
            <tr>
                <th>پروفایل</th>
                <th>موبایل</th>
                <th> کد معرف</th>
                <th> نام ونام خانوادگی</th>
                <th>کیف پول</th>
                <th>کوین</th>
                <th>مدیر فروشگاه</th>
                <th>تاریخ تولد</th>
                <th>کد ملی</th>
                <th>نوع اکانت</th>
                <th>نقش</th>
            </tr>
        </thead>
        <tbody>
            <tr class="text-center">
                <td>
                    <a href=''>
                        <img src='' alt="user_profile" class="d-block"
                            style="width: 100%; height: 20px; margin: auto; object-fit: contain;">
                    </a>
                </td>
                <td>{{$user->mobile}}</td>
                <td>{{$user->referrer}}</td>
                <td>{{$user->fullname()}}</td>
                <td class="rial">{{$user->wallet}}</td>
                <td class="rial">{{$user->coin}}</td>
                <td>
                    @if ($shop== '[]')
                    <div>
                        <p style="color:red;font-size: 20px;">&times;</p>
                    </div>
                    @else
                    <a data-toggle="collapse" href="#collapseExample_user{{$user->id}}" role="button" aria-expanded="false" aria-controls="collapseExample_user{{$user->id}}">
                        <div style="color: green;font-size: 20px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"></path>
                            </svg>
                        </div>
                    </a>
                    <div class="collapse" id="collapseExample_user{{$user->id}}">
                        <div class="card card-body">
                            @php
                            $shops = App\Models\Shop::where('user_id' ,$user->id)->get();
                            foreach ($shops as $shop) {
                            echo $shop->name.'
                            <hr>';
                            }
                            @endphp
                        </div>
                    </div>

                    @endif
                </td>
                <td>{{$user->date_of_birth}}</td>
                <td>{{$user->nationalcode}}</td>
                <td>
                    @if ($user->active==1)
                    <p class="text-success">فعال</p>

                    @elseif($user->active==2)
                    <p class="text-danger">بن</p>
                    @endif
                </td>
                <td>
                    @foreach ($roles as $role)
                    @if ($role->name == 'admin')
                    <p>ادمین</p>
                    @endif
                    @if ($role->name == 'marketer')
                    <p>بازاریاب</p>
                    @endif
                    @if ($role->name == 'shop_admin')
                    <p>ادمین شاپ</p>
                    @endif
                    @if ($role->name == 'content_manager')
                    <p>مدیر محتوا</p>
                    @endif
                    @endforeach
                </td>
            </tr>
        </tbody>

    </table>
</div>

<hr><hr>
{{-- 
<div class="container mt-4">

    <table id="banksTable" class="table table-bordered table-striped table-hover ">
        <thead class="table-primary">
            <tr>
                <th>شبا</th>
                <th>نام صاحب</th>
                <th>اسم بانک</th>
                <th>وضعیت</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($banks as $bank)
            <tr class="text-center">
                <td>{{$bank->shaba}}</td>
                <td>{{$bank->owner}}</td>
                <td>{{$bank->bank_name}}</td>
                <td>
                    @if ($bank->status == 1)
                    <p class="text-success">فعال</p>
                    @elseif($bank->status == 2)
                    <p class="text-danger">غیر انتخاب</p>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>

<hr><hr>

<div class="container mt-4">

    <table id="ordersTable" class="table table-bordered table-striped table-hover ">
        <thead class="table-primary">
            <tr>
                <th>شناسه</th>
                <th>نام فروشگاه</th>
                <th>خریدار</th>
                <th>موبایل</th>
                <th>مبلغ</th>
                <th>مبلغ پرداختی کاربر</th>
                <th>سهم فروشگاه</th>
                <th>رفته به درگاه</th>
                <th>وضعیت</th>
                <th>وضعیت تسویه</th>
                <th>زمان</th>
                @if ($user->hasRole('admin'))
                <th>اعمال</th>
                @endif

            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr class="text-center">
                <td>{{$order->id}}</td>
                <td>{{$order['shop_order_name']}}</td>
                <td>{{$order['user']}}</td>
                <td style="direction: ltr">{{$order['mobile']}}</td>
                <td class="rial">{{$order->price}}</td>
                <td class="rial">{{$order->getuservalue()}}</td>
                <td class="rial">{{$order->getshopvalue()}}</td>
                <td>
                    @if ($order->authority!=null)
                    <div style="color: green;font-size: 20px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"></path>
                        </svg>
                    </div>
                    @else
                    <div>
                        <p style="color:red;font-size: 20px;">&times;</p>
                    </div>

                    @endif
                </td>
                <td>
                    <p class="{{$order->getStatusClass()}}">
                        {{$order->getStatus()}}
                    </p>
                </td>
                <td>
                    <p class="{{$order->getStatus_tasvieClass()}}">
                        {{$order->getStatus_tasvie()}}
                    </p>
                </td>
                <td>{{$order['time']}}</td>
                @if ($user->hasRole('admin'))
                <td>
                    @if ($order->status == 2 && $order->status_tasvie == 0 )
                    <a href="/admin/tasvie-order/{{$order->id}}" class="btn btn-outline-info">تسویه</a>
                    @else
                    ---
                    @endif
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>

    </table>
</div>

<hr><hr>

<div class="container mt-4">

    <table id="transactionsTable" class="table table-bordered table-striped table-hover ">
        <thead class="table-primary">
            <tr>
                <th>شناسه</th>
                <th>نام فروشگاه</th>
                <th>شماره سفارش</th>
                <th>مبلغ</th>
                <th>کد پیگیری</th>
                <th>هزینه</th>
                <th>نوع هزینه</th>
                <th>عمل انجام شده</th>
            </tr>
        </thead>
        <tbody>
            @foreach($Transactions as $Transaction)
            <tr class="text-center">
                <td>{{$Transaction->id}}</td>
                <td>
                    @if ($Transaction->shop_id != null)
                    {{$Transaction->shop->name}}
                    @endif
                </td>
                <td>{{$Transaction->order_id}}</td>
                <td class="rial">{{$Transaction->value}}</td>
                <td>{{$Transaction->traking_code}}</td>
                <td>{{$Transaction->fee}}</td>
                <td>{{$Transaction->fee_type}}</td>
                <td class="{{$Transaction->getTypeClass()}}">
                    {{$Transaction->gettype()}}
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
--}}
@endsection

@section('script')


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS + Bootstrap integration -->
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
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
        $('#banksTable').DataTable({
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
        $('#ordersTable').DataTable({
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
        $('#transactionsTable').DataTable({
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

