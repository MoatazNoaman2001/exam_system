

@extends('layouts.app')

@section('title', __('setting.setting'))

@section('content')

<link rel="stylesheet" href="{{ asset('css/setting.css') }}">

<div class="container-md py-4">
    <div class="profile-header text-center mb-4">
        <img src="{{ $user->image ? asset('storage/avatars/' . $user->image) : asset('images/default-avatar.png') }}" alt="User Avatar" class="user-avatar">
        <div class="user-info-text">
            <div class="name fs-5 fw-bold mt-2">{{ auth()->user()->username ?? __('setting.username') }}</div>
            <div class="email text-secondary fs-6">{{ auth()->user()->email }}</div>
        </div>

        <!-- الشهادات والمتصدرين -->
        <div class="card custom-card mb-3">
            <a href="{{ route('certification') }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('setting.certifications') }}</span>
                    <span class="me-2">📄</span>
                </div>
            </a>

            <a href="{{ route('leaderboard', ['userId' => auth()->id()]) }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('setting.leaderboard') }}</span>
                    <span class="me-2">🏅</span>
                </div>
            </a>
        </div>

        <!-- إعدادات الحساب -->
        <h5 class="text-secondary mb-2">{{ __('setting.account') }}</h5>
        <div class="card custom-card mb-3">
            <a href="{{ route('student.profile.show') }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('setting.my_account') }}</span>
                    <span class="me-2">👤</span>
                </div>
            </a>

            <a href="{{ route('student.profile.show') }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('setting.security') }}</span>
                    <span class="me-2">🛡️</span>
                </div>
            </a>

            <div class="custom-item">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <form action="{{ route('user.delete') }}" method="POST" id="deleteAccountForm" style="margin: 0; padding: 0;">
                        @csrf
                        @method('DELETE')
                        <a href="#" class="text-danger delete-link" data-bs-toggle="modal" data-bs-target="#deleteModal">{{ __('setting.delete_account') }}</a>
                    </form>
                    <span class="me-2">🗑️</span>
                </div>
            </div>
        </div>

        <!-- إعدادات التطبيق -->
        <h5 class="text-secondary mb-2">{{ __('setting.app_settings') }}</h5>
        <div class="card custom-card mb-3">
            <div class="d-flex justify-content-between align-items-center py-3 px-4 border-bottom">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="notifications" checked>
                </div>
                <div class="d-flex align-items-center">
                    <span>{{ __('setting.notifications') }}</span>
                    <span class="me-2">🔔</span>
                </div>
            <div class="language-switcher d-flex justify-content-center gap-3 py-3 px-4">
    <a href="{{ route('locale.set', 'ar') }}" class="btn btn-outline-primary {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
        العربية
    </a>
    <a href="{{ route('locale.set', 'en') }}" class="btn btn-outline-primary {{ app()->getLocale() == 'en' ? 'active' : '' }}">
        English
    </a>
</div>
        </div>

        <!-- الدعم -->
        <h5 class="text-secondary mb-2">{{ __('setting.support') }}</h5>
        <div class="card custom-card mb-3">
            <a href="{{ route('terms.conditions') }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('setting.terms_and_policy') }}</span>
                    <span class="me-2">📄</span>
                </div>
            </a>

            <a href="{{ route('about') }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('setting.about') }}</span>
                    <span class="me-2">❗</span>
                </div>
            </a>

            <a href="{{ route('faq') }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('setting.faq') }}</span>
                    <span class="me-2">❓</span>
                </div>
            </a>

            <a href="{{ route('contact.us') }}" class="custom-item text-decoration-none text-dark">
                <span>‹</span>
                <div class="d-flex align-items-center">
                    <span>{{ __('setting.contact_us') }}</span>
                    <span class="me-2">📞</span>
                </div>
            </a>
        </div>

        <!-- تسجيل الخروج -->
        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#logoutModal">
            {{ __('setting.logout') }}
        </button>

        <!-- مودال تأكيد تسجيل الخروج -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logoutModalLabel">{{ __('setting.confirm_logout') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('setting.close') }}"></button>
                    </div>
                    <div class="modal-body text-center">
                        {{ __('setting.logout_confirmation') }}
                    </div>
                    <div class="modal-footer justify-content-center">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger px-4">{{ __('setting.yes_logout') }}</button>
                        </form>
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">{{ __('setting.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- مودال تأكيد حذف الحساب -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">{{ __('setting.confirm_delete_account') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('setting.close') }}"></button>
                    </div>
                    <div class="modal-body text-center">
                        {{ __('setting.delete_account_warning') }}
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" form="deleteAccountForm" class="btn btn-danger px-4">{{ __('setting.yes_delete_account') }}</button>
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">{{ __('setting.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
