@extends('layout/master')
@section('title')
اعتبار
@endsection
@section('wallet')
active
@endsection
@section('content')
<script>
    function separate(Number) {
        Number += '';
        Number = Number.replace(',', '');
        x = Number.split('.');
        y = x[0];
        z = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(y))
            y = y.replace(rgx, '$1' + ',' + '$2');
        return y + z;
    }
</script>
<!-- Page Content Wrapper-->
<div class="page-content-wrapper">
    <br>
    <div class="container">
        <br>
        <div class="payment-bill-info-box">
            <p class="payment-bill-title "><i class="fa-solid fa-wallet"></i></p>
            <p class="payment-bill-title order-success"> میزان اعتبار شما :<span class="price">{{ $user->wallet }}</span> {{ $MONEY_SIGN }}</p>
            {{-- <a class="btn btn-danger" href="/charge-wallet">شارژ کیف پول</a> --}}
            <br><br>

        </div>

     
    </div>
</div>

<script>
    function separate(Number) {
        Number += '';
        Number = Number.replace(',', '');
        x = Number.split('.');
        y = x[0];
        z = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(y))
            y = y.replace(rgx, '$1' + ',' + '$2');
        return y + z;
    }

    const prices = document.getElementsByClassName("price");
    for (let i = 0; i < prices.length; i++) {
        var item = prices[i];
        item.innerHTML = separate(item.innerHTML);
    }
</script>

@endsection