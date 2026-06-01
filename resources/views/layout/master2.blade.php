<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    @include('layout.head22')
    @yield('head')
    <style>
        #backButton {
            left: 0;
            position: absolute;
            
            margin-left: 10px;
            border-radius: 50%;
            border: 2px solid;
            border-color: #f43f5e;
            width: 50px;
            /* یا هر سایز دلخواه */
            height: 50px;
            /* باید با عرض یکی باشد */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            /* حذف padding اضافه */
        }

        /* برای اطمینان از اینکه آیکون در مرکز قرار می‌گیرد */
        #backButton span {
            color: #f43f5e;
            font-size: 40px;
            /* سایز آیکون */
        }
        .dropdown-sidbar a:hover{
            background-color: gainsboro;
        }
    </style>
    <link href="https://lib.arvancloud.ir/font-awesome/6.3.0/css/all.css" rel="stylesheet">
</head>

<body>
    <!-- Sidebar -->
  

    <!-- Main Content -->
    <main class="main-content" style="background-color: #F8eeeF;">

        <!-- Top Header -->
        <header class="top-header">
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>


            <div class="tit">
                <h4>
                
                </h4>
            </div>

            <!-- <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="جستجو...">
            </div>

            <div class="header-actions">
                <button class="header-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-dot"></span>
                </button>
                <button class="header-btn">
                    <i class="fas fa-envelope"></i>
                </button>
                <button class="header-btn">
                    <i class="fas fa-user-circle"></i>
                </button>
            </div> -->
        </header>

        <!-- Dashboard Content -->
        <div class="dashboard-content" style="background-color: #F8eeeF;">
            <div class="page-title">
                <div class="row">
                    <div class="col-md-6">
                        <h1>@yield('onvan')</h1>
                        <p>خوش آمدید به پنل مدیریت</p>
                    </div>
                    <div class="col-md-6">
                        @yield('back')
                    </div>
                </div>
            </div>
            @yield('main')
        </div>
    </main>

    <!-- Overlay for mobile -->
    <div class="overlay" id="overlay"></div>

    <script src="{{ asset('script/script.js') }}"></script>
    @yield('script')
</body>

</html>