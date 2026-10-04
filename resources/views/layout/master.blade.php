<!DOCTYPE html>
<html lang="fa">

<head>
    @include('layout/meta')
    <!-- The above tags *must* come first in the head, any other head content must come *after* these tags -->
    <!-- Title -->
    <title>
        یزدان پلاس
        @yield('title')
    </title>
    
    
    <!-- Bootstrap CSS -->
<!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">-->

<!-- Bootstrap JS (شامل Popper داخلی) -->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>-->

   
    <!--<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>-->
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">-->

    <!--<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>-->
    <!-- <script src="https://lib.arvancloud.ir/sweetalert/2.1.2/sweetalert.min.js"></script> -->

    <!-- CSS -->
   

    <!-- jQuery -->
    
    <script src="https://lib.arvancloud.ir/jquery/3.6.3/jquery.js"></script>

   



    <!--<link rel="preconnect" href="https://fonts.googleapis.com/">-->
    <!--<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>-->
    <!--<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&amp;display=swap"-->
    <!--    rel="stylesheet">-->
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('/img/icons/anetoLogoRed.png') }}">
    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" href="{{ asset('/img/icons/anetoLogoRed.png') }}">
    <link rel="apple-touch-icon" sizes="352x352" href="{{ asset('/img/icons/anetoLogoRed.png') }}">
    <link rel="apple-touch-icon" sizes="367x367" href="{{ asset('/img/icons/anetoLogoRed.png') }}">
    <link rel="apple-touch-icon" sizes="480x480" href="{{ asset('/img/icons/anetoLogoRed.png') }}">


        <!-- PWA Manifest -->
        <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <meta name="theme-color" content="#e30613">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="یزدان پلاس">

    <!-- CSS Libraries -->
    @include('layout/csslibraries')
    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('/style.css') }}">


    <!-- Web App Manifest -->
    <!-- <link rel="manifest" href="{{ asset('/manifest.json') }}"> -->

    <!-- <link href="{{ asset('dashboard/date/css/normalize.css') }}" rel="stylesheet" /> -->
    <!-- <link href="{{ asset('dashboard/date/css/prism.css') }}" rel="stylesheet" /> -->
  

    <!-- <script src="{{ asset('dashboard/date/js/prism.js') }}"></script> -->
    <!-- <script src="{{ asset('dashboard/date/js/vertical-responsive-menu.min.js') }}"></script> -->
<!-- 
   -->



    <link href="https://lib.arvancloud.ir/font-awesome/6.3.0/css/all.css" rel="stylesheet">
<style>/* footer */
.footer {
    padding-right: 60px;
    background-color: #fff;
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
}

/* تغییر رنگ آیکون‌ها در ستون "راه‌های ارتباطی" */
.footer .col-lg-3 .fa {
    color: black !important;  /* تغییر رنگ آیکون‌ها به مشکی */
}

.title-footer {
    /* padding-right: 40px; */
    text-align: right;
    color: #e30613;
}

.footer-link {
    color: black;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-link:hover {
    color: #e30613;
}
.li-fo{
    text-align: right;
    color: black;
    width: max-content;
}
.social-icon {
    width: 35px;
    height: 35px;
    line-height: 37px;
    border-radius: 50%;
    text-align: center;
    color: #fff;
    display: inline-block;
    margin: 0 8px;
    transition: all 0.3s ease;
}

.social-icon:hover {
    transform: translateY(-3px);
    color: #fff;
}

.logo-footer {
    width: 180px;
    padding-right: 35px;
}

@media (max-width:768px) {
    .logo{
        width: 100%;
        text-align: center;
    }
    .logo-footer {
    padding-right: 0;
}
    .social-links{
        text-align: center;
    }
    .title-footer {
    /* padding-right: 40px; */
    text-align: center;
    }
    .li-fo{
    text-align: center;
    width: auto !important;
}
.footer{
    padding-right: 0;
}
.p-us{
    text-align: center;
}
}
</style>
@yield('style')
</head>

<body>
    <!-- Preloader-->
    <!--@include('layout.preloader')-->
    <!-- Header Area -->
    @include('layout.header')
    @include('layout.sidenav')

    @yield('content')


    <!-- footer -->
    @include('layout.footer')

    <!-- Internet Connection Status -->
    {{--  @include('layout.connection') --}}

    <!-- Footer Nav-->
    @include('layout.bottomnav')
    <!-- All JavaScript Files-->
    @include('layout.script')
    @yield('scripts')


        <!-- PWA Install Button -->
       {{--  <div id="pwaInstallContainer" style="display: none; position: fixed; bottom: 20px; left: 20px; z-index: 9999;">
        <button id="pwaInstallButton" style="background: linear-gradient(135deg, #e30613, #b0050f); color: white; border: none; padding: 12px 24px; border-radius: 50px; font-size: 16px; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.2); font-weight: bold; font-family: inherit;">
            📱 نصب برنامه روی صفحه اصلی
        </button>
    </div>

    <!-- PWA Service Worker Registration -->
    <script>
        // ثبت سرویس ورکر
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('Service Worker registered successfully:', registration.scope);
                    })
                    .catch(function(error) {
                        console.log('Service Worker registration failed:', error);
                    });
            });
        }
        
        // مدیریت دکمه نصب
        let deferredPrompt;
        const installContainer = document.getElementById('pwaInstallContainer');
        const installButton = document.getElementById('pwaInstallButton');
        
        window.addEventListener('beforeinstallprompt', function(e) {
            // جلوگیری از نمایش خودکار
            e.preventDefault();
            // ذخیره رویداد
            deferredPrompt = e;
            // نمایش دکمه
            if (installContainer) {
                installContainer.style.display = 'block';
            }
        });
        
        if (installButton) {
            installButton.addEventListener('click', async function() {
                if (!deferredPrompt) return;
                
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`نصب برنامه: ${outcome}`);
                
                deferredPrompt = null;
                installContainer.style.display = 'none';
            });
        }
        
        // وقتی برنامه نصب شد
        window.addEventListener('appinstalled', function() {
            console.log('برنامه با موفقیت نصب شد');
            // می‌تونی یه نوتیفیکیشن کوچیک نشون بدی
        });
    </script> --}}
</body>

</html>
