<div>
    <!-- It is never too late to be what you might have been. - George Eliot -->
</div>
{{-- Question Component - Modern Design --}}
@props(['question', 'index', 'template' => false])

<style>
    .question-component {
        --primary-color: #6366f1;
        --primary-light: #e0e7ff;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --radius-sm: 0.375rem;
        --radius-md: 0.5rem;
        --radius-lg: 0.75rem;
        --radius-xl: 1rem;
    }

    .question-card {
        background: #ffffff;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        margin-bottom: 2rem;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .question-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        border-color: var(--primary-color);
    }

    .question-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .question-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        opacity: 0.1;
    }

    .question-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.125rem;
        font-weight: 600;
        position: relative;
        z-index: 1;
    }

    .question-number {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn-remove-question {
        background: rgba(239, 68, 68, 0.9);
        border: none;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(10px);
    }

    .btn-remove-question:hover {
        background: var(--danger-color);
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    .question-body {
        padding: 1.5rem;
    }

    .form-section {
        margin-bottom: 1.5rem;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--gray-100);
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--gray-700);
    }

    .section-icon {
        color: var(--primary-color);
        font-size: 1.125rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-weight: 500;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .lang-badge {
        background: var(--primary-light);
        color: var(--primary-color);
        padding: 0.125rem 0.375rem;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 500;
    }

    .form-control {
        border: 2px solid var(--gray-200);
        border-radius: var(--radius-md);
        padding: 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: var(--gray-50);
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        background: white;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .form-control::placeholder {
        color: var(--gray-400);
    }

    .textarea-control {
        resize: vertical;
        min-height: 4rem;
        line-height: 1.5;
    }

    .select-control {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5rem 1.5rem;
        padding-right: 2.5rem;
        cursor: pointer;
    }

    .form-grid-3 {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.25rem;
        align-items: end;
    }

    .points-input {
        max-width: 8rem;
    }

    .options-container {
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
    }

    .option-item {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-md);
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
        position: relative;
    }

    .option-item:hover {
        border-color: var(--primary-color);
        box-shadow: var(--shadow-md);
    }

    .option-item:last-child {
        margin-bottom: 0;
    }

    .option-grid {
        display: grid;
        grid-template-columns: 1fr 1fr auto auto;
        gap: 0.75rem;
        align-items: end;
    }

    .switch-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 2.75rem;
        height: 1.5rem;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: var(--gray-300);
        transition: .4s;
        border-radius: 1.5rem;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 1.125rem;
        width: 1.125rem;
        left: 0.1875rem;
        bottom: 0.1875rem;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
        box-shadow: var(--shadow-sm);
    }

    input:checked + .slider {
        background-color: var(--success-color);
    }

    input:checked + .slider:before {
        transform: translateX(1.25rem);
    }

    .switch-label {
        font-size: 0.75rem;
        color: var(--gray-600);
        font-weight: 500;
    }

    .btn-danger-sm {
        background: var(--danger-color);
        color: white;
        width: 2rem;
        height: 2rem;
        padding: 0;
        border-radius: 50%;
        font-size: 0.75rem;
    }

    .btn-danger-sm:hover {
        background: #dc2626;
        transform: scale(1.1);
    }

    .true-false-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .true-false-option {
        background: white;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius-md);
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .true-false-option:hover {
        border-color: var(--primary-color);
        transform: translateY(-1px);
    }

    .true-false-option.selected {
        border-color: var(--success-color);
        background: #ecfdf5;
    }

    .true-false-icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .true-false-text {
        font-weight: 600;
        color: var(--gray-700);
    }

    .rtl-input {
        direction: rtl;
        text-align: right;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .option-grid {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        
        .question-header {
            padding: 1rem;
        }
        
        .question-body {
            padding: 1rem;
        }
        
        .form-grid-3 {
            grid-template-columns: 1fr;
        }
        
        .true-false-container {
            grid-template-columns: 1fr;
        }
    }

    /* Animation for new options */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .option-item.new-option {
        animation: slideIn 0.3s ease-out;
    }
    .question-component{
        height: 80%;
    }
</style>

<div class="question-component">
    <div class="question-card">
        <!-- Question Header -->
        <div class="question-header">
            <div class="question-title">
                <i class="fas fa-question-circle"></i>
                {{ __('lang.question') }}
                <span class="question-number">{{ $index + 1 }}</span>
            </div>
            <button type="button" class="btn-remove-question" onclick="removeQuestion(this)" title="{{ __('Remove Question') }}">
                <i class="fas fa-trash"></i>
            </button>
        </div>

        <!-- Question Body -->
        <div class="question-body">
            <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
            
            <!-- Question Content Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="section-icon fas fa-edit"></i>
                    <h6 class="section-title">{{ __('Question Content') }}</h6>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            {{ __('Question Text') }}
                            <span class="lang-badge">EN</span>
                        </label>
                        <textarea class="form-control textarea-control" 
                                  name="questions[{{ $index }}][question]" 
                                  placeholder="{{ __('Enter your question in English...') }}"
                                  required>{{ old("questions.$index.question", $question->question) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            {{ __('Question Text') }}
                            <span class="lang-badge">العربية</span>
                        </label>
                        <textarea class="form-control textarea-control rtl-input" 
                                  name="questions[{{ $index }}][question-ar]" 
                                  placeholder="{{ __('أدخل سؤالك بالعربية...') }}"
                                  required>{{ old("questions.$index.question-ar", $question['question-ar']) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Question Settings Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="section-icon fas fa-cog"></i>
                    <h6 class="section-title">{{ __('Question Settings') }}</h6>
                </div>
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Question Type') }}</label>
                        <select class="form-control select-control" 
                                name="questions[{{ $index }}][type]" 
                                onchange="changeQuestionType(this)" required>
                            <option value="single_choice" {{ $question->type == 'single_choice' ? 'selected' : '' }}>
                                {{ __('Single Choice') }}
                            </option>
                            <option value="multiple_choice" {{ $question->type == 'multiple_choice' ? 'selected' : '' }}>
                                {{ __('Multiple Choice') }}
                            </option>
                            <option value="true_false" {{ $question->type == 'true_false' ? 'selected' : '' }}>
                                {{ __('True/False') }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Points') }}</label>
                        <input type="number" class="form-control points-input" 
                               name="questions[{{ $index }}][marks]" 
                               min="1" 
                               value="{{ old("questions.$index.marks", $question->marks) }}" 
                               required>
                    </div>
                </div>
            </div>

            <!-- Answer Options Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="section-icon fas fa-list"></i>
                    <h6 class="section-title">{{ __('Answer Options') }}</h6>
                </div>
                
                <div class="options-container">
                    <div class="options-list">
                        @if($question->type === 'true_false')
                            <div class="true-false-container">
                                @foreach($question->answers as $optionIndex => $option)
                                    <div class="true-false-option {{ $option->is_correct ? 'selected' : '' }}" 
                                         onclick="selectTrueFalse(this, {{ $optionIndex }})">
                                        <input type="hidden" name="questions[{{ $index }}][answers][{{ $optionIndex }}][id]" value="{{ $option->id }}">
                                        <div class="true-false-icon">
                                            <i class="fas {{ $option->answer === 'True' ? 'fa-check' : 'fa-times' }}" 
                                               style="color: {{ $option->answer === 'True' ? 'var(--success-color)' : 'var(--danger-color)' }};"></i>
                                        </div>
                                        <div class="true-false-text">{{ $option->answer }} / {{ $option['answer-ar'] }}</div>
                                        <input type="radio" name="questions[{{ $index }}][correct_answer]" 
                                               value="{{ $optionIndex }}" 
                                               {{ $option->is_correct ? 'checked' : '' }} 
                                               style="display: none;">
                                        <input type="hidden" name="questions[{{ $index }}][answers][{{ $optionIndex }}][answer]" value="{{ $option->answer }}">
                                        <input type="hidden" name="questions[{{ $index }}][answers][{{ $optionIndex }}][answer-ar]" value="{{ $option['answer-ar'] }}">
                                        <input type="hidden" name="questions[{{ $index }}][answers][{{ $optionIndex }}][is_correct]" value="{{ $option->is_correct ? '1' : '0' }}">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            @foreach($question->answers as $optionIndex => $option)
                                <div class="option-item">
                                    <input type="hidden" name="questions[{{ $index }}][answers][{{ $optionIndex }}][id]" value="{{ $option->id }}">
                                    <div class="option-grid">
                                        <div class="form-group">
                                            <label class="form-label">
                                                {{ __('English Option') }}
                                                <span class="lang-badge">EN</span>
                                            </label>
                                            <input type="text" class="form-control" 
                                                   name="questions[{{ $index }}][answers][{{ $optionIndex }}][answer]" 
                                                   value="{{ old("questions.$index.answers.$optionIndex.answer", $option->answer) }}" 
                                                   placeholder="{{ __('Enter option in English') }}" 
                                                   required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">
                                                {{ __('Arabic Option') }}
                                                <span class="lang-badge">العربية</span>
                                            </label>
                                            <input type="text" class="form-control rtl-input" 
                                                   name="questions[{{ $index }}][answers][{{ $optionIndex }}][answer-ar]" 
                                                   value="{{ old("questions.$index.answers.$optionIndex.answer-ar", $option['answer-ar']) }}" 
                                                   placeholder="{{ __('أدخل الخيار بالعربية') }}" 
                                                   required>
                                        </div>
                                        <div class="switch-container">
                                            <label class="switch">
                                                <input type="checkbox" 
                                                       name="questions[{{ $index }}][answers][{{ $optionIndex }}][is_correct]" 
                                                       value="1"
                                                       {{ $option->is_correct ? 'checked' : '' }}
                                                       @if($question->type != 'multiple_choice')
                                                           onchange="handleSingleChoice(this)"
                                                       @endif>
                                                <span class="slider"></span>
                                            </label>
                                            <span class="switch-label">{{ __('Correct') }}</span>
                                        </div>
                                        <button type="button" class="btn btn-danger-sm" onclick="removeOption(this)" title="{{ __('Remove option') }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    @if($question->type !== 'true_false')
                        <button type="button" class="btn btn-outline" onclick="addOption(this)">
                            <i class="fas fa-plus"></i>
                            {{ __('Add Option') }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced JavaScript functions
function removeQuestion(button) {
    if (confirm('{{ __("Are you sure you want to remove this question?") }}')) {
        const card = button.closest('.question-card');
        card.style.transform = 'translateX(-100%)';
        card.style.opacity = '0';
        setTimeout(() => {
            card.remove();
            updateQuestionNumbers();
        }, 300);
    }
}

function removeOption(button) {
    if (confirm('{{ __("Are you sure you want to remove this option?") }}')) {
        const option = button.closest('.option-item');
        option.style.transform = 'translateX(100%)';
        option.style.opacity = '0';
        setTimeout(() => {
            option.remove();
        }, 200);
    }
}

function addOption(button) {
    const optionsList = button.previousElementSibling;
    const questionIndex = getQuestionIndex(button);
    const optionCount = optionsList.children.length;
    
    const newOption = document.createElement('div');
    newOption.className = 'option-item new-option';
    newOption.innerHTML = `
        <div class="option-grid">
            <div class="form-group">
                <label class="form-label">
                    {{ __('English Option') }}
                    <span class="lang-badge">EN</span>
                </label>
                <input type="text" class="form-control" 
                       name="questions[${questionIndex}][answers][${optionCount}][answer]" 
                       placeholder="{{ __('Enter option in English') }}" 
                       required>
            </div>
            <div class="form-group">
                <label class="form-label">
                    {{ __('Arabic Option') }}
                    <span class="lang-badge">العربية</span>
                </label>
                <input type="text" class="form-control rtl-input" 
                       name="questions[${questionIndex}][answers][${optionCount}][answer-ar]" 
                       placeholder="{{ __('أدخل الخيار بالعربية') }}" 
                       required>
            </div>
            <div class="switch-container">
                <label class="switch">
                    <input type="checkbox" 
                           name="questions[${questionIndex}][answers][${optionCount}][is_correct]" 
                           value="1"
                           onchange="handleSingleChoice(this)">
                    <span class="slider"></span>
                </label>
                <span class="switch-label">{{ __('Correct') }}</span>
            </div>
            <button type="button" class="btn btn-danger-sm" onclick="removeOption(this)" title="{{ __('Remove option') }}">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    optionsList.appendChild(newOption);
}

function changeQuestionType(select) {
    const questionType = select.value;
    const questionIndex = getQuestionIndex(select);
    const optionsContainer = select.closest('.question-body').querySelector('.options-container');
    const optionsList = optionsContainer.querySelector('.options-list');
    const addButton = optionsContainer.querySelector('.btn-outline');
    
    if (questionType === 'true_false') {
        optionsList.innerHTML = `
            <div class="true-false-container">
                <div class="true-false-option" onclick="selectTrueFalse(this, 0)">
                    <div class="true-false-icon">
                        <i class="fas fa-check" style="color: var(--success-color);"></i>
                    </div>
                    <div class="true-false-text">True / صحيح</div>
                    <input type="radio" name="questions[${questionIndex}][correct_answer]" value="0" style="display: none;">
                    <input type="hidden" name="questions[${questionIndex}][answers][0][answer]" value="True">
                    <input type="hidden" name="questions[${questionIndex}][answers][0][answer-ar]" value="صحيح">
                </div>
                <div class="true-false-option" onclick="selectTrueFalse(this, 1)">
                    <div class="true-false-icon">
                        <i class="fas fa-times" style="color: var(--danger-color);"></i>
                    </div>
                    <div class="true-false-text">False / خطأ</div>
                    <input type="radio" name="questions[${questionIndex}][correct_answer]" value="1" style="display: none;">
                    <input type="hidden" name="questions[${questionIndex}][answers][1][answer]" value="False">
                    <input type="hidden" name="questions[${questionIndex}][answers][1][answer-ar]" value="خطأ">
                </div>
            </div>
        `;
        if (addButton) addButton.style.display = 'none';
    } else {
        // Reset to regular options with at least 2 options
        optionsList.innerHTML = `
            <div class="option-item">
                <div class="option-grid">
                    <div class="form-group">
                        <label class="form-label">
                            {{ __('English Option') }}
                            <span class="lang-badge">EN</span>
                        </label>
                        <input type="text" class="form-control" 
                               name="questions[${questionIndex}][answers][0][answer]" 
                               placeholder="{{ __('Enter option in English') }}" 
                               required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            {{ __('Arabic Option') }}
                            <span class="lang-badge">العربية</span>
                        </label>
                        <input type="text" class="form-control rtl-input" 
                               name="questions[${questionIndex}][answers][0][answer-ar]" 
                               placeholder="{{ __('أدخل الخيار بالعربية') }}" 
                               required>
                    </div>
                    <div class="switch-container">
                        <label class="switch">
                            <input type="checkbox" 
                                   name="questions[${questionIndex}][answers][0][is_correct]" 
                                   value="1"
                                   ${questionType !== 'multiple_choice' ? 'onchange="handleSingleChoice(this)"' : ''}>
                            <span class="slider"></span>
                        </label>
                        <span class="switch-label">{{ __('Correct') }}</span>
                    </div>
                    <button type="button" class="btn btn-danger-sm" onclick="removeOption(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        if (addButton) addButton.style.display = 'flex';
    }
}

function selectTrueFalse(element, value) {
    const container = element.parentElement;
    container.querySelectorAll('.true-false-option').forEach(opt => {
        opt.classList.remove('selected');
    });
    
    element.classList.add('selected');
    const radioButton = element.querySelector('input[type="radio"]');
    if (radioButton) {
        radioButton.checked = true;
    }
}

function handleSingleChoice(checkbox) {
    if (checkbox.checked) {
        const questionContainer = checkbox.closest('.question-body');
        const allCheckboxes = questionContainer.querySelectorAll('input[type="checkbox"][name*="[is_correct]"]');
        allCheckboxes.forEach(cb => {
            if (cb !== checkbox) {
                cb.checked = false;
            }
        });
    }
}

function getQuestionIndex(element) {
    const questionCard = element.closest('.question-card');
    const questionNumber = questionCard.querySelector('.question-number').textContent;
    return parseInt(questionNumber) - 1;
}

function updateQuestionNumbers() {
    const questionCards = document.querySelectorAll('.question-card');
    questionCards.forEach((card, index) => {
        const numberSpan = card.querySelector('.question-number');
        if (numberSpan) {
            numberSpan.textContent = index + 1;
        }
        
        // Update all form field names with new index
        const inputs = card.querySelectorAll('input[name*="questions["], select[name*="questions["], textarea[name*="questions["]');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            const newName = name.replace(/questions\[\d+\]/, `questions[${index}]`);
            input.setAttribute('name', newName);
        });
    });
}

// Initialize tooltips and enhance UX
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling behavior
    document.querySelectorAll('.question-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });
    
    // Auto-resize textareas
    document.querySelectorAll('.textarea-control').forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
    
    // Add validation styling
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('invalid', function() {
            this.style.borderColor = 'var(--danger-color)';
            this.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
        });
        
        input.addEventListener('input', function() {
            if (this.checkValidity()) {
                this.style.borderColor = 'var(--success-color)';
                this.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.1)';
            } else {
                this.style.borderColor = 'var(--gray-200)';
                this.style.boxShadow = 'none';
            }
        });
    });
});
</script>