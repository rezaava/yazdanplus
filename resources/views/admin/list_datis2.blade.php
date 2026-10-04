@extends('admin.layout.master')

@section('onvan')
لیست فروش داتیس 2
@endsection

@section('head')
<style>
    table {
        font-family: iran-md;
        text-align: center;
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
        font-size: 1rem;
        white-space: nowrap;
    }

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
        font-size: 0.9rem;
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

    .paginate_button.disabled .page-link {
        background-color: #333 !important;
        color: #888 !important;
        opacity: 0.5 !important;
        cursor: not-allowed !important;
    }

    .num-col {
        font-weight: 600;
    }

    .user-name {
        font-weight: 600;
    }

    .user-mobile {
        direction: ltr;
        font-weight: 500;
        color: #495057;
    }

    .user-meli {
        direction: ltr;
        font-weight: 500;
        color: #495057;
    }

    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }
        
        thead.table-primary th {
            font-size: 0.8rem;
            padding: 8px 4px;
        }
        
        tbody td {
            font-size: 0.75rem;
            padding: 6px 4px;
        }
    }
</style>
@endsection

@section('main')

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="salesTable" class="table table-bordered table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 5%;">ردیف</th>
                            <th style="width: 12%;">نام</th>
                            <th style="width: 12%;">نام خانوادگی</th>
                            <th style="width: 12%;">موبایل</th>
                            <th style="width: 12%;">کد ملی</th>
                            <th style="width: 12%;">وضعیت</th>
                            @foreach($products as $index => $product)
                            <th style="min-width: 60px; font-size: 0.85rem;">
                                {{ $product->name }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="user-name">{{ $row['name'] }}</td>
                            <td class="user-name">{{ $row['family'] }}</td>
                            <td class="user-mobile">{{ $row['mobile'] }}</td>
                            <td class="user-meli">{{ $row['meli_code'] }}</td>
                            <td class="user-status"> 
                               @if ($row['datis_turn'] == 1)
                                   نوبت اول

                                   @elseif ($row['datis_turn'] == 2)
                                   نوبت دوم
                               @endif  
                            </td>
                            
                            @foreach($products as $product)
                            @php
                                $count = $row['product_' . $product->id] ?? 0;
                            @endphp
                            <td class="num-col text-center {{ $count > 0 ? ' fw-bold' : 'text-muted' }}">
                                {{ $count }}
                            </td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ count($products) + 5 }}" class="text-center py-4 text-muted">
                                هیچ خریدی ثبت نشده است.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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

<!-- DataTables JS -->
<script src="{{ asset('dashboard/datatables.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('#salesTable').DataTable({
        "order": [[0, 'asc']],
        "pagingType": "full_numbers",
        "oLanguage": {
            "oPaginate": {
                "sLast": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>',
                "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                "sFirst": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>'
            },
            "sInfo": "نمایش صفحه _PAGE_ از _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "جستجو کنید...",
            "sLengthMenu": "نتایج : _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [10, 25, 50, 100],
        "pageLength": 10,
        "scrollX": true,
        "autoWidth": false,
        "columnDefs": [
            { "orderable": false, "targets": [0] }
        ]
    });
});

function showImage(src) {
    document.getElementById('modalImage').src = src;
}
</script>

@endsection