@extends('layout.master')
@section('title')
| ثبت کد معرف
@endsection
@section('content')
<div class="page-content-wrapper">
    <br><br>

    <div class="container">
        <!-- Profile Wrapper-->
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <!-- Profile Wrapper-->

                <form action="/referrer2" method="POST">
                    <div class="profile-wrapper-area py-3">
                        <!-- User Information-->
                        <div class="card user-info-card">
                            <br>
                            <div class="user-info text-center">
                                <p>کد معرف را وارد کنید</p>
                            </div>
                        </div>
                    </div>
                    <!-- User Meta Data-->
                    <div class="card user-data-card">

                        <div id="edit-profile-input-group" class="input-group card-body">
                            @if($errors->has('referrer'))
                            <div class="text-danger">{{ $errors->first('referrer') }}</div>
                            @elseif($errors=='[["repetition"]]')
                            <div class="text-danger">کد خودتو نزن :)</div>
                            @elseif($errors=='[["notfound"]]')
                            <div class="text-danger">کد وارد شده نا معتبر است</div>
                            @endif
                            <br>
                            <br>
                            @csrf
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $(".example1").pDatepicker();
                                });
                            </script>

                            <div class="mb-3">
                                <div class="title mb-2"><i class="fa-solid fa-sitemap"></i><span>کدمعرف</span></div>
                                <input class="form-control" type="text" name="referrer" minlength="4" maxlength="4">
                            </div>
                            <button class="btn btn-theme w-100" type="submit" style="border-top-right-radius: 10px;border-bottom-right-radius: 10px;">ذخیره</button>
                        </div>
                    </div>
            </div>
            </form>
            <script type="text/javascript">
                $(function() {
                    $(".sample-date-picker").mpdatepicker({
                        'timePicker': true,
                        onOpen: function() {
                            console.log('open');
                        },
                        onSelect: function(selected) {
                            console.log('select', selected);
                        },
                        onChange: function(oldVal, newVal) {
                            console.log('change', oldVal, newVal);
                        },
                        onClose: function() {
                            console.log('close');
                        },
                    });
                });
            </script>
        </div>
    </div>
</div>
</div>
@endsection