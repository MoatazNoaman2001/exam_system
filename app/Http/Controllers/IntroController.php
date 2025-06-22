<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IntroQuestion;
use App\Models\IntroSelection;
use App\Models\IntroAnswer;
use Illuminate\Support\Facades\Validator;
class IntroController extends Controller
{
    // أول صفحة بتحول على أول خطوة
    public function index()
    {
        return redirect()->route('student.intro.step', 1);
    }

    // عرض سؤال معين
    public function step($step)
    {
        $question = IntroQuestion::with('introSelections')
            ->orderBy('id')
            ->skip($step - 1)
            ->first();

        if (!$question) {
            return redirect()->route('student.intro.complete');
        }

        return view('intro.step', compact('question', 'step'));
    }

    // // حفظ إجابة السؤال
    // public function store(Request $request, $step)
    // {
    //     $question = IntroQuestion::orderBy('id')->skip($step - 1)->firstOrFail();

    //     $validated = $request->validate([
    //         'selection_id' => 'required|exists:intro_selections,id',
    //         'extra_text' => 'nullable|string|max:1000',
    //     ]);

    //     // مؤقتًا من غير تسجيل دخول، نستخدم session ID أو user ID ثابت للتجربة
    //     $userId = 1;

    //     IntroAnswer::updateOrCreate([
    //         'user_id' => $userId,
    //         'question_id' => $question->id,
    //     ], [
    //         'selection_id' => $validated['selection_id'],
    //         'extra_text' => $validated['extra_text'] ?? null,
    //     ]);

    //     return redirect()->route('intro.step', $step + 1);
    // }
    public function store(Request $request, $step)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|uuid|exists:users,id',
            'question_id' => 'required|uuid|exists:intro_questions,id',
            'selection_id' => 'required|exists:intro_selections,id',
            'extra_text' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Verify selection belongs to the question
        $selection = IntroSelection::find($validated['selection_id']);
        if (!$selection || $selection->question_id !== $validated['question_id']) {
            return response()->json([
                'message' => 'Selected option does not belong to this question'
            ], 422);
        }

        // Check if extra text is required
        if ($selection->has_extra_text && empty($validated['extra_text'])) {
            return response()->json([
                'message' => 'Extra text is required for this selection'
            ], 422);
        }

        // Check for existing answer
        $exists = IntroAnswer::where([
            'user_id' => $validated['user_id'],
            'question_id' => $validated['question_id'],
            'selection_id' => $validated['selection_id']
        ])->exists();

        if ($exists) {
            return redirect()->route('student.intro.step', $step + 1);
        }

        // Create the answer without expecting an ID return
        IntroAnswer::create($validated);

        return redirect()->route('student.intro.step', $step + 1);
    }

    // آخر صفحة (تم الانتهاء)
    public function complete()
    {
        return view('intro.complete');
    }
}
