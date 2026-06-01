@extends('admin.layout.master')

@section('onvan')
لیست اقساط کاربران 
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('dashboard/persian-datepicker.min.css') }}" />
<script src="{{ asset('dashboard/jquery.js') }}"></script>
<script src="{{ asset('dashboard/persian-date.min.js') }}"></script>
<script src="{{ asset('dashboard/persian-datepicker.min.js') }}"></script>
<style>
  #backButton{
    position: fixed !important;
    top: 100px;
    z-index: 999;
  }
</style>
@endsection

@section('back')
<a href="/admin/dashboard" id="backButton" class="btn  btn-lg">
    <span>
        ←
    </span>
</a>
@endsection

@section('main')
<div class="container">
  <div class="card p-4">
    <h4 class=" mb-4 fw-bold">👥 لیست اقساط</h4>

    <!-- فرم فیلتر -->
    <form method="POST" action="/admin/users/trans/excel" class="row g-3">
      @csrf
      <div class="col-md-5">
        <label for="from_date" class="form-label">انتخاب ماه و سال</label>
        <input type="text" id="from_date" name="from_date" class="form-control"
          placeholder="مثلاً 1404-01" required autocomplete="off">
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn w-100" style="background-color: #FFD8D8;">دانلود اکسل</button>
      </div>
    </form>

    <!-- نمایش خطا -->
    @if(session('error'))
    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    
  </div>
</div>
@endsection

@section('script')

 <script>
  $(document).ready(function() {

    // 🔹 تبدیل اعداد فارسی به انگلیسی
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

    // 🔹 دیت‌پیکر برای فیلد "از ماه"
    $("#from_date").persianDatepicker({
      format: 'YYYY/MM/DD',
      initialValue: true,
      autoClose: true,
      onSelect: function(unix) {
        const date = new persianDate(unix);
        const year = date.year();
        const month = date.month();
        const formatted = year + '-' + (month < 10 ? '0' + month : month);
        $('#from_date').val(formatted);
      }
    });

    // 🔹 دیت‌پیکر برای فیلد "تا ماه"
    $("#to_date").persianDatepicker({
      format: 'YYYY/MM/DD',
      initialValue: true,
      autoClose: true,
      onSelect: function(unix) {
        const date = new persianDate(unix);
        const year = date.year();
        const month = date.month();
        const formatted = year + '-' + (month < 10 ? '0' + month : month);
        $('#to_date').val(formatted);
      }
    });

    // 🔹 تضمین انگلیسی بودن اعداد در زمان تایپ یا ارسال
    $("#from_date, #to_date").on('change keyup', function() {
      $(this).val(persianToEnglishDigits($(this).val()));
    });

    $("form").on('submit', function() {
      $("#from_date, #to_date").each(function() {
        $(this).val(persianToEnglishDigits($(this).val()));
      });
    });

  });
</script>
@endsection