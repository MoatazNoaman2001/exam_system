<?php

namespace App\Imports;

use App\Models\Exam;
use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ExamsImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'En' => new QuestionsSheetImport('en'),
            'Ar' => new QuestionsSheetImport('ar'),
        ];
    }
}

class QuestionsSheetImport implements ToModel, WithStartRow
{
    protected $language;

    public function __construct($language)
    {
        $this->language = $language;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row[0])) {
            return null;
        }

        // Create or update exam
        $exam = Exam::firstOrCreate(
            ['text' => 'Sample Exam '.date('Y-m-d')], // Adjust as needed
            [
                'description' => 'Imported exam',
                'time' => 60,
                'passing_score' => 70,
                'is_active' => true
            ]
        );

        // Determine question type
        $questionType = str_contains($row[1], 'Single choice') ? 
        'single_choice' : 
                       (str_contains($row[1], 'Multiple choice') ? 
                       'multiple_choice' : 'single_choice');

        // Prepare options
        $options = [
            'en' => [
                'A' => $row[2] ?? '',
                'B' => $row[3] ?? '',
                'C' => $row[4] ?? '',
                'D' => $row[5] ?? ''
            ],
            'ar' => [
                'A' => $row[2] ?? '',
                'B' => $row[3] ?? '',
                'C' => $row[4] ?? '',
                'D' => $row[5] ?? ''
            ]
        ];

        // Prepare correct answers
        $correctAnswer = $this->parseCorrectAnswer($row[7] ?? '');

        return new Question([
            'exam_id' => $exam->id,
            'text' => [
                'en' => $this->cleanQuestionText($row[1]),
                'ar' => $this->cleanQuestionText($row[1]) // Adjust for Arabic sheet
            ],
            'type' => $questionType,
            'options' => $options,
            'correct_answer' => $correctAnswer,
            'explanation' => [
                'en' => $row[8] ?? '',
                'ar' => $row[8] ?? '' // Adjust for Arabic sheet
            ],
            'language' => $this->language
        ]);
    }

    protected function cleanQuestionText($text)
    {
        return preg_replace('/Single choice\.|Multiple choice\./', '', $text);
    }

    protected function parseCorrectAnswer($answer)
    {
        if (str_contains($answer, ' and ')) {
            return explode(' and ', str_replace(['Option', ' ', '.'], '', $answer));
        }
        return str_replace(['Option', ' ', '.'], '', $answer);
    }
}