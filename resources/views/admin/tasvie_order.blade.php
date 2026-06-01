@extends('admin.layout.master')

@section('onvan')
تسویه فروشگاه
@endsection

@section('main')
<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-8 col-lg-10 col-12 mx-auto">

            <!-- کارت اطلاعات سفارش -->
            <div class="card shadow-sm mb-4">
                <div class="card-body text-right">

                    <h5 class="mb-3 border-bottom pb-2">اطلاعات سفارش</h5>

                    <div class="row mb-2">
                        <div class="col-6"><strong>شناسه:</strong></div>
                        <div class="col-6 text-left">{{$order->id}}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-6"><strong>کاربر:</strong></div>
                        <div class="col-6 text-left">
                            {{$user->name.' '.$user->family}} <br>
                            {{$order->getmobileuser()}}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-6"><strong>فروشگاه:</strong></div>
                        <div class="col-6 text-left">{{$order->user->name}}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-6"><strong>مبلغ کل:</strong></div>
                        <div class="col-6 text-left text-primary">{{$order->price}}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-6"><strong>سهم فروشگاه:</strong></div>
                        <div class="col-6 text-left text-success">
                            {{$order->getshopvalue()}}
                            <small>(%{{$order->off}})</small>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-6"><strong>مبلغ پرداختی کاربر:</strong></div>
                        <div class="col-6 text-left">
                            {{$order->getuservalue()}}
                            <small>(%{{$order->user_off}})</small>
                        </div>
                    </div>

                    <div class="mt-3">
                        <strong>وضعیت تسویه:</strong>
                        @if ($order->status_tasvie == 0)
                            <span class="badge badge-danger">تسویه نشده</span>
                        @elseif($order->status_tasvie == 1)
                            <span class="badge badge-success">تسویه توسط درگاه</span>
                        @elseif($order->status_tasvie == 2)
                            <span class="badge badge-info">تسویه توسط سیستم</span>
                        @elseif($order->status_tasvie == 3)
                            <span class="badge badge-primary">تسویه توسط ادمین</span>
                        @endif
                    </div>

                    <div class="mt-2">
                        <strong>وضعیت پرداخت:</strong>
                        @if ($order->status == 1)
                            <span class="badge badge-warning">منتظر پرداخت</span>
                        @elseif($order->status == 2)
                            <span class="badge badge-success">پرداخت موفق</span>
                        @elseif($order->status == 4)
                            <span class="badge badge-info">پرداخت با کارت</span>
                        @endif
                    </div>

                </div>
            </div>


            <!-- نمایش خطاها -->
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{$error}}</div>
                    @endforeach
                </div>
            @endif


            <!-- فرم تسویه -->
            <div class="card shadow-sm">
                <div class="card-body text-right">

                    <h5 class="mb-4 border-bottom pb-2">ثبت اطلاعات تسویه</h5>

                    <form action="/admin/save-tasvie-order/{{$order->id}}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label>شماره پیگیری</label>
                            <input type="tel" name="traking_code" class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label>مبلغ تسویه</label>
                            <input type="tel" name="price" class="form-control"
                                   value="{{$order->getshopvalue()}}">
                        </div>

                        <div class="form-group mb-3">
                            <label>مبلغ کارمزد</label>
                            <input type="tel" name="fee" class="form-control">
                        </div>

                        <div class="form-group mb-4">
                            <label>رسید انتقال وجه</label>
                            <input type="file" name="receipt" class="form-control">
                        </div>

                        <div class="form-group mb-4">
                            <label class="d-block mb-2">روش واریز پول</label>
                            <div class="d-flex flex-wrap gap-3">
                                <label><input type="radio" name="Money_deposit" value="1"> دستگاه</label>
                                <label><input type="radio" name="Money_deposit" value="2"> کارت به کارت</label>
                                <label><input type="radio" name="Money_deposit" value="3"> همراه بانک</label>
                                <label><input type="radio" name="Money_deposit" value="4"> سایر</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            ذخیره اطلاعات تسویه
                        </button>

                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection