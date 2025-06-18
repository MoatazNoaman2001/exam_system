@extends('layouts.app')

@section('title', 'Logo')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/forgert-password.css') }}">

    <div class="mobile-forget-password">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mobile-forget-password__frame-446">
                <div class="mobile-forget-password__frame-2">
                    <div class="mobile-forget-password__frame-582">
                        <div class="mobile-forget-password__div">نسيت كلمة المرور؟</div>
                        <div class="mobile-forget-password__vuesax-linear-arrow-right">
                            <img class="mobile-forget-password__vuesax-linear-arrow-right2" src="/images/arrow-right.png" />
                        </div>
                    </div>
                    <div class="mobile-forget-password__div2">
                        قم بإدخال بريدك الإلكتروني المُسجل واستعيد الوصول إلى حسابك
                    </div>
                </div>

                <div class="mobile-forget-password__input-filed">
                    <div class="mobile-forget-password__top-text">البريد الالكتروني</div>
                    <div class="mobile-forget-password__box-frame">
                        <div class="mobile-forget-password__inner-frame">
                            <input
                                type="email"
                                name="email"
                                required
                                placeholder="abc@gmail.com"
                                class="mobile-forget-password__input"
                            >
                            <div class="mobile-forget-password__sms">
                                <img class="mobile-forget-password__vuesax-outline-sms" src="/images/sms.png" />
                            </div>
                        </div>
                    </div>
                    @error('email')
                        <div style="color:red; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mobile-forget-password__button">
                <button type="submit" class="mobile-forget-password__text">ارسل كود التحقق</button>
            </div>
        </form>
    </div>
@endsection









