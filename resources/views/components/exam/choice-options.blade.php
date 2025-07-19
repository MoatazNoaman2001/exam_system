<div>
    <!-- Order your soul. Reduce your wants. - Augustine -->
</div>
@props(['question', 'index', 'template' => false])

<div class="choice-options-container" x-data="choiceOptions({{ json_encode($question) }}, {{ $index ?? 'null' }})">
    <!-- Header Section with Add Button -->
    <div class="options-header d-flex justify-content-between align-items-center mb-4">
        <div class="header-info">
            <h6 class="mb-1 font-weight-bold text-primary">
                <i class="fas fa-list-ul me-2"></i>{{ __('Answer Options') }}
            </h6>
            <small class="text-muted">
                {{ __('Add multiple options for students to choose from') }}
            </small>
        </div>
        <button type="button" class="btn btn-sm btn-gradient-primary add-option-btn" 
                @click="addOption()" 
                data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                title="{{ __('Add new option') }}">
            <i class="fas fa-plus me-1"></i>{{ __('Add Option') }}
        </button>
    </div>
    
    <!-- Options Container -->
    <div class="options-container" x-ref="optionsContainer">
        @if(!$template && $question && in_array($question->type, ['single_choice', 'multiple_choice']))
            @foreach($question->answers as $optionIndex => $option)
                <div class="option-item mb-3 animate-fade-in" data-option-index="{{ $optionIndex }}">
                    <div class="option-card p-3 border rounded-lg shadow-sm">
                        <input type="hidden" name="questions[{{ $index }}][answers][{{ $optionIndex }}][id]" value="{{ $option->id }}">
                        
                        <!-- Option Header -->
                        <div class="option-header d-flex justify-content-between align-items-center mb-3">
                            <div class="option-label">
                                <span class="badge badge-outline-primary option-number">{{ $optionIndex + 1 }}</span>
                                <span class="text-muted ms-2">{{ __('Option') }}</span>
                            </div>
                            <div class="option-actions">
                                <!-- Correct Answer Toggle -->
                                <div class="form-check form-switch me-3">
                                    <input class="form-check-input correct-toggle" 
                                           type="{{ $question->type == 'multiple_choice' ? 'checkbox' : 'radio' }}" 
                                           name="questions[{{ $index }}][answers][{{ $optionIndex }}][is_correct]" 
                                           value="1"
                                           id="correct_{{ $index }}_{{ $optionIndex }}"
                                           {{ $option->is_correct ? 'checked' : '' }}>
                                    <label class="form-check-label correct-label" for="correct_{{ $index }}_{{ $optionIndex }}">
                                        <i class="fas fa-check-circle text-success me-1"></i>
                                        {{ __('Correct Answer') }}
                                    </label>
                                </div>
                                <!-- Remove Button -->
                                <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn" 
                                        @click="removeOption($el)"
                                        data-bs-toggle="tooltip" 
                                        title="{{ __('Remove this option') }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Option Content -->
                        <div class="row g-3">
                            <!-- English Option -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control option-input" 
                                           name="questions[{{ $index }}][answers][{{ $optionIndex }}][answer]" 
                                           value="{{ old("questions.$index.answers.$optionIndex.answer", $option->answer) }}" 
                                           placeholder="{{ __('Enter option text in English') }}"
                                           id="answer_en_{{ $index }}_{{ $optionIndex }}"
                                           required>
                                    <label for="answer_en_{{ $index }}_{{ $optionIndex }}">
                                        <i class="fas fa-font me-1"></i>{{ __('Option Text (English)') }}
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Arabic Option -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control option-input arabic-input" 
                                           name="questions[{{ $index }}][answers][{{ $optionIndex }}][answer-ar]" 
                                           value="{{ old("questions.$index.answers.$optionIndex.answer-ar", $option['answer-ar']) }}" 
                                           placeholder="{{ __('أدخل نص الخيار بالعربية') }}"
                                           id="answer_ar_{{ $index }}_{{ $optionIndex }}"
                                           dir="rtl" 
                                           required>
                                    <label for="answer_ar_{{ $index }}_{{ $optionIndex }}">
                                        <i class="fas fa-font me-1"></i>{{ __('نص الخيار (عربي)') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Option Status Indicator -->
                        <div class="option-status mt-2">
                            <div class="status-indicator" x-show="$el.closest('.option-item').querySelector('.correct-toggle').checked">
                                <i class="fas fa-star text-warning me-1"></i>
                                <small class="text-success font-weight-bold">{{ __('This is a correct answer') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    
    <!-- Options Summary -->
    <div class="options-summary mt-3 p-3 bg-light rounded">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="summary-item">
                    <h6 class="mb-0 text-primary" x-text="optionCount">{{ $question ? $question->answers->count() : 0 }}</h6>
                    <small class="text-muted">{{ __('Total Options') }}</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-item">
                    <h6 class="mb-0 text-success" x-text="getCorrectCount()">{{ $question ? $question->answers->where('is_correct', true)->count() : 0 }}</h6>
                    <small class="text-muted">{{ __('Correct Answers') }}</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-item">
                    <h6 class="mb-0 text-info">{{ $question->type == 'multiple_choice' ? __('Multiple') : __('Single') }}</h6>
                    <small class="text-muted">{{ __('Selection Type') }}</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Help Text -->
    <div class="help-text mt-3">
        <div class="alert alert-info alert-sm" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            @if($question->type == 'multiple_choice')
                <strong>{{ __('Multiple Choice:') }}</strong> {{ __('Students can select multiple correct answers. Mark all correct options.') }}
            @else
                <strong>{{ __('Single Choice:') }}</strong> {{ __('Students can select only one answer. Mark exactly one correct option.') }}
            @endif
        </div>
    </div>
</div>

<style>
/* Enhanced Styling */
.choice-options-container {
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e3e6f0;
}

.options-header {
    border-bottom: 2px solid #e3e6f0;
    padding-bottom: 1rem;
}

.btn-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
}

.btn-gradient-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
    color: white;
}

.option-card {
    background: white;
    border: 1px solid #e3e6f0;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.option-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    transform: translateY(-2px);
}

.option-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.option-card:hover::before {
    opacity: 1;
}

.badge-outline-primary {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
    border: 1px solid #667eea;
    font-weight: 600;
}

.option-number {
    min-width: 24px;
    height: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.form-floating {
    position: relative;
}

.form-floating label {
    color: #6c757d;
    font-weight: 500;
}

.option-input {
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.option-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.arabic-input {
    font-family: 'Arial', 'Tahoma', sans-serif;
    font-size: 14px;
}

.correct-toggle:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.correct-label {
    font-weight: 500;
    cursor: pointer;
    transition: color 0.3s ease;
}

.form-check-input:checked ~ .correct-label {
    color: #28a745;
}

.remove-option-btn {
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.remove-option-btn:hover {
    background-color: #dc3545;
    color: white;
    transform: scale(1.1);
}

.options-summary {
    border: 1px solid #e3e6f0;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.summary-item h6 {
    font-size: 1.25rem;
    font-weight: 700;
}

.alert-sm {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    border-radius: 8px;
    border: none;
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    color: #0c5460;
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.status-indicator {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
    100% {
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .choice-options-container {
        padding: 1rem;
    }
    
    .options-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .add-option-btn {
        margin-top: 1rem;
        width: 100%;
    }
    
    .option-actions {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .form-check {
        margin-bottom: 0.5rem;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .choice-options-container {
        background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
        border-color: #4a5568;
    }
    
    .option-card {
        background: #2d3748;
        border-color: #4a5568;
        color: #e2e8f0;
    }
    
    .form-control {
        background-color: #2d3748;
        border-color: #4a5568;
        color: #e2e8f0;
    }
}
</style>

<script>
function choiceOptions(question, index) {
    return {
        questionType: question ? question.type : 'single_choice',
        optionCount: question ? question.answers.length : 0,
        
        init() {
            // Initialize tooltips
            this.initializeTooltips();
            
            // Ensure minimum options
            if (this.optionCount < 2) {
                this.addMinimumOptions();
            }
            
            // Update summary
            this.$nextTick(() => {
                this.updateSummary();
            });
        },
        
        initializeTooltips() {
            // Initialize Bootstrap tooltips if available
            if (typeof bootstrap !== 'undefined') {
                const tooltipTriggerList = this.$el.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipTriggerList.forEach(tooltipTriggerEl => {
                    new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        },
        
        addOption() {
            const container = this.$refs.optionsContainer;
            const optionIndex = this.optionCount;
            
            const optionHtml = `
                <div class="option-item mb-3 animate-fade-in" data-option-index="${optionIndex}">
                    <div class="option-card p-3 border rounded-lg shadow-sm">
                        <div class="option-header d-flex justify-content-between align-items-center mb-3">
                            <div class="option-label">
                                <span class="badge badge-outline-primary option-number">${optionIndex + 1}</span>
                                <span class="text-muted ms-2">{{ __('Option') }}</span>
                            </div>
                            <div class="option-actions">
                                <div class="form-check form-switch me-3">
                                    <input class="form-check-input correct-toggle" 
                                           type="${this.questionType === 'multiple_choice' ? 'checkbox' : 'radio'}" 
                                           name="questions[${index}][answers][${optionIndex}][is_correct]" 
                                           value="1"
                                           id="correct_${index}_${optionIndex}">
                                    <label class="form-check-label correct-label" for="correct_${index}_${optionIndex}">
                                        <i class="fas fa-check-circle text-success me-1"></i>
                                        {{ __('Correct Answer') }}
                                    </label>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn" 
                                        onclick="this.closest('.choice-options-container').__x.$data.removeOption(this.closest('.option-item'))"
                                        data-bs-toggle="tooltip" 
                                        title="{{ __('Remove this option') }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control option-input" 
                                           name="questions[${index}][answers][${optionIndex}][answer]" 
                                           placeholder="{{ __('Enter option text in English') }}"
                                           id="answer_en_${index}_${optionIndex}"
                                           required>
                                    <label for="answer_en_${index}_${optionIndex}">
                                        <i class="fas fa-font me-1"></i>{{ __('Option Text (English)') }}
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control option-input arabic-input" 
                                           name="questions[${index}][answers][${optionIndex}][answer-ar]" 
                                           placeholder="{{ __('أدخل نص الخيار بالعربية') }}"
                                           id="answer_ar_${index}_${optionIndex}"
                                           dir="rtl" 
                                           required>
                                    <label for="answer_ar_${index}_${optionIndex}">
                                        <i class="fas fa-font me-1"></i>{{ __('نص الخيار (عربي)') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="option-status mt-2">
                            <div class="status-indicator" style="display: none;">
                                <i class="fas fa-star text-warning me-1"></i>
                                <small class="text-success font-weight-bold">{{ __('This is a correct answer') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', optionHtml);
            this.optionCount++;
            this.reindexOptions();
            this.updateSummary();
            this.initializeTooltips();
            
            // Show success message
            this.showNotification('{{ __("Option added successfully") }}', 'success');
        },
        
        removeOption(element) {
            if (this.optionCount > 2) {
                // Add fade out animation
                element.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => {
                    element.remove();
                    this.optionCount--;
                    this.reindexOptions();
                    this.updateSummary();
                    this.showNotification('{{ __("Option removed") }}', 'info');
                }, 300);
            } else {
                this.showNotification('{{ __("Each question must have at least 2 options") }}', 'warning');
            }
        },
        
        reindexOptions() {
            const options = this.$el.querySelectorAll('.option-item');
            options.forEach((option, idx) => {
                // Update option number
                const numberBadge = option.querySelector('.option-number');
                if (numberBadge) numberBadge.textContent = idx + 1;
                
                // Update form fields
                option.querySelectorAll('input').forEach(input => {
                    if (input.name && input.name.includes('[answer')) {
                        input.name = input.name.replace(/\[answers\]\[\d+\]/, `[answers][${idx}]`);
                        input.id = input.id.replace(/_\d+_\d+$/, `_${index}_${idx}`);
                    }
                });
                
                // Update labels
                option.querySelectorAll('label').forEach(label => {
                    if (label.getAttribute('for')) {
                        label.setAttribute('for', label.getAttribute('for').replace(/_\d+_\d+$/, `_${index}_${idx}`));
                    }
                });
            });
        },
        
        updateSummary() {
            // Update option count in summary
            this.$nextTick(() => {
                const summaryCount = this.$el.querySelector('.options-summary h6');
                if (summaryCount) summaryCount.textContent = this.optionCount;
            });
        },
        
        getCorrectCount() {
            const correctInputs = this.$el.querySelectorAll('.correct-toggle:checked');
            return correctInputs.length;
        },
        
        addMinimumOptions() {
            while (this.optionCount < 2) {
                this.addOption();
            }
        },
        
        showNotification(message, type = 'info') {
            // Create and show a toast notification
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : type === 'warning' ? 'exclamation-triangle' : 'info'}-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }
    }
}

// Additional CSS animation for fade out
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
`;
document.head.appendChild(style);
</script>