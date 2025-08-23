<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestions;
use App\Models\ExamQuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExamImportController extends Controller
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
     * Show import form
     */
    public function showImportForm()
    {
        return view('admin.exams.import');
    }

    /**
     * Generate and download Excel template
     */
    public function generateTemplateRoute()
    {
        try {
            // Generate the template
            $templatePath = $this->generateTemplate();
            
            // Check if file was actually created
            if (!file_exists($templatePath)) {
                throw new \Exception('Template file was not created successfully.');
            }
            
            // Return success response
            return redirect()->route('admin.exams.import.form')
                ->with('success', 'Excel template generated successfully! File saved to: ' . basename($templatePath));
                
        } catch (\Exception $e) {
            Log::error('Template generation failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.exams.import.form')
                ->with('error', 'Failed to generate template: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template
     */
    public function downloadTemplate()
    {
        $templatePath = storage_path('app/templates/exam_template.xlsx');
        
        // Generate template if it doesn't exist
        if (!file_exists($templatePath)) {
            try {
                Log::info('Template not found, generating new one', ['path' => $templatePath]);
                $this->generateTemplate();
            } catch (\Exception $e) {
                Log::error('Template generation failed during download', [
                    'error' => $e->getMessage(),
                    'path' => $templatePath
                ]);
                return redirect()->route('admin.exams.import.form')
                    ->with('error', 'Template file not found and generation failed: ' . $e->getMessage());
            }
        }

        // Final check
        if (!file_exists($templatePath)) {
            return redirect()->route('admin.exams.import.form')
                ->with('error', 'Template file not found at: ' . $templatePath . '. Please generate the template first.');
        }

        return response()->download($templatePath, 'exam_template.xlsx');
    }

    /**
     * Import exam from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            
            // Parse exam data
            $examData = $this->parseExamInfo($worksheet);
            $questionsData = $this->parseQuestions($worksheet);

            // Validate data
            $this->validateImportData($examData, $questionsData);

            // Create exam and questions in transaction
            $exam = DB::transaction(function () use ($examData, $questionsData) {
                // Create exam
                $exam = Exam::create([
                    'id' => Str::uuid(),
                    'text' => $examData['title_en'],
                    'text-ar' => $examData['title_ar'],
                    'description' => $examData['description_en'],
                    'description-ar' => $examData['description_ar'],
                    'time' => $examData['duration'],
                    'number_of_questions' => count($questionsData),
                    'is_completed' => false,
                ]);

                // Create questions
                foreach ($questionsData as $questionData) {
                    $this->createQuestion($exam, $questionData);
                }

                return $exam;
            });

            return redirect()->route('admin.exams.questions.index', $exam->id)
                ->with('success', "Exam '{$exam->text}' imported successfully with " . count($questionsData) . " questions!");

        } catch (\Exception $e) {
            Log::error('Excel import failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Parse exam information from Excel
     */
    private function parseExamInfo($worksheet)
    {
        $examData = [];
        
        // Expected format: Exam info in first few rows
        $examData['title_en'] = $this->getCellValue($worksheet, 'B2') ?: 'Imported Exam';
        $examData['title_ar'] = $this->getCellValue($worksheet, 'C2') ?: '';
        $examData['description_en'] = $this->getCellValue($worksheet, 'B3') ?: '';
        $examData['description_ar'] = $this->getCellValue($worksheet, 'C3') ?: '';
        $examData['duration'] = (int)($this->getCellValue($worksheet, 'B4') ?: 30);

        return $examData;
    }

    /**
     * Parse questions from Excel
     */
    private function parseQuestions($worksheet)
    {
        $questions = [];
        $startRow = 8; // Questions start from row 7
        $row = $startRow;

        while ($this->getCellValue($worksheet, 'A' . $row)) {
            $questionData = [
                'question_en' => $this->getCellValue($worksheet, 'B' . $row),
                'question_ar' => $this->getCellValue($worksheet, 'C' . $row),
                'type' => $this->getCellValue($worksheet, 'D' . $row),
                'points' => (int)($this->getCellValue($worksheet, 'E' . $row) ?: 1),
                'options' => []
            ];

            // Parse options (F to Q columns for 6 options max)
            $optionColumns = ['F', 'G', 'H', 'I', 'J', 'K']; // Option texts
            $optionArColumns = ['L', 'M', 'N', 'O', 'P', 'Q']; // Arabic option texts
            $correctColumns = ['R', 'S', 'T', 'U', 'V', 'W']; // Correct flags
            $reasonColumns = ['X', 'Y', 'Z', 'AA', 'AB', 'AC']; // Reasons
            $reasonArColumns = ['AD', 'AE', 'AF', 'AG', 'AH', 'AI']; // Arabic reasons

            for ($i = 0; $i < 6; $i++) {
                $optionText = $this->getCellValue($worksheet, $optionColumns[$i] . $row);
                if (!empty($optionText)) {
                    $questionData['options'][] = [
                        'text_en' => $optionText,
                        'text_ar' => $this->getCellValue($worksheet, $optionArColumns[$i] . $row) ?: '',
                        'is_correct' => $this->getCellValue($worksheet, $correctColumns[$i] . $row) == '1',
                        'reason' => $this->getCellValue($worksheet, $reasonColumns[$i] . $row) ?: '',
                        'reason_ar' => $this->getCellValue($worksheet, $reasonArColumns[$i] . $row) ?: '',
                    ];
                }
            }

            $questions[] = $questionData;
            $row++;
        }

        return $questions;
    }

    /**
     * Validate imported data
     */
    private function validateImportData($examData, $questionsData)
    {
        // Validate exam data
        if (empty($examData['title_en'])) {
            throw new \Exception('Exam title (English) is required.');
        }

        if ($examData['duration'] < 1 || $examData['duration'] > 300) {
            throw new \Exception('Exam duration must be between 1 and 300 minutes.');
        }

        // Validate questions
        if (empty($questionsData)) {
            throw new \Exception('No questions found in the Excel file.');
        }

        foreach ($questionsData as $index => $question) {
            $questionNum = $index + 1;

            if (empty($question['question_en'])) {
                throw new \Exception("Question {$questionNum}: English text is required.");
            }

            if (!in_array($question['type'], ['single_choice', 'multiple_choice'])) {
                throw new \Exception("Question {$questionNum}: Invalid question type. Use 'single_choice' or 'multiple_choice'.");
            }

            if (count($question['options']) < 2) {
                throw new \Exception("Question {$questionNum}: Must have at least 2 answer options.");
            }

            if (count($question['options']) > 6) {
                throw new \Exception("Question {$questionNum}: Cannot have more than 6 answer options.");
            }

            // Validate correct answers
            $correctCount = count(array_filter($question['options'], fn($opt) => $opt['is_correct']));
            
            if ($correctCount === 0) {
                throw new \Exception("Question {$questionNum}: Must have at least one correct answer.");
            }

            if ($question['type'] === 'single_choice' && $correctCount > 1) {
                throw new \Exception("Question {$questionNum}: Single choice questions can only have one correct answer.");
            }

            // Validate option texts
            foreach ($question['options'] as $optIndex => $option) {
                if (empty($option['text_en'])) {
                    throw new \Exception("Question {$questionNum}, Option " . ($optIndex + 1) . ": English text is required.");
                }
            }
        }
    }

    /**
     * Create question with answers
     */
    private function createQuestion(Exam $exam, array $questionData)
    {
        $question = ExamQuestions::create([
            'id' => Str::uuid(),
            'exam_id' => $exam->id,
            'question' => $questionData['question_en'],
            'question-ar' => $questionData['question_ar'],
            'text-ar' => $questionData['question_ar'],
            'type' => $questionData['type'],
            'marks' => $questionData['points'],
        ]);

        foreach ($questionData['options'] as $optionData) {
            ExamQuestionAnswer::create([
                'id' => Str::uuid(),
                'exam_question_id' => $question->id,
                'answer' => $optionData['text_en'],
                'answer-ar' => $optionData['text_ar'],
                'is_correct' => $optionData['is_correct'],
                'reason' => $optionData['reason'],
                'reason-ar' => $optionData['reason_ar'],
            ]);
        }
    }

    /**
     * Get cell value safely
     */
    private function getCellValue($worksheet, $cell)
    {
        try {
            $value = $worksheet->getCell($cell)->getValue();
            
            // Handle date values
            if (Date::isDateTime($worksheet->getCell($cell))) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d H:i:s');
            }
            
            return is_null($value) ? '' : trim((string)$value);
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Generate Excel template
     */
    private function generateTemplate()
    {
        $templateDir = storage_path('app/templates');
        $templatePath = $templateDir . '/exam_template.xlsx';

        try {
            // Create directory if it doesn't exist
            if (!file_exists($templateDir)) {
                if (!mkdir($templateDir, 0755, true)) {
                    throw new \Exception('Failed to create templates directory: ' . $templateDir);
                }
                Log::info('Created templates directory', ['path' => $templateDir]);
            }

            // Check if directory is writable
            if (!is_writable($templateDir)) {
                throw new \Exception('Templates directory is not writable: ' . $templateDir);
            }

            Log::info('Generating Excel template', ['path' => $templatePath]);

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $worksheet = $spreadsheet->getActiveSheet();

            // Set exam info headers
            $worksheet->setCellValue('A1', 'Exam Information');
            $worksheet->setCellValue('A2', 'Title:');
            $worksheet->setCellValue('B2', 'Sample Exam Title');
            $worksheet->setCellValue('C2', 'عنوان الاختبار النموذجي');
            $worksheet->setCellValue('A3', 'Description:');
            $worksheet->setCellValue('B3', 'Sample exam description');
            $worksheet->setCellValue('C3', 'وصف الاختبار النموذجي');
            $worksheet->setCellValue('A4', 'Duration (minutes):');
            $worksheet->setCellValue('B4', '30');

            // Set questions headers
            $worksheet->setCellValue('A6', 'Questions');
            $headers = [
                'A7' => 'Q#', 'B7' => 'Question (EN)', 'C7' => 'Question (AR)', 
                'D7' => 'Type', 'E7' => 'Points',
                'F7' => 'Option1(EN)', 'G7' => 'Option2(EN)', 'H7' => 'Option3(EN)', 
                'I7' => 'Option4(EN)', 'J7' => 'Option5(EN)', 'K7' => 'Option6(EN)',
                'L7' => 'Option1(AR)', 'M7' => 'Option2(AR)', 'N7' => 'Option3(AR)', 
                'O7' => 'Option4(AR)', 'P7' => 'Option5(AR)', 'Q7' => 'Option6(AR)',
                'R7' => 'Correct1', 'S7' => 'Correct2', 'T7' => 'Correct3', 
                'U7' => 'Correct4', 'V7' => 'Correct5', 'W7' => 'Correct6',
                'X7' => 'Reason1(EN)', 'Y7' => 'Reason2(EN)', 'Z7' => 'Reason3(EN)', 
                'AA7' => 'Reason4(EN)', 'AB7' => 'Reason5(EN)', 'AC7' => 'Reason6(EN)',
                'AD7' => 'Reason1(AR)', 'AE7' => 'Reason2(AR)', 'AF7' => 'Reason3(AR)', 
                'AG7' => 'Reason4(AR)', 'AH7' => 'Reason5(AR)', 'AI7' => 'Reason6(AR)',
            ];

            foreach ($headers as $cell => $value) {
                $worksheet->setCellValue($cell, $value);
            }

            // Add sample question
            $sampleData = [
                'A8' => '1',
                'B8' => 'What is the capital of France?',
                'C8' => 'ما هي عاصمة فرنسا؟',
                'D8' => 'single_choice',
                'E8' => '1',
                'F8' => 'Paris', 'G8' => 'London', 'H8' => 'Berlin', 'I8' => 'Madrid',
                'L8' => 'باريس', 'M8' => 'لندن', 'N8' => 'برلين', 'O8' => 'مدريد',
                'R8' => '1', 'S8' => '0', 'T8' => '0', 'U8' => '0',
                'X8' => 'Paris is the capital city of France',
                'Y8' => 'London is the capital of UK',
                'Z8' => 'Berlin is the capital of Germany',
                'AA8' => 'Madrid is the capital of Spain',
            ];

            foreach ($sampleData as $cell => $value) {
                $worksheet->setCellValue($cell, $value);
            }

            // Style the headers
            $headerStyle = [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 
                    'startColor' => ['rgb' => 'E2EFDA']
                ],
            ];
            $worksheet->getStyle('A1:AI7')->applyFromArray($headerStyle);

            // Auto-size columns
            foreach (range('A', 'AI') as $col) {
                $worksheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Save the file
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($templatePath);

            // Verify the file was created
            if (!file_exists($templatePath)) {
                throw new \Exception('Template file was not saved successfully to: ' . $templatePath);
            }

            $fileSize = filesize($templatePath);
            Log::info('Excel template generated successfully', [
                'path' => $templatePath,
                'size' => $fileSize . ' bytes'
            ]);

            return $templatePath;

        } catch (\Exception $e) {
            Log::error('Error generating Excel template', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'template_dir' => $templateDir,
                'template_path' => $templatePath,
                'dir_exists' => file_exists($templateDir),
                'dir_writable' => is_writable($templateDir)
            ]);
            throw new \Exception('Failed to generate Excel template: ' . $e->getMessage());
        }
    }
}