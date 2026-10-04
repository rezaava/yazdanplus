@extends('layout.master')
@section('title')
| ویرایش پروفایل
@endsection
@section('style')
<style>
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
@section('content')
<div class="page-content-wrapper">
    <br><br>

    <div class="container">
        <!-- Profile Wrapper-->
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <!-- Profile Wrapper-->

                <form action="/edit-profile" method="POST" enctype="multipart/form-data">
                    <div class="profile-wrapper-area py-3">
                        <!-- User Information-->
                        <div class="card user-info-card">
                            <div class="card-body p-4 d-flex align-items-center">
                                <div class="user-profile me-3"><img @if ($profile) src="{{ asset($profile->address) }}"  @else src="" @endif  alt="profile" style="width: 80px;height:80px;">
                                    <div class="change-user-thumb">
                                        <button><input type="file" name="pic">
                                            <i class="fa-solid fa-pen"></i></button>
                                    </div>
                                </div>
                                <div class="user-info">

                                    @if ($user->name && $user->family)
                                    <h5 class="mb-0">{{ $user->name }} {{ $user->family }}</h5>
                                    @else
                                    <h5 class="mb-0">بدون نام</h5>
                                    @endif
                                    <br>
                                    <p>{{ $user->mobile }}</p>

                                </div>
                            </div>
                        </div>
                        <!-- User Meta Data-->
                        <div class="card user-data-card">

                            <div id="edit-profile-input-group" class="input-group card-body">

                                @csrf
                                <div class="mb-3">
                                    <div class="title mb-2"><i class="fa-solid fa-user"></i><span> نام </span></div>
                                    <input class="form-control @error('name') is-invalid @enderror" name="name" type="text"
                                        value="{{ old('name', $user->name) }}">

                                    @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="title mb-2"><i class="fa-solid fa-user"></i><span> نام خانوادگی</span></div>
                                    <input class="form-control @error('family') is-invalid @enderror" type="text" name="family"
                                        value="{{ old('family', $user->family) }}">

                                    @error('family')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="title mb-2"><i class="fa-solid fa-address-card"></i><span>کدملی</span></div>
                                    <input class="form-control @error('nationalcode') is-invalid @enderror"
                                        type="tel"
                                        name="nationalcode"
                                        value="{{ old('nationalcode', $user->nationalcode) }}"
                                        minlength="10" maxlength="10">

                                    @error('nationalcode')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="title mb-2"><i class="fa-solid fa-calendar-alt"></i><span>تاریخ تولد</span></div>
                                    <div class="datepicker-container" style="z-index:1000;">
                                    <input type="text" name="fromDate" id="datepicker-input-1" class="form-control"
                                        placeholder="1356/06/04" readonly style="cursor: pointer" >
                                    <div class="datepicker-box" id="datepicker-box-1"></div>
                                    </div>
                                    @error('fromDate')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>



                                <button class="btn btn-theme w-100" type="submit" style="border-top-right-radius: 10px;border-bottom-right-radius: 10px;">همه تغییرات را ذخیره کنید</button>
                            </div>
                        </div>
                    </div>
                </form>
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
                            for (let y = 1330; y <= 1450; y++) {
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
            </div>
        </div>
    </div>
</div>
@endsection