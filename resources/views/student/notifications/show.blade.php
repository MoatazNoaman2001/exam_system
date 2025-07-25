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
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    .delete-btn {
        margin-right: 1rem;
    }
    @endif

    @if(app()->getLocale() == 'en')
    .notification-time {
        font-size: 0.8rem;
        color: #7b88a8;
        position: absolute;
        top: 1rem;
        right: 1rem;
    }       
    .delete-btn {
        margin-left: 1rem;
    }
    @endif

    .delete-btn {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        font-size: 1rem;
    }

    .delete-btn:hover {
        color: #b02a37;
    }

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
            flex-direction: column;
            align-items: flex-start;
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

        .delete-btn {
            margin: 0.5rem 0 0 0;
        }
    }

    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .notification.show {
        opacity: 1;
    }

    .notification.success {
        background-color: #d4edda;
        color: #155724;
    }

    .notification.error {
        background-color: #f8d7da;
        color: #721c24;
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
                <div class="notification-card {{ $notification->is_seen ? '' : 'unread' }}" data-id="{{ $notification->id }}">
                    <div class="notification-content">
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
                    <button class="delete-btn" data-id="{{ $notification->id }}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>

@section('scripts')
    <script>
        // وظيفة إظهار الإشعارات
        function showNotification(message, type = 'info') {
            console.log('Showing notification:', message, type);
            const existingNotification = document.querySelector('.notification');
            if (existingNotification) {
                existingNotification.remove();
            }

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

            document.body.appendChild(notification);
            setTimeout(() => notification.classList.add('show'), 100);
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

        // وظيفة حذف الإشعار
        window.deleteNotification = function(notificationId) {
            console.log('Attempting to delete notification with ID:', notificationId);
            if (confirm('{{ __("lang.confirm_delete_notification") }}')) {
                console.log('Sending DELETE request to /notifications/delete/' + notificationId);
                fetch('/notifications/delete/' + notificationId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        const card = document.querySelector(`.notification-card[data-id="${notificationId}"]`);
                        if (card) {
                            card.remove();
                            showNotification('{{ __("lang.notification_deleted") }}', 'success');
                            console.log('Notification removed from DOM');

                            if (!document.querySelector('.notification-card')) {
                                const notificationsList = document.getElementById('notifications-list');
                                notificationsList.innerHTML = `
                                    <div class="no-notifications">
                                        {{ __('lang.no_notifications') }}
                                    </div>
                                `;
                                console.log('No notifications left, showing empty message');
                            }
                        }
                    } else {
                        showNotification(data.message || '{{ __("lang.error_deleting_notification") }}', 'error');
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    showNotification('{{ __("lang.error_deleting_notification") }}', 'error');
                });
            }
        };

        // إضافة مستمعات الأحداث لأزرار الحذف
        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM loaded, adding event listeners for delete buttons');
            document.addEventListener('click', function (e) {
                if (e.target.closest('.delete-btn')) {
                    const button = e.target.closest('.delete-btn');
                    const id = button.getAttribute('data-id');
                    console.log('Delete button clicked for notification ID:', id);
                    deleteNotification(id);
                }
            });

            // تحديد الإشعارات كمقروءة عند عرض الصفحة
            console.log('Sending mark-as-read request');
            fetch('/notifications/mark-as-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
            })
            .then(response => {
                console.log('Mark as read response status:', response.status);
                if (!response.ok) {
                    console.error('Mark as read failed:', response.status);
                }
            })
            .catch(error => {
                console.error('Mark as read error:', error);
            });

            // تحديث الإشعارات عبر Laravel Echo
            if (typeof Echo !== 'undefined') {
                console.log('Echo is defined, setting up listener');
                Echo.private('notifications.{{ Auth::id() }}')
                    .listen('NotificationSent', (e) => {
                        console.log('New notification received:', e);
                        const notificationsList = document.getElementById('notifications-list');
                        const noNotifications = document.querySelector('.no-notifications');

                        if (noNotifications) {
                            noNotifications.remove();
                        }

                        const notificationCard = document.createElement('div');
                        notificationCard.className = 'notification-card unread';
                        notificationCard.setAttribute('data-id', e.id);
                        notificationCard.innerHTML = `
                            <div class="notification-content">
                                <div class="notification-time">${e.created_at}</div>
                                <div class="notification-title">${e.text}</div>
                                <div class="notification-subtext">${e.subtext || ''}</div>
                            </div>
                            <button class="delete-btn" data-id="${e.id}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        `;
                        notificationsList.prepend(notificationCard);
                        console.log('New notification added to DOM');

                        const newDeleteButton = notificationCard.querySelector('.delete-btn');
                        newDeleteButton.addEventListener('click', () => {
                            console.log('New delete button clicked for notification ID:', e.id);
                            deleteNotification(e.id);
                        });

                        fetch('/notifications/unread-count')
                            .then(response => response.json())
                            .then(data => {
                                console.log('Unread notifications:', data.unread_count);
                            })
                            .catch(error => {
                                console.error('Unread count error:', error);
                            });
                    });
            } else {
                console.error('Echo is not defined');
            }
        });
    </script>
@endsection