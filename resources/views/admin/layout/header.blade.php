<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fa fa-cog" style="margin-left: 10px;"></i>
            <span>پنل مدیریت</span>
        </div>
        <button class="close-btn" id="closeSidebar" style="font-size: larger;">
            &#10006;
        </button>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li class="active">
                <a href="/admin/dashboard">
                    <i class="fas fa-chart-line"></i>
                    <span>داشبورد</span>
                </a>
            </li>

            <!-- Dropdown Menu Item -->
            @if(Auth::user()->hasRole('admin'))
            <li class="dropdown-sidbar">
                <a href="#" class="dropdown-toggle-sidbar">
                    <i class="fas fa-users-gear"></i>
                    <span>کاربران</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </a>
                <ul class="dropdown-menu-sidbar">
                    <li><a href="/admin/users"><i class="fas fa-users"></i> لیست کاربران</a></li>
                    <li><a href="/admin/users/trans"><i class="fas fa-users"></i> گزارش اقساط کاربران</a></li>
                    <li><a href="/admin/users/add/user"><i class="fas fa-user-plus"></i> افزودن کاربر</a></li>
                    <!-- <li><a href="/admin/users/employee/"><i class="fas fa-list"></i> لیست کارمندان</a></li> -->
                    <li><a href="/admin/users/employee/add/"><i class="fas fa-user-check"></i> افزودن کارمند </a></li>
                    <li><a href="/admin/dateoftasvieh"><i class="fas fa-hand-holding-usd"></i>تسویه ها</a></li>
                </ul>
            </li>
            @endif
            <!-- Another Dropdown -->
            <li class="dropdown-sidbar">
                <a href="#" class="dropdown-toggle-sidbar">
                    <i class="fas fa-shopping-cart"></i>
                    <span>مشتریان</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </a>
                <ul class="dropdown-menu-sidbar">
                    <li><a href="/admin/list/sale"><i class="fas fa-receipt"></i> لیست فروش</a></li>
                    @if(Auth::user()->hasRole('admin'))
                    <li><a href="/admin/list/sale_datis"><i class="fas fa-receipt"></i> لیست فروش داتیس</a></li>
                    <li><a href="/admin/list/sale_datis2"><i class="fas fa-receipt"></i> لیست فروش داتیس 2</a></li>
                    <li><a href="{{ route('sale.arde') }}"><i class="fas fa-receipt"></i> لیست فروش ارده </a></li>
                    <li><a href="{{ route('sale.honey') }}"><i class="fas fa-receipt"></i> لیست فروش عسل </a></li>
                    <li><a href="{{ route('sale.kerem') }}"><i class="fas fa-receipt"></i> لیست فروش کرم </a></li>
                    <li><a href="{{ route('sale.aroosha') }}"><i class="fas fa-receipt"></i> لیست فروش آروشا </a></li>
                    @endif
                    {{-- <li><a href="#add-product"><i class="fas fa-plus"></i> افزودن محصول</a></li>
                    <li><a href="#categories"><i class="fas fa-tags"></i> دسته‌بندی‌ها</a></li>
                    <li><a href="#inventory"><i class="fas fa-warehouse"></i> موجودی انبار</a></li> --}}
                </ul>
            </li>
            @if(Auth::user()->hasRole('admin'))
            <li class="dropdown-sidbar">
                <a href="/admin/survey">
                    <!-- <i class="fas fa-shopping-cart"></i> -->
                    <i class="fas fa-chart-line"></i>
                    <span>نظر سنجی</span>
                </a>
            </li>
            <li class="dropdown-sidbar">
                <a href="/admin/stock">
                    <!-- <i class="fas fa-shopping-cart"></i> -->
                    <i class="fas fa-chart-line"></i>
                    <span>فراخوان</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('shop_admin'))
            <li class="dropdown-sidbar">
                <a href="#" class="dropdown-toggle-sidbar">
                    <i class="fas fa-store"></i>
                    <span>فروشگاه ها</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </a>
                <ul class="dropdown-menu-sidbar">
                    <li><a href="/admin/shops"><i class="fas fa-warehouse"></i> لیست فروشگاه ها</a></li>
                    @if (Auth::user()->hasRole('admin'))
                    <li><a href="/admin/shops/add"><i class="fas fa-plus-circle"></i> افزودن فروشگاه</a></li>
                    @endif
                    {{-- <li><a href="#categories"><i class="fas fa-tags"></i> دسته‌بندی‌ها</a></li>
                        <li><a href="#inventory"><i class="fas fa-warehouse"></i> موجودی انبار</a></li> --}}
                </ul>
            </li>
            @endif

            @if ($sale_type == 2 || $sale_type == 3 )

            <li class="dropdown-sidbar">
                <a href="/admin/shops/buy">
                    <i class="fas fa-shopping-cart"></i>
                    <span>ثبت خرید</span>

                </a>
            </li>
            @endif

            @if(Auth::user()->hasRole('admin'))
            <li class="dropdown-sidbar">
                <a href="/admin/contacts">
                    <i class="fas fa-envelope"></i>
                    <span>تماس با ما</span>
                </a>
            </li>
            @endif
            <li class="dropdown-sidbar">
                <a href="/">
                    <i class="fas fa-home"></i>
                    <span>خانه</span>

                </a>
            </li>
            <li class="dropdown-sidbar">
                <a href="/logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>خروج</span>

                </a>
            </li>

            <!-- Settings Dropdown -->

            <!-- <li>
                <a href="#messages">
                    <i class="fas fa-envelope"></i>
                    <span>پیام‌ها</span>
                    <span class="badge-sidbar">5</span>
                </a>
            </li> -->
        </ul>
    </nav>
    <div class="sidebar-footer">
        <div class="user-profile">
            <img src="" alt="پروفایل">
            <div class="user-info">
                <h4>{{Auth::user()->name}} {{Auth::user()->family}}</h4>
                <p class="m-0">{{Auth::user()->Roles()->first()->display_name}}</p>
            </div>
        </div>
        <!--<a href="/logout">-->
        <!--<button class="logout-btn">-->
        <!--    <i class="fas fa-sign-out-alt"></i>-->
        <!--</button>-->
        <!--</a>-->
    </div>
</aside>