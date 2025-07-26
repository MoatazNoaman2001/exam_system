<!-- Question Template -->
<template id="question-template">
    <div class="question-card">
        <div class="question-header">
            <div class="question-header-content">
                <div class="question-number-badge">
                    <span class="question-number">1</span>
                </div>
                <h4 class="question-title">
                    {{ __('exams.create.question') }} <span class="question-count">1</span>
                </h4>
            </div>
            <div class="question-actions">
                <button type="button" class="btn btn-danger btn-sm remove-question" title="{{ __('exams.create.buttons.remove_question') }}">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        
        <div class="question-body">
            <!-- Question Text (Bilingual) -->
            <div class="form-section">
                <h5 class="section-title">{{ __('exams.create.question_text') }}</h5>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">
                            {{ __('exams.create.fields.question_text_en') }}
                        </label>
                        <textarea class="form-control question-text-en" 
                                  rows="3" 
                                  placeholder="{{ __('exams.create.placeholders.question_text_en') }}"
                                  required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">
                            {{ __('exams.create.fields.question_text_ar') }}
                        </label>
                        <textarea class="form-control question-text-ar" 
                                  rows="3" 
                                  placeholder="{{ __('exams.create.placeholders.question_text_ar') }}"
                                  dir="rtl"
                                  required></textarea>
                    </div>
                </div>
            </div>

            <!-- Question Configuration -->
            <div class="form-section">
                <h5 class="section-title">{{ __('exams.create.question_settings') }}</h5>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">{{ __('exams.create.fields.question_type') }}</label>
                        <select class="form-control question-type" required>
                            <option value="">{{ __('exams.create.select_question_type') }}</option>
                            <option value="single_choice">{{ __('exams.create.single_choice') }}</option>
                            <option value="multiple_choice">{{ __('exams.create.multiple_choice') }}</option>
                        </select>
                    </div>
                    <div class="form-group form-group-quarter">
                        <label class="form-label required">{{ __('exams.create.fields.points') }}</label>
                        <input type="number" 
                               class="form-control question-points" 
                               min="1" 
                               value="1" 
                               placeholder="1"
                               required>
                    </div>
                    <div class="form-group form-group-quarter">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-success add-option">
                            <i class="fas fa-plus"></i>
                            {{ __('exams.create.buttons.add_option') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Options Container -->
            <div class="form-section">
                <h5 class="section-title">{{ __('exams.create.answer_options') }}</h5>
                <div class="options-container">
                    <!-- Options will be added here dynamically -->
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Option Template -->
<template id="option-template">
    <div class="option-card">
        <div class="option-header">
            <div class="option-header-content">
                <div class="option-number">
                    <i class="fas fa-circle"></i>
                </div>
                <h6 class="option-title">{{ __('exams.create.option') }}</h6>
            </div>
            <div class="option-actions">
                <div class="form-check">
                    <input class="form-check-input is-correct" type="checkbox" id="correct-option">
                    <label class="form-check-label" for="correct-option">
                        {{ __('exams.create.correct') }}
                    </label>
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-option" title="{{ __('exams.create.buttons.remove_option') }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="option-body">
            <!-- Option Text (Bilingual) -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">
                        {{ __('exams.create.fields.option_text_en') }}
                    </label>
                    <input type="text" 
                           class="form-control option-text-en" 
                           placeholder="{{ __('exams.create.placeholders.option_text_en') }}"
                           required>
                </div>
                <div class="form-group">
                    <label class="form-label required">
                        {{ __('exams.create.fields.option_text_ar') }}
                    </label>
                    <input type="text" 
                           class="form-control option-text-ar" 
                           placeholder="{{ __('exams.create.placeholders.option_text_ar') }}"
                           dir="rtl"
                           required>
                </div>
            </div>

            <!-- Reason/Explanation (Bilingual) - Updated with maxlength -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        {{ __('exams.create.fields.reason_en') }}
                    </label>
                    <textarea class="form-control reason-text-en" 
                              rows="3" 
                              maxlength="2000"
                              placeholder="{{ __('exams.create.placeholders.reason_en') }}"></textarea>
                    <div class="form-help">{{ __('exams.create.max_characters', ['max' => '2000']) }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        {{ __('exams.create.fields.reason_ar') }}
                    </label>
                    <textarea class="form-control reason-text-ar" 
                              rows="3" 
                              maxlength="2000"
                              placeholder="{{ __('exams.create.placeholders.reason_ar') }}"
                              dir="rtl"></textarea>
                    <div class="form-help">{{ __('exams.create.max_characters', ['max' => '2000']) }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Empty State Template -->
<template id="empty-questions-template">
    <div class="empty-questions">
        <div class="empty-icon">
            <i class="fas fa-question-circle"></i>
        </div>
        <h4 class="empty-title">{{ __('exams.create.empty_state.title') }}</h4>
        <p class="empty-text">{{ __('exams.create.empty_state.text') }}</p>
        <button type="button" class="btn btn-primary btn-lg" id="add-first-question">
            <i class="fas fa-plus"></i>
            {{ __('exams.create.buttons.add_question') }}
        </button>
    </div>
</template>