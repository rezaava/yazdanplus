<table>
    <thead>
        <tr>
            <th>نام</th>
            <th>فامیل</th>
            <th>موبایل</th>
            <th>کد ملی</th>
            <th>جمع قسط این ماه
                (ریال)
            </th>
            <th>شارژ اولیه</th>
            <th>نوع</th>
        </tr>
    </thead>
    <tbody>
        @php
        $sum=0;
        @endphp
        @forelse($data as $row)
        <tr>
            <td>{{ $row['نام'] }}</td>
            <td>{{ $row['فامیل'] }}</td>
            <td>{{ $row['موبایل'] }}</td>
            <td>{{ $row['کد ملی'] }}</td>
            <td>{{ $row['جمع قسط این ماه'] }}</td>
            <td>{{ $row['شارژ اولیه'] }}</td>
            <td>
            @if ($row['نوع'] == 1)
                هیات علمی
                @elseif ($row['نوع'] == 2)
                کارمند
            @endif    
        </td>
        </tr>
        @php
            $cleanValue = str_replace(['،', ',', '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
            ['', '', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
             $row['جمع قسط این ماه']);
            $sum += floatval($cleanValue);
        @endphp
        @empty
        <tr>
            <td colspan="5">هیچ داده‌ای یافت نشد</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td>
            {{ number_format($sum) }}
            </td>
            <td>مجموع :
                
            </td>
            <td colspan="4"> 
               {{ $month }} 
            </td>
        </tr>
    </tfoot>
</table>