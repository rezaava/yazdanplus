<style>
    .btn_red {
        background-color: #9f0415;
        color: #fefefe;
    }

    .btn_red:hover {
        background-color: #ff2130;
        color: #fefefe;

    }

   
</style>

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

<div class="header-area" id="headerArea">
    <div class="container h-100 d-flex align-items-center justify-content-between d-flex rtl-flex-d-row-r">
        <!-- Logo Wrapper -->
        <div class="logo-wrapper"><a href="/"><img src="{{ asset('img/core-img/aneto-logo2.png') }}"
                    id="top-menu-logo" alt="aniato"></a>
                </div>
            @if ($user)
            @if (!$user->hasRole('admin') && !$user->hasRole('shop_admin') && !$user->hasRole('shop_user'))
            <div class="logo-wrapper2" style="width: 190px;text-align: center;margin-right: 10px;">
            <a class="text-muted top-menu-a wwallet @yield('wallet')" href="/wallet"><i
            class="fa-solid fa-wallet"></i>
            اعتبار
                @if ($user->wallet > 0)
                <span class="rounded bg-danger text-white p-1 small price"
                style="font-size: 10px">{{ $user->wallet }} ریال</span>
                @endif
                
            </a>
            </div>
            @endif
            @endif
            
        <div id="top-menu" class="m-2" style="width: 100%;">
            <a class="text-muted top-menu-a mx-1 @yield('home')" href="/"><i
                    class="fa-solid fa-house mx-1 "></i>خانه</a>

            @yield('shop')


            @if ($user)




            @if (!$user->hasRole('admin') && !$user->hasRole('shop_admin') && !$user->hasRole('shop_user'))
            <a class="text-muted top-menu-a mx-1 @yield('order')" href="/orders"><i
                    class="fa-solid fa-shopping-cart"></i>خرید ها</a>

            <a class="text-muted top-menu-a mx-1 " href="/mablagh_ghest">
                <i class="fa-solid fa-money-bill"></i>
                مبلغ قسط این ماه: {{ $totalMonthlySum }} ریال
            </a>


            @endif




            </a>
            @if ($user->hasRole(['marketer', 'content_manager', 'shop_admin', 'admin','shop_user']))
            <a class="text-muted top-menu-a mx-1 @yield('manage')" href="/admin/dashboard"><i
                    class="fa-solid fa-address-card"></i>
                مدیریت </a>
            <a class="text-muted top-menu-a mx-1 @yield('manage')" href="/admin/list/sale"><i class="fas fa-receipt"></i>
                لیست فروش </a>
            @endif

            @endif

    
        </div>
        <!-- <div class="mx-2" style="display: inline-block; width: 80%;">
            <form action="/" method="get" style='position:sticky;top: 5px;'>
                <div class="input-group text-start">
                    <input class="form-control" style="height: 36px;" id="search" name="search"
                        placeholder="انتخاب فروشگاه" autocomplete="off" type="search">
                    <button type="submit" id="btn-search" style="border-bottom-left-radius: 5px;border-top-left-radius: 5px;" class="btn-theme input-group-text"><i
                            class="fa-solid fa-magnifying-glass mx-2"></i></button>
                </div>
                <div id="result" class="row search-list" style="display:none;">
                    <div id="memList">

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
        </div> -->
        <div class="navbar-support d-flex align-items-center gap-2 px-1 py-1" 
     style="border: 2px solid #dc3545; border-radius: 50px; background: #fff5f5; transition: all 0.3s ease;"
     onmouseover="this.style.background='#fff0f0'; this.style.borderColor='#b02a37';"
     onmouseout="this.style.background='#fff5f5'; this.style.borderColor='#dc3545';">
    
     
     <div class="support-info">
         <a href="tel:09103377432" class="support-number fw-bold text-dark text-decoration-none" 
         style="letter-spacing: 0.5px; direction: ltr; unicode-bidi: bidi-override;font-size: 14px;">
         ۰۹۱۰-۳۳۷-۷۴۳۲
        </a>
    </div>
    <i class="fas fa-phone-alt text-danger" style="font-size: 14px;"></i>
</div>

        <div class="navbar-logo-container d-flex align-items-center">
            <!-- User Profile Icon -->
            @if ($user == null)
            <a href="/login" class="btn home-page-btn btn-lg " style='padding:40px;width:70%;'>ورود </a>
            @else
            <div class="user-profile-icon ms-2">
                <a href="/profile"><img
                        @if ($profile==null) src="#" @else src='{{ asset("$profile->address") }}' @endif
                        alt="profile"></a>


            </div>
            @endif
            <!-- Navbar Toggler -->
            <div class="suha-navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="#suhaOffcanvas"
                aria-controls="suhaOffcanvas">
                <div><span></span><span></span><span></span></div>
            </div>
        </div>
    </div>

    <script>
        const prices = document.getElementsByClassName("price");
        for (let i = 0; i < prices.length; i++) {
            var item = prices[i];
            item.innerHTML = separate(item.innerHTML);
        }
    </script>
</div>