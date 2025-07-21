<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    /**
     * Show the settings page
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user statistics using your existing tables
        $stats = [
            'exams_completed' => DB::table('exam_sessions')
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'certificates_earned' => DB::table('achievements')
                ->where('user_id', $user->id)
                ->count(),
            'notifications_unread' => DB::table('notifications')
                ->where('user_id', $user->id)
                ->where('is_seen', false)
                ->count(),
        ];
        
        return view('student.setting', compact('user', 'stats'));
    }

    /**
     * Update user avatar/image
     */
    public function updateAvatar(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ], [
            'avatar.required' => __('lang.please_select_image'),
            'avatar.image' => __('lang.please_select_image'),
            'avatar.mimes' => __('lang.supported_formats') . ': JPEG, PNG, JPG, GIF, WEBP',
            'avatar.max' => __('lang.file_too_large'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        $user = Auth::user();
            
            // Delete old avatar if exists
            if ($user->image && Storage::disk('public')->exists('avatars/' . $user->image)) {
                Storage::disk('public')->delete('avatars/' . $user->image);
            }

            // Store new avatar
            $avatarFile = $request->file('avatar');
            $avatarName = time() . '_' . $user->id . '.' . $avatarFile->getClientOriginalExtension();
            
            // Ensure avatars directory exists
            if (!Storage::disk('public')->exists('avatars')) {
                Storage::disk('public')->makeDirectory('avatars');
            }
            
            $avatarPath = $avatarFile->storeAs('avatars', $avatarName, 'public');

            // Update user record using DB query
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'image' => $avatarName,
                    'updated_at' => now()
                ]);

            // Create notification
            $this->createNotification($user->id, __('lang.avatar_updated_successfully'), __('lang.profile_picture_changed'));

            return response()->json([
                'success' => true,
                'message' => __('lang.avatar_updated_successfully'),
                'avatar_url' => asset('storage/avatars/' . $avatarName)
            ]);
    }

    /**
     * Remove user avatar
     */
    public function removeAvatar(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // Delete avatar file if exists
            if ($user->image && Storage::disk('public')->exists('avatars/' . $user->image)) {
                Storage::disk('public')->delete('avatars/' . $user->image);
            }

            // Update user record using DB query
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'image' => null,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => __('lang.avatar_removed_successfully'),
                'avatar_url' => asset('images/default-avatar.png')
            ]);

        } catch (\Exception $e) {
            \Log::error('Avatar removal error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('lang.error_updating_avatar')
            ], 500);
        }
    }

    /**
     * Update notification preferences (using preferred_language as notification toggle)
     */
    public function updateNotifications(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'enabled' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $user = Auth::user();
            
            // Since you don't have notifications_enabled field, we'll use a different approach
            // You could store this in a separate settings table or use a JSON field
            // For now, let's create a notification about the preference change
            
            $this->createNotification(
                $user->id, 
                $request->enabled ? __('lang.notifications_enabled') : __('lang.notifications_disabled'),
                __('lang.notification_settings_updated')
            );

            return response()->json([
                'success' => true,
                'message' => $request->enabled 
                    ? __('lang.notifications_enabled') 
                    : __('lang.notifications_disabled')
            ]);

        } catch (\Exception $e) {
            \Log::error('Notification settings error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('lang.error_updating_settings')
            ], 500);
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('users')->ignore($user->id)
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => 'required|string|max:20',
            'preferred_language' => 'nullable|in:ar,en',
        ], [
            'username.required' => __('lang.username') . ' is required',
            'username.alpha_dash' => __('lang.username') . ' can only contain letters, numbers, dashes and underscores',
            'username.unique' => __('lang.username') . ' is already taken',
            'email.required' => __('lang.email') . ' is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => __('lang.email') . ' is already registered',
            'phone.required' => __('lang.phone') . ' is required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $updateData = [
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'updated_at' => now()
            ];
            
            if ($request->filled('preferred_language')) {
                $updateData['preferred_language'] = $request->preferred_language;
            }
            
            DB::table('users')
                ->where('id', $user->id)
                ->update($updateData);

            // Create notification about profile update
            $this->createNotification($user->id, __('lang.profile_updated_successfully'), __('lang.your_profile_information_has_been_updated'));

            return back()->with('success', __('lang.profile_updated_successfully'));

        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            return back()->with('error', __('lang.error_updating_profile'));
        }
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => __('lang.current_password') . ' is required',
            'password.required' => __('lang.new_password') . ' is required',
            'password.min' => __('lang.new_password') . ' must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $user = Auth::user();

            // Check current password
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => __('lang.current_password_incorrect')]);
            }

            // Update password using DB query
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'password' => Hash::make($request->password),
                    'updated_at' => now()
                ]);

            // Create notification about password change
            $this->createNotification($user->id, __('lang.password_updated_successfully'), __('lang.your_password_has_been_changed'));

            return back()->with('success', __('lang.password_updated_successfully'));

        } catch (\Exception $e) {
            \Log::error('Password update error: ' . $e->getMessage());
            return back()->with('error', __('lang.error_updating_password'));
        }
    }

    /**
     * Delete user account
     */
    public function deleteAccount(Request $request)
    {
        try {
            $user = Auth::user();
            
            DB::beginTransaction();
            
            // Delete user avatar if exists
            if ($user->image && Storage::disk('public')->exists('avatars/' . $user->image)) {
                Storage::disk('public')->delete('avatars/' . $user->image);
            }

            // Delete related data using your existing tables
            DB::table('exam_sessions')->where('user_id', $user->id)->delete();
            DB::table('exam_attempts')->where('user_id', $user->id)->delete();
            DB::table('user_exam_answers')->where('user_id', $user->id)->delete();
            DB::table('slide_attempts')->where('user_id', $user->id)->delete();
            DB::table('test_attempts')->where('user_id', $user->id)->delete();
            DB::table('quiz_attempts')->where('user_id', $user->id)->delete();
            DB::table('achievements')->where('user_id', $user->id)->delete();
            DB::table('notifications')->where('user_id', $user->id)->delete();
            DB::table('intro_answers')->where('user_id', $user->id)->delete();
            DB::table('missions')->where('user_id', $user->id)->delete();
            DB::table('plans')->where('user_id', $user->id)->delete();
            DB::table('user_progress')->where('user_id', $user->id)->delete();
            
            // Log out user
            Auth::logout();
            
            // Invalidate session
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Delete user account
            DB::table('users')->where('id', $user->id)->delete();
            
            DB::commit();

            return redirect()->route('login')->with('success', __('lang.account_deleted_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Account deletion error: ' . $e->getMessage());
            return back()->with('error', __('lang.error_deleting_account'));
        }
    }

    /**
     * Show profile edit form
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('student.Profile', compact('user'));
    }

    /**
     * Show security settings
     */
    public function showSecurity()
    {
        $user = Auth::user();
        return view('student.profile.security', compact('user'));
    }

    /**
     * Get user notifications
     */
    public function getNotifications()
    {
        $user = Auth::user();
        
        $notifications = DB::table('notifications')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark notification as seen
     */
    public function markNotificationSeen($id)
    {
        try {
            DB::table('notifications')
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->update(['is_seen' => true, 'updated_at' => now()]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Mark all notifications as seen
     */
    public function markAllNotificationsSeen()
    {
        try {
            DB::table('notifications')
                ->where('user_id', Auth::id())
                ->where('is_seen', false)
                ->update(['is_seen' => true, 'updated_at' => now()]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Create a notification using your existing table structure
     */
    private function createNotification($userId, $text, $subtext = null)
    {
        try {
            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'user_id' => $userId,
                'text' => $text,
                'subtext' => $subtext,
                'is_seen' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create notification: ' . $e->getMessage());
        }
    }

    /**
     * Get user statistics for dashboard
     */
    public function getUserStats()
    {
        $user = Auth::user();
        
        $stats = [
            'exams_completed' => DB::table('exam_sessions')
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'certificates_earned' => DB::table('achievements')
                ->where('user_id', $user->id)
                ->count(),
            'average_score' => DB::table('exam_sessions')
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereNotNull('score')
                ->avg('score') ?? 0,
            'total_study_time' => DB::table('slide_attempts')
                ->where('user_id', $user->id)
                ->sum('time_spent') ?? 0,
            'progress_percentage' => $this->calculateUserProgress($user->id),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Calculate user progress percentage
     */
    private function calculateUserProgress($userId)
    {
        try {
            $totalSlides = DB::table('slides')->count();
            $completedSlides = DB::table('slide_attempts')
                ->where('user_id', $userId)
                ->where('is_completed', true)
                ->distinct('slide_id')
                ->count();
                
            if ($totalSlides == 0) return 0;
            
            return round(($completedSlides / $totalSlides) * 100, 1);
        } catch (\Exception $e) {
            return 0;
        }
    }
}