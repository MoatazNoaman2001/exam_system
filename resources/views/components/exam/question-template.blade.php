<div>
    <!-- He who is contented is rich. - Laozi -->
</div>
{{-- Question Template Component - question-template.blade.php --}}
<div class="card mb-4 question-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary">
            {{ __('Question') }} <span class="question-number">INDEX_PLACEHOLDER</span>
        </h5>
        <button type="button" class="btn btn-sm btn-danger" onclick="removeQuestion(this)">
            <i class="fas fa-trash"></i>
        </button>
    </div>
    <div class="card-body">
        <!-- Question Text -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('Question Text (English)') }}</label>
                    <textarea class="form-control" 
                              name="questions[INDEX_PLACEHOLDER][question]" 
                              rows="2" 
                              placeholder="{{ __('Enter your question in English...') }}"
                              required></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('Question Text (Arabic)') }} <span class="text-muted">(النص بالعربية)</span></label>
                    <textarea class="form-control" 
                              name="questions[INDEX_PLACEHOLDER][question-ar]" 
                              rows="2" dir="rtl" 
                              placeholder="{{ __('أدخل سؤالك بالعربية...') }}"
                              required></textarea>
                </div>
            </div>
        </div>

        <!-- Question Type & Points -->
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>{{ __('Question Type') }}</label>
                    <select class="form-control" 
                            name="questions[INDEX_PLACEHOLDER][type]" 
                            onchange="changeQuestionType(this)" required>
                        <option value="single_choice">{{ __('Single Choice') }}</option>
                        <option value="multiple_choice">{{ __('Multiple Choice') }}</option>
                        <option value="true_false">{{ __('True/False') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{ __('Points') }}</label>
                    <input type="number" class="form-control" 
                           name="questions[INDEX_PLACEHOLDER][marks]" 
                           min="1" value="1" required>
                </div>
            </div>
        </div>

        <!-- Options Section -->
        <div class="options-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">{{ __('Answer Options') }}</h6>
                <button type="button" class="btn btn-success btn-sm" onclick="addOption(this)">
                    <i class="fas fa-plus"></i> {{ __('Add Option') }}
                </button>
            </div>
            
            <div class="options-list">
                <!-- Options will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>
