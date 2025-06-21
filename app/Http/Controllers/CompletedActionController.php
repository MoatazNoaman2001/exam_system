<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CompletedActionController extends Controller
{
    public function completedAction(User $user)
    {
        if (auth()->id() !== $user->id) {
            abort(403, 'غير مصرح لك بعرض هذه الصفحة.');
        }

        return view('student.completedAction', compact('user'));
    }
}
