@extends('layouts.app')

@section('title', __('lang.settings'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/setting.css') }}">

<div class="settings-container" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Professional Header -->
    <div class="page-header">
        <h1 class="page-title">
            <div class="icon">
                <i class="fas fa-cog"></i>
            </div>
            {{ __('lang.settings') }}
        </h1>
    </div>

    <!-- Profile Section -->
    <div class="profile-section">
        <div class="profile-content">
            <div class="avatar-container">
                <img src="{{ $user->image ? asset('storage/avatars/' . $user->image) : asset('images/person_placeholder.png') }}" 
                     alt="{{ __('lang.user_avatar') }}" 
                     class="user-avatar"
                     id="userAvatar">
                <button class="avatar-edit-btn" data-bs-toggle="modal" data-bs-target="#avatarModal" 
                        aria-label="{{ __('lang.change_profile_picture') }}">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
            <div class="profile-info">
                <h2 class="user-name">{{ $user->username ?? __('lang.user') }}</h2>
                <p class="user-email">{{ $user->email }}</p>
                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['exams_completed'] ?? 0 }}</span>
                        <span class="stat-label">{{ __('lang.exams_completed') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['certificates_earned'] ?? 0 }}</span>
                        <span class="stat-label">{{ __('lang.certificates') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['notifications_unread'] ?? 0 }}</span>
                        <span class="stat-label">{{ __('lang.notifications') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Grid -->
    <div class="settings-grid">
        <!-- Achievements Card -->
        <div class="settings-card achievement-card">
            <div class="card-header">
                <i class="fas fa-trophy card-icon"></i>
                <h3 class="card-title">{{ __('lang.achievements') }}</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('student.certification') }}" class="settings-item">
                    <div class="item-content">
                        <i class="fas fa-certificate item-icon"></i>
                        <span class="item-text">{{ __('lang.certifications') }}</span>
                    </div>
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} item-arrow"></i>
                </a>
                <a href="{{ route('student.leaderboard', ['userId' => auth()->id()]) }}" class="settings-item">
                    <div class="item-content">
                        <i class="fas fa-medal item-icon"></i>
                        <span class="item-text">{{ __('lang.leaderboard') }}</span>
                    </div>
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} item-arrow"></i>
                </a>
            </div>
        </div>

        <!-- Account Settings Card -->
        <div class="settings-card account-card">
            <div class="card-header">
                <i class="fas fa-user-cog card-icon"></i>
                <h3 class="card-title">{{ __('lang.account_settings') }}</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('student.profile.show') }}" class="settings-item">
                    <div class="item-content">
                        <i class="fas fa-user-edit item-icon"></i>
                        <span class="item-text">{{ __('lang.edit_profile') }}</span>
                    </div>
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} item-arrow"></i>
                </a>
                <a href="{{ route('student.security.show') }}" class="settings-item">
                    <div class="item-content">
                        <i class="fas fa-shield-alt item-icon"></i>
                        <span class="item-text">{{ __('lang.security_privacy') }}</span>
                    </div>
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} item-arrow"></i>
                </a>
                <a href="#" class="settings-item delete-item" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <div class="item-content">
                        <i class="fas fa-trash-alt item-icon"></i>
                        <span class="item-text">{{ __('lang.delete_account') }}</span>
                    </div>
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} item-arrow"></i>
                </a>
            </div>
        </div>

        <!-- App Settings Card -->
        <div class="settings-card app-card">
            <div class="card-header">
                <i class="fas fa-mobile-alt card-icon"></i>
                <h3 class="card-title">{{ __('lang.app_settings') }}</h3>
            </div>
            <div class="card-body">
               <div class="settings-item toggle-item">
    <div class="item-content">
        <i class="fas fa-bell item-icon"></i>
        <span class="item-text">{{ __('lang.notifications') }}</span>
    </div>
    <label class="switch">
        <input type="checkbox" id="notificationToggle" {{ Auth::user()->notifications_enabled ? 'checked' : '' }}>
        <span class="slider"></span>
    </label>
