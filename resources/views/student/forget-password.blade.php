@extends('layouts.app')

@section('title', 'Logo')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/forgert-password.css') }}">

<div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="custom-card">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-section">
                            <div class="header-group">
                                <div class="title">نسيت كلمة المرور؟</div>
                                <div class="mobile-forget-password__vuesax-linear-arrow-right">
                                    <img class="mobile-forget-password__vuesax-linear-arrow-right2 arrow-icon" src="{{ asset('images/arrow-right.png') }}" />
                                </div>
                            </div>
                            <div class="instruction">
                                قم بإدخال بريدك الإلكتروني المُسجل واستعيد الوصول إلى حسابك
                            </div>
                        </div>

                        <div class="input-field">
                            <div class="label">البريد الالكتروني</div>
                            <div class="input-box">
                                <div class="input-inner">
                                    <input
                                        type="email"
                                        name="email"
                                        required
                                        placeholder="abc@gmail.com"
                                        class="input"
                                    >
                                    <div class="mobile-forget-password__sms">
                                        <img class="mobile-forget-password__vuesax-outline-sms sms-icon" src="{{ asset('images/sms.png') }}" />
                                    </div>
                                </div>
                            </div>
                            @error('email')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="submit-button">
                            <button type="submit" class="button-text">ارسل كود التحقق</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection