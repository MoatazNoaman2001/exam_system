<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'excel_file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:10240', // 10MB
                function ($attribute, $value, $fail) {
                    // Additional file validation
                    if ($value) {
                        $extension = strtolower($value->getClientOriginalExtension());
                        if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
                            $fail('The file must be an Excel or CSV file.');
                        }
                        
                        // Check file size more precisely
                        if ($value->getSize() > 10 * 1024 * 1024) {
                            $fail('The file size must not exceed 10MB.');
                        }
                        
                        // Check for valid Excel magic bytes
                        if (in_array($extension, ['xlsx', 'xls'])) {
                            $handle = fopen($value->getPathname(), 'rb');
                            $bytes = fread($handle, 8);
                            fclose($handle);
                            
                            // Check for Excel file signatures
                            $xlsxSignature = "\x50\x4B\x03\x04"; // ZIP signature (XLSX)
                            $xlsSignature = "\xD0\xCF\x11\xE0"; // OLE signature (XLS)
                            
                            if ($extension === 'xlsx' && strpos($bytes, $xlsxSignature) !== 0) {
                                $fail('The file does not appear to be a valid Excel file.');
                            }
                        }
                    }
                },
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'excel_file.required' => 'Please select an Excel file to import.',
            'excel_file.file' => 'The uploaded file is not valid.',
            'excel_file.mimes' => 'Only Excel files (.xlsx, .xls) and CSV files (.csv) are allowed.',
            'excel_file.max' => 'The file size cannot exceed 10MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'excel_file' => 'Excel file',
        ];
    }
}