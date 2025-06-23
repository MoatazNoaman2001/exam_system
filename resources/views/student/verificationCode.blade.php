@extends('layouts.app')

@section('title', 'verificationCode')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/verificationCode.css') }}">
<div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="custom-card">
                    <form method="POST" action="{{ route('verification-code') }}">
                        @csrf
                        <div class="form-section">
                            <div class="header-group">
                                <div class="title">كود التحقق</div>
                                <div class="mobile-verification-code__vuesax-linear-arrow-right">
                                    <img class="mobile-verification-code__vuesax-linear-arrow-right2 arrow-icon" src="{{ asset('images/arrow-right.png') }}" />
                                </div>
                            </div>
                            <div class="instruction">
                                الرجاء إدخال الرمز المكوّن من 5 أرقام الذي تم إرساله إلى بريدك الإلكتروني {{ auth()->user()->email ?? 'abc@gmail.com' }}.
                            </div>
                            <div class="otp-input-field">
                                @for ($i = 0; $i < 5; $i++)
                                    <div class="otp-input">
                                        <input type="text" name="code[]" maxlength="1" class="otp-text" pattern="\d*" inputmode="numeric" required>
                                    </div>
                                @endfor
                            </div>
                            <div class="timer-section">
                                <div class="timer-label">الوقت المتبقي:</div>
                                <div class="timer-group">
                                    <div class="timer-box">
                                        <div class="timer-value" id="minutes">01</div>
                                        <div class="timer-unit">د</div>
                                    </div>
                                    <div class="timer-box">
                                        <div class="timer-value" id="seconds">30</div>
                                        <div class="timer-unit">ث</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-section">
                            <div class="resend-link">
                                <div class="resend-text-primary">أرسل الرمز مجدداً</div>
                                <div class="resend-text-secondary">لم تحصل على رمز؟</div>
                            </div>
                            <button type="submit" class="submit-button">
                                <div class="button-text">التحقق من الرمز</div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let minutes = 1;
        let seconds = 30;

        const minuteDisplay = document.getElementById('minutes');
        const secondDisplay = document.getElementById('seconds');

        function updateTimer() {
            if (seconds === 0) {
                if (minutes === 0) {
                    clearInterval(timerInterval);
                    return;
                } else {
                    minutes--;
                    seconds = 59;
                }
            } else {
                seconds--;
            }

            minuteDisplay.textContent = minutes.toString().padStart(2, '0');
            secondDisplay.textContent = seconds.toString().padStart(2, '0');
        }

        const timerInterval = setInterval(updateTimer, 1000);

        document.querySelectorAll('input[name="code[]"]').forEach((input, index, inputs) => {
            input.addEventListener('focus', function () {
                this.select();
            });

            input.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '');

                if (this.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                } else if (this.value.length === 0 && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    </script>
