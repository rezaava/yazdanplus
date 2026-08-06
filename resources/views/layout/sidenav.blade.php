<div class="offcanvas offcanvas-start suha-offcanvas-wrap" tabindex="-1" id="suhaOffcanvas"
  aria-labelledby="suhaOffcanvasLabel">
  <!-- Close button-->
  <button class="btn-close btn-close-white" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  <!-- Offcanvas body-->
  <div class="offcanvas-body">
    @if (!$user == null)
    <!-- Sidenav Profile-->
    <div class="sidenav-profile">
      <a href="/profile">
        <div class="user-profile"><img
            @if ($profile==null) src="#" @else src='{{ asset("$profile->address") }}' @endif
            alt="profile"></div>

      </a>
      <a href="/profile" style="display: block;color: white;text-align: center;">

        ویرایش پروفایل

      </a>

      <div class="user-info">
        <h5 class="user-name mb-1 text-white">{{ $user->name }} {{ $user->family }}</h5>
        <!-- <p class="available-balance text-white"> موجودی <span
                            class="counter price">{{number_format($user->wallet) }}</span> <span>{{ $MONEY_SIGN }}</span> </p> -->
      </div>
    </div>
    @endif
    <!-- Sidenav Nav-->
    <ul class="sidenav-nav ps-0">
      @if ($user == null)
      <li><a href="/login"><i class="fa-solid fa-toggle-off"></i>ورود</a></li>
      @endif
      @if (!$user == null)

      @if($user->hasRole(['shop_admin','admin']))
      <li><a href="/admin/dashboard"><i class="fa-solid fa-address-card"></i>پنل مدیریت</a></li>
      @endif
      @if (!$user->hasRole('admin') && !$user->hasRole('shop_admin'))
      <li><a href="wallet"><i class="fa-solid fa-wallet"></i>
       اعتبار
       :
       <span class="rounded  p-1  price"
                                style="font-size: 14px;color: red !important;background-color: #fff;">{{  number_format($user->wallet)  }}
                              ریال
                              </span>

      </a></li>
      <li>
            <a href="/mablagh_ghest" style="font-size: 13px;">
                <i class="fa-solid fa-money-bill"></i>
                 قسط این ماه: {{ $totalMonthlySum }} ریال
            </a></li>
      @endif
      <!-- <li><a href="/shop-card"><i class="fa-solid fa-credit-card"></i>شماره کارت</a></li> -->
      @if ($user)
      <li><a href="/orders"><i class="fa-solid fa-shopping-cart"></i>خرید ها</a></li>
      @endif
      @if ($user->hasRole('user') || $user->hasRole('shop_user'))
      <li><a href="/orders"><i class="fa-solid fa-shopping-cart"></i>خرید ها</a></li>
      @endif
      {{-- <li><a href="/transactions"><i class="fa-solid fa-list"></i>گردش حساب</a></li> --}}
      <li><a href="/reportform">
          <i class="fa-solid fa-file-text"></i>گردش حساب
        </a>
      </li>
       @if ($user->hasRole('shop_admin'))  
       {{-- <li><a href="/sharayet">
          <i class="fa-solid fa-file-text"></i>شرایط ویژه
        </a>
      </li> --}}
      @endif
      @endif
      <!-- <li><a href="/shop-registration"><i class="fa-solid fa-shop"></i>فروشنده شو</a></li> -->
      <li><a href="/about-us"><i class="fa-solid fa-file-text"></i>درباره ما</a></li>
      <li><a href="/contact"><i class="fab fa-teamspeak fa-lg" style="font-size: 1.1rem;"></i>ارتباط با ما</a>
      </li>
      @if (!$user == null)
      <li><a href="/logout"><i class="fa-solid fa-toggle-off"></i>خروج از سیستم</a></li>
      @endif
    </ul>
  </div>
  {{-- <!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<button onclick="reportAlert()">باز کردن هشدار</button>

<script>
  function reportAlert() {
    Swal.fire({
      title: 'گزارش گیری',
      html: `<input id="reportInput" class="swal2-input" placeholder="نام گزارش...">`,
      showCancelButton: false,
      confirmButtonText: 'دانلود',
      customClass: {
        confirmButton: 'swal2-download-btn'
      },
      didOpen: () => {
        // فوکوس روی input وقتی پنجره باز شد
        const input = document.getElementById('reportInput');
        input.focus();
        // اگر لازم بود، این خط رو اضافه کن برای فعال کردن انتخاب متن input:
        input.select();
      },
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        const val = document.getElementById('reportInput').value;
        if(!val) {
          Swal.fire('خطا', 'لطفا نام گزارش را وارد کنید', 'error');
          return;
        }
        // عملیات دانلود یا هر کاری که میخوای انجام بده
        console.log('گزارش:', val);
        // window.location.href = 'https://example.com/file.zip';
      }
    });
  }
</script>

<style>
  .swal2-download-btn {
    background-color: #28a745 !important;
    color: white !important;
    padding: 10px 25px;
    font-size: 16px;
  }
</style> --}}


</div>

<script>
  const prices = document.getElementsByClassName("price");
  for (let i = 0; i < prices.length; i++) {
    var item = prices[i];
    item.innerHTML = separate(item.innerHTML);
  }
</script>