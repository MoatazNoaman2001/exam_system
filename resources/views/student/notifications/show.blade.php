@extends('layouts.app')

@section('content')
<style>
    .notifications-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 {{ app()->isLocale('ar') ? '0' : '1rem' }} 5rem {{ app()->isLocale('ar') ? '1rem' : '0' }};
        font-family: 'Tajawal', 'Cairo', sans-serif;
        direction: {{ app()->isLocale('ar') ? 'rtl' : 'ltr' }};
    }

    /* Enhanced Header Styles */
    .notifications-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 2.5rem;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .notifications-header::before {
        content: "";
        position: absolute;
        top: -50%;
        {{ app()->isLocale('ar') ? 'left' : 'right' }}: -20%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .notifications-header::after {
        content: "";
        position: absolute;
        bottom: -30%;
        {{ app()->isLocale('ar') ? 'right' : 'left' }}: -10%;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .header-icon {
        font-size: 2.2rem;
        margin-{{ app()->isLocale('ar') ? 'left' : 'right' }}: 1rem;
        color: white;
        background: rgba(255, 255, 255, 0.2);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }

    .header-content {
        z-index: 1;
    }

    .notifications-title {
        font-size: 1.8rem;
        font-weight: 700;
      margin-top: 20px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .notifications-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        font-weight: 400;
    }

    .notification-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        position: relative;
        transition: all 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        width: 100%;
        border: 1px solid #f0f0f0;
    }

    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    }

    .notification-card.unread {
        background: #f8fafc;
        border-left: {{ app()->isLocale('ar') ? '4px solid #667eea' : 'none' }};
        border-right: {{ app()->isLocale('ar') ? 'none' : '4px solid #667eea' }};
    }

    .notification-content {
        flex: 1;
        padding-{{ app()->isLocale('ar') ? 'left' : 'right' }}: 1rem;
    }

    .notification-title {
        font-size: 1.1rem;
        font-weight: 500;
        color: #2d3748;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .notification-title i {
        margin-{{ app()->isLocale('ar') ? 'left' : 'right' }}: 0.6rem;
        color: #667eea;
        font-size: 1.2rem;
    }

    .notification-subtext {
        font-size: 0.95rem;
        color: #4a5568;
        line-height: 1.4;
        padding-{{ app()->isLocale('ar') ? 'right' : 'left' }}: 1.8rem;
    }

    .notification-time {
        font-size: 0.85rem;
        color: #718096;
        white-space: nowrap;
        position: absolute;
        {{ app()->isLocale('ar') ? 'left' : 'right' }}: 1.5rem;
        top: 1.5rem;
        display: flex;
        align-items: center;
    }

    .notification-time i {
        margin-{{ app()->isLocale('ar') ? 'right' : 'left' }}: 0.3rem;
    }

    .no-notifications {
        text-align: center;
        font-size: 1.2rem;
        color: #a0aec0;
        padding: 3rem;
        background: #f8fafc;
        border-radius: 12px;
        margin-top: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    @media (max-width: 767.98px) {
        .notifications-container {
            padding: 1rem;
            padding-bottom: 5rem;
        }

        .notifications-header {
            padding: 1.2rem;
            flex-direction: column;
            text-align: center;
        }

        .header-icon {
            margin-{{ app()->isLocale('ar') ? 'left' : 'right' }}: 0;
            margin-bottom: 1rem;
            width: 50px;
            height: 50px;
            font-size: 1.8rem;
        }

        .notifications-title {
            font-size: 1.5rem;
        }

        .notification-card {
            flex-direction: column;
            padding: 1.25rem;
        }

        .notification-time {
            position: static;
            margin: 0.75rem 0 0 0;
            justify-content: flex-end;
        }
    }
</style>

<div class="notifications-container">
    <div class="notifications-header">
        <div class="header-icon">
            <i class="fas fa-bell"></i>
        </div>
        <div class="header-content">
            <h1 class="notifications-title">{{ __('lang.notifications') }}</h1>
        </div>
    </div>
    
    @if($notifications->isEmpty())
        <div class="no-notifications">
            <i class="far fa-bell-slash" style="font-size: 2.5rem; margin-bottom: 1rem; color: #cbd5e0;"></i>
            <p>{{ __('lang.no_notifications') }}</p>
            <p class="text-muted" style="font-size: 0.9rem; margin-top: 0.5rem;">{{ __('lang.no_notifications_subtext') }}</p>
        </div>
    @else
        <div id="notifications-list">
            @foreach($notifications as $notification)
                <div class="notification-card {{ $notification->is_seen ? '' : 'unread' }}" data-id="{{ $notification->id }}">
                    <div class="notification-content">
                        <div class="notification-title">
                            <i class="fas fa-{{ $notification->type === 'alert' ? 'exclamation-circle' : ($notification->type === 'message' ? 'envelope' : 'info-circle') }}"></i>
                            {{ $notification->text }}
                        </div>
                        @if($notification->subtext)
                            <div class="notification-subtext">
                                {{ $notification->subtext }}
                            </div>
                        @endif
                    </div>
                    <div class="notification-time">
                        <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@section('scripts')
<script>
    // Scripts remain the same as previous version
    document.addEventListener('DOMContentLoaded', function() {
        // Mark notifications as read when page loads
        fetch('/notifications/mark-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        }).catch(error => console.error('Error marking as read:', error));

        // Real-time notifications with Echo
        if (typeof Echo !== 'undefined') {
            Echo.private('notifications.{{ Auth::id() }}')
                .listen('NotificationSent', (e) => {
                    const notificationsList = document.getElementById('notifications-list');
                    const noNotifications = document.querySelector('.no-notifications');
                    
                    if (noNotifications) noNotifications.remove();
                    
                    const now = new Date();
                    const timeString = `${now.getHours()}:${String(now.getMinutes()).padStart(2, '0')}`;
                    
                    const notificationCard = document.createElement('div');
                    notificationCard.className = 'notification-card unread';
                    notificationCard.setAttribute('data-id', e.id);
                    notificationCard.innerHTML = `
                        <div class="notification-content">
                            <div class="notification-title">
                                <i class="fas fa-${e.type === 'alert' ? 'exclamation-circle' : (e.type === 'message' ? 'envelope' : 'info-circle')}"></i>
                                ${e.text}
                            </div>
                            ${e.subtext ? `<div class="notification-subtext">${e.subtext}</div>` : ''}
                        </div>
                        <div class="notification-time">
                            <i class="far fa-clock"></i> ${timeString}
                        </div>
                    `;
                    
                    notificationsList.prepend(notificationCard);
                });
        }
    });
</script>
@endsection
@endsection