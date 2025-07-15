<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}