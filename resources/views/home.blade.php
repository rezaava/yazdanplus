@extends('layout/master')
@section('title')
@endsection
@section('home')
active
@endsection
@section('shop')
<!-- <a class="text-muted top-menu-a mx-1" href="#shops"><svg style="width: 21px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
    </svg>
    فروشگاه</a> -->
@endsection
@section('content')
<!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.6.8/dist/css/splide.min.css">-->

<style>
    body{
        padding-top: 50px;
    }
    .page-link {}

    * {
        box-sizing: border-box;
    }

    .catagory-card img {
        max-height: 80px;
    }

    .catagory-card span {
        padding: 3px;
        min-height: 40px;
    }

    #myInput {
        background-image: url('/css/searchicon.png');
        background-position: 10px 12px;
        background-repeat: no-repeat;
        width: 100%;
        font-size: 16px;
        padding: 12px 20px 12px 40px;
        border: 1px solid #ddd;
        margin-bottom: 12px;
    }

    #myUL {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    #myUL li a {
        border: 1px solid #ddd;
        margin-top: -1px;
        /* Prevent double borders */
        background-color: #f6f6f6;
        padding: 12px;
        text-decoration: none;
        font-size: 18px;
        color: black;
        display: block
    }

    #myUL li a:hover:not(.header) {
        background-color: #eee;
    }


    .vendor-profile {
        /*padding: 6px !important;*/
        overflow: hidden;
        bottom: 1rem !important;
    }

    .vendor-profile figure {
        width: 100% !important;
        height: 100% !important;
    }

    .vendor-profile figure img {
        /*padding: 4px;*/
        width: 100% !important;
        height: 100% !important;
        max-width: unset !important;
        border-radius: 50%;
    }

    #image-slider .splide__slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 30px;
        /* مهم */
    }

    .splide__arrow {
        top: 55%;
        transform: translateY(-50%);
    }

    .form-select {
        width: 30%;
        display: inline-block;
        margin-right: 2px;
    }
    .vendor-buttons {
  position: relative;
  z-index: 3;
  display: flex;
  flex-direction: column;
  align-items: flex-end; /* چینش به سمت راست */
  gap: 0.4rem;
}

/* دکمه اصلی */
.home-page-btn,
.home-page-btn-secondary {
  width: 80%; /* عرض کمتر از کارت */
  text-align: center;
  padding: 0.35rem 0.5rem;
  font-size: 0.9em;
  border-radius: 6px;
  transition: all 0.2s ease-in-out;
}

/* رنگ‌ها */
.home-page-btn {
  border: none;
}

.home-page-btn:hover {
  background-color: palevioletred;
  color: red;
}

.home-page-btn-secondary {
  background-color: rgba(255,255,255,0.15);
  color: #fff;
  border: 1px solid rgba(255,255,255,0.3);
}

.home-page-btn-secondary:hover {
  background-color: rgba(255,255,255,0.3);
  color: red;
}
/* بنر ویژه بالای سرچ */
.special-home-banner {
    display: block;
    width: 100%;
    padding: 22px 30px;
    border-radius: 16px;
    text-decoration: none !important;
    background: linear-gradient(135deg, #e22d4e, #d25c6d);
    /* box-shadow: 0 8px 25px rgba(74, 0, 224, 0.25); */
    transition: all 0.25s ease;
    direction: rtl;
}

.special-home-banner:hover {
    transform: translateY(-3px);
    /* box-shadow: 0 12px 30px rgba(74, 0, 224, 0.35); */
}

.special-home-banner-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.special-home-banner-title {
    display: block;
    color: #fff;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 5px;
}

.special-home-banner-text {
    display: block;
    color: rgba(255, 255, 255, 0.9);
    font-size: 15px;
}

.special-home-banner-icon {
    color: #fff;
    font-size: 30px;
    flex-shrink: 0;
}

@media (max-width: 576px) {
    .special-home-banner {
        padding: 18px 20px;
        margin-top: 10px;
    }

    .special-home-banner-title {
        font-size: 19px;
    }

    .special-home-banner-text {
        font-size: 13px;
    }

    .special-home-banner-icon {
        font-size: 23px;
    }
}

/* بنر فراخوان افزایش سرمایه - فقط پس‌زمینه متفاوت */
.special-home-banner--stock {
    background: linear-gradient(135deg, #1e3c72, #2a5298);
}


/* ---------- CATEGORY FILTER ---------- */
.category-filter-wrap {
    display: flex;
    justify-content: center;
    padding: 10px 0;
}

.category-filter {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    padding: 12px 20px;
    background: #f8fafc;
    border-radius: 50px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
}

.category-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    padding: 9px 22px;
    border-radius: 50px;

    background: #ffffff;
    color: #1e293b !important;
    text-decoration: none !important;

    font-size: 14px;
    font-weight: 600;

    border: 2px solid #e2e8f0;

    transition: all 0.25s ease;
    white-space: nowrap;
    cursor: pointer;
}

.category-btn i {
    font-size: 14px;
    color: #64748b;
    transition: color 0.25s ease;
}

.category-btn:hover {
    background: #fef2f2;
    border-color: #dc2626;
    color: #dc2626 !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(220, 38, 38, 0.15);
}

.category-btn:hover i {
    color: #dc2626;
}

/* حالت فعال */
.category-btn.active {
    background: #dc2626;
    border-color: #dc2626;
    color: #fff !important;
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.3);
}

