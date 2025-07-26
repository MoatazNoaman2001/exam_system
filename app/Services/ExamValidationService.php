<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Support\Facades\Log;

class ExamValidationService
{
    /**
     * Validate exam integrity after creation/update
     */
    public function validateExamIntegrity(Exam $exam): array
    {
        $errors = [];
        $warnings = [];
        $statistics = [];

        try {
            // Load required relationships
            $exam->load(['examQuestions.answers']);

            // Basic exam validation
            $this->validateBasicExamData($exam, $errors);

            // Validate questions
            $this->validateExamQuestions($exam, $errors, $warnings);

            // Generate statistics
            $statistics = $this->generateExamStatistics($exam);

            // Business logic validation
            $this->validateBusinessRules($exam, $errors, $warnings);

        } catch (\Exception $e) {
            Log::error('Exam integrity validation failed', [
                'exam_id' => $exam->id,
                'error' => $e->getMessage()
            ]);
            $errors[] = 'An error occurred during exam validation.';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
            'statistics' => $statistics
        ];
    }

    /**
     * Validate exam data from request (before saving)
     */
    public function validateExamData(array $examData, Exam $existingExam = null): array
    {
        $errors = [];
        $warnings = [];
        $statistics = [];

        try {
            // Validate basic structure
            $this->validateExamDataStructure($examData, $errors);

            // Validate questions data
            if (isset($examData['questions'])) {
                $this->validateQuestionsData($examData['questions'], $errors, $warnings);
                $statistics = $this->generateDataStatistics($examData);
            }

        } catch (\Exception $e) {
            Log::error('Exam data validation failed', [
                'error' => $e->getMessage(),
                'data' => $examData
            ]);
            $errors[] = 'An error occurred during data validation.';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
            'statistics' => $statistics
        ];
    }

