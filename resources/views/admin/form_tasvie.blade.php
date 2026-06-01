@extends('admin.layout.master')

@section('onvan')
تسویه فروشگاه {{ $shop->name }}
@endsection

@section('head')
<script src="{{ asset('dashboard/jquery.js') }}"></script>
<link href="{{ asset('dashboard/persian-datepicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('dashboard/persian-date.min.js') }}"></script>
<script src="{{ asset('dashboard/persian-datepicker.min.js') }}"></script>

@endsection

@section('main')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      <div class="card p-4">
        <h5 class="mb-4 text-center fw-bold text-danger">فرم ثبت تسویه فروشگاه</h5>
        @if(Session::has('suc'))
        <div class="alert alert-success" role="alert">
          <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
            <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"></path>
            <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"></path>
          </svg>
          {{ Session::get('suc') }}
        </div>
        @endif
        <form action="/admin/shops/factor_tasvie/{{ $shop->id }}" method="post">
          @csrf

          <div class="mb-3">
            <label for="monthYear" class="form-label">ماه و سال</label>
            <input type="text" id="monthYear" name="month_year" class="form-control"
            required autocomplete="off" placeholder="انتخاب تاریخ">
          </div>

          <div class="mb-3">
            <label class="form-label">کل حساب</label>
            <input type="text" id="dd" class="form-control" placeholder="کل" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label">مانده حساب</label>
            <input type="text" id="remaining" class="form-control" placeholder="باقی‌مانده" readonly>
          </div>

          <div class="mb-3">
            <label for="price" class="form-label">مبلغ</label>
            <input type="text" id="price" name="price" class="form-control" required placeholder="مبلغ را وارد کنید">
          </div>

          <div class="mb-3">
            <label for="date" class="form-label">تاریخ</label>
            <input type="text" id="date" name="date" class="form-control" required placeholder="تاریخ را انتخاب کنید" readonly>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">توضیحات</label>
            <textarea id="description" name="description" rows="3" class="form-control"></textarea>
          </div>

          <input type="hidden" id="shop_id" name="shop_id" value="{{ $shop->id }}">
          <input type="hidden" id="delay" name="delay">

         <!-- تغییر دکمه به این شکل -->
          <button type="button" id="rizOrderBtn" class="btn w-100" style="background-color: #FFD8D8;">ریز خرید ها</button>
          <button type="submit" class="btn w-100 mt-2" style="background-color: #FFD8D8;">ثبت</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  // دکمه ریز خرید ها
$("#rizOrderBtn").on('click', function() {
    let monthYear = $("#monthYear").val();
    
    if (!monthYear) {
        alert('لطفا ابتدا ماه و سال را انتخاب کنید');
        return false;
    }
    
    // ساخت آدرس با پارامتر ماه و سال
    let url = "/admin/shops/tasvie_riz_order/" + $("#shop_id").val() + "?month_year=" + monthYear;
    
    // هدایت به آدرس مورد نظر
    window.location.href = url;
});
</script>
<script>
  $(document).ready(function() {

    // 🔹 تبدیل فارسی به انگلیسی
    function persianToEnglishDigits(str) {
      if (!str) return '';
      const persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
      const arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
      for (let i = 0; i < 10; i++) {
        str = str.replace(new RegExp(persianNumbers[i], 'g'), i)
          .replace(new RegExp(arabicNumbers[i], 'g'), i);
      }
      return str;
    }

    // 🔹 فرمت سه‌رقمی (افزودن کاما)
    function numberFormat(num) {
      num = persianToEnglishDigits(num.toString());
      return num.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // 🔹 دیت‌پیکر انتخاب ماه و سال
    $("#monthYear").persianDatepicker({
      format: 'YYYY/MM/DD',
      initialValue: false,
      autoClose: true,
      onSelect: function(unix) {
        const date = new persianDate(unix);
        const year = date.year();
        const month = date.month();
        const formatted = year + '-' + (month < 10 ? '0' + month : month);
        $('#monthYear').val(formatted);
        fetchMonthSummary(formatted);
      }
    });

    // 🔹 دیت‌پیکر تاریخ پرداخت
    $("#date").persianDatepicker({
      format: 'YYYY/MM/DD',
      initialValue: false,
      autoClose: true,
      toolbox: {
        calendarSwitch: {
          enabled: false
        }
      }
    });

    // 🔹 دریافت اطلاعات از سرور
    function fetchMonthSummary(monthYear) {
      $.ajax({
        url: '/shops/get-month-summary',
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          month_year: monthYear,
          shop_id: $('#shop_id').val()
        },
        success: function(res) {
          if (res.success) {

            // 🔥 سه‌رقمی کردن کل حساب و مانده حساب
            $("#dd").val(numberFormat(res.total_sales));
            $("#remaining").val(numberFormat(res.remaining));

            // برای چک کردن محدودیت برداشت
            $("#price").attr('data-remaining', res.remaining);
          } else {
            alert(res.message || 'خطا در محاسبه');
          }
        },
        error: function(xhr) {
          console.error('AJAX Error:', xhr.responseText);
          alert('خطا در دریافت اطلاعات از سرور');
        }
      });
    }

    // 🔹 سه‌رقمی کردن و چک محدودیت مبلغ
    $("#price").on('input', function() {

      let val = persianToEnglishDigits($(this).val().replace(/,/g, ""));
      val = val.replace(/\D/g, ""); // فقط عدد، بدون حروف

      if (val) {
        $(this).val(numberFormat(val)); // سه‌رقمی
      }

      const remaining = parseInt($(this).attr('data-remaining') || 0);
      const entered = parseInt(val) || 0;

      $("#price-warning").remove();

      if (entered > remaining) {
        $(this).addClass('is-invalid');
        $('<small id="price-warning" class="text-danger">مبلغ نباید بیشتر از مانده حساب باشد.</small>')
          .insertAfter($(this));
      } else {
        $(this).removeClass('is-invalid');
      }
    });

  });
</script>

<script>
  $(document).ready(function() {

    // تابع تبدیل اعداد فارسی → انگلیسی
    function p2e(str) {
      const persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
      const arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
      for (let i = 0; i < 10; i++) {
        str = str.replace(new RegExp(persian[i], 'g'), i)
          .replace(new RegExp(arabic[i], 'g'), i);
      }
      return str;
    }

    // موقع SUBMIT شدن فرم
    $("form").on("submit", function(e) {

      let monthYear = $("#monthYear").val(); // مثل 1404-05
      let tasvieDate = $("#date").val(); // مثل 1404/08/20

      if (!monthYear || !tasvieDate) {
        return; // خالی بودند → نمی‌سنجیم
      }

      // فارسی‌زدایی
      monthYear = p2e(monthYear);
      tasvieDate = p2e(tasvieDate);

      // تجزیه تاریخ‌ها
      let [y1, m1] = monthYear.split("-");
      let parts = tasvieDate.split("/");
      let y2 = parts[0];
      let m2 = parts[1];

      y1 = parseInt(y1);
      m1 = parseInt(m1);
      y2 = parseInt(y2);
      m2 = parseInt(m2);

      // محاسبه اختلاف ماه
      let delay = (y2 - y1) * 12 + (m2 - m1);
      if (delay < 0) delay = 0;

      // ذخیره داخل اینپوت hidden
      $("#delay").val(delay);
    });

  });
</script>
@endsection