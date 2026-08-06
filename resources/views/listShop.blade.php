<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لیست فروشگاه‌ها</title>

    <link href="{{asset('dashboard/bootstrap.rtl.min.css')}}" rel="stylesheet">

    <style>
        body{
            background:#f5f5f5;
            font-family:sans-serif;
        }

        .page-wrapper{
            max-width: 1200px;
            margin: 20px auto;
        }

        .top-banner{
            background:#E30613;
            color:#fff;
            height:80px;
            display:flex;
            align-items:center;
            position:relative;
        }

        .top-banner .logo{
            width:80px;
            height:80px;
            background:#aab7c4;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .top-banner img{
            max-width:80px;
        }

        .top-banner .title{
            flex:1;
            text-align:center;
            font-size:28px;
            font-weight:500;
        }

        .shops-table{
            margin-top:40px;
            background:#fff;
        }

        .shops-table th{
            background:#e9c9c9;
            text-align:center;
            vertical-align:middle;
            font-size:24px;
            padding:20px 10px;
            border:1px solid #555;
        }

        .shops-table td{
            text-align:center;
            vertical-align:middle;
            padding:18px 10px;
            border:1px solid #555;
            font-size:16px;
        }

        .shops-table a{
            color:#3d6d8a;
            text-decoration:underline;
        }

        .address-col{
            min-width:350px;
        }

        .store-name{
            color:#3d6d8a;
        }
        td{
            font-weight: bold !important;
        }
    </style>
</head>
<body>

<div class="page-wrapper">

    <div class="top-banner">
        
        <div class="title">
            لیست فروشگاه‌های طرف قرارداد صندوق رفاه اعضای هیأت علمی دانشگاه یزد
        </div>

        <div class="logo">
            <img src="{{ asset('/files/mm.jpg') }}" alt="">
        </div>
    </div>

    <table class="table shops-table">
        <thead>
        <tr>
            <th>نام فروشگاه</th>
            <th>شرایط پرداخت (ماه)</th>
            <th>تلفن</th>
            <th class="address-col">آدرس</th>
            <th>لینک</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($shops as $shop )
            
        <tr>
            <td>
                <a href="/shop/{{ $shop->id }}" style="text-decoration: none;" class="store-name">
                    {{ $shop->name }}
                </a>
            </td>
            
            <td>
                {{ $shop->installments_number }}
            </td>
            
            <td>
                <a href="tel: @if (substr($shop->telephone,0,1) != 0 ) 035 @endif {{ $shop->telephone }}">
                {{ $shop->telephone }}
                </a>
            </td>
            
            <td>
                {{ $shop->address }}
            </td>
            
            <td>
                <a href="/shop/{{ $shop->id }}" style="text-decoration: none;">لینک</a>
            </td>
        </tr>
        
        @endforeach
        </tbody>
    </table>

</div>

</body>
</html>