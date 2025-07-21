@extends('layouts.app')

@section('content')
<style>
    .notifications-container {
        max-width: 800px;
        margin: 0 auto;
        padding: {{ app()->isLocale('ar') ? '1rem 0 5rem' : '1rem' }};
        font-family: 'Tajawal', 'Cairo', sans-serif;
        direction: {{ app()->isLocale('ar') ? 'rtl' : 'ltr' }};
    }

    .notification-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 1rem;
        margin-bottom: 1rem;
        position: relative;
        transition: all 0.3s ease;
    }

    .notification-card.unread {
        background: #f8f9fa;
        border-right: 4px solid #dc3545;
    }

    @if(app()->getLocale() == 'ar')
    .notification-card.unread {
        border-right: none;
        border-left: 4px solid #dc3545;
    }
    @endif

    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .notification-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .notification-subtext {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    @if (app()->isLocale('ar'))
    .notification-time {
        font-size: 0.8rem;
        color: #7b88a8;
        position: absolute;
        top: 1rem;
        left: 1rem;
    }       
    @endif

    @if (app()->isLocale('en'))
    .notification-time {
        font-size: 0.8rem;
        color: #7b88a8;
        position: absolute;
        top: 1rem;
        right: 1rem;
    }       
    @endif


    .no-notifications {
        text-align: center;
        font-size: 1.2rem;
        color: #6b7280;
        padding: 2rem;
    }

    @media (max-width: 767.98px) {
        .notifications-container {
            padding: 0.5rem;
            padding-bottom: 5rem;
        }

        .notification-card {
            padding: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .notification-title {
            font-size: 1rem;
        }

        .notification-subtext {
            font-size: 0.85rem;
        }

        .notification-time {
            font-size: 0.75rem;
            position: static;
            display: block;
            margin-top: 0.5rem;
        }
    }
</style>

<div class="notifications-container">
    <h2 class="text-center mb-4">{{ __('lang.notifications') }}</h2>
    @if($notifications->isEmpty())
        <div class="no-notifications">
            {{ __('lang.no_notifications') }}
        </div>
    @else
        <div id="notifications-list">
            @foreach($notifications as $notification)
                <div class="notification-card {{ $notification->is_seen ? '' : 'unread' }}">
                    <div class="notification-time">
                        {{ $notification->created_at->diffForHumans() }}
                    </div>
                    <div class="notification-title">
                        {{ $notification->text }}
                    </div>
                    <div class="notification-subtext">
                        {{ $notification->subtext }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@section('scripts')
    <script>
        Echo.private('notifications.{{ Auth::id() }}')
            .listen('NotificationSent', (e) => {
                const notificationsList = document.getElementById('notifications-list');
                const noNotifications = document.querySelector('.no-notifications');

                if (noNotifications) {
                    noNotifications.remove();
                }

                const notificationCard = document.createElement('div');
                notificationCard.className = 'notification-card unread';
                notificationCard.innerHTML = `
                    <div class="notification-time">${e.created_at}</div>
                    <div class="notification-title">${e.text}</div>
                    <div class="notification-subtext">${e.subtext || ''}</div>
                `;
                notificationsList.prepend(notificationCard);

                // Update unread count
                fetch('/notifications/unread-count')
                    .then(response => response.json())
                    .then(data => {
                        console.log('Unread notifications:', data.unread_count);
                    });
            });

        // Mark notifications as read when viewed
        document.addEventListener('DOMContentLoaded', () => {
            fetch('/notifications/mark-as-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            });
        });
    </script>
@endsection
@endsection