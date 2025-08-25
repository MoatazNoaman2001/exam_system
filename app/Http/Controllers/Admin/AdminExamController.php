<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use App\Models\Certificate;
use Illuminate\Support\Str;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use App\Models\ExamQuestions;
use App\Models\ExamQuestionAnswer;
use App\Models\QuestionExamAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;

class AdminExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            // dd(Auth::user()->role);
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
        $search = $request->get('search');
            $certificateId = $request->get('certificate_id');

            
            $exams = Exam::query()
                ->with('certificate')
                ->withCount(['examQuestions as questions_count'])
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('text', 'like', "%{$search}%")
                          ->orWhere('text-ar', 'like', "%{$search}%");
                    });
                })
                ->when($certificateId, function ($query, $certificateId) {
                    $query->where('certificate_id', $certificateId);
                })
                ->latest()
                ->paginate(15);

            // Get certificates for filter dropdown
            $certificates = Certificate::active()->ordered()->get();

            return view('admin.exams.index', compact('exams', 'search', 'certificates', 'certificateId'));
    }

    /**
     * Show the form for creating a new exam
     */
    public function create()
    {
        $certificates = Certificate::active()->ordered()->get();
        return view('admin.exams.create', compact('certificates'));
    }

    /**
     * Store a newly created exam (basic info only)
     */
    public function store(Request $request)
    {
        $request->validate([
            'certificate_id' => 'required|exists:certificates,id',
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
            'duration' => 'required|integer|min:1|max:300',
        ]);

        try {
            DB::beginTransaction();

            $exam = Exam::create([
                'id' => Str::uuid(),
                'certificate_id' => $request->certificate_id,
                'text' => $request->title_en,
                'text-ar' => $request->title_ar,
                'description' => $request->description_en,
                'description-ar' => $request->description_ar,
                'time' => $request->duration,
                'number_of_questions' => 0,
                'is_completed' => false,
            ]);

            // Update certificate timestamp to reflect new exam
            if ($exam->certificate) {
                $exam->certificate->touch();
            }

            DB::commit();

            return redirect()->route('admin.exams.questions.index', $exam->id)
                ->with('success', 'Exam created successfully! Now add questions to complete your exam.');
        } catch (\Exception $e) {
            DB::rollBack();
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
            $exam = Exam::with(['certificate', 'examQuestions.answers'])
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
            $exam = Exam::with('certificate')->findOrFail($examId);
            $certificates = Certificate::active()->ordered()->get();
            return view('admin.exams.edit', compact('exam', 'certificates'));
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
            'certificate_id' => 'required|exists:certificates,id',
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
            'duration' => 'required|integer|min:1|max:300',
        ]);

        try {
            DB::beginTransaction();

            $exam = Exam::findOrFail($examId);

            $oldCertificateId = $exam->certificate_id;

            $exam->update([
                'certificate_id' => $request->certificate_id,
                'text' => $request->title_en,
                'text-ar' => $request->title_ar,
                'description' => $request->description_en,
                'description-ar' => $request->description_ar,
                'time' => $request->duration,
            ]);

            $exam->refresh(); // Refresh to get latest data
            // Update new certificate timestamp
            
            $exam->certificate->touch();

            // Update old certificate timestamp if changed
            if ($oldCertificateId && $oldCertificateId !== $exam->certificate_id) {
                Certificate::find($oldCertificateId)?->touch();
            }


            DB::commit();

            return redirect()->route('admin.exams.show', $exam->id)
                ->with('success', 'Exam updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
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
            $exam = Exam::with('certificate')->findOrFail($examId);

            // Check if exam has sessions
            if (DB::table('exam_sessions')->where('exam_id', $exam->id)->exists()) {
                return redirect()->back()
                    ->with('error', 'Cannot delete exam that has student sessions.');
            }

            DB::transaction(function () use ($exam) {
                $certificate = $exam->certificate;
                
                // Delete questions and answers (cascade)
                $exam->examQuestions()->delete();
                
                // Delete exam
                $exam->delete();

                // Update certificate timestamp to reflect exam deletion
                $certificate?->touch();
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

    /**
     * Get exams by certificate (AJAX)
     */
    public function getByCertificate(Certificate $certificate)
    {
        try {
            $exams = $certificate->exams()
                ->withCount('examQuestions')
                ->orderBy('text')
                ->get();

            return response()->json([
                'success' => true,
                'exams' => $exams
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting exams by certificate', [
                'certificate_id' => $certificate->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error loading exams.'
            ], 500);
        }
    }

    /**
     * Duplicate an exam
     */
    public function duplicate($examId)
    {
        try {
            DB::beginTransaction();

            $originalExam = Exam::with(['examQuestions.answers'])->findOrFail($examId);

            // Create new exam
            $newExam = Exam::create([
                'id' => Str::uuid(),
                'certificate_id' => $originalExam->certificate_id,
                'text' => $originalExam->text . ' (Copy)',
                'text-ar' => $originalExam->{'text-ar'} . ' (نسخة)',
                'description' => $originalExam->description,
                'description-ar' => $originalExam->{'description-ar'},
                'time' => $originalExam->time,
                'number_of_questions' => $originalExam->number_of_questions,
                'is_completed' => false,
            ]);

            // Copy questions and answers
            foreach ($originalExam->examQuestions as $question) {
                $newQuestion = ExamQuestions::create([
                    'exam_id' => $newExam->id,
                    'question' => $question->question,
                    'question-ar' => $question->{'question-ar'},
                    'text-ar' => $question->{'text-ar'},
                    'type' => $question->type,
                    'marks' => $question->marks,
                ]);

                // Copy answers
                foreach ($question->answers as $answer) {
                    ExamQuestionAnswer::create([
                        'exam_question_id' => $newQuestion->id,
                        'answer' => $answer->answer,
                        'answer-ar' => $answer->{'answer-ar'},
                        'is_correct' => $answer->is_correct,
                    ]);
                }
            }

            // Update certificate timestamp
            $newExam->certificate->touch();

            DB::commit();

            return redirect()->route('admin.exams.edit', $newExam->id)
                ->with('success', 'Exam duplicated successfully! You can now modify the copy.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Exam duplication failed', [
                'exam_id' => $examId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while duplicating the exam.');
        }
    }

    /**
     * Toggle exam completion status
     */
    public function toggleCompletion($examId)
    {
        try {
            $exam = Exam::findOrFail($examId);
            
            $exam->update(['is_completed' => !$exam->is_completed]);
            
            $status = $exam->is_completed ? 'completed' : 'active';
            
            return response()->json([
                'success' => true,
                'message' => "Exam marked as {$status} successfully.",
                'is_completed' => $exam->is_completed
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating exam status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get exam statistics
     */
    public function getStats($examId)
    {
        try {
            $exam = Exam::with(['examQuestions', 'certificate'])->findOrFail($examId);

            $stats = [
                'total_questions' => $exam->examQuestions->count(),
                'total_marks' => $exam->examQuestions->sum('marks'),
                'avg_marks_per_question' => $exam->examQuestions->count() > 0 
                    ? round($exam->examQuestions->avg('marks'), 2) 
                    : 0,
                'duration_minutes' => $exam->time,
                'certificate' => [
                    'name' => app()->getLocale() == 'ar' ? $exam->certificate->name_ar : $exam->certificate->name,
                    'code' => $exam->certificate->code,
                    'color' => $exam->certificate->color,
                ],
                'question_types' => $exam->examQuestions
                    ->groupBy('type')
                    ->map(function ($questions, $type) {
                        return [
                            'type' => $type,
                            'count' => $questions->count(),
                            'marks' => $questions->sum('marks')
                        ];
                    })
                    ->values(),
                'created_at' => $exam->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $exam->updated_at->format('Y-m-d H:i:s'),
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading exam statistics.'
            ], 500);
        }
    }
}