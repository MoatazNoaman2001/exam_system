<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AchievementPointController extends Controller
{
         public function AchievementPoint()
    {
  $user = Auth()->user();
    $progress = $user->progress;
        
        return view('student.Achievement-point');
    }
}
