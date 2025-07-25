<?php

namespace App\Services;

use App\Models\Exam;
use Illuminate\Support\Str;
use App\Models\ExamQuestion;
use App\Models\ExamQuestionAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExamImportService
{
    private $errors = [];
    private $warnings = [];

    /**
     * Import exam from uploaded file
     */
    public function importExam($filePath): array
    {
        try {
            DB::beginTransaction();

            // Load and parse the spreadsheet
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            
            // Parse the Excel data
            $examData = $this->parseExcelData($worksheet);
            
            // Validate the parsed data
            $validation = $this->validateExamData($examData);
            if (!$validation['valid']) {
                throw new \Exception('Validation failed: ' . implode(', ', $validation['errors']));
            }
            
            // Create the exam
            $exam = $this->createExam($examData['exam']);
            
            // Create questions and answers
            $this->createQuestionsAndAnswers($exam, $examData['questions']);
            
            DB::commit();
            
            Log::info('Exam imported successfully', [
                'exam_id' => $exam->id,
                'exam_title' => $exam->text,
                'questions_count' => count($examData['questions'])
            ]);
            
            return [
                'success' => true,
                'exam' => $exam,
                'questions_count' => count($examData['questions']),
                'warnings' => $this->warnings,
                'message' => "Exam '{$exam->text}' imported successfully with " . count($examData['questions']) . " questions."
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Exam import failed', [
                'error' => $e->getMessage(),
                'file' => $filePath,
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'errors' => $this->errors
            ];
        }
    }

    /**
     * Parse Excel data from worksheet
     */
    private function parseExcelData($worksheet): array
    {
        $highestRow = $worksheet->getHighestRow();
        $questions = [];
        $examData = null;
        
        // Check if file has data
        if ($highestRow < 2) {
            throw new \Exception('The Excel file appears to be empty. Please ensure you have data starting from row 2.');
        }
        
        for ($row = 2; $row <= $highestRow; $row++) {
            // Skip empty rows
            $questionText = trim($worksheet->getCell('F' . $row)->getValue() ?? '');
            if (empty($questionText)) {
                if ($row <= 10) { // Only warn for first 10 rows
                    $this->warnings[] = "Row {$row} is empty and will be skipped.";
                }
                continue;
            }
            
            // Extract exam data (from first non-empty row)
            if ($examData === null) {
                $examData = [
                    'title_en' => trim($worksheet->getCell('A' . $row)->getValue() ?? ''),
                    'title_ar' => trim($worksheet->getCell('B' . $row)->getValue() ?? ''),
                    'description_en' => trim($worksheet->getCell('C' . $row)->getValue() ?? ''),
                    'description_ar' => trim($worksheet->getCell('D' . $row)->getValue() ?? ''),
                    'duration' => (int) ($worksheet->getCell('E' . $row)->getValue() ?? 0),
                ];
            }
            
            // Extract question data
            $question = [
                'row' => $row,
                'text_en' => $questionText,
                'text_ar' => trim($worksheet->getCell('G' . $row)->getValue() ?? ''),
                'type' => trim($worksheet->getCell('H' . $row)->getValue() ?? ''),
                'points' => max(1, (int) ($worksheet->getCell('I' . $row)->getValue() ?? 1)),
                'options' => []
            ];
            
            // Extract options (up to 6 options)
            for ($optionIndex = 1; $optionIndex <= 6; $optionIndex++) {
                $baseCol = 9 + (($optionIndex - 1) * 5); // Columns J, O, T, Y, AD, AI
                
                $optionTextEn = trim($worksheet->getCell($this->getColumnLetter($baseCol) . $row)->getValue() ?? '');
                
                if (empty($optionTextEn)) {
                    break; // No more options
                }
                
                $optionTextAr = trim($worksheet->getCell($this->getColumnLetter($baseCol + 1) . $row)->getValue() ?? '');
                $isCorrect = (bool) ($worksheet->getCell($this->getColumnLetter($baseCol + 2) . $row)->getValue() ?? false);
                $reasonEn = trim($worksheet->getCell($this->getColumnLetter($baseCol + 3) . $row)->getValue() ?? '');
                $reasonAr = trim($worksheet->getCell($this->getColumnLetter($baseCol + 4) . $row)->getValue() ?? '');
                
                // Validate reason length
                if (strlen($reasonEn) > 2000) {
                    $reasonEn = substr($reasonEn, 0, 2000);
                    $this->warnings[] = "Row {$row}, Option {$optionIndex}: English reason truncated to 2000 characters.";
                }
                
                if (strlen($reasonAr) > 2000) {
                    $reasonAr = substr($reasonAr, 0, 2000);
                    $this->warnings[] = "Row {$row}, Option {$optionIndex}: Arabic reason truncated to 2000 characters.";
                }
                
                $option = [
                    'text_en' => $optionTextEn,
                    'text_ar' => $optionTextAr,
                    'is_correct' => $isCorrect,
                    'reason_en' => $reasonEn ?: null,
                    'reason_ar' => $reasonAr ?: null,
                ];
                
                $question['options'][] = $option;
            }
            
            $questions[] = $question;
        }
        
        if (empty($questions)) {
            throw new \Exception('No valid questions found in the Excel file.');
        }
        
        return [
            'exam' => $examData,
            'questions' => $questions
        ];
    }

    /**
     * Get column letter by index (1-based)
     */
    private function getColumnLetter($index): string
    {
        $letter = '';
        while ($index > 0) {
            $index--;
            $letter = chr(65 + ($index % 26)) . $letter;
            $index = intval($index / 26);
        }
        return $letter;
    }

    /**
     * Validate exam data
     */
    private function validateExamData($data): array
    {
        $errors = [];
        
        // Validate exam data
        if (empty($data['exam']['title_en'])) {
            $errors[] = 'Exam title (English) is required in column A';
        }
        
        if (empty($data['exam']['title_ar'])) {
            $errors[] = 'Exam title (Arabic) is required in column B';
        }
        
        if (empty($data['exam']['duration']) || $data['exam']['duration'] < 1) {
            $errors[] = 'Exam duration must be at least 1 minute (column E)';
        }
        
        if ($data['exam']['duration'] > 600) {
            $errors[] = 'Exam duration cannot exceed 600 minutes (10 hours)';
        }
        
        // Validate questions
        if (empty($data['questions'])) {
            $errors[] = 'At least one question is required';
        }
        
        if (count($data['questions']) > 200) {
            $errors[] = 'Maximum 200 questions are allowed per exam';
        }
        
        foreach ($data['questions'] as $index => $question) {
            $rowNum = $question['row'];
            
            // Required fields validation
            if (empty($question['text_en'])) {
                $errors[] = "Row {$rowNum}: Question text (English) is required";
            }
            
            if (empty($question['text_ar'])) {
                $errors[] = "Row {$rowNum}: Question text (Arabic) is required";
            }
            
            if (!in_array($question['type'], ['single_choice', 'multiple_choice'])) {
                $errors[] = "Row {$rowNum}: Question type must be 'single_choice' or 'multiple_choice'";
            }
            
            if ($question['points'] < 1 || $question['points'] > 100) {
                $errors[] = "Row {$rowNum}: Question points must be between 1 and 100";
            }
            
            // Options validation
            if (count($question['options']) < 2) {
                $errors[] = "Row {$rowNum}: At least 2 answer options are required";
            }
            
            if (count($question['options']) > 6) {
                $errors[] = "Row {$rowNum}: Maximum 6 answer options are allowed";
            }
            
            $correctAnswers = array_filter($question['options'], function($option) {
                return $option['is_correct'];
            });
            
            if (empty($correctAnswers)) {
                $errors[] = "Row {$rowNum}: At least one correct answer is required";
            }
            
            if ($question['type'] === 'single_choice' && count($correctAnswers) > 1) {
                $errors[] = "Row {$rowNum}: Single choice questions can have only one correct answer";
            }
            
            // Validate each option
            foreach ($question['options'] as $optionIndex => $option) {
                $optionNum = $optionIndex + 1;
                
                if (empty($option['text_en'])) {
                    $errors[] = "Row {$rowNum}, Option {$optionNum}: English text is required";
                }
                
                if (empty($option['text_ar'])) {
                    $errors[] = "Row {$rowNum}, Option {$optionNum}: Arabic text is required";
                }
                
                // Check for duplicate options
                $duplicates = array_filter($question['options'], function($otherOption) use ($option) {
                    return $otherOption['text_en'] === $option['text_en'] && 
                           $otherOption !== $option;
                });
                
                if (!empty($duplicates)) {
                    $errors[] = "Row {$rowNum}, Option {$optionNum}: Duplicate option text found";
                }
            }
        }
        
        $this->errors = $errors;
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Create exam record
     */
    private function createExam($examData): Exam
    {
        return Exam::create([
            'id' => Str::uuid(),
            'text' => $examData['title_en'],
            'text-ar' => $examData['title_ar'],
            'description' => $examData['description_en'] ?: null,
            'description-ar' => $examData['description_ar'] ?: null,
            'number_of_questions' => 0, // Will be updated after questions are created
            'time' => $examData['duration'],
            'is_completed' => false,
        ]);
    }

    /**
     * Create questions and answers
     */
    private function createQuestionsAndAnswers($exam, $questions): void
    {
        $questionCount = 0;
        
        foreach ($questions as $questionData) {
            $question = ExamQuestion::create([
                'id' => Str::uuid(),
                'exam_id' => $exam->id,
                'question' => $questionData['text_en'],
                'question-ar' => $questionData['text_ar'],
                'text-ar' => $questionData['text_ar'], // For compatibility
                'type' => $questionData['type'],
                'marks' => $questionData['points'],
            ]);
            
            foreach ($questionData['options'] as $optionData) {
                ExamQuestionAnswer::create([
                    'id' => Str::uuid(),
                    'exam_question_id' => $question->id,
                    'answer' => $optionData['text_en'],
                    'answer-ar' => $optionData['text_ar'],
                    'reason' => $optionData['reason_en'],
                    'reason-ar' => $optionData['reason_ar'],
                    'is_correct' => $optionData['is_correct'],
                ]);
            }
            
            $questionCount++;
        }
        
        // Update exam with actual question count
        $exam->update(['number_of_questions' => $questionCount]);
    }

    /**
     * Get validation errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get warnings
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }
}