.category-btn.active i {
    color: #fff;
}

.category-btn.active:hover {
    background: #b91c1c;
    border-color: #b91c1c;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
}

@media (max-width: 768px) {
    .category-filter-wrap {
        padding: 10px 0;
        overflow: hidden;
    }

    .category-filter {
        padding: 10px 16px;
        gap: 8px;
        border-radius: 16px;

        /* 👇 اسکرول افقی */
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
        justify-content: flex-start;

    }

    .category-filter::-webkit-scrollbar {
    height: 4px;
}

.category-filter::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.category-filter::-webkit-scrollbar-thumb {
    background: #dc2626;
    border-radius: 10px;
}

    .category-btn {
        padding: 8px 16px;
        font-size: 13px;
        flex-shrink: 0; /* 👈 جلوگیری از فشرده شدن دکمه‌ها */
    }

    .category-btn i {
        font-size: 12px;
    }
}

@media (max-width: 400px) {
    .category-btn {
        padding: 6px 14px;
        font-size: 12px;
    }
}
</style>

<div class="container">

    <!--<div class="row" style="margin-top: 100px;">-->
    <!--    <div id="image-slider" class="splide" dir="ltr">-->
    <!--        <div class="splide__track">-->
    <!--            <ul class="splide__list">-->
    <!--                <li class="splide__slide"><img src="{{ asset('/img/slider/bb.jpg') }}" alt="Image 1"></li>-->
    <!--                <li class="splide__slide"><img src="{{ asset('img/slider/cc.jpg') }}" alt="Image 2"></li>-->
    <!--                <li class="splide__slide"><img src="{{ asset('/img/slider/dd.jpg') }}" alt="Image 3"></li>-->
    <!--            </ul>-->
    <!--        </div>-->
    <!--    </div>-->

    <!-- <div class="home-page-title-text-wrapper">
                  <h3 class="mb-4 home-page-title-text home-page-title-text1">
                      با
                      <span class="home-page-title-bold me-1">یزدان پلاس</span>
                  </h3>
                  <h3 class="mb-4 home-page-title-text">
                      هوشمندانه انتخاب کن
                      <span class="home-page-title-bold">

                      </span>

                  </h3>
              </div> -->

    <!--</div>-->

    <script>
        function scrollWin() {
            // screenSize = screen.height;
            // window.scrollBy(0, screenSize-80);
            window.location.href = "#shops";
        }
    </script>


    <script>
        setInterval(showSlides, 3000)

        function showSlides() {
            slideDots = document.getElementsByClassName('owl-dot');
            i = 0;
            for (n = 0; n < 3; n++) {
                if (slideDots[i].classList.contains('active')) {
                    break;
                }
                i++;
            }
            if (i == 2) {
                slideDots[0].click();
            } else {
                slideDots[i + 1].click();
            }
        }
    </script>


    <!--<div class="col-md-6">-->
    <!--    <a href="/survey/mobile" class="special-home-banner">-->
    <!--        <div class="special-home-banner-content">-->
    <!--            <div>-->
    <!--                <span class="special-home-banner-title">-->
    <!--                    نظر سنجی-->
    <!--                </span>-->
    <!--                <span class="special-home-banner-text">-->
    <!--                    برای مشاهده کلیک کنید -->
    <!--                </span>-->
    <!--            </div>-->

    <!--            <i class="fa-solid fa-arrow-left special-home-banner-icon"></i>-->
    <!--        </div>-->
    <!--    </a>-->
    <!--</div>-->
    
<div class="row mb-4 mt-5">

<div class="col-md-12">
    <a href="/stock" class="special-home-banner special-home-banner--stock">
            <div class="special-home-banner-content">
                <div>
                    <span class="special-home-banner-title">
                    فراخوان افزایش سرمایه   
                    </span>
                    <span class="special-home-banner-text">
                    فراخوان افزایش سرمایه و توسعه جامعه سهام‌داران صندوق رفاه دانشگاه یزد
                    </span>
                </div>

                <i class="fa-solid fa-arrow-left special-home-banner-icon"></i>
            </div>
        </a>
    </div>

    <!-- <div class="col-md-6 mt-lg-2">
        <a href="/datis" class="special-home-banner">
            <div class="special-home-banner-content">
                <div>
                    <span class="special-home-banner-title">
                        محصولات روغن برند داتیس   
                    </span>
                    <span class="special-home-banner-text">
                        برای مشاهده لیست محصولات کلیک کنید 
                    </span>
                </div>

                <i class="fa-solid fa-arrow-left special-home-banner-icon"></i>
            </div>
        </a>
    </div> -->

    <!-- <div class="col-md-6 mt-lg-2">
        <a href="/Arde" class="special-home-banner">
            <div class="special-home-banner-content">
                <div>
                    <span class="special-home-banner-title">
                    محصولات ارده  
                    </span>
                    <span class="special-home-banner-text">
                    برای مشاهده لیست محصولات کلیک کنید 
                    </span>
                </div>

                <i class="fa-solid fa-arrow-left special-home-banner-icon"></i>
            </div>
        </a>
    </div> -->

    <!-- <div class="col-md-6 mt-lg-2">
        <a href="/honey" class="special-home-banner">
            <div class="special-home-banner-content">
                <div>
                    <span class="special-home-banner-title">
                    محصولات عسل بنادکوک  
                    </span>
                    <span class="special-home-banner-text">
                    برای مشاهده لیست محصولات کلیک کنید 
                    </span>
                </div>

                <i class="fa-solid fa-arrow-left special-home-banner-icon"></i>
            </div>
        </a>
    </div> -->
    <div class="col-md-6 mt-lg-2">
        <a href="/kerem" class="special-home-banner">
            <div class="special-home-banner-content">
                <div>
                    <span class="special-home-banner-title">
                     محصولات کرم بیسکویت داتیس  
                    </span>
                    <span class="special-home-banner-text">
                    برای مشاهده لیست محصولات کلیک کنید 
                    </span>
                </div>

                <i class="fa-solid fa-arrow-left special-home-banner-icon"></i>
            </div>
        </a>
    </div>
    <div class="col-md-6 mt-lg-2">
        <a href="/aroosha" class="special-home-banner">
            <div class="special-home-banner-content">
                <div>
                    <span class="special-home-banner-title">
                    محصولات روغن داتیس (برند آروشا) 
                    </span>
                    <span class="special-home-banner-text">
                    برای مشاهده لیست محصولات کلیک کنید 
                    </span>
                </div>

                <i class="fa-solid fa-arrow-left special-home-banner-icon"></i>
            </div>
        </a>
    </div>

    <!-- <div class="col-md-4">
        <a href="/datis2" class="special-home-banner">
            <div class="special-home-banner-content">
                <div>
                    <span class="special-home-banner-title">
                    داتیس (ویژه کارکنان شرکتی دانشگاه یزد)  
                    </span>
                    <span class="special-home-banner-text">
                    برای مشاهده لیست محصولات کلیک کنید 
                    </span>
                </div>
                <i class="fa-solid fa-arrow-left special-home-banner-icon"></i>
            </div>
        </a>
    </div> -->
    
</div>


