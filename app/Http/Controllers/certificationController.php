<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProgress;
use App\Models\Chapter;
use App\Models\Domain;
use App\Models\Exam;

class CertificationController extends Controller
{
    public function certification()
    {
        $user = Auth::user();
        $progress = new \stdClass();

        // عدد الكلي للـ chapters, domains, exams
        $progress->chapters_total = Chapter::count();
        $progress->domains_total = Domain::count();
        $progress->exams_total = Exam::count();

        // عدد الـ chapters, domains, exams اللي اكتملوا
        $progress->chapters_completed = UserProgress::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->whereIn('slide_id', function ($query) {
                $query->select('id')->from('slides')->whereNotNull('chapter_id');
            })->distinct('slide_id')->count();

        $progress->domains_completed = UserProgress::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->whereIn('slide_id', function ($query) {
                $query->select('id')->from('slides')->whereNotNull('domain_id');
            })->distinct('slide_id')->count();

        $progress->exams_completed = UserProgress::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->whereNotNull('exam_id')
            ->distinct('exam_id')->count();

        return view('student.certification', compact('progress'));
    }

    public function downloadCertificate()
    {
        $user = Auth::user();
        // التأكد من إكمال كل الشروط قبل السماح بالتحميل
        $chapters_total = Chapter::count();
        $domains_total = Domain::count();
        $exams_total = Exam::count();

        $chapters_completed = UserProgress::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->whereIn('slide_id', function ($query) {
                $query->select('id')->from('slides')->whereNotNull('chapter_id');
            })->distinct('slide_id')->count();

        $domains_completed = UserProgress::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->whereIn('slide_id', function ($query) {
                $query->select('id')->from('slides')->whereNotNull('domain_id');
            })->distinct('slide_id')->count();

        $exams_completed = UserProgress::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->whereNotNull('exam_id')
            ->distinct('exam_id')->count();

        if ($chapters_completed == $chapters_total && 
            $domains_completed == $domains_total && 
            $exams_completed == $exams_total) {
            // هنا كود تحميل الشهادة (مثلاً باستخدام مكتبة مثل DomPDF)
            // لسه محتاج تطبيق لتحميل الشهادة كـ PDF
            return response()->download(public_path('images/canva-blue-and-gold-simple-certificate-zxaa-6-y-b-ua-u-10.png'), 'certificate.png');
        }

        return redirect()->back()->with('error', 'يجب إكمال جميع الدروس والاختبارات لتحميل الشهادة!');
    }

    public function viewCertificate()
{
    $user = Auth::user();
    // التأكد من إكمال الشروط
    $chapters_total = Chapter::count();
    $domains_total = Domain::count();
    $exams_total = Exam::count();

    $chapters_completed = UserProgress::where('user_id', $user->id)
        ->whereNotNull('completed_at')
        ->whereIn('slide_id', function ($query) {
            $query->select('id')->from('slides')->whereNotNull('chapter_id');
        })->distinct('slide_id')->count();

    $domains_completed = UserProgress::where('user_id', $user->id)
        ->whereNotNull('completed_at')
        ->whereIn('slide_id', function ($query) {
            $query->select('id')->from('slides')->whereNotNull('domain_id');
        })->distinct('slide_id')->count();

    $exams_completed = UserProgress::where('user_id', $user->id)
        ->whereNotNull('completed_at')
        ->whereNotNull('exam_id')
        ->distinct('exam_id')->count();

    if ($chapters_completed == $chapters_total && 
        $domains_completed == $domains_total && 
        $exams_completed == $exams_total) {
        return view('student.certificate-view', ['user' => $user]);
    }

    return redirect()->route('certificate')->with('error', 'الشهادة غير متاحة حتى تكمل جميع الشروط!');
}
}