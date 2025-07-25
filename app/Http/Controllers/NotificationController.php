<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->get();
        return view('student.notifications.show', ['notifications' => $notifications]);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->notifications()->where('is_seen', false)->count();
        return response()->json(['unread_count' => $count]);
    }

    public function markAsRead()
    {
        Auth::user()->notifications()->where('is_seen', false)->update(['is_seen' => true]);
        return response()->json(['success' => true]);
    }

    public function delete(Request $request, $id)
    {
        try {
            \Log::info('Attempting to delete notification with ID: ' . $id . ' for user: ' . Auth::id());
            $notification = Notification::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if ($notification) {
                $notification->delete();
                \Log::info('Notification deleted successfully: ID ' . $id);
                return response()->json(['success' => true]);
            } else {
                \Log::warning('Notification not found or not owned by user. ID: ' . $id . ', User: ' . Auth::id());
                return response()->json([
                    'success' => false,
                    'message' => __('lang.notification_not_found')
                ], 404);
            }
        } catch (\Exception $e) {
            \Log::error('Notification deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('lang.error_deleting_notification')
            ], 500);
        }
    }
}