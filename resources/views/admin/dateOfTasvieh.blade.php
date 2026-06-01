@extends('admin.layout.master')

@section('head')
<style>
    body {
        background-color: #f8f9fa;
        font-family: sans-serif;
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
@section('onvan')
تسویه ها
@endsection

@section('main')
<div class="layout-px-spacing mt-5">
    <div class="row layout-top-spacing" id="cancel-row">

        @error('error')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <form method="POST" action="/admin/exceldownload">
                @csrf
                <div class="row">
                    <!-- از تاریخ -->
                    <div class="col-lg-4 col-md-6">
                        <div class="datepicker-container" style="z-index:1;">
                            <label>از تاریخ</label>
                            <input type="text" name="fromDate" id="datepicker-input-1" class="form-control"
                                placeholder="از تاریخ" readonly style="cursor: pointer">
                            <div class="datepicker-box" id="datepicker-box-1"></div>
                        </div>
                    </div>

                    <!-- تا تاریخ -->
                    <div class="col-lg-4 col-md-6">
                        <div class="datepicker-container" style="z-index:1;">
                            <label>تا تاریخ</label>
                            <input type="text" name="toDate" id="datepicker-input-2" class="form-control"
                                placeholder="تا تاریخ" readonly style="cursor: pointer">
                            <div class="datepicker-box" id="datepicker-box-2"></div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn" style="background-color: #FFD8D8;">دانلود اکسل</button>
            </form>
        </div>

    </div>
</div>

@endsection

@section('script')
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
    buildDatePicker('datepicker-box-3', 'datepicker-input-3');
</script>

@endsection