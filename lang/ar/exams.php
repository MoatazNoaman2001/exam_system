<?php


return [
    'create' => [
        'page_title' => 'إنشاء امتحان جديد',
        'back_to_exams' => 'العودة إلى الامتحانات',
        'exam_configuration' => 'إعداد الامتحان',
        'basic_information' => 'المعلومات الأساسية',
        'questions_section' => 'أسئلة الامتحان',
        'questions_help' => 'أضف الأسئلة وخيارات الإجابة. يمكنك تحديد التفسيرات لكل خيار لمساعدة الطلاب على فهم سبب صحة أو خطأ الإجابات.',
        'answer_options' => 'خيارات الإجابة',
        'answer_options_help' => 'أضف على الأقل خيارين لكل سؤال. حدد الإجابة (الإجابات) الصحيحة واختياريًا قدم التفسيرات.',
        'question' => 'السؤال',
        'question_number' => 'السؤال',
        'correct' => 'صحيح',
        'Minutes'=>"Minutes",
        'close' => 'إغلاق',

        'select_question_type'=>"اختر نوع السؤال",

        // Form Fields
        'fields' => [
            'title_en' => 'عنوان الامتحان (بالإنجليزية)',
            'title_ar' => 'عنوان الامتحان (بالعربية)',
            'description_en' => 'الوصف (بالإنجليزية)',
            'description_ar' => 'الوصف (بالعربية)',
            'duration' => 'المدة (بالدقائق)',
            'question_text_en' => 'نص السؤال (بالإنجليزية)',
            'question_text_ar' => 'نص السؤال (بالعربية)',
            'question_type' => 'نوع السؤال',
            'points' => 'النقاط',
            'option_text_en' => 'الخيار (بالإنجليزية)',
            'option_text_ar' => 'الخيار (بالعربية)',
            'reason_en' => 'التفسير/السبب (بالإنجليزية)',
            'reason_ar' => 'التفسير/السبب (بالعربية)',
        ],

        // Placeholders
        'placeholders' => [
            'title_en' => 'Enter exam title in English',
            'title_ar' => 'أدخل عنوان الامتحان بالعربية',
            'description_en' => 'Enter exam description in English',
            'description_ar' => 'أدخل وصف الامتحان بالعربية',
            'duration' => 'المدة بالدقائق',
            'question_text_en' => 'Enter question text in English',
            'question_text_ar' => 'أدخل نص السؤال بالعربية',
            'question_type' => 'اختر نوع السؤال',
            'points' => 'النقاط',
            'option_text_en' => 'Option text in English',
            'option_text_ar' => 'نص الخيار بالعربية',
            'reason_en' => 'Explain why this option is correct/incorrect',
            'reason_ar' => 'اشرح لماذا هذا الخيار صحيح/خاطئ',
        ],

        // Question Types
        'question_types' => [
            'single_choice' => 'اختيار واحد',
            'multiple_choice' => 'اختيار متعدد',
            'true_false' => 'صحيح/خطأ',
        ],

        // Buttons
        'buttons' => [
            'add_question' => 'إضافة سؤال',
            'remove_question' => 'حذف السؤال',
            'add_option' => 'إضافة خيار',
            'remove_option' => 'حذف الخيار',
            'create_exam' => 'إنشاء الامتحان',
            'cancel' => 'إلغاء',
            'add_first_question'=>"اضف السؤال الاول",
            'save_draft' => 'حفظ كمسودة',
        ],

        // Hints and Help Text
        'hints' => [
            'duration' => 'مدة الامتحان بالدقائق (1-300)',
            'reason' => 'اختياري: اشرح لماذا هذه الإجابة صحيحة أو خاطئة',
            'single_choice' => 'يمكن للطلاب اختيار إجابة واحدة فقط',
            'multiple_choice' => 'يمكن للطلاب اختيار إجابات متعددة',
        ],

        // Empty States
        'empty_state' => [
            'title' => 'لم يتم إضافة أسئلة بعد',
            'text' => 'انقر على "إضافة سؤال" لبدء إنشاء امتحانك.',
        ],

        // Error Messages
        'errors' => [
            'validation_title' => 'يرجى تصحيح الأخطاء التالية:',
            'exam_creation_failed' => 'فشل في إنشاء الامتحان. يرجى المحاولة مرة أخرى.',
        ],

        // Validation Messages
        'validation' => [
            'min_options' => 'يجب أن يحتوي كل سؤال على خيارين على الأقل.',
            'min_questions' => 'يجب أن يحتوي الامتحان على سؤال واحد على الأقل.',
            'required_field' => 'هذا الحقل مطلوب.',
            'single_correct_answer' => 'يمكن أن تحتوي أسئلة الاختيار الواحد على إجابة صحيحة واحدة فقط.',
            'at_least_one_correct' => 'يجب تحديد خيار واحد على الأقل كإجابة صحيحة.',
            'title_en_required' => 'العنوان بالإنجليزية مطلوب.',
            'title_ar_required' => 'العنوان بالعربية مطلوب.',
            'duration_required' => 'المدة مطلوبة.',
            'duration_min' => 'يجب أن تكون المدة دقيقة واحدة على الأقل.',
            'duration_max' => 'لا يمكن أن تتجاوز المدة 300 دقيقة.',
            'questions_required' => 'سؤال واحد على الأقل مطلوب.',
            'question_text_en_required' => 'نص السؤال بالإنجليزية مطلوب.',
            'question_text_ar_required' => 'نص السؤال بالعربية مطلوب.',
            'question_type_required' => 'نوع السؤال مطلوب.',
            'question_type_invalid' => 'نوع السؤال غير صالح.',
            'points_required' => 'نقاط السؤال مطلوبة.',
            'points_min' => 'يجب أن تكون نقاط السؤال نقطة واحدة على الأقل.',
            'options_required' => 'خيارات السؤال مطلوبة.',
            'options_min' => 'يجب أن يحتوي كل سؤال على خيارين على الأقل.',
            'option_text_en_required' => 'نص الخيار بالإنجليزية مطلوب.',
            'option_text_ar_required' => 'نص الخيار بالعربية مطلوب.',
        ],

        // Success Messages
        'success' => [
            'exam_created' => 'تم إنشاء الامتحان بنجاح!',
            'exam_updated' => 'تم تحديث الامتحان بنجاح!',
            'exam_deleted' => 'تم حذف الامتحان بنجاح!',
        ],

        // Confirmation Messages
        'confirmations' => [
            'delete_question' => 'هل أنت متأكد من أنك تريد حذف هذا السؤال؟',
            'delete_option' => 'هل أنت متأكد من أنك تريد حذف هذا الخيار؟',
            'leave_page' => 'لديك تغييرات غير محفوظة. هل أنت متأكد من أنك تريد المغادرة؟',
        ],
    ],

    // General exam-related translations
    'index' => [
        'title' => 'إدارة الامتحانات',
        'create_new' => 'إنشاء امتحان جديد',
        'no_exams' => 'لم يتم العثور على امتحانات.',
        'search_placeholder' => 'البحث في الامتحانات...',
    ],

    'edit' => [
        'page_title' => 'تعديل الامتحان',
        'update_exam' => 'تحديث الامتحان',
        'save_changes' => 'حفظ التغييرات',
    ],

    'show' => [
        'exam_details' => 'تفاصيل الامتحان',
        'questions_count' => 'الأسئلة',
        'duration' => 'المدة',
        'created_at' => 'تاريخ الإنشاء',
        'updated_at' => 'آخر تحديث',
    ],

    'common' => [
        'actions' => 'الإجراءات',
        'edit' => 'تعديل',
        'delete' => 'حذف',
        'view' => 'عرض',
        'status' => 'الحالة',
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'draft' => 'مسودة',
        'published' => 'منشور',
    ],
];