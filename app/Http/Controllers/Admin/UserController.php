<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);

    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        // Handle verified filter only if explicitly set to '1' or '0'
        if ($request->has('verified') && in_array($request->input('verified'), ['0', '1'])) {
            $query->where('verified', $request->input('verified') === '1');
        }

        $users = $query->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'role' => 'required|in:admin,student',
            'phone' => 'nullable|string|max:20|regex:/^[+\-\d\s]+$/',
            'preferred_language' => 'nullable|string|in:en,fr,es,de,it',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'username.regex' => 'Username may only contain letters, numbers, and underscores.',
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('profile-images', 'public');
            }

            $user = User::create([
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'role' => $validatedData['role'],
                'phone' => $validatedData['phone'] ?? null,
                'preferred_language' => $validatedData['preferred_language'] ?? 'en',
                'email_verified_at' => $request->verified ? now() : null,
                'profile_image' => $imagePath,
                'is_agree' => true,
            ]);

            if (!$request->verified) {
                $user->sendEmailVerificationNotification();
            }

            return redirect()->route('admin.users.index')
                   ->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return back()->withInput()
                   ->with('error', 'Error creating user: '.$e->getMessage());
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load(['quizAttempts', 'testAttempts', 'missions', 'notifications']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username,' . $user->id,
                'regex:/^[a-zA-Z0-9_]+$/'
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users,email,' . $user->id
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'role' => 'required|in:admin,student',
            'phone' => 'nullable|string|max:20|regex:/^[+\-\d\s]+$/',
            'preferred_language' => 'nullable|string|in:en,fr,es,ar',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email_verified' => 'boolean',
            'is_active' => 'boolean'
        ], [
            'password.regex' => 'Password must contain at least one uppercase, one lowercase, one number and one special character.',
            'username.regex' => 'Username may only contain letters, numbers and underscores.',
            'phone.regex' => 'Please enter a valid phone number.'
        ]);

        try {
            if ($request->hasFile('image')) {
                if ($user->profile_image) {
                    Storage::delete('public/'.$user->profile_image);
                }
                $validatedData['profile_image'] = $request->file('image')->store('profile-images', 'public');
            }

            if (!empty($validatedData['password'])) {
                $validatedData['password'] = $validatedData['password'];
            } else {
                unset($validatedData['password']);
            }

            $validatedData['email_verified_at'] = $request->verified == "on" ? now() : null;
            $validatedData['verified'] = $request->verified == "on";
            unset($validatedData['email_verified']);

            \Illuminate\Database\Eloquent\Model::unguard();
            $user->update($validatedData);
            \Illuminate\Database\Eloquent\Model::reguard();

            return redirect()->route('admin.users.index')
                   ->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            if (isset($validatedData['profile_image'])) {
                Storage::delete('public/'.$validatedData['profile_image']);
            }

            return back()->withInput()
                   ->with('error', 'Error updating user: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}