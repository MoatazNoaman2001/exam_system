@extends('layouts.app')

@section('title', __('setting'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/setting.css') }}">

<div class="container-md py-4 ">
    <div class="profile-header text-center mb-4">
        <img src="{{ $user->image ? asset('storage/avatars/' . $user->image) : asset('images/default-avatar.png') }}" alt="User Avatar" class="user-avatar">
        <div class="user-info-text">
            <div class="name fs-5 fw-bold mt-2">{{ auth()->user()->username ?? __('username') }}</div>
            <div class="email text-secondary fs-6">{{ auth()->user()->email }}</div>
        </div>

        <div class="card custom-card mb-3">
            <a href="{{ route('certification') }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('certifications') ?? 'شهاداتي' }}</span>
                    <span class="me-2">📄</span>
                </div>
            </a>

            <a href="{{ route('leaderboard', ['userId' => auth()->id()]) }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('leaderboard') ?? 'قائمة المتصدرين' }}</span>
                    <span class="me-2">🏅</span>
                </div>
            </a>
        </div>

        <h5 class="text-secondary mb-2">{{ __('account') ?? 'الحساب' }}</h5>
        <div class="card custom-card mb-3">
            <a href="{{ route('student.profile.show') }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('my_account') ?? 'حسابي' }}</span>
                    <span class="me-2">👤</span>
                </div>
            </a>
            
            <a href="{{ route('student.profile.show') }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('security') ?? 'الأمان' }}</span>
                    <span class="me-2">🛡️</span>
                </div>
            </a>
            
            <div class="custom-item">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <form action="{{ route('user.delete') }}" method="POST" id="deleteAccountForm" style="margin: 0; padding: 0;">
                        @csrf
                        @method('DELETE')
                        <a href="#" class="text-danger delete-link" data-bs-toggle="modal" data-bs-target="#deleteModal">{{ __('delete_account') ?? 'حذف حسابي' }}</a>
                    </form>
                    <span class="me-2">🗑️</span>
                </div>
            </div>
        </div>

        <<!-- ... (الكود السابق حتى قسم app_settings) ... -->

<h5 class="text-secondary mb-2">{{ __('app_settings') ?? 'إعدادات التطبيق' }}</h5>
<div class="card custom-card mb-3">
    <div class="d-flex justify-content-between align-items-center py-3 px-4 border-bottom">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="notifications" checked>
        </div>
        <div class="d-flex align-items-center">
            <span>{{ __('notifications') ?? 'الإشعارات' }}</span>
            <span class="me-2">🔔</span>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center py-3 px-4">
        <form action="{{ route('locale.set', 'ar') }}" method="GET" style="display:inline; margin-right: 10px;">
            <button type="submit" class="btn btn-sm {{ app()->getLocale() == 'ar' ? 'btn-primary' : 'btn-outline-primary' }}" style="font-family: 'Tajawal', sans-serif;">العربية</button>
        </form>
        <form action="{{ route('locale.set', 'en') }}" method="GET" style="display:inline;">
            <button type="submit" class="btn btn-sm {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-outline-primary' }}" style="font-family: 'Tajawal', sans-serif;">English</button>
        </form>
    </div>
</div>

        <h5 class="text-secondary mb-2">{{ __('support') ?? 'الدعم' }}</h5>
        <div class="card custom-card mb-3">
            <a href="{{ route('terms.conditions') }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('terms_and_policy') ?? 'شروط وسياسة الاستخدام' }}</span>
                    <span class="me-2">📄</span>
                </div>
            </a>
            
            <a href="{{ route('about') }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('about') ?? 'عن الموقع' }}</span>
                    <span class="me-2">❗</span>
                </div>
            </a>
            
            <a href="{{ route('faq') }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('faq') ?? 'FAQ' }}</span>
                    <span class="me-2">❓</span>
                </div>
            </a>
            
            <a href="{{ route('contact.us') }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('contact_us') ?? 'تواصل معنا' }}</span>
                    <span class="me-2">📞</span>
                </div>
            </a>
        </div>

        <!-- زر تسجيل الخروج -->
        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#logoutModal">
            {{ __('logout') }}
        </button>

        <!-- Modal لتأكيد تسجيل الخروج -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logoutModalLabel">{{ __('confirm_logout') ?? 'تأكيد تسجيل الخروج' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('close') }}"></button>
                    </div>
                    <div class="modal-body text-center">
                        {{ __('logout_confirmation') ?? 'هل أنت متأكد أنك تريد تسجيل الخروج؟' }}
                    </div>
                    <div class="modal-footer justify-content-center">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger px-4">{{ __('yes_logout') ?? 'نعم، سجل الخروج' }}</button>
                        </form>
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">{{ __('cancel') ?? 'إلغاء' }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal لحذف الحساب -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">{{ __('confirm_delete_account') ?? 'تأكيد حذف الحساب' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('close') }}"></button>
                    </div>
                    <div class="modal-body text-center">
                        {{ __('delete_account_warning') ?? 'هل أنت متأكد أنك تريد حذف حسابك؟ سيتم حذف جميع بياناتك نهائيًا.' }}
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" form="deleteAccountForm" class="btn btn-danger px-4">{{ __('yes_delete_account') ?? 'نعم، احذف الحساب' }}</button>
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">{{ __('cancel') ?? 'إلغاء' }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection