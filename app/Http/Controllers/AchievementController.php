<?php

    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Carbon\Carbon;

class AchievementController extends Controller
{
       public function Achievement()
    {

        $user = Auth::user();
        if (!$user) {
            abort(403, 'يجب تسجيل الدخول أولاً.');
        }

        // جلب أو إنشاء تقدم المستخدم
        $progress = $user->progress ?? $user->progress()->create([
            'days_left' => 30, // القيمة الافتراضية تعتمد على مدة الخطة
            'progress' => 0,
            'points' => 0,
            'current_level' => 'مبتدئ',
            'points_to_next_level' => 150,
            'plan_duration' => 30, // مدة الخطة (يمكن تغييرها حسب الحاجة)
            'plan_end_date' => Carbon::now()->addDays(30)->toDateString(), // نهاية الخطة بعد 30 يومًا
            'domains_completed' => 0,
            'domains_total' => 5,
            'lessons_completed' => 0,
            'lessons_total' => 40,
            'exams_completed' => 0,
            'exams_total' => 10,
            'questions_completed' => 0,
            'questions_total' => 200,
            'lessons_milestone' => 0,
            'questions_milestone' => 0,
            'streak_days' => 0,
        ]);

        // حساب الأيام المتبقية بناءً على plan_end_date
        $planEndDate = Carbon::parse($progress->plan_end_date);
        $daysLeft = max(0, $planEndDate->diffInDays(Carbon::now(), false));
        $progress->days_left = $daysLeft; // تحديث القيمة يوميًا
        $progress->save();
        return view('student.Achievement', compact('user', 'progress'));
    }
}