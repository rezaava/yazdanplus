@extends('admin.layout.master')

@section('onvan')
فروشگاه
{{ $shop->name }} 
@endsection

@section('head')
<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
<style>
    .layout-px-spacing {
        background: #f4f6f9;
        /* min-height: 100vh; */
        padding: 30px;
    }

    /* کارت اصلی */
    .widget-content-area {
        background: #ffffff;
        border-radius: 20px;
        padding: 35px 40px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.06);
    }

    /* عنوان سکشن‌ها */
    .widget-content-area p {
        font-weight: 700;
        margin-top: 30px;
        margin-bottom: 15px;
        color: #333;
        border-right: 4px solid #4361ee;
        padding-right: 10px;
    }

    /* فرم */
    .form-group label {
        margin-top: 12px;
        font-weight: 500;
        font-size: 14px;
        color: #444;
    }

    /* اینپوت‌ها */
    .form-control {
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        padding: 10px 14px;
        transition: 0.25s;
    }

    .form-control:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    /* چک‌باکس دسته‌بندی */
    input[type="checkbox"] {
        margin-left: 8px;
        transform: scale(1.1);
    }

    /* رادیو */
    input[type="radio"] {
        margin-left: 6px;
    }

    /* select2 */
    .select2-container--default .select2-selection--single {
        height: 42px;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        padding-top: 6px;
    }

    .select2-container--default .select2-selection--single:focus {
        border-color: #4361ee;
    }

    /* بخش عکس‌ها */
    .custom-file-input {
        border-radius: 10px;
    }

    .custom-file-label {
        border-radius: 10px;
    }

    /* باکس تصویر */
    .widget-content-area img {
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    /* دکمه ذخیره */
    .btn-primary {
        border-radius: 14px;
        padding: 12px;
        font-weight: 700;
        font-size: 15px;
        background: linear-gradient(45deg, #4361ee, #3a0ca3);
        border: none;
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
        transition: 0.25s;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        background: linear-gradient(45deg, #3a0ca3, #240046);
    }

    /* فاصله بهتر بین سکشن‌ها */
    .form-group>br {
        display: none;
    }

    /* ریسپانسیو */
    @media (max-width: 768px) {
        .widget-content-area {
            padding: 25px 20px;
        }

        .layout-px-spacing {
            padding: 15px;
        }
    }
</style>
@endsection 

@section('main')

<div class="layout-px-spacing">
@if(Session::has('suc'))
            
            <div class="alert alert-success" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                    <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"></path>
                    <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"></path>
                </svg>
                {{ Session::get('suc') }}
            </div>
         
       
            @endif
    <div class="row layout-top-spacing" id="cancel-row">
        <div id="basic" class="col-lg-12 layout-spacing">
            <div class="widget-content widget-content-area">

                <div class="row">
                    <div class="col-lg-6 col-12 mx-auto">
                       
                        <form method="post" action="/admin/shop_pass/{{ $shop->id }}" enctype="multipart/form-data">
                            @csrf

                           
                            <div class="form-group">
                                <label for="password">رمز فروشگاه</label>
                                <input type="text" name="password" class="form-control"
                                    id="password" value="{{ old('password') }}">
                            </div>

                            <div class="form-group">
                                <button type="submit" style="width: 100%;background-color: #FFD8D8;" class="mt-4 btn btn-block">
                                    ذخیره
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
<!-- <script>
    $(function() {
        $("#country").select2();
    });
</script> -->
@endsection