<div>
    <!-- Simplicity is the ultimate sophistication. - Leonardo da Vinci -->
</div>
<template id="question-template">
    <div class="question-card card border-start border-4 border-primary mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
            <h6 class="mb-0 fw-bold text-primary">
                <i class="fas fa-question-circle me-2"></i>
                {{ __('Question') }} <span class="question-number badge bg-primary">1</span>
            </h6>
            <button type="button" class="btn btn-outline-danger btn-sm remove-question" title="{{ __('Remove Question') }}">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <div class="card-body">
            <!-- Question Text (Bilingual) -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <textarea class="form-control question-text-en" 
                                  style="height: 80px" 
                                  placeholder="{{ __('Enter question in English') }}"
                                  required></textarea>
                        <label>{{ __('Question Text (English)') }} <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <textarea class="form-control question-text-ar" 
                                  style="height: 80px" 
                                  placeholder="أدخل السؤال بالعربية"
                                  dir="rtl"
                                  required></textarea>
                        <label>{{ __('Question Text (Arabic)') }} <span class="text-danger">*</span></label>
                    </div>
                </div>
            </div>

            <!-- Question Configuration -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select question-type" required>
                            <option value="">{{ __('Select question type') }}</option>
                            <option value="single_choice">{{ __('Single Choice') }}</option>
                            <option value="multiple_choice">{{ __('Multiple Choice') }}</option>
                        </select>
                        <label>{{ __('Question Type') }} <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="number" 
                               class="form-control question-points" 
                               min="1" 
                               value="1" 
                               placeholder="{{ __('Points') }}"
                               required>
                        <label>{{ __('Points') }} <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <button type="button" class="btn btn-outline-success w-100 add-option">
                        <i class="fas fa-plus me-1"></i>{{ __('Add Option') }}
                    </button>
                </div>
            </div>

            <!-- Options Container -->
            <div class="options-container">
                <h6 class="text-muted mb-3">{{ __('Answer Options') }}</h6>
                <!-- Options will be added here dynamically -->
            </div>
        </div>
    </div>
</template>

<!-- Option Template (Hidden) -->
<template id="option-template">
    <div class="option-item card border-0 bg-light mb-3">
        <div class="card-body p-3">
            <!-- Option Text (Bilingual) -->
            <div class="row mb-2">
                <div class="col-md-5">
                    <div class="form-floating">
                        <input type="text" 
                               class="form-control option-text-en" 
                               placeholder="{{ __('Option text in English') }}"
                               required>
                        <label>{{ __('Option (English)') }} <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-floating">
                        <input type="text" 
                               class="form-control option-text-ar" 
                               placeholder="نص الخيار بالعربية"
                               dir="rtl"
                               required>
                        <label>{{ __('Option (Arabic)') }} <span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-center justify-content-center">
                    <div class="form-check me-2">
                        <input class="form-check-input is-correct" type="checkbox" id="correct-option">
                        <label class="form-check-label" for="correct-option">
                            {{ __('Correct') }}
                        </label>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-option ms-2" title="{{ __('Remove Option') }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Reason/Explanation (Bilingual) -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating">
                        <textarea class="form-control reason-text-en" 
                                  style="height: 60px" 
                                  placeholder="{{ __('Explain why this option is correct/incorrect') }}"></textarea>
                        <label>{{ __('Reason/Explanation (English)') }}</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <textarea class="form-control reason-text-ar" 
                                  style="height: 60px" 
                                  placeholder="اشرح لماذا هذا الخيار صحيح/خاطئ"
                                  dir="rtl"></textarea>
                        <label>{{ __('Reason/Explanation (Arabic)') }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>