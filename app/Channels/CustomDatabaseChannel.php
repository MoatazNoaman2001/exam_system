<?php

namespace App\Channels;

use App\Models\Notification;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\Str;

class CustomDatabaseChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable  المستخدم المستلم للإشعار
     * @param  \Illuminate\Notifications\Notification  $notification  كائن الإشعار
     * @return \App\Models\Notification
     * @throws \Exception
     */
    public function send($notifiable, BaseNotification $notification)
    {
        // استخرج البيانات المخصصة من طريقة toDatabase في إشعارك
        $data = $notification->toDatabase($notifiable);

        // سجل البيانات لتسهيل التتبع
        \Log::info('CustomDatabaseChannel Processing', [
            'user_id' => $notifiable->id,
            'data' => $data,
            'notifiable_class' => get_class($notifiable),
        ]);

        // تحقق من وجود نص الإشعار
        $text = $data['text'] ?? null;

        if (empty($text)) {
            \Log::error('Notification text is empty, cannot insert into notifications table', ['data' => $data]);
            throw new \Exception('Notification text cannot be empty');
        }

        try {
            // إنشاء سجل إشعار جديد في جدول notifications
            $notificationModel = Notification::create([
                'id' => $data['id'] ?? Str::uuid()->toString(),
                'user_id' => $notifiable->id,
                'text' => $text,
                'subtext' => $data['subtext'] ?? null,
                'is_seen' => $data['is_seen'] ?? false,
                'created_at' => $data['created_at'] ?? now(),
                'updated_at' => $data['updated_at'] ?? now(),
            ]);

            \Log::info('Notification Created Successfully', [
                'notification_id' => $notificationModel->id,
                'text' => $notificationModel->text,
                'subtext' => $notificationModel->subtext,
            ]);

            return $notificationModel;
        } catch (\Exception $e) {
            \Log::error('Failed to create notification', [
                'error' => $e->getMessage(),
                'data' => $data,
                'user_id' => $notifiable->id,
            ]);
            throw $e;
        }
    }
}
