@extends('layout.master')

@section('title')
| تماس با ما
@endsection

@section('style')
<style>
    body{
        padding-top: 100px;
        background: #f7f9fa;
    }

    .contact-card {
        background: #ffffff;
        border: 1px solid #e5e5e5;
        border-radius: 15px;
        padding: 40px 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transition: 0.3s;
    }

    .contact-card:hover {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
    }

    .contact-title {
        font-weight: 700;
        margin-bottom: 10px;
    }

    .form-label {
        font-weight: 600;
    }

    .form-control {
        border-radius: 10px;
        padding: 12px;
    }

    .btn-contact {
        border-radius: 10px;
        padding: 12px;
        font-weight: 600;
        background-color: #ff2130;
        color: #fff;
        transition: 0.3s;
    }

    .btn-contact:hover {
        background-color: #9f0415;
        color: #fff;
    }
</style>
@endsection


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="contact-card">

                <div class="text-center mb-4">
                    <h4 class="contact-title">با ما تماس بگیرید</h4>
                    <p class="text-muted">برای ما بنویسید</p>
                </div>

                <form action="send-contact" method="POST">
                    @csrf

                    @if (!$user)
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fa-solid fa-phone me-2 text-danger"></i>
                            موبایل
                        </label>

                        <input class="form-control @error('mobile') is-invalid @enderror"
                               type="tel"
                               name="mobile"
                               placeholder="09131234567"
                               value="{{ old('mobile') }}"
                               dir="ltr">

                        @error('mobile')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    @endif


                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fa-solid fa-user me-2 text-danger"></i>
                            نام
                        </label>

                        <input class="form-control @error('name') is-invalid @enderror"
                               type="text"
                               name="name"
                               placeholder="سوها"
                               value="{{ old('name') }}">

                        @error('name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fa-solid fa-user me-2 text-danger"></i>
                            نام خانوادگی
                        </label>

                        <input class="form-control @error('family') is-invalid @enderror"
                               type="text"
                               name="family"
                               placeholder="جانات"
                               value="{{ old('family') }}">

                        @error('family')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fa-solid fa-pen-fancy me-2 text-danger"></i>
                            پیام شما
                        </label>

                        <textarea class="form-control @error('text') is-invalid @enderror"
                                  name="text"
                                  rows="5"
                                  placeholder="چیزی بنویسید...">{{ old('text') }}</textarea>

                        @error('text')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    <button class="btn btn-contact w-100">
                        ارسال پیام
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>
@endsection
