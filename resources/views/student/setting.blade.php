@extends('layouts.app')

@section('title', __('lang.setting'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/setting.css') }}">

<div class="settings-container">
    <div class="profile-section text-center mb-5">
        <div class="avatar-container">
            <img src="{{ $user->image ? asset('storage/avatars/' . $user->image) : asset('images/default-avatar.png') }}" 
                 alt="User Avatar" 
                 class="user-avatar">
            <button class="avatar-edit-btn" data-bs-toggle="modal" data-bs-target="#avatarModal">
                <i class="fas fa-camera"></i>
            </button>
        </div>
        <h2 class="user-name mt-3">{{ auth()->user()->username ?? __('lang.username') }}</h2>
        <p class="user-email text-muted">{{ auth()->user()->email }}</p>
    </div>

    <div class="settings-card achievement-card mb-4">
        <div class="card-header">
            <i class="fas fa-trophy card-icon"></i>
            <h3 class="card-title">{{ __('lang.achievements') }}</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('certification') }}" class="settings-item">
                <div class="item-content">
                    <i class="fas fa-file-certificate item-icon"></i>
                    <span>{{ __('lang.certifications') }}</span>
                </div>
                <i class="fas fa-chevron-left item-arrow"></i>
            </a>
            <a href="{{ route('leaderboard', ['userId' => auth()->id()]) }}" class="settings-item">
                <div class="item-content">
                    <i class="fas fa-medal item-icon"></i>
                    <span>{{ __('lang.leaderboard') }}</span>
                </div>
                <i class="fas fa-chevron-left item-arrow"></i>
            </a>
        </div>
    </div>

    <div class="settings-card account-card mb-4">
        <div class="card-header">
            <i class="fas fa-user-cog card-icon"></i>
            <h3 class="card-title">{{ __('lang.account') }}</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('student.profile.show') }}" class="settings-item">
                <div class="item-content">
                    <i class="fas fa-user-edit item-icon"></i>
                    <span>{{ __('lang.my_account') }}</span>
                </div>
                <i class="fas fa-chevron-left item-arrow"></i>
            </a>
            <a href="{{ route('student.profile.show') }}" class="settings-item">
                <div class="item-content">
                    <i class="fas fa-shield-alt item-icon"></i>
                    <span>{{ __('lang.security') }}</span>
                </div>
                <i class="fas fa-chevron-left item-arrow"></i>
            </a>
            <a href="{{ route('delete-account') }}" class="settings-item delete-item" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <div class="item-content">
                    <i class="fas fa-trash-alt item-icon"></i>
                    <span>{{ __('lang.delete_account') }}</span>
                </div>
                <i class="fas fa-chevron-left item-arrow"></i>
                </a>
           

        </div>
    </div>

    <div class="settings-card app-card mb-4">
        <div class="card-header">
            <i class="fas fa-cog card-icon"></i>
            <h3 class="card-title">{{ __('lang.app_settings') }}</h3>
        </div>
        <div class="card-body">
            <div class="settings-item toggle-item">
                <div class="item-content">
                    <i class="fas fa-bell item-icon"></i>
                    <span>{{ __('lang.notifications') }}</span>
                </div>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider round"></span>
                </label>
            </div>
            <div class="settings-item language-item">
                <div class="item-content">
                    <i class="fas fa-globe item-icon"></i>
                    <span>{{ __('lang.language') }}</span>
                </div>
                <div class="language-buttons">
                    <a href="{{ route('locale.set', 'ar') }}" class="btn-lang {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                        العربية
                    </a>
                    <a href="{{ route('locale.set', 'en') }}" class="btn-lang {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                        English
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="settings-card support-card mb-4">
        <div class="card-header">
            <i class="fas fa-headset card-icon"></i>
            <h3 class="card-title">{{ __('lang.support') }}</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('terms.conditions') }}" class="settings-item">
                <div class="item-content">
                    <i class="fas fa-file-contract item-icon"></i>
                    <span>{{ __('lang.terms_and_policy') }}</span>
                </div>
                <i class="fas fa-chevron-left item-arrow"></i>
            </a>
            <a href="{{ route('about') }}" class="settings-item">
                <div class="item-content">
                    <i class="fas fa-info-circle item-icon"></i>
                    <span>{{ __('lang.about') }}</span>
                </div>
                <i class="fas fa-chevron-left item-arrow"></i>
            </a>
            <a href="{{ route('faq') }}" class="settings-item">
                <div class="item-content">
                    <i class="fas fa-question-circle item-icon"></i>
                    <span>{{ __('lang.faq') }}</span>
                </div>
                <i class="fas fa-chevron-left item-arrow"></i>
            </a>
            <a href="{{ route('student.contact.us') }}" class="settings-item">
                <div class="item-content">
                    <i class="fas fa-envelope item-icon"></i>
                    <span>{{ __('lang.contact_us') }}</span>
                </div>
                <i class="fas fa-chevron-left item-arrow"></i>
            </a>
        </div>
    </div>





    <button class="logout-btn" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <i class="fas fa-sign-out-alt"></i>
        {{ __('lang.logout') }}
    </button>

<!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="logoutModalLabel">{{ __('lang.confirm_logout') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('lang.close') }}"></button>
                </div>
                <div class="modal-body text-center py-3">
                    <p>{{ __('lang.logout_confirmation') }}</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('lang.cancel') }}</button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">{{ __('lang.yes_logout') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



 <!-- Delete Account Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="deleteModalLabel">{{ __('lang.confirm_delete_account') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('lang.close') }}"></button>
                </div>
                <div class="modal-body text-center py-3">
                    <p>{{ __('lang.delete_account_warning') }}</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('lang.cancel') }}</button>
<form id="deleteAccountForm" action="{{ route('delete-account') }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">{{ __('lang.yes_delete_account') }}</button>
</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection