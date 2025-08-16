<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of notifications
     */
    public function index()
    {
        $notifications = Notification::with('user')->latest()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new notification
     */
    public function create()
    {
        $users = User::all();
        return view('admin.notifications.create', compact('users'));
    }

    /**
     * Store a newly created notification
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'subtext' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'send_to_all' => 'boolean',
        ]);
  
        if ($request->send_to_all) {
            $users = User::all();
            foreach ($users as $user) {
                Notification::create([
                    'text' => $request->text,
                    'subtext' => $request->subtext,
                    'user_id' => $user->id,
                    'is_seen' => false,
                ]);
            }
        } else {
            Notification::create($request->only(['text', 'subtext', 'user_id']));
        }

        return redirect()->route('admin.notifications.index')->with('success', 'Notification(s) sent successfully.');
    }

    /**
     * Display the specified notification
     */
    public function show(Notification $notification)
    {
        $notification->load('user');
        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Show the form for editing the specified notification
     */
    public function edit(Notification $notification)
    {
        $users = User::all();
        return view('admin.notifications.edit', compact('notification', 'users'));
    }

    /**
     * Update the specified notification
     */
    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'subtext' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'is_seen' => 'boolean',
        ]);

        $notification->update($request->only(['text', 'subtext', 'user_id', 'is_seen']));

        return redirect()->route('admin.notifications.index')->with('success', 'Notification updated successfully.');
    }

    /**
     * Remove the specified notification
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')->with('success', 'Notification deleted successfully.');
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_seen' => true]);
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(User $user)
    {
        $user->notifications()->update(['is_seen' => true]);
        return response()->json(['success' => true]);
    }

    /**
     * Get unread notifications count for a user
     */
    public function getUnreadCount(User $user)
    {
        $count = $user->notifications()->where('is_seen', false)->count();
        return response()->json(['count' => $count]);
    }
}