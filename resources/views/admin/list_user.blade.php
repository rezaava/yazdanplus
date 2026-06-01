@extends('admin.layout.master')

@section('onvan')
لیست کاربران
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
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <form method="POST" action="{{ route('userreport') }}">
                    @csrf
                    <div class="row">
                        <!-- از تاریخ -->
                        <div class="col-md-4">
                            <div class="datepicker-container">
                                <label>از تاریخ</label>
                                <input type="text" name="from_date" id="datepicker-input-1" class="form-control"
                                    placeholder="از تاریخ" readonly style="cursor: pointer">
                                <div class="datepicker-box" id="datepicker-box-1"></div>
                            </div>
                        </div>

                        <!-- تا تاریخ -->
                        <div class="col-md-4">
                            <div class="datepicker-container">
                                <label>تا تاریخ</label>
                                <input type="text" name="to_date" id="datepicker-input-2" class="form-control"
                                    placeholder="تا تاریخ" readonly style="cursor: pointer">
                                <div class="datepicker-box" id="datepicker-box-2"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button style="display: inline;width: 100%;background-color: #FFD8D8;" type="submit" class="btn mt-4">فیلتر</button>
                        </div>
                    </div>

                </form>
                <table id="salesTable" class="table table-bordered table-striped table-hover ">
                    <thead class="table-primary">
                        <tr>
                            <th>شناسه</th>
                            <th> نام ونام خانوادگی</th>
                            <th>نقش کاربر</th>
                            <th>موبایل</th>

                            <th>شارژ</th>
                            <th>خلاصه کارکرد</th>
                            <th>اعمال</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr class="text-center">
                            <td class="text-center">{{ $user->id }}</td>
                            <td>
                                <a href="/admin/user/show/{{ $user->id }}" style="text-decoration: none;">{{ $user->fullname() }}</a>
                            </td>
                            <td>
                                @if ($user->type == 1)
                                هیئت علمی
                                @elseif ($user->type == 2)
                                    کارمند

                                    @else
                                    ...
                                @endif
                                 </td>
                            <td class="text-center">
                                <a href="/admin/user/show/{{ $user->id }}" style="text-decoration: none;">{{ $user->mobile }}</a>
                            </td>


                            <td class="text-center rial">{{ number_format($user->wallet) }}</td>

                            <td class="text-center">        
                                <a href="/admin/users/performance/{{ $user->id }}" class="btn" style="background-color: #FFD8D8;">خلاصه کارکرد</a>
                            </td>
                            <td class="text-center">        
                                <a href="/admin/users/edit/{{ $user->id }}" class="btn" style="background-color: #FFD8D8;">ویرایش</a>
                            </td>

                        </tr>
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
    const monthNames = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
        'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
    ];
    const daysInMonth = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];

    function j2g(jy, jm, jd) {
        jy += 1595;
        let days = -355668 + (365 * jy) + parseInt(jy / 33) * 8 + parseInt(((jy % 33) + 3) / 4) +
            jd + (jm < 7 ? (jm - 1) * 31 : ((jm - 7) * 30) + 186);
        let gy = 400 * parseInt(days / 146097);
        days %= 146097;
        if (days > 36524) {
            gy += 100 * parseInt(--days / 36524);
            days %= 36524;
            if (days >= 365) days++;
        }
        gy += 4 * parseInt(days / 1461);
        days %= 1461;
        if (days > 365) {
            gy += parseInt((days - 1) / 365);
            days = (days - 1) % 365;
        }
        let gd = days + 1;
        const sal = [0, 31, ((gy % 4 == 0 && gy % 100 != 0) || (gy % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30,
            31, 30, 31
        ];
        let gm = 0;
        for (gm = 1; gm <= 12 && gd > sal[gm]; gm++) gd -= sal[gm];
        return [gy, gm, gd];
    }

    function g2j(gy, gm, gd) {
        const gdm = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        let jy = (gy > 1600 ? 979 : 0),
            gy2 = gy - (gy > 1600 ? 1600 : 621);
        let days = 365 * gy2 + parseInt((gy2 + 3) / 4) - parseInt((gy2 + 99) / 100) +
            parseInt((gy2 + 399) / 400) - 80 + gd + gdm[gm - 1];
        jy += 33 * parseInt(days / 12053);
        days %= 12053;
        jy += 4 * parseInt(days / 1461);
        days %= 1461;
        if (days > 365) {
            jy += parseInt((days - 1) / 365);
            days = (days - 1) % 365;
        }
        let jm = (days < 186) ? 1 + parseInt(days / 31) : 7 + parseInt((days - 186) / 30);
        let jd = 1 + ((days < 186) ? (days % 31) : ((days - 186) % 30));
        return [jy, jm, jd];
    }

    function buildDatePicker(containerId, inputId) {
        const container = document.getElementById(containerId);
        const input = document.getElementById(inputId);

        const yearSelect = document.createElement('select');
        const monthSelect = document.createElement('select');
        yearSelect.className = monthSelect.className = 'form-control';

        const row = document.createElement('div');
        row.className = 'form-row mb-2';
        const col1 = document.createElement('div');
        const col2 = document.createElement('div');
        col1.className = col2.className = 'col';
        col1.appendChild(yearSelect);
        col2.appendChild(monthSelect);
        row.appendChild(col1);
        row.appendChild(col2);

        const weekdayRow = document.createElement('div');
        weekdayRow.className = 'days-grid';
        ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'].forEach(d => {
            const el = document.createElement('div');
            el.className = 'weekday';
            el.textContent = d;
            weekdayRow.appendChild(el);
        });

        const daysGrid = document.createElement('div');
        daysGrid.className = 'days-grid';

        container.appendChild(row);
        container.appendChild(weekdayRow);
        container.appendChild(daysGrid);

        function fillYearMonth() {
            for (let y = 1390; y <= 1450; y++) {
                const opt = document.createElement('option');
                opt.value = y;
                opt.textContent = y;
                yearSelect.appendChild(opt);
            }

            monthNames.forEach((name, i) => {
                const opt = document.createElement('option');
                opt.value = i + 1;
                opt.textContent = name;
                monthSelect.appendChild(opt);
            });
        }

        function buildCalendar(jy, jm) {
            daysGrid.innerHTML = '';
            const [gy, gm, gd] = j2g(jy, jm, 1);
            const firstDay = new Date(gy, gm - 1, gd).getDay(); // Sunday = 0
            const offset = (firstDay + 1) % 7;

            for (let i = 0; i < offset; i++) {
                const empty = document.createElement('div');
                daysGrid.appendChild(empty);
            }

            for (let d = 1; d <= daysInMonth[jm - 1]; d++) {
                const day = document.createElement('div');
                day.className = 'day';
                day.textContent = d;
                day.onclick = () => {
                    input.value = `${jy}/${String(jm).padStart(2, '0')}/${String(d).padStart(2, '0')}`;
                    container.style.display = 'none';
                };
                daysGrid.appendChild(day);
            }
        }

        input.addEventListener('click', () => {
            container.style.display = 'block';
        });

        document.addEventListener('click', (e) => {
            if (!container.contains(e.target) && e.target !== input) {
                container.style.display = 'none';
            }
        });

        yearSelect.addEventListener('change', () => {
            buildCalendar(+yearSelect.value, +monthSelect.value);
        });

        monthSelect.addEventListener('change', () => {
            buildCalendar(+yearSelect.value, +monthSelect.value);
        });

        // Init
        fillYearMonth();
        const today = new Date();
        const [jy, jm, jd] = g2j(today.getFullYear(), today.getMonth() + 1, today.getDate());
        yearSelect.value = jy;
        monthSelect.value = jm;
        buildCalendar(jy, jm);
    }

    buildDatePicker('datepicker-box-1', 'datepicker-input-1');
    buildDatePicker('datepicker-box-2', 'datepicker-input-2');
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
</script>
@endsection