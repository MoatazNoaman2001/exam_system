@extends('layouts.app')

@section('title', __('lang.profile'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/Profile.css') }}">

<div class="container my-5" style="max-width: 800px;" >
    <div class="bg-white p-4 p-md-5 rounded-4 shadow border profile-card">
        <!-- Page Title -->
        <div class="d-flex justify-content-start mb-4">
            <a href="{{ url()->previous() }}" class="text-decoration-none">
                <img src="{{ asset('images/arrow-right.png') }}" alt="{{ __('lang.back') }}" class="arrow-right" style="width: 24px; height: 24px;">
            </a>
            <h3 class="profile-title">{{ __('lang.my_account') }}</h3>
        </div>

        <!-- Profile Image -->
        <div class="text-center mb-4 position-relative">
            <div class="profile-image mx-auto" style="background-image: url('{{ auth()->user()->image ? asset('storage/avatars/' . auth()->user()->image) : asset('images/tl.webp') }}');">
                <a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#editImageModal">
                    <img src="{{ asset('images/edit.png') }}" alt="{{ __('lang.edit') }}" style="width: 24px; height: 24px;">
                </a>
            </div>
        </div>

        <!-- Profile Form -->
        <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-3">
                <label for="username" class="form-label top-text">{{ __('lang.name') }}</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <img src="{{ asset('images/user.png') }}" alt="{{ __('lang.user') }}" style="width: 20px; height: 20px;">
                    </span>
                    <input type="text" name="username" id="username" class="form-control" 
                           value="{{ old('username', auth()->user()->username) }}" required>
                </div>
                @error('username')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label top-text">{{ __('lang.email') }}</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <img src="{{ asset('images/sms.png') }}" alt="{{ __('lang.email') }}" style="width: 20px; height: 20px;">
                    </span>
                    <input type="email" name="email" id="email" class="form-control" 
                           value="{{ old('email', auth()->user()->email) }}" required>
                </div>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label for="phone" class="form-label top-text">{{ __('lang.phone') }}</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <img src="{{ asset('images/Icon.png') }}" alt="{{ __('lang.phone') }}" style="width: 20px; height: 20px;">
                    </span>
                    <input type="text" name="phone" id="phone" class="form-control" 
                           value="{{ old('phone', auth()->user()->phone) }}" required>
                </div>
                @error('phone')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Save Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5 py-2 save-button">{{ __('lang.save') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Image Upload -->
<div class="modal fade" id="editImageModal" tabindex="-1" aria-labelledby="editImageModalLabel" aria-hidden="true" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editImageModalLabel">{{ __('lang.change_profile_image') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('student.profile.update-image') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="image" class="form-label">{{ __('lang.select_new_image') }}</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('lang.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('lang.upload') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.profile-image').style.backgroundImage = `url(${e.target.result})`;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    const editImageModal = document.getElementById('editImageModal');
    if (editImageModal) {
        editImageModal.addEventListener('hide.bs.modal', () => {
            setTimeout(() => {
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            }, 200);
        });
    }
});
</script>
@endsection