{{-- فیلتر دسته بندی --}}
<div class="row mt-4 mb-3">
    <div class="col-md-12">
        <div class="category-filter-wrap">
            <div class="category-filter">
                {{-- همه فروشگاه‌ها --}}
                <a href="{{ url('/') }}#shops"
                   class="category-btn {{ !request('cat') ? 'active' : '' }}">
                    <i class="fa-solid fa-store"></i>
                    همه فروشگاه‌ها
                </a>

                {{-- دسته بندی‌ها --}}
                @foreach ($categories as $category)
                    <a href="{{ url('/?cat=' . $category->id) }}#shops"
                       class="category-btn {{ request('cat') == $category->id ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>


    <!-- Products -->
    <div class="row mt-5 mb-2">

        <div class="col-md-12" style="display: flex;justify-content: center;">
            <form action="/" method="get" class="home-page-title-search-box mr-3">
                <div class="input-group text-start" style="height:100%;">
                    <input class="form-control" id="search" name="search" placeholder="نام فروشگاه یا کالا را وارد کنید"
                        autocomplete="off" type="search" <?php
                                                            if (isset($_GET['search'])) {
                                                                echo 'value=' . $_GET['search'] . '';
                                                            }
                                                            ?>
                        @php
                        if (!isset($_GET['search'])) {
                        echo 'autofocus' ;
                        } @endphp>
                    <button type="submit" id="btn-search" class="btn-theme input-group-text"
                        style="border-radius: 8px 0 0 8px;"><i class="fa-solid fa-magnifying-glass mx-2"></i></button>
                    <div id="result" class="row search-list" style="margin-top: 50px;">
                        <div id="memList" style="padding-right:0;width:457px;">

                        </div>
                    </div>
                </div>

                <script>
                    $(document).ready(function() {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $('#search').keyup(function() {
                            var search = document.getElementById("search").value;
                            if (search == "") {
                                $("#memList").html("");
                                $('#result').hide();
                            } else {
                                $.get("{{ URL::to('search') }}", {
                                    search: search
                                }, function(data) {
                                    $('#memList').empty().html(data);
                                    $('#result').show();
                                })
                            }
                        });
                    });
                </script>
            </form>
        </div>
                    
        <div class="top-products-area py-3" id="shops">
            <div class="container">
                <div dir="ltr" class="row g-2">
                    @if (count($shops) === 0)
                    <p style="direction: rtl; font-size: 1.7em; text-align: center;">هنوز فروشگاهی ثبت نشده است!</p>
                    @endif

                    @foreach ($shops as $index => $shop)
                    <div class="col-md-3 {{ ($index % 3 == 1) ? 'col-md-6' : '' }}">
                        <div class="single-vendor-wrap p-4 bg-img bg-overlay"
                            style="background-image: url('{{ $shop->cover }}');">
                            <h5 class="vendor-title text-white">{{ $shop->name }}</h5>
                            <div class="vendor-info">
                                <p class="mb-1 text-white">
                                    {{ $shop->address }}
                                    <i class="fa-solid fa-location-dot me-1"></i>
                                </p>
                            </div>

                            <!-- دکمه‌ها در یک بلوک جدا -->
                            <div class="vendor-buttons mt-3 d-flex flex-column gap-2">
                                <a class="btn home-page-btn btn-sm " href="/shop/{{ $shop->slug_code }}">
                                    <i class="fa-solid fa-arrow-left-long ms-1"></i> مشاهده فروشگاه
                                </a>
                                @if ($shop->sale_type == 1)             
                                <a class="btn home-page-btn-secondary btn-sm " href="/buy/{{ $shop->slug_code }}">
                                    <i class="fa-solid fa-cart-shopping ms-1"></i> خرید از فروشگاه
                                </a>
                                @endif
                            </div>

                            <div class="vendor-profile shadow">
                                <figure class="m-0">
                                    <img src="{{ $shop->icon }}" alt="">
                                </figure>
                            </div>
                            <!-- <a class="single-vendor-link" href="/shop/{{ $shop->slug_code }}"> </a> -->
                        </div>

                    </div>
                    @endforeach

                    @if ($user && $user->id == 766 || $user && $user->id == 1073 || $user && $user->id == 1387 )
                    <div class="col-md-6 ">
                        <div class="single-vendor-wrap p-4 bg-img bg-overlay"
                            style="background-image: url('{{ $shop->cover }}');">
                            <h5 class="vendor-title text-white">{{ $shop_test->name }}</h5>
                            <div class="vendor-info">
                                <p class="mb-1 text-white">
                                    {{ $shop_test->address }}
                                    <i class="fa-solid fa-location-dot me-1"></i>
                                </p>
                            </div>

                            <!-- دکمه‌ها در یک بلوک جدا -->
                            <div class="vendor-buttons mt-3 d-flex flex-column gap-2">
                                <a class="btn home-page-btn btn-sm " href="/shop/{{ $shop_test->slug_code }}">
                                    <i class="fa-solid fa-arrow-left-long ms-1"></i> مشاهده فروشگاه
                                </a>
                                <a class="btn home-page-btn-secondary btn-sm " href="/buy/{{ $shop_test->slug_code }}">
                                    <i class="fa-solid fa-cart-shopping ms-1"></i> خرید از فروشگاه
                                </a>
                            </div>

                            <div class="vendor-profile shadow">
                                <figure class="m-0">
                                    <img src="{{ $shop_test->icon }}" alt="">
                                </figure>
                            </div>
                            <!-- <a class="single-vendor-link" href="/shop/{{ $shop->slug_code }}"> </a> -->
                        </div>

                    </div>
                    @endif

                </div>
            </div>
        </div>
        <!-- pagination -->
        <div class="container mt-4 ">
            <ul class="pagination justify-content-center">
                {!! $shops->links('vendor.pagination.bootstrap-5') !!}
            </ul>
        </div>

        <br>

    </div>
</div>
@if (isset($_GET['search']))
<script>
    window.location.href = "#shops";
</script>
@endif
<!--<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.6.8/dist/js/splide.min.js"></script>-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var splide = new Splide('#image-slider', {
            type: 'loop',
            height: '300px', // ← اینو اضافه کن
            pagination: false,
            arrows: true,
            autoplay: true,
            interval: 3000,
        });



        splide.mount();
    });
</script>

@endsection