</div>
                <div class="settings-item">
                    <div class="item-content">
                        <i class="fas fa-globe item-icon"></i>
                        <span class="item-text">{{ __('lang.language') }}</span>
                    </div>
                    <div class="language-buttons">
                        <a href="{{ route('locale.set', 'ar') }}" 
                           class="btn-lang {{ $user->preferred_language == 'ar' ? 'active' : '' }}">
                            العربية
                        </a>
                        <a href="{{ route('locale.set', 'en') }}" 
                           class="btn-lang {{ $user->preferred_language == 'en' ? 'active' : '' }}">
                            English
                        </a>
                    </div>
                </div>
                <a href="#" class="settings-item">
                    <div class="item-content">
                        <i class="fas fa-palette item-icon"></i>
                        <span class="item-text">{{ __('lang.theme') }}</span>
                    </div>
                    <span class="badge bg-secondary">{{ __('lang.coming_soon') }}</span>
                </a>
            </div>
        </div>

        <!-- Support & Help Card -->
        <div class="settings-card support-card">
            <div class="card-header">
                <i class="fas fa-life-ring card-icon"></i>
                <h3 class="card-title">{{ __('lang.support_help') }}</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('privacy-policy') }}" class="settings-item">
                    <div class="item-content">
                        <i class="fas fa-file-contract item-icon"></i>
                        <span class="item-text">{{ __('lang.terms_conditions') }}</span>
                    </div>
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} item-arrow"></i>
                </a>
                <a href="{{ route('about') }}" class="settings-item">
                    <div class="item-content">
                        <i class="fas fa-info-circle item-icon"></i>
                        <span class="item-text">{{ __('lang.about_app') }}</span>
                    </div>
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} item-arrow"></i>
                </a>
                <a href="{{ route('faq') }}" class="settings-item">
                    <div class="item-content">
                        <i class="fas fa-question-circle item-icon"></i>
                        <span class="item-text">{{ __('lang.faq') }}</span>
                    </div>
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} item-arrow"></i>
                </a>
                <a href="{{ route('student.contact.us') }}" class="settings-item">
                    <div class="item-content">
                        <i class="fas fa-envelope item-icon"></i>
                        <span class="item-text">{{ __('lang.contact_us') }}</span>
                    </div>
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} item-arrow"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Logout Button -->
    <button class="logout-btn" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <i class="fas fa-sign-out-alt"></i>
        {{ __('lang.logout') }}
    </button>
</div>

