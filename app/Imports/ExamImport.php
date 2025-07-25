<?php

namespace App\Imports;

use App\Models\Exam;
use Illuminate\Support\Str;
use App\Models\ExamQuestion;
use App\Models\ExamQuestionAnswer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExamImport implements ToCollection, WithHeadingRow
{
    private $exam = null;
    private $questionsCreated = 0;
    
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            // Skip empty rows
            if (empty($row['question_text_en'])) {
                continue;
            }
            
            // Create exam only once (from first question row)
            if ($this->exam === null) {
                $this->exam = $this->createExam($row);
            }
            
            // Create question
            $this->createQuestion($row);
        }
        
        // Update exam with total questions count
        if ($this->exam) {
            $this->exam->update(['number_of_questions' => $this->questionsCreated]);
        }
    }
    
    private function createExam($row)
    {
        return Exam::create([
            'id' => Str::uuid(),
            'text' => $row['exam_title_en'] ?? 'Imported Exam',
            'text-ar' => $row['exam_title_ar'] ?? '',
            'description' => $row['exam_description_en'] ?? '',
            'description-ar' => $row['exam_description_ar'] ?? '',
            'time' => (int) ($row['duration_minutes'] ?? 30),
            'number_of_questions' => 0, // Will update later
            'is_completed' => false,
        ]);
    }
    
    private function createQuestion($row)
    {
        $question = ExamQuestion::create([
            'id' => Str::uuid(),
            'exam_id' => $this->exam->id,
            'question' => $row['question_text_en'],
            'question-ar' => $row['question_text_ar'] ?? '',
            'text-ar' => $row['question_text_ar'] ?? '',
            'type' => $row['question_type'] ?? 'single_choice',
            'marks' => (int) ($row['question_points'] ?? 1),
        ]);
        
        $this->questionsCreated++;
        
        // Create options (up to 4)
        for ($i = 1; $i <= 4; $i++) {
            $optionTextEn = $row["option_{$i}_text_en"] ?? null;
            
            if (empty($optionTextEn)) {
                break; // No more options
            }
            
            ExamQuestionAnswer::create([
                'id' => Str::uuid(),
                'exam_question_id' => $question->id,
                'answer' => $optionTextEn,
                'answer-ar' => $row["option_{$i}_text_ar"] ?? '',
                'reason' => $row["option_{$i}_reason_en"] ?? null,
                'reason-ar' => $row["option_{$i}_reason_ar"] ?? null,
                'is_correct' => ($row["option_{$i}_is_correct"] ?? 0) == 1,
            ]);
        }
    }
    
    public function getExam()
    {
        return $this->exam;
    }
    
    public function getQuestionsCount()
    {
        return $this->questionsCreated;
    }
}