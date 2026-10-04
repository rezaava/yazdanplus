@extends('layout.master')

@section('title')
| تماس با ما
@endsection

@section('style')
<style>
    body {
        padding-top: 100px;
        background: #f7f9fa;
    }

    .contact-card {
        background: #ffffff;
        border: 1px solid #eee;
        border-radius: 20px;
        padding: 35px 35px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .contact-header {
        display: flex;
        align-items: center;
        /* justify-content: space-between; */
        margin-bottom: 30px;
    }

    .contact-header-text {
        text-align: right;
    }

    .contact-title {
        font-weight: 700;
        font-size: 1.4rem;
        margin-bottom: 6px;
    }

    .contact-subtitle {
        color: #8a8a8a;
        font-size: 0.9rem;
        margin: 0;
    }

    .contact-header-icon {
        width: 55px;
        height: 55px;
        min-width: 55px;
        border-radius: 15px;
        background: #fdeaec;
        color: #ff2130;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .form-label {
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        font-size: 0.92rem;
    }

    .form-label .label-icon {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #fdeaec;
        color: #ff2130;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .form-control {
        border-radius: 12px;
        padding: 12px 15px;
        border: 1px solid #e5e5e5;
        background: #fafafa;
    }

    .form-control:focus {
        border-color: #ff2130;
        box-shadow: 0 0 0 3px rgba(255, 33, 48, 0.1);
        background: #fff;
    }

    .btn-contact {
        border-radius: 12px;
        padding: 13px;
        font-weight: 600;
        background-color: #ff2130;
        color: #fff;
        border: none;
        transition: 0.3s;
    }

    .btn-contact:hover {
        background-color: #9f0415;
        color: #fff;
    }
    .alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-success i {
    color: #28a745;
}
</style>
@endsection


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="contact-card">

                <div class="contact-header">
                    <div class="contact-header-icon">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <div class="contact-header-text">
                        <h4 class="contact-title">با ما تماس بگیرید</h4>
                        <p class="contact-subtitle">برای ما بنویسید</p>
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; border-right: 4px solid #198754;">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; border-right: 4px solid #dc3545;">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="send-contact" method="POST">
                    @csrf

                    <div class="row">

                        <div class="{{ $user ? 'col-12' : 'col-md-6' }} mb-4">
                            <label class="form-label">
                                <span class="label-icon"><i class="fa-solid fa-user"></i></span>
                                نام
                            </label>

                            <input class="form-control @error('name') is-invalid @enderror"
                                type="text"
                                name="name"
                                placeholder="مثال: علی "
                                value="{{ old('name') }}">

                            @error('name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        @if (!$user)
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <span class="label-icon"><i class="fa-solid fa-phone"></i></span>
                                شماره تماس
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

                    </div>


                    <div class="mb-4">
                        <label class="form-label">
                            <span class="label-icon"><i class="fa-solid fa-user"></i></span>
                            نام خانوادگی
                        </label>

                        <input class="form-control @error('family') is-invalid @enderror"
                            type="text"
                            name="family"
                            placeholder="مثال: دهقان"
                            value="{{ old('family') }}">

                        @error('family')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>


                    <div class="mb-4">
                        <label class="form-label">
                            <span class="label-icon"><i class="fa-solid fa-pen-fancy"></i></span>
                            متن پیام شما
                        </label>

                        <textarea class="form-control @error('text') is-invalid @enderror"
                            name="text"
                            rows="5"
                            placeholder="پیام خود را بنویسید... ما مشتاق شنیدن نظرات و سوالات شما هستیم.">{{ old('text') }}</textarea>

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