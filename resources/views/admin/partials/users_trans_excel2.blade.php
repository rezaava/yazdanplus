<table>
    <thead>
        <tr>
            <th>نام</th>
            <th>فامیل</th>
            <th>موبایل</th>
            <th>کد ملی</th>
            <th>داتیس نوبت ۱</th>
            <!-- <th>داتیس نوبت ۲</th> -->
            <th>قسط عادی این ماه (ریال)</th>
            <th>جمع کل قسط (ریال)</th>
            <th>شارژ اولیه</th>
            <th>نوع</th>
        </tr>
    </thead>
    <tbody>
        @php
            $sumDatis1  = 0;
            $sumTotal   = 0;
            $sumMonthly = 0;
            @endphp
            <!-- sumDatis2  = 0; -->

        @forelse($data as $row)
            <tr>
                <td>{{ $row['نام'] }}</td>
                <td>{{ $row['فامیل'] }}</td>
                <td>{{ $row['موبایل'] }}</td>
                <td>{{ $row['کد ملی'] }}</td>
                <td>{{ number_format($row['داتیس یک']) }}</td>
                <!-- <td> number_format داتیس دو </td> -->
                <td>{{ number_format($row['قسط عادی این ماه']) }}</td>
                <td>{{ number_format($row['جمع کل قسط']) }}</td>
                <td>{{ number_format($row['شارژ اولیه']) }}</td>
                <td>
                    @if ($row['نوع'] == 1)
                        هیات علمی
                    @elseif ($row['نوع'] == 2)
                        کارمند
                    @elseif ($row['نوع'] == 3)
                        هیات علمی بازنشسته
                    @elseif ($row['نوع'] == 4)
                        کارمند بازنشسته
                    @endif
                </td>
            </tr>

            <!-- sumDatis2  += floatval(row['داتیس دو']); -->
            @php
                $sumDatis1  += floatval($row['داتیس یک']);
                $sumMonthly += floatval($row['قسط عادی این ماه']);
                $sumTotal   += floatval($row['جمع کل قسط']);
            @endphp
        @empty
            <tr>
                <td colspan="10">هیچ داده‌ای یافت نشد</td>
            </tr>
        @endforelse
    </tbody>

    <tfoot>
        <tr>
            <td colspan="4" style="text-align:right; font-weight:bold;">مجموع :</td>
            <td style="font-weight:bold;">{{ number_format($sumDatis1) }}</td>
            <!-- <td style="font-weight:bold;">(sumDatis2) </td> -->
            <td style="font-weight:bold;">{{ number_format($sumMonthly) }}</td>
            <td style="font-weight:bold;">{{ number_format($sumTotal) }}</td>
            <td colspan="2">{{ $month }}</td>
        </tr>
    </tfoot>
</table>