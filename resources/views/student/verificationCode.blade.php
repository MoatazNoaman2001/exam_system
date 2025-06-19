@extends('layouts.app')

@section('title', 'verificationCode')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/verificationCode.css') }}">

<form method="POST" action="{{ route('verificationCode') }}" class="mobile-verification-code">
    @csrf

    <div class="mobile-verification-code__frame-446">
        <div class="mobile-verification-code__frame-2">
            <div class="mobile-verification-code__frame-582">
                <div class="mobile-verification-code__div">كود التحقق</div>
                <div class="mobile-verification-code__vuesax-linear-arrow-right">
                    <img
                        class="mobile-verification-code__vuesax-linear-arrow-right2"
                        src="/images/arrow-right.png"
                    />
                </div>
            </div>
            <div class="mobile-verification-code___5-abc-gmail-com">
                الرجاء إدخال الرمز المكوّن من 5 أرقام الذي تم إرساله إلى بريدك
                الإلكتروني abc@gmail.com.
            </div>
        </div>

        <div class="mobile-verification-code__verification-code-input-field">
            <div class="mobile-verification-code__mega-input-field-base">
                <input type="text" name="code[]" maxlength="1" class="mobile-verification-code__text" value="0">
            </div>
            <div class="mobile-verification-code__mega-input-field-base">
                <input type="text" name="code[]" maxlength="1" class="mobile-verification-code__text" value="0">
            </div>
            <div class="mobile-verification-code__mega-input-field-base2">
                <input type="text" name="code[]" maxlength="1" class="mobile-verification-code__text2" value="0">
            </div>
            <div class="mobile-verification-code__mega-input-field-base2">
                <input type="text" name="code[]" maxlength="1" class="mobile-verification-code__text2" value="0">
            </div>
            <div class="mobile-verification-code__mega-input-field-base2">
                <input type="text" name="code[]" maxlength="1" class="mobile-verification-code__text2"  pattern="\d*" inputmode="numeric" value="0">
            </div>
        </div>

        <div class="mobile-verification-code__frame-427321853">
            <div class="mobile-verification-code__div2">الوقت المتبقي:</div>
            <div class="mobile-verification-code__frame-427321852">
                <div class="mobile-verification-code__frame-427321849">
                    <div class="mobile-verification-code___01">01</div>
                    <div class="mobile-verification-code__div3">د</div>
                </div>
                <div class="mobile-verification-code__frame-427321851">
                    <div class="mobile-verification-code___30">30</div>
                    <div class="mobile-verification-code__div3">ث</div>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile-verification-code__frame-441">
        <div class="mobile-verification-code__frame-427321848">
            <div class="mobile-verification-code__div4">أرسل الرمز مجدداً</div>
            <div class="mobile-verification-code__div5">لم تحصل على رمز؟</div>
        </div>
        <button type="submit" class="mobile-verification-code__button">
            <div class="mobile-verification-code__text3">التحقق من الرمز</div>
        </button>
    </div>
</form>
<script>
    let minutes = 1
    let seconds = 30;

    const minuteDisplay = document.querySelector('.mobile-verification-code___01');
    const secondDisplay = document.querySelector('.mobile-verification-code___30');

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
            }
        });
    });
</script>
@endsection
