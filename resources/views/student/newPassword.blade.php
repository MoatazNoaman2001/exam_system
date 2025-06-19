@extends('layouts.app')

@section('title', 'New-Password')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/newPassword.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<form method="POST" action="{{ route('password.update') }}" class="mobile-new-password">
    @csrf

    <div class="mobile-new-password__frame-446">
        <div class="mobile-new-password__frame-2">
            <div class="mobile-new-password__frame-582">
                <div class="mobile-new-password__div">كلمة المرور الجديدة</div>
                <div class="mobile-new-password__vuesax-linear-arrow-right">
                    <img class="mobile-new-password__vuesax-linear-arrow-right2" src="/images/arrow-right.png" />
                </div>
            </div>
            <div class="mobile-new-password__div2">
                "قم بتحديث كلمة المرور لتعزيز أمان حسابك وضمان حماية معلوماتك الشخصية"
            </div>
        </div>

        <!-- كلمة المرور -->
        <div class="mobile-new-password__input-filed">
            <div class="mobile-new-password__top-text">كلمة المرور الجديدة</div>
            <div class="mobile-new-password__box-frame">
                <div class="mobile-new-password__eye-slash">
                    <i class="fa-solid fa-eye-slash toggle-password" onclick="togglePassword(this, 'new-password')"></i>
                </div>
                <div class="mobile-new-password__inner-frame">
                    <input id="new-password" type="password" name="password" required class="mobile-new-password__input" placeholder="*********">
                    <div class="mobile-new-password__lock">
                        <img class="mobile-new-password__vuesax-outline-lock" src="/images/lock.png" />
                    </div>
                </div>
            </div>
        </div>

        <!-- تأكيد كلمة المرور -->
        <div class="mobile-new-password__input-filed2">
            <div class="mobile-new-password__top-text">تأكيد كلمة المرور الجديدة</div>
            <div class="mobile-new-password__box-frame">
                <div class="mobile-new-password__eye-slash">
                    <i class="fa-solid fa-eye-slash toggle-password" onclick="togglePassword(this, 'confirm-password')"></i>
                </div>
                <div class="mobile-new-password__inner-frame">
                    <input id="confirm-password" type="password" name="password_confirmation" required class="mobile-new-password__input" placeholder="*********">
                    <div class="mobile-new-password__lock">
                        <img class="mobile-new-password__vuesax-outline-lock2" src="/images/lock.png" />
                    </div>
                </div>
            </div>
                   <p id="confirm-error" style="color: red; font-size: 0.9rem; margin: 0.5rem 0 0; display: none;">
   تاكيد كلمة المرور غير متطابقة
</p>
        </div>
 


        <div class="mobile-new-password__frame-33835">
            <div class="mobile-new-password__div3">:قوة كلمة المرور </div>
            <div class="mobile-new-password__frame-33834">
                <div class="mobile-new-password__circle-check-with-text">
                    <div class="mobile-new-password___8">على الأقل 8 أحرف</div>
                    <div class="checkmark" id="check-length"><i class="fa fa-check"></i></div>
                </div>

                <div class="mobile-new-password__circle-check-with-text">
                    <div class="mobile-new-password__div4">يجب أن تحتوي على رقم أو رمز</div>
                    <div class="checkmark" id="check-symbol"><i class="fa fa-check"></i></div>
                </div>

                <div class="mobile-new-password__circle-check-with-text">
                    <div class="mobile-new-password__div4">يجب ألا تحتوي على اسمك أو بريدك الإلكتروني</div>
                    <div class="checkmark" id="check-personal"><i class="fa fa-check"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile-new-password__button">
        <button type="submit" class="mobile-new-password__text">حفظ كلمة المرور الجديدة</button>
    </div>
</form>
<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.3); z-index: 9998;"></div>

 
<div id="success-alert" class="face-id" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; background: white;">
  <img class="face-id__sf-symbol-checkmark-circle" src="/images/checkmark.circle.png" />
  <div class="face-id__div">تم تغيير كلمة المرور بنجاح</div>
</div>

<script>
    function togglePassword(icon, inputId) {
        const input = document.getElementById(inputId);
        const isHidden = input.type === "password";
        input.type = isHidden ? "text" : "password";
        icon.classList.toggle("fa-eye");
        icon.classList.toggle("fa-eye-slash");
    }


document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('new-password'); 
    const userName = "user"; 
    const userEmail = "example@gmail.com";

    input.addEventListener('input', function () {
        const val = input.value;

        toggleCheck('check-length', val.length >= 8);

        toggleCheck('check-symbol', /[\d\W]/.test(val));

        toggleCheck('check-personal', !val.includes(userName) && !val.includes(userEmail));
    });

    function toggleCheck(id, condition) {
        const el = document.getElementById(id);
        if (condition) {
            el.classList.add('active');
        } else {
            el.classList.remove('active');
        }
    }
});


document.querySelector('form').addEventListener('submit', function (e) {
    e.preventDefault();

    const password = document.getElementById('new-password').value;
    const confirm = document.getElementById('confirm-password').value;
    const userName = "user";
    const userEmail = "example@gmail.com";

    const lengthOk = password.length >= 8;
    const symbolOk = /[\d\W]/.test(password);
    const personalOk = !password.includes(userName) && !password.includes(userEmail);
    const match = password === confirm;

    const confirmError = document.getElementById('confirm-error');

    if (!match) {
        confirmError.style.display = 'block';
        return;
    } else {
        confirmError.style.display = 'none';
    }

    if (lengthOk && symbolOk && personalOk) {
        document.getElementById('success-alert').style.display = 'flex';
        document.getElementById('overlay').style.display = 'block';
    } else {
        alert("من فضلك تحقق من شروط كلمة المرور");
    }
});


</script>
@endsection
