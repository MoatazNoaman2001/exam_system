<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Chapter;
use App\Models\Slide;
use App\Models\Test;
use App\Models\TestAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SlideController extends Controller
{
    /**
     * Display a listing of the slides.
     */
    public function index()
    {
        try {
            $slides = Slide::with(['domain'])
                ->withCount('tests')
                ->latest()
                ->paginate(15);

            return view('admin.slides.index', compact('slides'));
        } catch (\Exception $e) {
            Log::error('Error loading slides: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading slides.');
        }
    }

    /**
     * Show the form for creating a new slide.
     */
    public function create()
    {
        try {
            $domains = Domain::orderBy('text')->get();
            $chapters = Chapter::orderBy('text')->get();
            
            return view('admin.slides.create', compact('domains', 'chapters'));
        } catch (\Exception $e) {
            Log::error('Error loading create form: ' . $e->getMessage());
            return redirect()->route('admin.slides.index')
                ->with('error', 'Error loading create form.');
        }
    }

    /**
     * Store a newly created slide in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $this->validateSlideRequest($request);

        try {
            DB::beginTransaction();

            // Handle PDF file upload
            $pdfPath = null;
            if ($request->hasFile('content')) {
                $file = $request->file('content');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $pdfPath = $file->storeAs('slides/pdfs', $fileName, 'public');
            }

            // Create the slide
            $slide = Slide::create([
                'text' => $validatedData['text'],
                'content' => $pdfPath,
                'domain_id' => $validatedData['domain_id'] ?? null,
                'chapter_id' => $validatedData['chapter_id'] ?? null,
            ]);

            // Create questions if domain is selected and questions are provided
            $questionsCreated = 0;
            if ($slide->domain_id && isset($validatedData['questions']) && is_array($validatedData['questions'])) {
                $questionsCreated = $this->createQuestionsForSlide($slide, $validatedData['questions']);
            }

            DB::commit();

            $message = $questionsCreated > 0 
                ? "Slide created successfully with {$questionsCreated} questions."
                : "Slide created successfully.";

            return redirect()->route('admin.slides.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded file if slide creation failed
            if ($pdfPath && Storage::disk('public')->exists($pdfPath)) {
                Storage::disk('public')->delete($pdfPath);
            }
            
            Log::error('Error creating slide: ' . $e->getMessage(), [
                'request_data' => $request->except(['content']),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'An error occurred while creating the slide. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified slide.
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
     * Show the form for editing the specified slide.
     */
    public function edit(Slide $slide)
    {
        try {
            $domains = Domain::orderBy('text')->get();
            $chapters = Chapter::orderBy('text')->get();
            $slide->load(['tests.answers']);
            
            return view('admin.slides.edit', compact('slide', 'domains', 'chapters'));
        } catch (\Exception $e) {
            Log::error('Error loading edit form: ' . $e->getMessage());
            return redirect()->route('admin.slides.index')
                ->with('error', 'Error loading edit form.');
        }
    }

    /**
     * Update the specified slide in storage.
     */
    public function update(Request $request, Slide $slide)
    {
        // Validate the request
        $validatedData = $this->validateSlideRequest($request, false);

        try {
            DB::beginTransaction();

            $updateData = [
                'text' => $validatedData['text'],
                'domain_id' => $validatedData['domain_id'] ?? null,
                'chapter_id' => $validatedData['chapter_id'] ?? null,
            ];

            // Handle PDF file upload if new file provided
            if ($request->hasFile('content')) {
                // Delete old file
                if ($slide->content && Storage::disk('public')->exists($slide->content)) {
                    Storage::disk('public')->delete($slide->content);
                }
                
                $file = $request->file('content');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $updateData['content'] = $file->storeAs('slides/pdfs', $fileName, 'public');
            }

            // Update the slide
            $slide->update($updateData);

            // Handle questions update
            $questionsCreated = 0;
            if ($slide->domain_id && isset($validatedData['questions']) && is_array($validatedData['questions'])) {
                // Delete existing tests and answers
                $slide->tests()->delete(); // This will cascade delete answers
                
                // Create new questions
                $questionsCreated = $this->createQuestionsForSlide($slide, $validatedData['questions']);
            } elseif (!$slide->domain_id) {
                // If domain is removed, delete all questions
                $slide->tests()->delete();
            }

            DB::commit();

            $message = $questionsCreated > 0 
                ? "Slide updated successfully with {$questionsCreated} questions."
                : "Slide updated successfully.";

            return redirect()->route('admin.slides.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating slide: ' . $e->getMessage(), [
                'slide_id' => $slide->id,
                'request_data' => $request->except(['content']),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'An error occurred while updating the slide. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified slide from storage.
     */
    public function destroy(Slide $slide)
    {
        try {
            DB::beginTransaction();

            // Delete PDF file
            if ($slide->content && Storage::disk('public')->exists($slide->content)) {
                Storage::disk('public')->delete($slide->content);
            }
            
            // Delete the slide (will cascade delete tests and answers)
            $slide->delete();
            
            DB::commit();
            
            return redirect()->route('admin.slides.index')
                ->with('success', 'Slide deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting slide: ' . $e->getMessage(), [
                'slide_id' => $slide->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the slide.');
        }
    }

    /**
     * Download the PDF file for a slide.
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
     * Duplicate a slide.
     */
    public function duplicate(Slide $slide)
    {
        try {
            DB::beginTransaction();

            // Create a copy of the slide
            $newSlide = $slide->replicate();
            $newSlide->text = $slide->text . ' (Copy)';
            $newSlide->save();

            // Copy PDF file if exists
            if ($slide->content && Storage::disk('public')->exists($slide->content)) {
                $oldPath = $slide->content;
                $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
                $newFileName = time() . '_copy_' . pathinfo($oldPath, PATHINFO_FILENAME) . '.' . $extension;
                $newPath = 'slides/pdfs/' . $newFileName;
                
                Storage::disk('public')->copy($oldPath, $newPath);
                $newSlide->content = $newPath;
                $newSlide->save();
            }

            // Copy questions and answers
            foreach ($slide->tests as $test) {
                $newTest = Test::create([
                    'question_ar' => $test->question_ar,
                    'question_en' => $test->question_en,
                    'slide_id' => $newSlide->id,
                ]);

                foreach ($test->answers as $answer) {
                    TestAnswer::create([
                        'text_ar' => $answer->text_ar,
                        'text_en' => $answer->text_en,
                        'is_correct' => $answer->is_correct,
                        'test_id' => $newTest->id,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.slides.show', $newSlide)
                ->with('success', 'Slide duplicated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error duplicating slide: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error duplicating slide.');
        }
    }

    /**
     * Get questions for AJAX requests.
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
     * Validate slide request.
     */
    private function validateSlideRequest(Request $request, $isCreate = true)
    {
        $rules = [
            'text' => 'required|string|max:255',
            'domain_id' => 'nullable|exists:domains,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'questions' => 'nullable|array',
            'questions.*.question_ar' => 'required_with:questions|string|max:1000',
            'questions.*.question_en' => 'required_with:questions|string|max:1000',
            'questions.*.answers' => 'required_with:questions|array|min:2|max:6',
            'questions.*.answers.*.text_ar' => 'required_with:questions.*.answers|string|max:255',
            'questions.*.answers.*.text_en' => 'required_with:questions.*.answers|string|max:255',
            'questions.*.correct_answer' => 'required_with:questions|integer|min:0',
        ];

        // Add content validation based on create/update
        if ($isCreate) {
            $rules['content'] = 'required|file|mimes:pdf|max:5120'; // 5MB max for create
        } else {
            $rules['content'] = 'nullable|file|mimes:pdf|max:5120'; // Optional for update
        }

        $messages = [
            'text.required' => 'Slide title is required.',
            'text.max' => 'Slide title cannot exceed 255 characters.',
            'content.required' => 'PDF file is required.',
            'content.file' => 'Content must be a valid file.',
            'content.mimes' => 'File must be a PDF.',
            'content.max' => 'File size must not exceed 5MB.',
            'domain_id.exists' => 'Selected domain does not exist.',
            'chapter_id.exists' => 'Selected chapter does not exist.',
            
            // Question validation messages
            'questions.*.question_ar.required_with' => 'Question in Arabic is required when adding questions.',
            'questions.*.question_ar.max' => 'Question in Arabic cannot exceed 1000 characters.',
            'questions.*.question_en.required_with' => 'Question in English is required when adding questions.',
            'questions.*.question_en.max' => 'Question in English cannot exceed 1000 characters.',
            
            // Answer validation messages
            'questions.*.answers.required_with' => 'Each question must have answers.',
            'questions.*.answers.min' => 'Each question must have at least 2 answers.',
            'questions.*.answers.max' => 'Each question cannot have more than 6 answers.',
            'questions.*.answers.*.text_ar.required_with' => 'Answer text in Arabic is required.',
            'questions.*.answers.*.text_ar.max' => 'Answer text in Arabic cannot exceed 255 characters.',
            'questions.*.answers.*.text_en.required_with' => 'Answer text in English is required.',
            'questions.*.answers.*.text_en.max' => 'Answer text in English cannot exceed 255 characters.',
            'questions.*.correct_answer.required_with' => 'Please select the correct answer for each question.',
            'questions.*.correct_answer.integer' => 'Correct answer must be a valid number.',
        ];

        return $request->validate($rules, $messages);
    }

    /**
     * Create questions for a slide.
     */
    private function createQuestionsForSlide(Slide $slide, array $questions): int
    {
        $questionsCreated = 0;

        foreach ($questions as $questionData) {
            // Validate that correct_answer index exists in answers array
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
     * Get slides by domain for AJAX requests.
     */
    public function getSlidesByDomain(Request $request)
    {
        try {
            $domainId = $request->get('domain_id');
            
            if (!$domainId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Domain ID is required.'
                ], 400);
            }

            $slides = Slide::where('domain_id', $domainId)
                ->withCount('tests')
                ->orderBy('text')
                ->get()
                ->map(function ($slide) {
                    return [
                        'id' => $slide->id,
                        'text' => $slide->text,
                        'questions_count' => $slide->tests_count,
                        'has_pdf' => $slide->content ? true : false,
                    ];
                });

            return response()->json([
                'success' => true,
                'slides' => $slides
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting slides by domain: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading slides.'
            ], 500);
        }
    }
}