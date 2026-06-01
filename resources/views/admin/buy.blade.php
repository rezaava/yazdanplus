@extends('admin.layout.master')

@section('onvan')
خرید از فروشگاه {{ $shop->name }}
@endsection

@section('head')
<script src="{{ asset('dashboard/jquery.js') }}"></script>
@endsection

@section('main')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      <div class="card p-4">
        <h5 class="mb-4 text-center fw-bold ">فرم ثبت خرید فروشگاه</h5>
        
        @if(Session::has('suc'))
        <div class="alert alert-success" role="alert">
          <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
            <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"></path>
            <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"></path>
          </svg>
          {{ Session::get('suc') }}
        </div>
        @endif
        
        <form id="buyForm">
          @csrf
          
          <div class="mb-3">
            <label class="form-label">شماره موبایل</label>
            <input type="text" id="mobile" name="mobile" class="form-control" autocomplete="off" placeholder="موبایل" required>
          </div>

          <div class="mb-3">
    <label for="price" class="form-label">مبلغ (ریال)</label>
    <input type="text" id="price" name="price" class="form-control" autocomplete="off" required placeholder="مبلغ را وارد کنید">
    <div id="priceWords" style="display: none;"></div>
</div>

          <button type="button" id="submitBtn" class="btn w-100 mt-2" style="background-color: #FFD8D8;">ثبت</button>
        </form>
        
        <!-- باکس نمایش نتیجه - قشنگ‌تر از پایین فرم -->
        <div id="resultBox" class="mt-4" style="display: none;">
          <div class="card border-success">
            <div class="card-header bg-success text-white">
              <strong>📋 نتیجه ثبت خرید</strong>
            </div>
            <div class="card-body" id="resultContent">
              <!-- اطلاعات ثبت شده اینجا نمایش داده میشه -->
            </div>
          </div>
        </div>
        
        <!-- باکس خطا -->
        <div id="errorBox" class="mt-4" style="display: none;">
          <div class="card border-danger">
            <div class="card-header bg-danger text-white">
              <strong>⚠️ خطا</strong>
            </div>
            <div class="card-body" id="errorContent">
              <!-- خطاها اینجا نمایش داده میشه -->
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
// ذخیره order_id برای استفاده در تایید کد
var currentOrderId = null;

