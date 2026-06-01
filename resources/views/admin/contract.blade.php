@extends('admin.layout.master')

@section('onvan')
قرارداد فروشگاه
@endsection

@section('head')

@endsection

@section('main')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="card p-4">

                <h3 class="mb-3">مدیریت قرارداد فروشگاه: {{ $shop->name }}</h3>

                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- جدول قراردادها -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>اختلاف ماه (delay)</th>
                            <th>درصد تخفیف (off)</th>

                            <th>حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contracts as $contract)


                        <tr>
                            <td>{{ $contract->delay }}</td>
                            <td>{{ $contract->off }}%</td>

                            <td><a href="/admin/shops/{{ $contract->id }}/contracts/delete"><button type="button" class="btn btn-danger">حذف</button></a></td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>

                <hr>

                <h5 class="mt-3 mb-3">افزودن قرارداد جدید</h5>

                <form method="POST" action="{{ route('contracts.store', $shop->id) }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">اختلاف ماه</label>
                            <input type="number" min="0" name="delay" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">درصد تخفیف</label>
                            <input type="number" min="0" max="100" name="off" class="form-control" required>
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn w-100" style="background-color: #FFD8D8;">ثبت قرارداد</button>
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