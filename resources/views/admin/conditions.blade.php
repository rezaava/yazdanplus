@extends('admin.layout.master')

@section('onvan')
شرایط فروشگاه
@endsection

@section('head')

@endsection

@section('main')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">

                <h3 class="mb-3">مدیریت قرارداد فروشگاه: {{ $shop->name }} </h3>

                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- جدول قراردادها -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ماه</th>
                            <th> درصد(کارمزد)</th>
                            <th>درصد پیش پرداخت</th>
                            <th>حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($conditions as $condition)

                        <tr>
                            <td>{{ $condition->month }}</td>
                            <td>{{ $condition->percent }}%</td>
                            <td>{{ $condition->advance_payment }}%</td>
                            <td><a href="/admin/shops/conditions/delete/{{ $condition->id }}"><button type="button" class="btn btn-danger">حذف</button></a></td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>

                <hr>

                <h5 class="mt-3 mb-3">افزودن شرایط جدید</h5>

                <form method="POST" action="/admin/shops/conditions/{{ $shop->id }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">ماه</label>
                            <input type="number" min="0" name="month" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">درصد</label>
                            <input type="number" min="0" max="100" name="percent" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">درصد پیش پرداخت</label>
                            <input type="number" min="0" max="100" name="advance_payment" class="form-control" required>
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn w-100" style="background-color: #FFD8D8;">ثبت شرایط</button>
                        </div>

                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection