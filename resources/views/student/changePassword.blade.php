@extends('layouts.app')

@section('title', 'Change_Password')

@section('content')
<link rel="stylesheet" href="{{ asset('css/changePassword.css') }}">

<div class="change-password-container" dir="rtl">
    <div id="overlay" style="display:none; position: fixed; top:0; left:0; width:100vw; height:100vh; background: rgba(0,0,0,0.4); z-index: 999;"></div>

    <h2 class="text-end" style="color: #181818; font-weight: 700; font-size: 1.25rem;">كلمة المرور</h2>
    <form id="changePasswordForm">
        <div class="form-group mb-3">
            <label for="current_password">كلمة المرور الحالية</label>
            <div class="input-group">
                <input type="password" class="form-control" id="current_password" placeholder="**********" required>
                <span class="input-group-text">
                    <i class="fa-solid fa-eye-slash toggle-password"></i>
                </span>
            </div>
        </div>
        <div class="divider">تغيير كلمة المرور</div>
        <div class="form-group mb-3">
            <label for="new_password">كلمة المرور الجديدة</label>
            <div class="input-group">
                <input type="password" class="form-control" id="new_password" placeholder="**********" required>
                <span class="input-group-text">
                    <i class="fa-solid fa-eye-slash toggle-password"></i>
                </span>
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="confirm_password">تأكيد كلمة المرور الجديدة</label>
            <div class="input-group">
                <input type="password" class="form-control" id="confirm_password" placeholder="**********" required>
            </div>
        </div>

        <div class="password-strength" dir="ltr">
            <div class="strength-item">
                <input type="radio" name="lengthCheckRadio" id="lengthCheckRadio" disabled>
                <label for="lengthCheckRadio">على الأقل 8 أحرف</label>
            </div>

            <div class="strength-item">
                <input type="radio" name="specialCheckRadio" id="specialCheckRadio" disabled>
                <label for="specialCheckRadio">يجب أن تحتوي على رقم أو رمز</label>
            </div>

            <div class="strength-item">
                <input type="radio" name="usernameCheckRadio" id="usernameCheckRadio" disabled>
                <label for="usernameCheckRadio">يجب ألا تحتوي على اسمك أو بريدك الإلكتروني</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-4">حفظ</button>
    </form>

    <div class="face-id" id="passwordSuccess" style="display: none;">
        <img class="face-id__sf-symbol-checkmark-circle" src="{{ asset('images/checkmark.circle.png') }}" />
        <div class="face-id__div">تم تغيير كلمة المرور بنجاح</div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('changePasswordForm');
        const currentPassword = document.getElementById('current_password');
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('confirm_password');
        const user = @json(auth()-> user());

        function checkPasswordStrength() {
            const password = newPassword.value;
            const username = user.username || '';
            const email = user.email || '';

            const isLengthValid = password.length >= 8;
            const hasSpecial = /[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
            const hasUsername = password.toLowerCase().includes(username.toLowerCase());
            const hasEmail = password.toLowerCase().includes(email.toLowerCase());
            const isClean = !(hasUsername || hasEmail);

            const lengthRadio = document.getElementById('lengthCheckRadio');
            const specialRadio = document.getElementById('specialCheckRadio');
            const usernameRadio = document.getElementById('usernameCheckRadio');

            lengthRadio.checked = isLengthValid;
            specialRadio.checked = hasSpecial;
            usernameRadio.checked = isClean;

            document.querySelector("label[for='lengthCheckRadio']").classList.toggle('text-primary', isLengthValid);
            document.querySelector("label[for='specialCheckRadio']").classList.toggle('text-primary', hasSpecial);
            document.querySelector("label[for='usernameCheckRadio']").classList.toggle('text-primary', isClean);
        }

        newPassword.addEventListener('input', checkPasswordStrength);
        confirmPassword.addEventListener('input', checkPasswordStrength);

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (newPassword.value !== confirmPassword.value) {
                alert('كلمات المرور الجديدة غير متطابقة');
                return;
            }

            if (newPassword.value.length < 8 || !/[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(newPassword.value)) {
                alert('كلمة المرور يجب أن تحتوي على 8 أحرف على الأقل ورقم أو رمز');
                return;
            }

            if (newPassword.value.toLowerCase().includes(user.username.toLowerCase()) || newPassword.value.toLowerCase().includes(user.email.toLowerCase())) {
                alert('كلمة المرور لا يمكن أن تحتوي على اسمك أو بريدك الإلكتروني');
                return;
            }

            $.ajax({
                url: '{{ route("password.update") }}',
                method: 'POST',
                data: {
                    current_password: currentPassword.value,
                    new_password: newPassword.value,
                    new_password_confirmation: confirmPassword.value,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        const successBox = document.getElementById('passwordSuccess');
                        const overlay = document.getElementById('overlay');

                        overlay.style.display = 'block'; // إظهار الخلفية المبهتة
                        successBox.style.display = 'flex';

                        setTimeout(() => {
                            successBox.style.display = 'none';
                            overlay.style.display = 'none'; // إخفاء الخلفية بعد الانتهاء
                            window.location.href = '/login';
                        }, 3000);
                    } else {
                        alert(response.message);
                    }
                },

                error: function() {
                    alert('حدث خطأ أثناء تحديث كلمة المرور');
                }
            });
        });
    });

    document.querySelectorAll('.toggle-password').forEach(function(icon) {
        icon.addEventListener('click', function() {
            const input = this.closest('.input-group').querySelector('input');
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });
</script>
@endsection