@extends('admin.layout.master')

@section('onvan')
گزارش فروشگاه {{ $shop->name ?? '' }}
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
    <h4 class=" mb-4 text-success fw-bold">📊 گزارش فروشگاه {{ $shop->name ?? '' }}</h4>

    <!-- فرم فیلتر -->
    <form method="GET" action="{{ url('/admin/shops/report_g/' . $shop->id) }}" class="row g-3">
      <div class="col-md-5">
        <label for="from_date" class="form-label">از ماه</label>
        <input type="text" id="from_date" name="from_date" class="form-control"
          placeholder="مثلاً 1404-01" required autocomplete="off">
      </div>
      <div class="col-md-5">
        <label for="to_date" class="form-label">تا ماه</label>
        <input type="text" id="to_date" name="to_date" class="form-control"
          placeholder="مثلاً 1404-08" required autocomplete="off">
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn w-100" style="background-color: #FFD8D8;">نمایش گزارش</button>
      </div>
    </form>

    <!-- نمایش خطا -->
    @if(session('error'))
    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    <!-- جدول گزارش -->
    @if(!empty($report))
    <p dir="rtl">
        گزارش از 
        {{request('from_date')}}
        تا
        {{request('to_date')}}
    </p>
    
    <div class="table-responsive mt-4">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-success">
          <tr>
            <th>ماه</th>
            <th>کل فروش</th>
            <th>کل تسویه</th>
            <th>تخفیف</th>
            <th>باقی‌مانده</th>
          </tr>
        </thead>
        <tbody>
          @foreach($report as $item)
          <tr @if($item['month']==='جمع کل' ) class="table-success fw-bold" @endif>
            <td>{{ $item['month'] }}</td>
            <td>{{ number_format($item['total_sales']) }} ریال</td>
            <td>{{ number_format($item['total_payments']) }} ریال</td>
            <td>{{ number_format($item['total_payments_off']) }} ریال</td>
            <td>{{ number_format($item['remaining']) }} ریال</td>
          </tr>
          @endforeach
        </tbody>

      </table>
    </div>
    @elseif(request()->filled(['from_date','to_date']))
    <div class="alert alert-warning mt-4">هیچ اطلاعاتی برای بازه‌ی انتخاب‌شده یافت نشد.</div>
    @endif
  </div>
</div>
@if(!empty($report))
<div class="mt-3 text-end">
  <form action="{{ route('shops.report_g.excel', $shop->id) }}" method="GET">
    <input type="hidden" name="from_date" value="{{ request('from_date') }}">
    <input type="hidden" name="to_date" value="{{ request('to_date') }}">
    <button type="submit" class="btn" style="background-color: #FFD8D8;">
      📥 دانلود Excel گزارش
    </button>
  </form>
</div>
@endif




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