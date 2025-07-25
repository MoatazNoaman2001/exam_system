<?php

return [
    'create' => [
        'page_title' => 'Create New Exam',
        'back_to_exams' => 'Back to Exams',
        'exam_configuration' => 'Exam Configuration',
        'basic_information' => 'Basic Information',
        'questions_section' => 'Exam Questions',
        'questions_help' => 'Add questions and their answer options. You can specify explanations for each option to help students understand why answers are correct or incorrect.',
        'answer_options' => 'Answer Options',
        'answer_options_help' => 'Add at least 2 options for each question. Mark the correct answer(s) and optionally provide explanations.',
        'question' => 'Question',
        'question_number' => 'Question',
        'correct' => 'Correct',
        'close' => 'Close',

        // Form Fields
        'fields' => [
            'title_en' => 'Exam Title (English)',
            'title_ar' => 'Exam Title (Arabic)',
            'description_en' => 'Description (English)',
            'description_ar' => 'Description (Arabic)',
            'duration' => 'Duration (minutes)',
            'question_text_en' => 'Question Text (English)',
            'question_text_ar' => 'Question Text (Arabic)',
            'question_type' => 'Question Type',
            'points' => 'Points',
            'option_text_en' => 'Option (English)',
            'option_text_ar' => 'Option (Arabic)',
            'reason_en' => 'Explanation/Reason (English)',
            'reason_ar' => 'Explanation/Reason (Arabic)',
        ],

        // Placeholders
        'placeholders' => [
            'title_en' => 'Enter exam title in English',
            'title_ar' => 'أدخل عنوان الامتحان بالعربية',
            'description_en' => 'Enter exam description in English',
            'description_ar' => 'أدخل وصف الامتحان بالعربية',
            'duration' => 'Duration in minutes',
            'question_text_en' => 'Enter question text in English',
            'question_text_ar' => 'أدخل نص السؤال بالعربية',
            'question_type' => 'Select question type',
            'points' => 'Points',
            'option_text_en' => 'Option text in English',
            'option_text_ar' => 'نص الخيار بالعربية',
            'reason_en' => 'Explain why this option is correct/incorrect',
            'reason_ar' => 'اشرح لماذا هذا الخيار صحيح/خاطئ',
        ],

        // Question Types
        'question_types' => [
            'single_choice' => 'Single Choice',
            'multiple_choice' => 'Multiple Choice',
            'true_false' => 'True/False',
        ],

        // Buttons
        'buttons' => [
            'add_question' => 'Add Question',
            'remove_question' => 'Remove Question',
            'add_option' => 'Add Option',
            'remove_option' => 'Remove Option',
            'create_exam' => 'Create Exam',
            'cancel' => 'Cancel',
            'save_draft' => 'Save as Draft',
        ],

        // Hints and Help Text
        'hints' => [
            'duration' => 'Exam duration in minutes (1-300)',
            'reason' => 'Optional: Explain why this answer is correct or incorrect',
            'single_choice' => 'Students can select only one answer',
            'multiple_choice' => 'Students can select multiple answers',
        ],

        // Empty States
        'empty_state' => [
            'title' => 'No Questions Added Yet',
            'text' => 'Click "Add Question" to start building your exam.',
        ],

        // Error Messages
        'errors' => [
            'validation_title' => 'Please correct the following errors:',
            'exam_creation_failed' => 'Failed to create exam. Please try again.',
        ],

        // Validation Messages
        'validation' => [
            'min_options' => 'Each question must have at least 2 options.',
            'min_questions' => 'Exam must have at least 1 question.',
            'required_field' => 'This field is required.',
            'single_correct_answer' => 'Single choice questions can have only one correct answer.',
            'at_least_one_correct' => 'At least one option must be marked as correct.',
            'title_en_required' => 'English title is required.',
            'title_ar_required' => 'Arabic title is required.',
            'duration_required' => 'Duration is required.',
            'duration_min' => 'Duration must be at least 1 minute.',
            'duration_max' => 'Duration cannot exceed 300 minutes.',
            'questions_required' => 'At least one question is required.',
            'question_text_en_required' => 'Question text in English is required.',
            'question_text_ar_required' => 'Question text in Arabic is required.',
            'question_type_required' => 'Question type is required.',
            'question_type_invalid' => 'Invalid question type.',
            'points_required' => 'Question points are required.',
            'points_min' => 'Question points must be at least 1.',
            'options_required' => 'Question options are required.',
            'options_min' => 'Each question must have at least 2 options.',
            'option_text_en_required' => 'Option text in English is required.',
            'option_text_ar_required' => 'Option text in Arabic is required.',
        ],

        // Success Messages
        'success' => [
            'exam_created' => 'Exam created successfully!',
            'exam_updated' => 'Exam updated successfully!',
            'exam_deleted' => 'Exam deleted successfully!',
        ],

        // Confirmation Messages
        'confirmations' => [
            'delete_question' => 'Are you sure you want to delete this question?',
            'delete_option' => 'Are you sure you want to delete this option?',
            'leave_page' => 'You have unsaved changes. Are you sure you want to leave?',
        ],
    ],

    // General exam-related translations
    'index' => [
        'title' => 'Exams Management',
        'create_new' => 'Create New Exam',
        'no_exams' => 'No exams found.',
        'search_placeholder' => 'Search exams...',
    ],

    'edit' => [
        'page_title' => 'Edit Exam',
        'update_exam' => 'Update Exam',
        'save_changes' => 'Save Changes',
    ],

    'show' => [
        'exam_details' => 'Exam Details',
        'questions_count' => 'Questions',
        'duration' => 'Duration',
        'created_at' => 'Created',
        'updated_at' => 'Last Updated',
    ],

    'common' => [
        'actions' => 'Actions',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'view' => 'View',
        'status' => 'Status',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'draft' => 'Draft',
        'published' => 'Published',
    ],
];