    /**
     * Validate individual question data
     */
    public function validateQuestionData(array $questionData, int $questionNumber): array
    {
        $errors = [];

        // Basic structure validation
        if (empty($questionData['text_en'])) {
            $errors[] = "Question {$questionNumber}: English text is required.";
        }

        if (empty($questionData['text_ar'])) {
            $errors[] = "Question {$questionNumber}: Arabic text is required.";
        }

        if (empty($questionData['type']) || !in_array($questionData['type'], ['single_choice', 'multiple_choice'])) {
            $errors[] = "Question {$questionNumber}: Valid question type is required.";
        }

        if (!isset($questionData['points']) || $questionData['points'] < 1) {
            $errors[] = "Question {$questionNumber}: Points must be at least 1.";
        }

        // Validate options
        if (empty($questionData['options']) || !is_array($questionData['options'])) {
            $errors[] = "Question {$questionNumber}: Options are required.";
        } else {
            $this->validateQuestionOptions($questionData, $questionNumber, $errors);
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Validate basic exam data
     */
    protected function validateBasicExamData(Exam $exam, array &$errors): void
    {
        if (empty($exam->text)) {
            $errors[] = 'Exam English title is required.';
        }

        if (empty($exam->{'text-ar'})) {
            $errors[] = 'Exam Arabic title is required.';
        }

        if ($exam->time < 1) {
            $errors[] = 'Exam duration must be at least 1 minute.';
        }

        if ($exam->examQuestions->isEmpty()) {
            $errors[] = 'Exam must have at least one question.';
        }

        // Validate question count matches
        $actualQuestionCount = $exam->examQuestions->count();
        if ($exam->number_of_questions !== $actualQuestionCount) {
            $errors[] = "Question count mismatch: expected {$exam->number_of_questions}, found {$actualQuestionCount}.";
        }
    }

    /**
     * Validate exam questions
     */
    protected function validateExamQuestions(Exam $exam, array &$errors, array &$warnings): void
    {
        $questionNumber = 1;

        foreach ($exam->examQuestions as $question) {
            $this->validateSingleQuestion($question, $questionNumber, $errors, $warnings);
            $questionNumber++;
        }
    }

    /**
     * Validate single question
     */
    protected function validateSingleQuestion(ExamQuestion $question, int $questionNumber, array &$errors, array &$warnings): void
    {
        // Basic question validation
        if (empty($question->question)) {
            $errors[] = "Question {$questionNumber}: English text is required.";
        }

        if (empty($question->{'question-ar'})) {
            $errors[] = "Question {$questionNumber}: Arabic text is required.";
        }

        if (!in_array($question->type, ['single_choice', 'multiple_choice'])) {
            $errors[] = "Question {$questionNumber}: Invalid question type '{$question->type}'.";
        }

        if ($question->marks < 1) {
            $errors[] = "Question {$questionNumber}: Marks must be at least 1.";
        }

        // Validate answers
        $answers = $question->answers;

        if ($answers->count() < 2) {
            $errors[] = "Question {$questionNumber}: Must have at least 2 answer options.";
        }

        if ($answers->count() > 10) {
            $warnings[] = "Question {$questionNumber}: Has {$answers->count()} options, consider reducing for better usability.";
        }

        // Validate correct answers based on question type
        $this->validateQuestionCorrectAnswers($question, $questionNumber, $errors, $warnings);

        // Validate answer uniqueness
        $this->validateAnswerUniqueness($question, $questionNumber, $errors);
    }

    /**
     * Validate correct answers for a question
     */
    protected function validateQuestionCorrectAnswers(ExamQuestion $question, int $questionNumber, array &$errors, array &$warnings): void
    {
        $correctAnswers = $question->answers->where('is_correct', true);
        $totalAnswers = $question->answers->count();

        if ($question->type === 'single_choice') {
            if ($correctAnswers->count() === 0) {
                $errors[] = "Question {$questionNumber}: Single choice question must have exactly one correct answer.";
            } elseif ($correctAnswers->count() > 1) {
                $errors[] = "Question {$questionNumber}: Single choice question cannot have multiple correct answers. Found {$correctAnswers->count()}.";
            }
        } elseif ($question->type === 'multiple_choice') {
            if ($correctAnswers->count() === 0) {
                $errors[] = "Question {$questionNumber}: Multiple choice question must have at least one correct answer.";
            } elseif ($correctAnswers->count() === $totalAnswers && $totalAnswers > 1) {
                $warnings[] = "Question {$questionNumber}: All options are marked as correct. Please verify this is intentional.";
            }
        }
    }

    /**
     * Validate answer uniqueness within a question
     */
    protected function validateAnswerUniqueness(ExamQuestion $question, int $questionNumber, array &$errors): void
    {
        $answers = $question->answers;

        // Check English answer uniqueness
        $englishAnswers = $answers->pluck('answer')->map(function ($text) {
            return strtolower(trim($text));
        });

        if ($englishAnswers->count() !== $englishAnswers->unique()->count()) {
            $errors[] = "Question {$questionNumber}: English answer options must be unique.";
        }

        // Check Arabic answer uniqueness
        $arabicAnswers = $answers->pluck('answer-ar')->filter()->map(function ($text) {
            return trim($text);
        });

        if ($arabicAnswers->count() !== $arabicAnswers->unique()->count()) {
            $errors[] = "Question {$questionNumber}: Arabic answer options must be unique.";
        }
    }

    /**
     * Validate exam data structure
     */
    protected function validateExamDataStructure(array $examData, array &$errors): void
    {
        if (empty($examData['title_en'])) {
            $errors[] = 'English title is required.';
        }

        if (empty($examData['title_ar'])) {
            $errors[] = 'Arabic title is required.';
        }

        if (!isset($examData['duration']) || $examData['duration'] < 1) {
            $errors[] = 'Duration must be at least 1 minute.';
        }

        if (empty($examData['questions']) || !is_array($examData['questions'])) {
            $errors[] = 'At least one question is required.';
        }
    }

    /**
     * Validate questions data array
     */
    protected function validateQuestionsData(array $questionsData, array &$errors, array &$warnings): void
    {
        foreach ($questionsData as $index => $questionData) {
            $questionNumber = $index + 1;
            $validation = $this->validateQuestionData($questionData, $questionNumber);
            
            if (!$validation['valid']) {
                $errors = array_merge($errors, $validation['errors']);
            }
        }
    }

    /**
     * Validate question options
     */
    protected function validateQuestionOptions(array $questionData, int $questionNumber, array &$errors): void
    {
        $options = $questionData['options'];
        $questionType = $questionData['type'];

        if (count($options) < 2) {
            $errors[] = "Question {$questionNumber}: Must have at least 2 options.";
            return;
        }

        if (count($options) > 10) {
            $errors[] = "Question {$questionNumber}: Cannot have more than 10 options.";
            return;
        }

        // Validate each option
        foreach ($options as $optionIndex => $option) {
            $optionNumber = $optionIndex + 1;

            if (empty($option['text_en'])) {
                $errors[] = "Question {$questionNumber}, Option {$optionNumber}: English text is required.";
            }

            if (empty($option['text_ar'])) {
                $errors[] = "Question {$questionNumber}, Option {$optionNumber}: Arabic text is required.";
            }
        }

        // Validate correct answers
        $this->validateOptionsCorrectAnswers($questionData, $questionNumber, $errors);

        // Validate option uniqueness
        $this->validateOptionsUniqueness($options, $questionNumber, $errors);
    }

    /**
     * Validate correct answers in options
     */
    protected function validateOptionsCorrectAnswers(array $questionData, int $questionNumber, array &$errors): void
    {
        $questionType = $questionData['type'];
        $options = $questionData['options'];

        if ($questionType === 'single_choice') {
            $correctAnswer = $questionData['correct_answer'] ?? null;

            if ($correctAnswer === null) {
                $errors[] = "Question {$questionNumber}: Please select exactly one correct answer.";
            } elseif ($correctAnswer < 0 || $correctAnswer >= count($options)) {
                $errors[] = "Question {$questionNumber}: Selected correct answer index is invalid.";
            }
        } elseif ($questionType === 'multiple_choice') {
            $hasCorrectAnswer = false;
            $correctCount = 0;

            foreach ($options as $option) {
                if (!empty($option['is_correct'])) {
                    $hasCorrectAnswer = true;
                    $correctCount++;
                }
            }

            if (!$hasCorrectAnswer) {
                $errors[] = "Question {$questionNumber}: Please mark at least one option as correct.";
            }

            if ($correctCount === count($options) && count($options) > 1) {
                // This is a warning, not an error
            }
        }
    }

    /**
     * Validate option uniqueness
     */
    protected function validateOptionsUniqueness(array $options, int $questionNumber, array &$errors): void
    {
        // Check English option uniqueness
        $englishTexts = array_column($options, 'text_en');
        $englishTexts = array_map(function($text) {
            return strtolower(trim($text));
        }, $englishTexts);

        if (count($englishTexts) !== count(array_unique($englishTexts))) {
            $errors[] = "Question {$questionNumber}: English option texts must be unique.";
        }

        // Check Arabic option uniqueness
        $arabicTexts = array_column($options, 'text_ar');
        $arabicTexts = array_map('trim', $arabicTexts);

        if (count($arabicTexts) !== count(array_unique($arabicTexts))) {
            $errors[] = "Question {$questionNumber}: Arabic option texts must be unique.";
        }
    }

    /**
     * Validate business rules
     */
    protected function validateBusinessRules(Exam $exam, array &$errors, array &$warnings): void
    {
        // Check if exam duration is reasonable for number of questions
        $questionsCount = $exam->examQuestions->count();
        $timePerQuestion = $questionsCount > 0 ? $exam->time / $questionsCount : 0;

        if ($timePerQuestion < 0.5) {
            $warnings[] = "Very short time per question ({$timePerQuestion} minutes). Consider increasing exam duration.";
        } elseif ($timePerQuestion > 10) {
            $warnings[] = "Very long time per question ({$timePerQuestion} minutes). Consider reducing exam duration.";
        }

        // Check total marks
        $totalMarks = $exam->examQuestions->sum('marks');
        if ($totalMarks === 0) {
            $errors[] = 'Total exam marks cannot be zero.';
        }

        // Validate question distribution
        $singleChoiceCount = $exam->examQuestions->where('type', 'single_choice')->count();
        $multipleChoiceCount = $exam->examQuestions->where('type', 'multiple_choice')->count();

        if ($questionsCount > 0) {
            if ($singleChoiceCount === 0 && $multipleChoiceCount === 0) {
                $errors[] = 'Exam must have at least some single choice or multiple choice questions.';
            }
        }
    }

    /**
     * Generate exam statistics
     */
    protected function generateExamStatistics(Exam $exam): array
    {
        $questions = $exam->examQuestions;
        
        return [
            'total_questions' => $questions->count(),
            'total_marks' => $questions->sum('marks'),
            'average_marks_per_question' => $questions->count() > 0 ? round($questions->avg('marks'), 2) : 0,
            'single_choice_questions' => $questions->where('type', 'single_choice')->count(),
            'multiple_choice_questions' => $questions->where('type', 'multiple_choice')->count(),
            'total_options' => $questions->sum(function($q) { return $q->answers->count(); }),
            'average_options_per_question' => $questions->count() > 0 ? round($questions->avg(function($q) { return $q->answers->count(); }), 2) : 0,
            'time_per_question' => $questions->count() > 0 ? round($exam->time / $questions->count(), 2) : 0,
            'exam_duration_hours' => round($exam->time / 60, 2),
        ];
    }

    /**
     * Generate statistics from data array
     */
    protected function generateDataStatistics(array $examData): array
    {
        $questions = $examData['questions'] ?? [];
        $totalQuestions = count($questions);
        $duration = $examData['duration'] ?? 0;
        
        $totalMarks = 0;
        $singleChoiceCount = 0;
        $multipleChoiceCount = 0;
        $totalOptions = 0;

        foreach ($questions as $question) {
            $totalMarks += $question['points'] ?? 0;
            
            if (($question['type'] ?? '') === 'single_choice') {
                $singleChoiceCount++;
            } elseif (($question['type'] ?? '') === 'multiple_choice') {
                $multipleChoiceCount++;
            }
            
            $totalOptions += count($question['options'] ?? []);
        }

        return [
            'total_questions' => $totalQuestions,
            'total_marks' => $totalMarks,
            'average_marks_per_question' => $totalQuestions > 0 ? round($totalMarks / $totalQuestions, 2) : 0,
            'single_choice_questions' => $singleChoiceCount,
            'multiple_choice_questions' => $multipleChoiceCount,
            'total_options' => $totalOptions,
            'average_options_per_question' => $totalQuestions > 0 ? round($totalOptions / $totalQuestions, 2) : 0,
            'time_per_question' => $totalQuestions > 0 ? round($duration / $totalQuestions, 2) : 0,
            'exam_duration_hours' => round($duration / 60, 2),
        ];
    }
}