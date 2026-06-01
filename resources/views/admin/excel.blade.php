<table style="text-align: right">
    <thead style="background-color: #f2f2f2;">
        <tr>
            <th colspan="3">نوع تراکنش</th>
            <th colspan="2">مانده حساب</th>
            <th colspan="2">مبلغ تخفیف / ریال</th>
            <th colspan="2">درصد تخفیف%</th>
            <th colspan="2">پرداخت به فروشگاه</th>
            <th colspan="2">مبلغ تسویه / ریال</th>
            <th colspan="2">ساعت</th>
            <th colspan="2">تاریخ</th>
            <th colspan="2">شناسه پرداخت</th>
            <th colspan="2">صورت حساب</th>
            <th colspan="4">فروشگاه</th>
            <th>ردیف</th>
        </tr>
    </thead>
    <tbody>
        @php
            $count = 0;
            $totalPrice = 0;
        @endphp
        @foreach ($transactions as $tx)
            <tr>
                <td colspan="3">{{ $tx->description }}</td>
                <td colspan="2"></td>
                <td colspan="2">{{ number_format(($tx->value*$tx->darsad)/100) }}</td>
                <td colspan="2">{{ $tx->darsad }}%</td>
                <td colspan="2">{{number_format((100-$tx->darsad)*$tx->value/100)}}</td>
                <td colspan="2">{{ number_format($tx->value) }}</td>
                <td colspan="2">{{ jdate($tx->created_at)->format('H:i:s') }}</td>
                <td colspan="2">{{ jdate($tx->date)->format('Y/m/d') }}</td>
                <td colspan="2">{{$tx->id}}</td>
                <td colspan="2"></td>
                <td colspan="4">{{ $tx->shop->name ?? 'بدون نام' }}</td>
                <td>{{ ++$count }}</td>
            </tr>
            @php
                $totalPrice += $tx->value;
            @endphp
        @endforeach
        <tr>
            <td colspan="3"></td>
            <td colspan="2"></td>
            <td colspan="2"></td>
            <td colspan="2"></td>
            <td colspan="2"></td>
            <td colspan="2" style="color: red">{{ number_format($totalPrice) }}</td>
            <td colspan="2"></td>
            <td colspan="2"></td>
            <td colspan="2"></td>
            <td colspan="2"></td>
            <td colspan="4"></td>
            <td></td>
        </tr>
    </tbody>
</table>
