<table style="width:100%; border-collapse:collapse;" dir="rtl">
    <thead>
        <tr>
            <th>ماه</th>
            <th>کل فروش</th>
            <th>کل تسویه</th>
            <th>تخفیف</th>
            <th>باقی مانده</th>
        </tr>
    </thead>
    <tbody>
        @foreach($report as $row)
            <tr>
                <td>{{ $row['ماه'] }}</td>
                <td>{{ $row['کل فروش'] }}</td>
                <td>{{ $row['کل تسویه'] }}</td>
                <td>{{ $row['تخفیف'] }}</td>
                <td>{{ $row['باقی مانده'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
