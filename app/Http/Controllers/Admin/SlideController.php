<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use App\Models\Domain;
use App\Models\Chapter;
use App\Models\Test;
use App\Models\TestAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SlideController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of slides
     */
    public function index()
    {
        try {
            $slides = Slide::with(['domain', 'chapter'])
                ->withCount('tests')
                ->latest()
                ->paginate(20);
            
            return view('admin.slides.index', compact('slides'));
        } catch (\Exception $e) {
            Log::error('Error loading slides: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading slides.');
        }
    }

    /**
     * Show the form for creating a new slide
     */
    public function create()
    {
        try {
            $domains = Domain::orderBy('text')->get();
            $chapters = Chapter::orderBy('text')->get();
            
            return view('admin.slides.create', compact('domains', 'chapters'));
        } catch (\Exception $e) {
            Log::error('Error loading create slide form: ' . $e->getMessage());
            return redirect()->route('admin.slides.index')
                ->with('error', 'Error loading create form.');
        }
    }

    /**
     * Store a newly created slide
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateSlideData($request);

        try {
            DB::beginTransaction();

            // Store the PDF file
            $filePath = $request->file('content')->store('slides', 'public');


            // Create the slide
            $slide = Slide::create([
                'text' => $validatedData['text'],
                'content' => $filePath,
                'domain_id' => empty($validatedData['domain_id']) ? null : $validatedData['domain_id'],
                'chapter_id' => empty($validatedData['chapter_id']) ? null : $validatedData['chapter_id'],
            ]);

            // Update associated certificate timestamp
            $this->updateRelatedCertificate($slide);



            // Create questions ONLY if domain is selected and questions are provided
            $questionsCreated = 0;
            if ($slide->domain_id && isset($validatedData['questions']) && is_array($validatedData['questions'])) {
                $questionsCreated = $this->createQuestionsForSlide($slide, $validatedData['questions']);
            }


            DB::commit();

            $message = $questionsCreated > 0 
                ? "Slide created successfully with {$questionsCreated} questions."
                : "Slide created successfully.";


            return redirect()
                ->route('admin.slides')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded file on error
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            Log::error('Error creating slide: ' . $e->getMessage(), [
                'request_data' => $request->except(['content']),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error creating slide: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified slide
     */
    public function show(Slide $slide)
    {
        try {
            $slide->load(['domain', 'chapter', 'tests.answers']);
            return view('admin.slides.show', compact('slide'));
        } catch (\Exception $e) {
            Log::error('Error showing slide: ' . $e->getMessage());
            return redirect()->route('admin.slides.index')
                ->with('error', 'Error loading slide details.');
        }
    }

    /**
     * Show the form for editing the specified slide
     */
    public function edit(Slide $slide)
    {
        try {
            $domains = Domain::orderBy('text')->get();
            $chapters = Chapter::orderBy('text')->get();
            
            // Load existing questions for editing
            $slide->load(['tests.answers']);
            
            return view('admin.slides.edit', compact('slide', 'domains', 'chapters'));
        } catch (\Exception $e) {
            Log::error('Error loading edit slide form: ' . $e->getMessage());
            return redirect()->route('admin.slides.index')
                ->with('error', 'Error loading edit form.');
        }
    }

    /**
     * Update the specified slide
     */
    public function update(Request $request, Slide $slide)
    {
        $validatedData = $this->validateSlideData($request, false);
        DB::beginTransaction();

        // Store old associations for certificate update
        $oldDomainId = $slide->domain_id;
        $oldChapterId = $slide->chapter_id;

        $updateData = [
            'text' => $validatedData['text'],
            'domain_id' => $validatedData['domain_id'] ?? null,
            'chapter_id' => $validatedData['chapter_id'] ?? null,
        ];
        // Handle PDF file update if new file provided
        if ($request->hasFile('content')) {
            
            // Delete old file
            if ($slide->content && Storage::disk('public')->exists($slide->content)) {
                Storage::disk('public')->delete($slide->content);
            }
            
            $updateData['content'] = $request->file('content')->store('slides', 'public');
        }
        else{

            $updateData['content'] = $slide['content'];
        }

        dd('updateDate: ' . json_encode($updateData) . 'content: ' . ($request->hasFile('content') ? 'true': 'false'));
        
        // Update the slide
        $slide->update($updateData);


        dd('slide: ' . strval($slide));


        // Update related certificates (old and new)
        $this->updateRelatedCertificate($slide, $oldDomainId, $oldChapterId);

        // Handle questions update - ONLY for slides with domains
        $questionsCreated = 0;
        if ($slide->domain_id) {
            // If domain is selected, handle questions
            if (isset($validatedData['questions']) && is_array($validatedData['questions'])) {
                // Delete existing tests and answers
                $slide->tests()->delete();
                
                // Create new questions
                $questionsCreated = $this->createQuestionsForSlide($slide, $validatedData['questions']);
            }
        } else {
            // If no domain selected, remove all questions
            $slide->tests()->delete();
        }

        DB::commit();

        $message = $questionsCreated > 0 
            ? "Slide updated successfully with {$questionsCreated} questions."
            : "Slide updated successfully.";

        return redirect()->route('admin.slides')
            ->with('success', $message);
        // try {
            

        // } catch (\Exception $e) {
        //     DB::rollBack();
            
        //     Log::error('Error updating slide: ' . $e->getMessage(), [
        //         'slide_id' => $slide->id,
        //         'request_data' => $request->except(['content'])
        //     ]);

        //     return back()
        //         ->withInput()
        //         ->with('error', 'Error updating slide: ' . $e->getMessage());
        // }
    }

    /**
     * Remove the specified slide
     */
    public function destroy(Slide $slide)
    {
        try {
            DB::beginTransaction();

            // Store associations for certificate update
            $domainId = $slide->domain_id;
            $chapterId = $slide->chapter_id;

            // Delete the PDF file
            if ($slide->content && Storage::disk('public')->exists($slide->content)) {
                Storage::disk('public')->delete($slide->content);
            }

            // Delete the slide (will cascade delete tests and answers)
            $slide->delete();

            // Update related certificate
            $this->updateCertificateFromIds($domainId, $chapterId);
            
            DB::commit();

            return redirect()->route('admin.slides')
                ->with('success', 'Slide deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting slide: ' . $e->getMessage(), [
                'slide_id' => $slide->id
            ]);

            return redirect()->route('admin.slides.index')
                ->with('error', 'Error deleting slide: ' . $e->getMessage());
        }
    }

    /**
     * Download slide PDF
     */
    public function downloadPdf(Slide $slide)
    {
        try {
            if (!$slide->content || !Storage::disk('public')->exists($slide->content)) {
                return redirect()->back()->with('error', 'PDF file not found.');
            }

            $fileName = $slide->text . '.pdf';
            return Storage::disk('public')->download($slide->content, $fileName);
            
        } catch (\Exception $e) {
            Log::error('Error downloading PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error downloading PDF file.');
        }
    }

    /**
     * Get questions for AJAX requests
     */
    public function getQuestions(Slide $slide)
    {
        try {
            $questions = $slide->tests()
                ->with('answers')
                ->get()
                ->map(function ($test) {
                    return [
                        'id' => $test->id,
                        'question_ar' => $test->question_ar,
                        'question_en' => $test->question_en,
                        'answers' => $test->answers->map(function ($answer) {
                            return [
                                'id' => $answer->id,
                                'text_ar' => $answer->text_ar,
                                'text_en' => $answer->text_en,
                                'is_correct' => $answer->is_correct,
                            ];
                        }),
                    ];
                });

            return response()->json([
                'success' => true,
                'questions' => $questions
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting questions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading questions.'
            ], 500);
        }
    }

    /**
     * Private helper methods
     */
    
    /**
     * Validate slide data including questions
     */
    private function validateSlideData(Request $request, $isCreate = true)
    {
        $rules = [
            'text' => 'required|string|max:255',
            'domain_id' => 'nullable|exists:domains,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            
            // Question validation rules - only when questions are provided
            'questions' => 'nullable|array',
            'questions.*.question_ar' => 'required_with:questions|string|max:1000',
            'questions.*.question_en' => 'required_with:questions|string|max:1000',
            'questions.*.answers' => 'required_with:questions|array|min:2|max:6',
            'questions.*.answers.*.text_ar' => 'required_with:questions.*.answers|string|max:255',
            'questions.*.answers.*.text_en' => 'required_with:questions.*.answers|string|max:255',
            'questions.*.correct_answer' => 'required_with:questions|integer|min:0',
        ];

        // File validation based on create/update
        if ($isCreate) {
            $rules['content'] = 'required|file|mimes:pdf|max:512000';
        } else {
            $rules['content'] = 'nullable|file|mimes:pdf|max:512000';
        }

        $messages = [
            'text.required' => 'Slide title is required.',
            'content.required' => 'PDF file is required.',
            'content.mimes' => 'File must be a PDF.',
            'content.max' => 'File size must not exceed 5MB.',
            
            // Question validation messages
            'questions.*.question_ar.required_with' => 'Question in Arabic is required.',
            'questions.*.question_en.required_with' => 'Question in English is required.',
            'questions.*.answers.min' => 'Each question must have at least 2 answers.',
            'questions.*.answers.max' => 'Each question cannot have more than 6 answers.',
            'questions.*.answers.*.text_ar.required_with' => 'Answer text in Arabic is required.',
            'questions.*.answers.*.text_en.required_with' => 'Answer text in English is required.',
            'questions.*.correct_answer.required_with' => 'Please select the correct answer.',
        ];

        $validatedData = $request->validate($rules, $messages);

        // Additional validation: Either domain or chapter must be selected
        if (empty($validatedData['domain_id']) && empty($validatedData['chapter_id'])) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['domain_id' => 'Either Domain or Chapter must be selected.']
            );
        }

        // Additional validation: Questions can only be added if domain is selected
        if (!empty($validatedData['questions']) && empty($validatedData['domain_id'])) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['questions' => 'Questions can only be added to slides with a domain selected.']
            );
        }

        return $validatedData;
    }

    /**
     * Create questions for a slide
     */
    private function createQuestionsForSlide(Slide $slide, array $questions): int
    {
        $questionsCreated = 0;

        foreach ($questions as $questionData) {
            // Validate correct answer index
            $correctAnswerIndex = (int) $questionData['correct_answer'];
            
            if (!isset($questionData['answers'][$correctAnswerIndex])) {
                throw new \InvalidArgumentException('Invalid correct answer index for question.');
            }

            // Create the test (question)
            $test = Test::create([
                'question_ar' => $questionData['question_ar'],
                'question_en' => $questionData['question_en'],
                'slide_id' => $slide->id,
            ]);

            // Create answers
            foreach ($questionData['answers'] as $answerIndex => $answerData) {
                TestAnswer::create([
                    'text_ar' => $answerData['text_ar'],
                    'text_en' => $answerData['text_en'],
                    'is_correct' => $answerIndex == $correctAnswerIndex,
                    'test_id' => $test->id,
                ]);
            }

            $questionsCreated++;
        }

        return $questionsCreated;
    }

    /**
     * Update related certificate timestamp when slide changes
     */
    private function updateRelatedCertificate(Slide $slide, $oldDomainId = null, $oldChapterId = null)
    {
        // Update current certificate
        if ($slide->domain_id) {
            $slide->domain->certificate?->touch();
        }
        if ($slide->chapter_id) {
            $slide->chapter->certificate?->touch();
        }

        // Update old certificates if they changed
        if ($oldDomainId && $oldDomainId !== $slide->domain_id) {
            Domain::find($oldDomainId)?->certificate?->touch();
        }
        if ($oldChapterId && $oldChapterId !== $slide->chapter_id) {
            Chapter::find($oldChapterId)?->certificate?->touch();
        }
    }

    /**
     * Update certificate from domain/chapter IDs
     */
    private function updateCertificateFromIds($domainId, $chapterId)
    {
        if ($domainId) {
            Domain::find($domainId)?->certificate?->touch();
        }
        if ($chapterId) {
            Chapter::find($chapterId)?->certificate?->touch();
        }
    }
}