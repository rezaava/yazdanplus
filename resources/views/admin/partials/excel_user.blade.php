@extends('admin.layout.master')

@section('main')

<div class="container mt-4">

    <div class="d-flex justify-content-end mb-3">

        <a href="{{ route('admin.excel_user.download') }}"
           class="btn btn-success"
           style="font-family: iran-md; border-radius: 8px;">

            <i class="fas fa-file-excel"></i>
            دانلود اکسل کاربران

        </a>

    </div>

    <div class="table-responsive">

        <table class="table table-bordered table-striped table-hover text-center">

            <thead class="table-primary">

                <tr>
                    <th>ردیف</th>
                    <th>نام</th>
                    <th>نام خانوادگی</th>
                    <th>موبایل</th>
                    <th>کد ملی</th>
                    <th>Wallet</th>
                    <th>وضعیت</th>
                </tr>

            </thead>

            <tbody>

                @foreach($users as $index => $user)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>{{ $user->name ?? '' }}</td>

                        <td>{{ $user->family ?? '' }}</td>

                        <td>{{ $user->mobile ?? '' }}</td>

                        <td>{{ $user->nationalcode ?? '' }}</td>

                        <td>{{ $user->wallet ?? 0 }}</td>

                        <td>

                            @if($user->type == 1)
                                هیئت علمی
                            @elseif($user->type == 2)
                                کارمند
                            @elseif($user->type == 3)
                                هیئت علمی بازنشسته
                            @elseif($user->type == 4)
                                کارمند بازنشسته
                            @elseif($user->type == 5)
                                لیست دکتر تدین
                            @endif

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection