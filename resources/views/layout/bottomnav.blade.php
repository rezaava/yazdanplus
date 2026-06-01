<div class="footer-nav-area" id="footerNav">
    <div class="suha-footer-nav">
      <ul class="h-100 d-flex align-items-center justify-content-between ps-0 d-flex rtl-flex-d-row-r">
        <li ><a href="/"><i class="fa-solid fa-house"></i>خانه</a></li>
        @if (Auth::user())
        
        @if(Auth::user()->hasRole('user'))
        <li><a href="/orders"><i class="fa-solid fa-shopping-cart"></i>خرید ها</a></li>
        @endif
        @if (Auth::user()->hasRole('shop_admin') || Auth::user()->hasRole('shop_user'))
        <li><a href="/admin/list/sale"><i class="fa-solid fa-address-card"></i>لیست فروش</a></li>
        @endif
        <!-- <li  @yield('wallet')><a href="/wallet"><i class="fa-solid fa-wallet"></i> اعتبار 
     
      </a></li> -->
        <!-- <li  @yield('coin')><a href="/coin"><i class="fa-solid fa-money-check-dollar"></i>کویناتو</a></li> -->
        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('shop_admin'))
            <li  ><a href="/admin/dashboard"><i class="fa-solid fa-address-card"></i>مدیریت</a></li>
        @endif

        @endif
       
      </ul>
    </div>
</div>
