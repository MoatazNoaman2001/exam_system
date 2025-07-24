<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\QuestionExamAnswer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ExamImportController extends Controller
{
    /**
     * Show the import form
     */
    public function showImportForm()
    {
        return view('admin.exams.import');
    }

    /**
     * Download Excel template
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        
        // Create Instructions sheet
        $this->createInstructionsSheet($spreadsheet);
        
        // Create Template sheet
        $this->createTemplateSheet($spreadsheet);
        
        // Create Example sheet
        $this->createExampleSheet($spreadsheet);
        
        // Set active sheet to template
        $spreadsheet->setActiveSheetIndex(1);
        
        $writer = new Xlsx($spreadsheet);
        $filename = 'exam_import_template_' . date('Y-m-d') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'exam_template');
        
        $writer->save($tempFile);
        
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Import exam from Excel
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Please upload a valid Excel file (max 10MB).');
        }

        try {
            DB::beginTransaction();

            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            
            // Parse the Excel data
            $examData = $this->parseExcelData($worksheet);
            
            // Validate the parsed data
            $validation = $this->validateExamData($examData);
            if (!$validation['valid']) {
                throw new \Exception('Validation failed: ' . implode(', ', $validation['errors']));
            }
            
            // Create the exam
            $exam = $this->createExam($examData);
            
            // Create questions and answers
            $this->createQuestionsAndAnswers($exam, $examData['questions']);
            
            DB::commit();
            
            return redirect()->route('admin.exams')
                ->with('success', "Exam '{$exam->text}' imported successfully with " . count($examData['questions']) . " questions.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Create instructions sheet
     */
    private function createInstructionsSheet($spreadsheet)
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Instructions');
        
        // Header
        $sheet->setCellValue('A1', 'Exam Import Template - Instructions');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->mergeCells('A1:E1');
        
        // Instructions
        $instructions = [
            '',
            'HOW TO USE THIS TEMPLATE:',
            '',
            '1. Fill in the exam details in the "Template" sheet',
            '2. Add your questions starting from row 2',
            '3. Each question can have 2-6 answer options',
            '4. Mark correct answers with "1" in the is_correct columns',
            '5. Save the file and upload it to the system',
            '',
            'COLUMN EXPLANATIONS:',
            '',
            'exam_title_en: Exam title in English (required)',
            'exam_title_ar: Exam title in Arabic (required)',
            'exam_description_en: Exam description in English (optional)',
            'exam_description_ar: Exam description in Arabic (optional)',
            'duration_minutes: Exam duration in minutes (required)',
            'question_text_en: Question text in English (required)',
            'question_text_ar: Question text in Arabic (required)',
            'question_type: "single_choice" or "multiple_choice" (required)',
            'question_points: Points for this question (default: 1)',
            'option_X_text_en: Answer option text in English',
            'option_X_text_ar: Answer option text in Arabic',
            'option_X_is_correct: 1 if correct, 0 if incorrect',
            'option_X_reason_en: Explanation in English (optional)',
            'option_X_reason_ar: Explanation in Arabic (optional)',
            '',
            'IMPORTANT NOTES:',
            '',
            '• All questions must have the same exam details (title, description, duration)',
            '• Single choice questions can have only ONE correct answer',
            '• Multiple choice questions can have multiple correct answers',
            '• Each question must have at least 2 answer options',
            '• Maximum 6 answer options per question',
            '• Reason fields support up to 2000 characters',
        ];
        
        foreach ($instructions as $index => $instruction) {
            $row = $index + 2;
            $sheet->setCellValue('A' . $row, $instruction);
            
            if (strpos($instruction, ':') !== false && !empty(trim($instruction))) {
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            }
        }
        
        // Auto-size columns
        $sheet->getColumnDimension('A')->setWidth(80);
    }

    /**
     * Create template sheet
     */
    private function createTemplateSheet($spreadsheet)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Template');
        
        // Headers
        $headers = [
            'exam_title_en', 'exam_title_ar', 'exam_description_en', 'exam_description_ar', 'duration_minutes',
            'question_text_en', 'question_text_ar', 'question_type', 'question_points',
            'option_1_text_en', 'option_1_text_ar', 'option_1_is_correct', 'option_1_reason_en', 'option_1_reason_ar',
            'option_2_text_en', 'option_2_text_ar', 'option_2_is_correct', 'option_2_reason_en', 'option_2_reason_ar',
            'option_3_text_en', 'option_3_text_ar', 'option_3_is_correct', 'option_3_reason_en', 'option_3_reason_ar',
            'option_4_text_en', 'option_4_text_ar', 'option_4_is_correct', 'option_4_reason_en', 'option_4_reason_ar',
            'option_5_text_en', 'option_5_text_ar', 'option_5_is_correct', 'option_5_reason_en', 'option_5_reason_ar',
            'option_6_text_en', 'option_6_text_ar', 'option_6_is_correct', 'option_6_reason_en', 'option_6_reason_ar',
        ];
        
        foreach ($headers as $index => $header) {
            $column = chr(65 + $index); // A, B, C, etc.
            if ($index >= 26) {
                $column = 'A' . chr(65 + ($index - 26)); // AA, AB, AC, etc.
            }
            $sheet->setCellValue($column . '1', $header);
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
            $sheet->getColumnDimension($column)->setWidth(20);
        }
        
        // Freeze first row
        $sheet->freezePane('A2');
        
        // Add data validation for question_type column
        $validation = $sheet->getCell('H2')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Invalid Question Type');
        $validation->setError('Please select either "single_choice" or "multiple_choice"');
        $validation->setPromptTitle('Question Type');
        $validation->setPrompt('Select the question type');
        $validation->setFormula1('"single_choice,multiple_choice"');
        
        // Apply validation to entire column
        $sheet->setDataValidation('H2:H1000', clone $validation);
    }

    /**
     * Create example sheet
     */
    private function createExampleSheet($spreadsheet)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Example');
        
        // Copy headers from template
        $templateSheet = $spreadsheet->getSheet(1);
        $headers = [];
        for ($col = 'A'; $col <= 'AX'; $col++) {
            $value = $templateSheet->getCell($col . '1')->getValue();
            if (empty($value)) break;
            $headers[] = $value;
            $sheet->setCellValue($col . '1', $value);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getColumnDimension($col)->setWidth(20);
        }
        
        // Add example data
        $exampleData = [
            // Question 1
            [
                'Programming Fundamentals Quiz', 'اختبار أساسيات البرمجة', 'Basic programming concepts test', 'اختبار أساسيات مفاهيم البرمجة', '30',
                'What is a variable in programming?', 'ما هو المتغير في البرمجة؟', 'single_choice', '2',
                'A storage location with a name', 'موقع تخزين له اسم', '1', 'Variables store data values', 'المتغيرات تخزن قيم البيانات',
                'A function that executes code', 'دالة تنفذ الكود', '0', 'This describes a function, not a variable', 'هذا يصف دالة، وليس متغير',
                'A loop structure', 'هيكل حلقة تكرار', '0', 'This describes a loop, not a variable', 'هذا يصف حلقة تكرار، وليس متغير',
                'A conditional statement', 'عبارة شرطية', '0', 'This describes a condition, not a variable', 'هذا يصف شرط، وليس متغير',
                '', '', '', '', '',
                '', '', '', '', '',
            ],
            // Question 2
            [
                'Programming Fundamentals Quiz', 'اختبار أساسيات البرمجة', 'Basic programming concepts test', 'اختبار أساسيات مفاهيم البرمجة', '30',
                'Which of the following are programming languages?', 'أي من التالي لغات برمجة؟', 'multiple_choice', '3',
                'Python', 'بايثون', '1', 'Python is a popular programming language', 'بايثون لغة برمجة شائعة',
                'JavaScript', 'جافا سكريبت', '1', 'JavaScript is used for web development', 'جافا سكريبت تستخدم لتطوير الويب',
                'HTML', 'إتش تي إم إل', '0', 'HTML is a markup language, not programming', 'إتش تي إم إل لغة ترميز، وليست برمجة',
                'Java', 'جافا', '1', 'Java is an object-oriented programming language', 'جافا لغة برمجة كائنية التوجه',
                '', '', '', '', '',
                '', '', '', '', '',
            ],
        ];
        
        foreach ($exampleData as $rowIndex => $rowData) {
            $row = $rowIndex + 2;
            foreach ($rowData as $colIndex => $value) {
                $column = chr(65 + $colIndex);
                if ($colIndex >= 26) {
                    $column = 'A' . chr(65 + ($colIndex - 26));
                }
                $sheet->setCellValue($column . $row, $value);
            }
        }
        
        // Freeze first row
        $sheet->freezePane('A2');
    }

    /**
     * Parse Excel data
     */
    private function parseExcelData($worksheet)
    {
        $highestRow = $worksheet->getHighestRow();
        $questions = [];
        $examData = null;
        
        for ($row = 2; $row <= $highestRow; $row++) {
            // Skip empty rows
            if (empty(trim($worksheet->getCell('F' . $row)->getValue()))) {
                continue;
            }
            
            // Extract exam data (from first row)
            if ($examData === null) {
                $examData = [
                    'title_en' => trim($worksheet->getCell('A' . $row)->getValue()),
                    'title_ar' => trim($worksheet->getCell('B' . $row)->getValue()),
                    'description_en' => trim($worksheet->getCell('C' . $row)->getValue()),
                    'description_ar' => trim($worksheet->getCell('D' . $row)->getValue()),
                    'duration' => (int) $worksheet->getCell('E' . $row)->getValue(),
                ];
            }
            
            // Extract question data
            $question = [
                'text_en' => trim($worksheet->getCell('F' . $row)->getValue()),
                'text_ar' => trim($worksheet->getCell('G' . $row)->getValue()),
                'type' => trim($worksheet->getCell('H' . $row)->getValue()),
                'points' => (int) ($worksheet->getCell('I' . $row)->getValue() ?: 1),
                'options' => []
            ];
            
            // Extract options (up to 6 options)
            for ($optionIndex = 1; $optionIndex <= 6; $optionIndex++) {
                $baseCol = 9 + (($optionIndex - 1) * 5); // J, O, T, Y, AD, AI
                
                $optionTextEn = trim($worksheet->getCell($this->getColumnLetter($baseCol) . $row)->getValue());
                $optionTextAr = trim($worksheet->getCell($this->getColumnLetter($baseCol + 1) . $row)->getValue());
                
                if (empty($optionTextEn)) {
                    break; // No more options
                }
                
                $option = [
                    'text_en' => $optionTextEn,
                    'text_ar' => $optionTextAr,
                    'is_correct' => (bool) $worksheet->getCell($this->getColumnLetter($baseCol + 2) . $row)->getValue(),
                    'reason_en' => trim($worksheet->getCell($this->getColumnLetter($baseCol + 3) . $row)->getValue()),
                    'reason_ar' => trim($worksheet->getCell($this->getColumnLetter($baseCol + 4) . $row)->getValue()),
                ];
                
                $question['options'][] = $option;
            }
            
            $questions[] = $question;
        }
        
        return [
            'exam' => $examData,
            'questions' => $questions
        ];
    }

    /**
     * Get column letter by index
     */
    private function getColumnLetter($index)
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
    private function validateExamData($data)
    {
        $errors = [];
        
        // Validate exam data
        if (empty($data['exam']['title_en'])) {
            $errors[] = 'Exam title (English) is required';
        }
        
        if (empty($data['exam']['title_ar'])) {
            $errors[] = 'Exam title (Arabic) is required';
        }
        
        if (empty($data['exam']['duration']) || $data['exam']['duration'] < 1) {
            $errors[] = 'Exam duration must be at least 1 minute';
        }
        
        // Validate questions
        if (empty($data['questions'])) {
            $errors[] = 'At least one question is required';
        }
        
        foreach ($data['questions'] as $index => $question) {
            $questionNum = $index + 1;
            
            if (empty($question['text_en'])) {
                $errors[] = "Question {$questionNum}: English text is required";
            }
            
            if (empty($question['text_ar'])) {
                $errors[] = "Question {$questionNum}: Arabic text is required";
            }
            
            if (!in_array($question['type'], ['single_choice', 'multiple_choice'])) {
                $errors[] = "Question {$questionNum}: Invalid question type";
            }
            
            if (count($question['options']) < 2) {
                $errors[] = "Question {$questionNum}: At least 2 options are required";
            }
            
            $correctAnswers = array_filter($question['options'], function($option) {
                return $option['is_correct'];
            });
            
            if (empty($correctAnswers)) {
                $errors[] = "Question {$questionNum}: At least one correct answer is required";
            }
            
            if ($question['type'] === 'single_choice' && count($correctAnswers) > 1) {
                $errors[] = "Question {$questionNum}: Single choice questions can have only one correct answer";
            }
            
            foreach ($question['options'] as $optionIndex => $option) {
                $optionNum = $optionIndex + 1;
                
                if (empty($option['text_en'])) {
                    $errors[] = "Question {$questionNum}, Option {$optionNum}: English text is required";
                }
                
                if (empty($option['text_ar'])) {
                    $errors[] = "Question {$questionNum}, Option {$optionNum}: Arabic text is required";
                }
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Create exam
     */
    private function createExam($data)
    {
        return Exam::create([
            'id' => Str::uuid(),
            'text' => $data['exam']['title_en'],
            'text-ar' => $data['exam']['title_ar'],
            'description' => $data['exam']['description_en'],
            'description-ar' => $data['exam']['description_ar'],
            'number_of_questions' => count($data['questions']),
            'time' => $data['exam']['duration'],
            'is_completed' => false,
        ]);
    }

    /**
     * Create questions and answers
     */
    private function createQuestionsAndAnswers($exam, $questions)
    {
        foreach ($questions as $questionData) {
            $question = ExamQuestion::create([
                'id' => Str::uuid(),
                'exam_id' => $exam->id,
                'question' => $questionData['text_en'],
                'question-ar' => $questionData['text_ar'],
                'type' => $questionData['type'],
                'marks' => $questionData['points'],
                'text-ar' => $questionData['text_ar'], // For compatibility
            ]);
            
            foreach ($questionData['options'] as $optionData) {
                QuestionExamAnswer::create([
                    'id' => Str::uuid(),
                    'exam_question_id' => $question->id,
                    'answer' => $optionData['text_en'],
                    'answer-ar' => $optionData['text_ar'],
                    'reason' => $optionData['reason_en'] ?: null,
                    'reason-ar' => $optionData['reason_ar'] ?: null,
                    'is_correct' => $optionData['is_correct'],
                ]);
            }
        }
    }
}