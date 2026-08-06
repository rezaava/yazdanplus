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
    <!-- Products -->
    <div class="row mt-5 mb-2">

        <div class="col-md-6">
            <form action="/" method="get" class="home-page-title-search-box mr-3">
                <div class="input-group text-start" style="height:100%;">
                    <input class="form-control" id="search" name="search" placeholder="انتخاب فروشگاه"
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
        <div class="col-md-6">
            <!--             
             <select id="filter" class="form-select mt-2">
                <option selected>دسته بندی</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
              </select>
             <select id="filter" class="form-select mt-2">
                <option selected>دسته بندی</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
              </select>
             <select id="filter" class="form-select mt-2">
                <option selected>دسته بندی</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
              </select>
            </div> -->
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