// تابع تبدیل عدد به حروف فارسی
function numberToPersianWords(num) {
    if (num === '' || num === null || num === undefined) return '';
    
    let number = num.toString().replace(/[^0-9]/g, '');
    if (number === '') return '';
    
    let intNumber = parseInt(number, 10);
    
    var ones = ['', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه'];
    var tens = ['', '', 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود'];
    var teens = ['ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده'];
    var hundreds = ['', 'یکصد', 'دویست', 'سیصد', 'چهارصد', 'پانصد', 'ششصد', 'هفتصد', 'هشتصد', 'نهصد'];
    
    function convertLessThanThousand(n) {
        if (n === 0) return '';
        
        var result = '';
        var hundred = Math.floor(n / 100);
        var remainder = n % 100;
        
        if (hundred > 0) {
            result += hundreds[hundred];
            if (remainder > 0) result += ' و ';
        }
        
        if (remainder >= 10 && remainder <= 19) {
            result += teens[remainder - 10];
        } else {
            var ten = Math.floor(remainder / 10);
            var one = remainder % 10;
            
            if (ten > 0) {
                result += tens[ten];
                if (one > 0) result += ' و ';
            }
            
            if (one > 0) {
                result += ones[one];
            }
        }
        
        return result;
    }
    
    function convertToWords(n) {
        if (n === 0) return 'صفر';
        
        var units = ['', 'هزار', 'میلیون', 'میلیارد', 'تریلیون'];
        var result = '';
        var unitIndex = 0;
        
        while (n > 0) {
            var chunk = n % 1000;
            if (chunk > 0) {
                var chunkWords = convertLessThanThousand(chunk);
                if (chunkWords !== '') {
                    result = chunkWords + (units[unitIndex] ? ' ' + units[unitIndex] : '') + (result ? ' و ' + result : '');
                }
            }
            n = Math.floor(n / 1000);
            unitIndex++;
        }
        
        return result;
    }
    
    return convertToWords(intNumber);
}

$(document).ready(function() {
    
    // وقتی کاربر در فیلد مبلغ تایپ میکنه - فقط نمایش حروف، بدون کاما
    $('#price').on('input', function() {
        let value = $(this).val();
        let number = value.replace(/[^0-9]/g, '');
        
        if(number) {
            $(this).val(number); // فقط عدد بدون کاما
            let words = numberToPersianWords(number);
            if(words) {
                $('#priceWords').html(`
                    <div class="alert alert-info mt-2 mb-0 py-2">
                        <strong>📝 ${words} ریال</strong>
                    </div>
                `).show();
            } else {
                $('#priceWords').hide();
            }
        } else {
            $(this).val('');
            $('#priceWords').hide();
        }
    });
    
    $('#submitBtn').on('click', function(e) {
        e.preventDefault();
        
        // گرفتن مقادیر
        var mobile = $('#mobile').val();
        var price = $('#price').val();
        var token = $('input[name="_token"]').val();
        
        // اعتبارسنجی ساده
        if(!mobile) {
            showError('لطفاً شماره موبایل را وارد کنید');
            return;
        }
        
        if(!price || price == 0) {
            showError('لطفاً مبلغ را وارد کنید');
            return;
        }
        
        // غیرفعال کردن دکمه در حین ارسال
        $('#submitBtn').prop('disabled', true).text('در حال ثبت...');
        
        // درخواست ایجکس
        $.ajax({
            url: '/admin/shops/buy/{{ $shop->id }}',
            type: 'POST',
            data: {
                mobile: mobile,
                price: price,
                _token: token
            },
            success: function(response) {
                // ذخیره order_id
                currentOrderId = response.order_id;
                
                // مخفی کردن باکس خطا
                $('#errorBox').hide();
                
                // نمایش باکس نتیجه با فرم تایید کد
                $('#resultBox').show();
                
                // پر کردن باکس نتیجه با فرم تایید کد
                var resultHtml = `
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="alert alert-info text-center">
                                <strong>✅ مرحله اول ثبت شد</strong><br>
                                کد تایید به شماره ${response.karbar.mobile} ارسال شد
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>🆔 شماره سفارش:</strong> ${response.order_id}
                        </div>
                        <div class="col-12 mb-2">
                            <strong>📱 شماره موبایل:</strong> ${response.karbar.mobile}
                        </div>
                        <div class="col-12 mb-2">
                            <strong>💰 مبلغ:</strong> ${number_format(response.price)} ریال
                        </div>
                        <div class="col-12 mb-2">
                            <strong>🏪 فروشگاه:</strong> {{ $shop->name }}
                        </div>
                        <div class="col-12 mb-3">
                            <strong>👤 نام کاربر:</strong> ${response.karbar.name || 'نامشخص'}
                        </div>
                        
                        <hr>
                        
                        <!-- فرم تایید کد -->
                        <div class="col-12 mt-3">
                            <label class="form-label fw-bold">📲 کد تایید</label>
                            <input type="text" id="verifyCode" class="form-control text-center" 
                                   placeholder="کد 4 رقمی" maxlength="4" 
                                   style="font-size: 24px; letter-spacing: 5px; direction: ltr;">
                            <small class="text-muted mt-2 d-block text-center">
                                کد تایید به شماره ${response.karbar.mobile} ارسال شد
                            </small>
                        </div>
                        
                        <div class="col-12 mt-3">
                            <button type="button" id="verifyBtn" class="btn btn-success w-100">
                                ✓ تایید و ثبت نهایی
                            </button>
                        </div>
                    </div>
                `;
                
                $('#resultContent').html(resultHtml);
                
                // اتصال رویداد تایید
                attachVerifyEvent();
                
                // پاک کردن فرم
                $('#mobile').val('');
                $('#price').val('');
                $('#priceWords').hide();
                
                // اسکرول به باکس نتیجه
                $('html, body').animate({
                    scrollTop: $('#resultBox').offset().top
                }, 500);
            },
            error: function(xhr) {
                var errorMessage = '';
                
                if(xhr.status === 400 && xhr.responseJSON) {
                    errorMessage = `
                        <div class="alert alert-danger">
                            <strong>⚠️ ${xhr.responseJSON.message}</strong>
                        </div>
                    `;
                }
                else if(xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    errorMessage = '<div class="alert alert-danger">';
                    for(var key in errors) {
                        errorMessage += '• ' + errors[key][0] + '<br>';
                    }
                    errorMessage += '</div>';
                }
                else if(xhr.status === 404 && xhr.responseJSON) {
                    errorMessage = `<div class="alert alert-warning">⚠️ ${xhr.responseJSON.message}</div>`;
                }
                else if(xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = `<div class="alert alert-danger">${xhr.responseJSON.message}</div>`;
                }
                else {
                    errorMessage = '<div class="alert alert-danger">خطایی رخ داده است. لطفاً دوباره تلاش کنید.</div>';
                }
                
                showError(errorMessage);
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false).text('ثبت');
            }
        });
    });
    
    // تابع اتصال رویداد تایید کد
    function attachVerifyEvent() {
        $('#verifyBtn').off('click').on('click', function() {
            var code = $('#verifyCode').val();
            
            if(!code) {
                showErrorInBox('لطفاً کد تایید را وارد کنید');
                return;
            }
            
            if(code.length !== 4) {
                showErrorInBox('کد تایید باید 4 رقم باشد');
                return;
            }
            
            // غیرفعال کردن دکمه
            $('#verifyBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> در حال تایید...');
            
            // درخواست تایید کد
            $.ajax({
                url: '/admin/shops/buy/verify/' + currentOrderId,
                type: 'POST',
                data: {
                    code: code,
                    _token: $('input[name="_token"]').val()
                },
                success: function(response) {
                    // نمایش پیام موفقیت
                    $('#resultContent').html(`
                        <div class="text-center">
                            <div class="alert alert-success">
                                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 6L9 17l-5-5" stroke="green" stroke-width="3" fill="none"/>
                                </svg>
                                <h4 class="mt-2">${response.message}</h4>
                                <hr>
                                <p class="mb-0">سفارش شما با موفقیت تایید شد</p>
                                <button type="button" id="newBuyBtn" class="btn btn-primary mt-3">ثبت خرید جدید</button>
                            </div>
                        </div>
                    `);
                    
                    // دکمه خرید جدید
                    $('#newBuyBtn').on('click', function() {
                        $('#resultBox').hide();
                        $('#mobile').val('');
                        $('#price').val('');
                        $('#priceWords').hide();
                        $('#errorBox').hide();
                    });
                },
                error: function(xhr) {
                    var errorMsg = '';
                    if(xhr.status === 400 && xhr.responseJSON) {
                        errorMsg = xhr.responseJSON.message;
                    } else {
                        errorMsg = 'خطا در تایید کد. لطفاً دوباره تلاش کنید.';
                    }
                    
                    $('#verifyBtn').prop('disabled', false).text('✓ تایید و ثبت نهایی');
                    showErrorInBox(errorMsg);
                }
            });
        });
    }
    
    // تابع نمایش خطا در باکس نتیجه
    function showErrorInBox(message) {
        // تغییر رنگ باکس به قرمز
        $('#resultBox').find('.card').removeClass('border-success').addClass('border-danger');
        $('#resultBox').find('.card-header').removeClass('bg-success').addClass('bg-danger');
        
        // نمایش خطا
        var errorHtml = `
            <div class="alert alert-danger text-center mt-3">
                <strong>⚠️ ${message}</strong>
            </div>
        `;
        $('#resultContent').append(errorHtml);
        
        // برگشت به حالت عادی بعد از 3 ثانیه
        setTimeout(function() {
            $('.alert-danger').fadeOut(function() {
                $(this).remove();
                $('#resultBox').find('.card').removeClass('border-danger').addClass('border-success');
                $('#resultBox').find('.card-header').removeClass('bg-danger').addClass('bg-success');
            });
        }, 3000);
    }
    
    // تابع نمایش خطا
    function showError(message) {
        $('#resultBox').hide();
        $('#errorBox').show();
        $('#errorContent').html(message);
        
        $('html, body').animate({
            scrollTop: $('#errorBox').offset().top
        }, 500);
        
        setTimeout(function() {
            $('#errorBox').fadeOut();
        }, 5000);
    }
    
    // تابع فرمت اعداد
    function number_format(number) {
        return new Intl.NumberFormat('fa-IR').format(number);
    }
});
</script>
@endsection