<!-- Avatar Upload Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarModalLabel">
                    <i class="fas fa-camera me-2"></i>
                    {{ __('lang.update_profile_picture') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" 
                        aria-label="{{ __('lang.close') }}"></button>
            </div>
            <div class="modal-body">
                <form id="avatarForm" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-area text-center p-4 border border-2 border-dashed rounded-3 mb-3">
                        <i class="fas fa-cloud-upload-alt text-muted mb-3" style="font-size: 3rem;"></i>
                        <h6 class="mb-2">{{ __('lang.drag_drop_or_click') }}</h6>
                        <p class="text-muted small mb-3">{{ __('lang.supported_formats') }}: JPG, PNG, GIF</p>
                        <input type="file" class="form-control" id="avatarInput" name="avatar" accept="image/*" required>
                    </div>
                    <div id="imagePreview" class="text-center" style="display: none;">
                        <img id="previewImg" src="" alt="{{ __('lang.preview') }}" 
                             class="img-fluid rounded-circle mb-3" style="max-width: 150px; max-height: 150px;">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetUpload()">
                            <i class="fas fa-times me-1"></i>
                            {{ __('lang.remove') }}
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    {{ __('lang.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" id="uploadAvatarBtn" disabled>
                    <span class="loading-spinner d-none" role="status"></span>
                    <i class="fas fa-upload me-2"></i>
                    {{ __('lang.upload') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    {{ __('lang.confirm_logout') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" 
                        aria-label="{{ __('lang.close') }}"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-question-circle text-warning" style="font-size: 4rem;"></i>
                </div>
                <h6 class="mb-3">{{ __('lang.are_you_sure_logout') }}</h6>
                <p class="text-muted">{{ __('lang.logout_description') }}</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    {{ __('lang.cancel') }}
                </button>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        {{ __('lang.yes_logout') }}
                    </button>
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
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ __('lang.delete_account') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" 
                        aria-label="{{ __('lang.close') }}"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                </div>
                <h6 class="mb-3 text-danger">{{ __('lang.permanent_action') }}</h6>
                <div class="text-start">
                    <p class="mb-2"><strong>{{ __('lang.this_will_delete') }}:</strong></p>
                    <ul class="list-unstyled">
                        <li class="mb-1">
                            <i class="fas fa-check text-danger me-2"></i>
                            {{ __('lang.profile_data') }}
                        </li>
                        <li class="mb-1">
                            <i class="fas fa-check text-danger me-2"></i>
                            {{ __('lang.exam_results') }}
                        </li>
                        <li class="mb-1">
                            <i class="fas fa-check text-danger me-2"></i>
                            {{ __('lang.certificates') }}
                        </li>
                        <li class="mb-1">
                            <i class="fas fa-check text-danger me-2"></i>
                            {{ __('lang.all_progress') }}
                        </li>
                    </ul>
                </div>
                <p class="text-muted mt-3">{{ __('lang.cannot_be_undone') }}</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    {{ __('lang.cancel') }}
                </button>
                <form id="deleteAccountForm" action="{{ route('delete-account') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirmDeletion()">
                        <i class="fas fa-trash-alt me-2"></i>
                        {{ __('lang.delete_permanently') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Avatar upload functionality
    const avatarInput = document.getElementById('avatarInput');
    const uploadBtn = document.getElementById('uploadAvatarBtn');
    const previewImg = document.getElementById('previewImg');
    const imagePreview = document.getElementById('imagePreview');
    const uploadArea = document.querySelector('.upload-area');

    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-primary', 'bg-light');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-primary', 'bg-light');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-primary', 'bg-light');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            avatarInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    // File input change
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFileSelect(file);
        }
    });

    function handleFileSelect(file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            showNotification('{{ __("lang.please_select_image") }}', 'error');
            return;
        }
        
        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            showNotification('{{ __("lang.file_too_large") }}', 'error');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            imagePreview.style.display = 'block';
            uploadArea.style.display = 'none';
            uploadBtn.disabled = false;
        };
        reader.readAsDataURL(file);
    }

    // Reset upload
    window.resetUpload = function() {
        avatarInput.value = '';
        imagePreview.style.display = 'none';
        uploadArea.style.display = 'block';
        uploadBtn.disabled = true;
    };

    // Upload avatar
    uploadBtn.addEventListener('click', function() {
        const formData = new FormData(document.getElementById('avatarForm'));
        const spinner = this.querySelector('.loading-spinner');
        const icon = this.querySelector('.fas');
        
        // Show loading state
        spinner.classList.remove('d-none');
        icon.classList.add('d-none');
        this.disabled = true;

        fetch('{{ route("student.profile.update-avatar") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update avatar in the page
                document.getElementById('userAvatar').src = data.avatar_url;
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('avatarModal')).hide();
                
                // Show success message
                showNotification('{{ __("lang.avatar_updated_successfully") }}', 'success');
                
                // Reset form
                resetUpload();
            } else {
                showNotification(data.message || '{{ __("lang.error_updating_avatar") }}', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('{{ __("lang.error_updating_avatar") }}', 'error');
        })
        .finally(() => {
            // Hide loading state
            spinner.classList.add('d-none');
            icon.classList.remove('d-none');
            this.disabled = false;
        });
    });

    // Notification toggle
const notificationToggle = document.getElementById('notificationToggle');
if (notificationToggle) {
    notificationToggle.addEventListener('change', function() {
        const enabled = this.checked;

        fetch('{{ route("student.settings.notifications") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ enabled: enabled })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
            } else {
                this.checked = !enabled;
                showNotification(data.message || '{{ __("lang.error_updating_settings") }}', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.checked = !enabled;
            showNotification('{{ __("lang.error_updating_settings") }}', 'error');
        });
    });
}
    // Confirm deletion
    window.confirmDeletion = function() {
        return confirm('{{ __("lang.type_delete_to_confirm") }}\n\n{{ __("lang.final_warning") }}');
    };

    // Show notification function
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotification = document.querySelector('.notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-${getNotificationIcon(type)} me-2"></i>
                    <span>${message}</span>
                </div>
                <button type="button" class="btn-close btn-sm" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;

        // Add to page
        document.body.appendChild(notification);

        // Show notification
        setTimeout(() => notification.classList.add('show'), 100);

        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 5000);
    }

    function getNotificationIcon(type) {
        switch(type) {
            case 'success': return 'check-circle';
            case 'error': return 'times-circle';
            case 'warning': return 'exclamation-triangle';
            default: return 'info-circle';
        }
    }

    // Show session messages
    @if(session('success'))
        showNotification('{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
        showNotification('{{ session('error') }}', 'error');
    @endif

    @if(session('warning'))
        showNotification('{{ session('warning') }}', 'warning');
    @endif

    // Reset modal state when closed
    document.getElementById('avatarModal').addEventListener('hidden.bs.modal', function() {
        resetUpload();
    });

    // Smooth scroll for better UX
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>

@endsection