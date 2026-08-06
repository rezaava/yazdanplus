
<div class="mt-3">

    {{-- اقساط --}}
    <h5 class="mt-4">گزارش </h5>
    @if($installments->isEmpty())
        <div class="alert alert-warning">هیچ خرید در این بازه وجود ندارد.</div>
    @else
        <table class="table table-bordered table-sm">
            <thead>
            <tr>
                <th>#</th>
                <th>تاریخ</th>
                <th>مبلغ</th>
            </tr>
            </thead>
            <tbody>
                @php
                    $sum=0;
                @endphp
            @foreach($installments as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ jdate($item->created_at)->format('Y/m/d') }}</td>
                   
                    <td>{{ number_format($item->price) }}
                       {{--  ({{ number_format($item->final_price) }})  --}}
                    </td>
                    @php
                        $sum += $item->price;
                    @endphp
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <td></td>
                <td>مجموع: </td>
                <td>
                    {{ number_format($sum) }}
                </td>
            </tfoot>
        </table>
    @endif

    {{-- تسویه‌ها --}}
    <h5 class="mt-4">گزارش تسویه‌ها</h5>
    @if($settlements->isEmpty())
        <div class="alert alert-warning">هیچ تسویه‌ای در این بازه وجود ندارد.</div>
    @else
        <table class="table table-bordered table-sm">
            <thead>
            <tr>
                <th>#</th>
                <th>تاریخ</th>
               
                <th>مبلغ</th>
            </tr>
            </thead>
            <tbody>
            @foreach($settlements as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ jdate($item->created_at)->format('Y/m/d') }}</td>
                   
                    <td>{{ number_format($item->value) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    {{-- تسویه با تخفیف --}}
    <h5 class="mt-4">گزارش تسویه با تخفیف</h5>
    @if($discounts->isEmpty())
        <div class="alert alert-warning">هیچ داده‌ای ثبت نشده است.</div>
    @else
        <table class="table table-bordered table-sm">
            <thead>
            <tr>
                <th>#</th>
                <th>تاریخ</th>
                
                <th>مبلغ</th>
            </tr>
            </thead>
            <tbody>
            @foreach($discounts as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ jdate($item->created_at)->format('Y/m/d') }}</td>
                  
                    <td>{{ number_format($item->value) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>

