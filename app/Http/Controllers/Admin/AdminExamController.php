<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use Illuminate\Support\Str;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use App\Models\ExamQuestions;
use App\Models\ExamQuestionAnswer;
use App\Models\QuestionExamAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;

class AdminExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of exams
     */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search');

            $exams = Exam::query()
                ->withCount(['examQuestions as questions_count'])
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('text', 'like', "%{$search}%")
                          ->orWhere('text-ar', 'like', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate(15);

            return view('admin.exams.index', compact('exams', 'search'));
        } catch (\Exception $e) {
            Log::error('Error loading exams index', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error loading exams.');
        }
    }

    /**
     * Show the form for creating a new exam
     */
    public function create()
    {
        return view('admin.exams.create');
    }

    /**
     * Store a newly created exam (basic info only)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
            'duration' => 'required|integer|min:1|max:300',
        ]);

        try {
            $exam = Exam::create([
                'id' => Str::uuid(),
                'text' => $request->title_en,
                'text-ar' => $request->title_ar,
                'description' => $request->description_en,
                'description-ar' => $request->description_ar,
                'time' => $request->duration,
                'number_of_questions' => 0,
                'is_completed' => false,
            ]);

            return redirect()->route('admin.exams.questions.index', $exam->id)
                ->with('success', 'Exam created successfully! Now add questions to complete your exam.');
        } catch (\Exception $e) {
            Log::error('Exam creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the exam.');
        }
    }

    /**
     * Display the specified exam
     */
    public function show($examId)
    {
        try {
            $exam = Exam::with(['examQuestions.answers'])
                ->findOrFail($examId);

            return view('admin.exams.show', compact('exam'));
        } catch (\Exception $e) {
            Log::error('Error showing exam', ['exam_id' => $examId, 'error' => $e->getMessage()]);
            return redirect()->route('admin.exams.index')->with('error', 'Exam not found.');
        }
    }

    /**
     * Show the form for editing the specified exam (basic info only)
     */
    public function edit($examId)
    {
        try {
            $exam = Exam::findOrFail($examId);
            return view('admin.exams.edit', compact('exam'));
        } catch (\Exception $e) {
            Log::error('Error loading exam for edit', ['exam_id' => $examId, 'error' => $e->getMessage()]);
            return redirect()->route('admin.exams.index')->with('error', 'Exam not found.');
        }
    }

    /**
     * Update the specified exam (basic info only)
     */
    public function update(Request $request, $examId)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
            'duration' => 'required|integer|min:1|max:300',
        ]);

        try {
            $exam = Exam::findOrFail($examId);

            $exam->update([
                'text' => $request->title_en,
                'text-ar' => $request->title_ar,
                'description' => $request->description_en,
                'description-ar' => $request->description_ar,
                'time' => $request->duration,
            ]);

            return redirect()->route('admin.exams.show', $exam->id)
                ->with('success', 'Exam updated successfully!');

        } catch (\Exception $e) {
            Log::error('Exam update failed', [
                'exam_id' => $examId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the exam: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified exam
     */
    public function destroy($examId)
    {
        try {
            $exam = Exam::findOrFail($examId);

            // Check if exam has sessions
            if (DB::table('exam_sessions')->where('exam_id', $exam->id)->exists()) {
                return redirect()->back()
                    ->with('error', 'Cannot delete exam that has student sessions.');
            }

            DB::transaction(function () use ($exam) {
                // Delete questions and answers (cascade)
                $exam->examQuestions()->delete();
                
                // Delete exam
                $exam->delete();
            });

            return redirect()->route('admin.exams.index')
                ->with('success', 'Exam deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Exam deletion failed', [
                'exam_id' => $examId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while deleting the exam.');
        }
    }

   
}