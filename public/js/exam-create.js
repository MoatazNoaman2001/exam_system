'use strict';

class ExamQuestionManager {
    constructor() {
        console.log('Initializing Enhanced Exam Question Manager...');
        
        this.questionCount = 0;
        this.questionsContainer = document.getElementById('questions-container');
        this.questionTemplate = document.getElementById('question-template');
        this.optionTemplate = document.getElementById('option-template');
        this.emptyTemplate = document.getElementById('empty-questions-template');
        this.addQuestionBtn = document.getElementById('add-question');
        this.form = document.getElementById('examForm');
        this.translations = window.examCreateTranslations || {};
        
        this.init();
    }

    init() {
        if (!this.questionsContainer || !this.questionTemplate || !this.optionTemplate) {
            console.error('Required elements not found');
            return;
        }

        // Show empty state initially
        this.showEmptyState();
        
        // Event listeners
        this.setupEventListeners();
        
        // Warn before leaving page with unsaved changes
        this.setupUnsavedChangesWarning();
        
        console.log('Enhanced Exam Question Manager initialized successfully');
    }

    setupEventListeners() {
        // Add question button
        if (this.addQuestionBtn) {
            this.addQuestionBtn.addEventListener('click', () => {
                this.addQuestion();
            });
        }

        // Form submission
        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        // Handle empty state add button
        document.addEventListener('click', (e) => {
            if (e.target.id === 'add-first-question') {
                this.addQuestion();
            }
        });

        // Real-time validation
        document.addEventListener('input', (e) => {
            if (e.target.closest('.question-card')) {
                this.validateQuestionInRealTime(e.target.closest('.question-card'));
            }
        });

        document.addEventListener('change', (e) => {
            if (e.target.closest('.question-card')) {
                this.validateQuestionInRealTime(e.target.closest('.question-card'));
            }
        });
    }

    addQuestion() {
        this.questionCount++;
        
        // Hide empty state
        this.hideEmptyState();
        
        // Clone template content
        const templateContent = this.questionTemplate.content.cloneNode(true);
        const questionCard = templateContent.querySelector('.question-card');
        
        // Update question number
        const questionNumber = questionCard.querySelector('.question-number');
        const questionCount = questionCard.querySelector('.question-count');
        if (questionNumber) questionNumber.textContent = this.questionCount;
        if (questionCount) questionCount.textContent = this.questionCount;
        
        // Set up form field names with proper structure
        this.setupQuestionFieldNames(questionCard, this.questionCount);
        
        // Add to container
        this.questionsContainer.appendChild(questionCard);
        
        // Setup event listeners for this question
        this.setupQuestionEvents(questionCard);
        
        // Add default options
        this.addDefaultOptions(questionCard);
        
        // Focus on question text
        setTimeout(() => {
            const questionTextEn = questionCard.querySelector('.question-text-en');
            if (questionTextEn) {
                questionTextEn.focus();
            }
        }, 100);

        console.log(`Added question ${this.questionCount}`);
    }

    setupQuestionFieldNames(questionCard, questionIndex) {
        // Set names for question fields using proper array notation
        const questionTextEn = questionCard.querySelector('.question-text-en');
        const questionTextAr = questionCard.querySelector('.question-text-ar');
        const questionType = questionCard.querySelector('.question-type');
        const questionPoints = questionCard.querySelector('.question-points');
        
        // Use zero-based index for array notation
        const arrayIndex = questionIndex - 1;
        
        if (questionTextEn) questionTextEn.name = `questions[${arrayIndex}][text_en]`;
        if (questionTextAr) questionTextAr.name = `questions[${arrayIndex}][text_ar]`;
        if (questionType) questionType.name = `questions[${arrayIndex}][type]`;
        if (questionPoints) questionPoints.name = `questions[${arrayIndex}][points]`;
        
        // Set data attributes for easier identification
        questionCard.setAttribute('data-question-index', arrayIndex);
    }

    setupQuestionEvents(questionCard) {
        // Remove question button
        const removeButton = questionCard.querySelector('.remove-question');
        if (removeButton) {
            removeButton.addEventListener('click', () => {
                this.removeQuestion(questionCard);
            });
        }
        
        // Question type change - THIS IS CRITICAL FOR CORRECT ANSWER HANDLING
        const questionTypeSelect = questionCard.querySelector('.question-type');
        if (questionTypeSelect) {
            questionTypeSelect.addEventListener('change', (e) => {
                this.handleQuestionTypeChange(questionCard, e.target.value);
            });
        }
        
        // Add option button
        const addOptionButton = questionCard.querySelector('.add-option');
        if (addOptionButton) {
            addOptionButton.addEventListener('click', () => {
                const questionIndex = this.getQuestionArrayIndex(questionCard);
                this.addOption(questionCard, questionIndex);
            });
        }
    }

    addDefaultOptions(questionCard) {
        const questionIndex = this.getQuestionArrayIndex(questionCard);
        // Add 2 default options
        this.addOption(questionCard, questionIndex);
        this.addOption(questionCard, questionIndex);
    }

    // ENHANCED: Proper handling of question type changes with correct input names
    handleQuestionTypeChange(questionCard, questionType) {
        const options = questionCard.querySelectorAll('.option-card');
        const questionIndex = this.getQuestionArrayIndex(questionCard);
        
        // Clear any existing validation errors
        this.clearQuestionValidationErrors(questionCard);
        
        options.forEach((option, optionIndex) => {
            const correctInput = option.querySelector('.is-correct');
            const correctLabel = option.querySelector('.form-check-label');
            
            if (questionType === 'single_choice') {
                // Convert to radio button for single choice
                correctInput.type = 'radio';
                correctInput.name = `questions[${questionIndex}][correct_answer]`;
                correctInput.value = optionIndex;
                correctInput.checked = false; // Clear previous selections
                
                // Update label text
                if (correctLabel) {
                    correctLabel.textContent = this.translations.correctAnswer || 'Correct Answer';
                }
                
            } else if (questionType === 'multiple_choice') {
                // Convert to checkbox for multiple choice
                correctInput.type = 'checkbox';
                correctInput.name = `questions[${questionIndex}][options][${optionIndex}][is_correct]`;
                correctInput.value = '1';
                correctInput.checked = false; // Clear previous selections
                
                // Update label text
                if (correctLabel) {
                    correctLabel.textContent = this.translations.correctOption || 'Correct Option';
                }
            }
            
            // Update unique IDs to avoid conflicts
            this.updateOptionIds(option, questionIndex, optionIndex);
        });
        
        // Add visual indicators for question type
        this.updateQuestionTypeVisuals(questionCard, questionType);
        
        // Validate after type change
        this.validateQuestionInRealTime(questionCard);
    }

    addOption(questionCard, questionIndex) {
        const optionsContainer = questionCard.querySelector('.options-container');
        const templateContent = this.optionTemplate.content.cloneNode(true);
        const optionCard = templateContent.querySelector('.option-card');
        
        // Get current option index
        const currentOptions = optionsContainer.querySelectorAll('.option-card');
        const optionIndex = currentOptions.length;
        
        // Setup field names with proper array structure
        this.setupOptionFieldNames(optionCard, questionIndex, optionIndex);
        
        // Setup remove button
        const removeButton = optionCard.querySelector('.remove-option');
        if (removeButton) {
            removeButton.addEventListener('click', () => {
                this.removeOption(optionCard, questionCard);
            });
        }
        
        // Add character counter for reason fields
        this.setupCharacterCounters(optionCard);
        
        // Add to container
        optionsContainer.appendChild(optionCard);
        
        // Update question type behavior with correct input names
        const questionType = questionCard.querySelector('.question-type').value;
        if (questionType) {
            this.handleQuestionTypeChange(questionCard, questionType);
        }
        
        // Update unique IDs for form controls
        this.updateOptionIds(optionCard, questionIndex, optionIndex);

        console.log(`Added option ${optionIndex + 1} to question ${questionIndex + 1}`);
    }

    // ENHANCED: Proper option field name setup
    setupOptionFieldNames(optionCard, questionIndex, optionIndex) {
        const optionTextEn = optionCard.querySelector('.option-text-en');
        const optionTextAr = optionCard.querySelector('.option-text-ar');
        const reasonTextEn = optionCard.querySelector('.reason-text-en');
        const reasonTextAr = optionCard.querySelector('.reason-text-ar');
        const isCorrect = optionCard.querySelector('.is-correct');
        
        // Use proper array notation for nested fields
        if (optionTextEn) optionTextEn.name = `questions[${questionIndex}][options][${optionIndex}][text_en]`;
        if (optionTextAr) optionTextAr.name = `questions[${questionIndex}][options][${optionIndex}][text_ar]`;
        if (reasonTextEn) reasonTextEn.name = `questions[${questionIndex}][options][${optionIndex}][reason]`;
        if (reasonTextAr) reasonTextAr.name = `questions[${questionIndex}][options][${optionIndex}][reason_ar]`;
        
        // The is_correct field name will be set by handleQuestionTypeChange
        // to ensure it matches the question type (radio vs checkbox)
        if (isCorrect) {
            isCorrect.setAttribute('data-question-index', questionIndex);
            isCorrect.setAttribute('data-option-index', optionIndex);
        }
    }

    setupCharacterCounters(optionCard) {
        const reasonFields = optionCard.querySelectorAll('.reason-text-en, .reason-text-ar');
        
        reasonFields.forEach(field => {
            const maxLength = field.getAttribute('maxlength') || 2000;
            
            // Create character counter
            const counter = document.createElement('div');
            counter.className = 'character-counter';
            counter.style.cssText = `
                font-size: 0.75rem;
                color: var(--gray-500);
                text-align: right;
                margin-top: 0.25rem;
            `;
            
            // Insert after field
            field.parentNode.insertBefore(counter, field.nextSibling);
            
            // Update counter
            const updateCounter = () => {
                const remaining = maxLength - field.value.length;
                counter.textContent = `${field.value.length}/${maxLength}`;
                
                if (remaining < 100) {
                    counter.style.color = 'var(--warning-amber)';
                } else if (remaining < 50) {
                    counter.style.color = 'var(--danger-red)';
                } else {
                    counter.style.color = 'var(--gray-500)';
                }
            };
            
            // Initial update
            updateCounter();
            
            // Listen for input
            field.addEventListener('input', updateCounter);
        });
    }

    updateOptionIds(optionCard, questionIndex, optionIndex) {
        const isCorrect = optionCard.querySelector('.is-correct');
        const label = optionCard.querySelector('.form-check-label');
        
        if (isCorrect && label) {
            const uniqueId = `correct-option-${questionIndex}-${optionIndex}`;
            isCorrect.id = uniqueId;
            label.setAttribute('for', uniqueId);
        }
    }

    removeOption(optionCard, questionCard) {
        // Prevent removing if only 2 options left
        const optionsContainer = questionCard.querySelector('.options-container');
        const currentOptions = optionsContainer.querySelectorAll('.option-card');
        
        if (currentOptions.length <= 2) {
            this.showNotification(
                this.translations.validationErrors?.minOptions || 'Each question must have at least 2 options.',
                'warning'
            );
            return;
        }
        
        // Add fade out animation
        optionCard.classList.add('fade-out');
        
        setTimeout(() => {
            optionCard.remove();
            this.reindexOptions(questionCard);
        }, 300);
    }

    // ENHANCED: Proper reindexing with correct input names
    reindexOptions(questionCard) {
        const questionIndex = this.getQuestionArrayIndex(questionCard);
        const options = questionCard.querySelectorAll('.option-card');
        
        options.forEach((option, index) => {
            // Update all field names with new index
            this.setupOptionFieldNames(option, questionIndex, index);
            this.updateOptionIds(option, questionIndex, index);
        });
        
        // Update question type behavior after reindexing to fix input names
        const questionType = questionCard.querySelector('.question-type').value;
        if (questionType) {
            this.handleQuestionTypeChange(questionCard, questionType);
        }
        
        // Validate after reindexing
        this.validateQuestionInRealTime(questionCard);
    }

    removeQuestion(questionCard) {
        // Prevent removing if only 1 question left
        const currentQuestions = this.questionsContainer.querySelectorAll('.question-card');
        
        if (currentQuestions.length <= 1) {
            this.showNotification(
                this.translations.validationErrors?.minQuestions || 'Exam must have at least 1 question.',
                'warning'
            );
            return;
        }
        
        // Add fade out animation
        questionCard.classList.add('fade-out');
        
        setTimeout(() => {
            questionCard.remove();
            this.updateQuestionNumbers();
            
            // Show empty state if no questions left
            if (this.questionsContainer.querySelectorAll('.question-card').length === 0) {
                this.showEmptyState();
            }
        }, 300);
    }

    // ENHANCED: Proper question numbering with correct input names
    updateQuestionNumbers() {
        const questions = this.questionsContainer.querySelectorAll('.question-card');
        
        questions.forEach((question, index) => {
            const questionNumber = question.querySelector('.question-number');
            const questionCount = question.querySelector('.question-count');
            const newDisplayNumber = index + 1;
            const newArrayIndex = index; // Zero-based for array
            
            if (questionNumber) questionNumber.textContent = newDisplayNumber;
            if (questionCount) questionCount.textContent = newDisplayNumber;
            
            // Update data attribute
            question.setAttribute('data-question-index', newArrayIndex);
            
            // Update all field names in this question
            this.setupQuestionFieldNames(question, newDisplayNumber);
            
            // Update options for this question
            this.reindexOptions(question);
        });
        
        this.questionCount = questions.length;
    }

    // ENHANCED: Get proper array index (zero-based)
    getQuestionArrayIndex(questionCard) {
        const dataIndex = questionCard.getAttribute('data-question-index');
        if (dataIndex !== null) {
            return parseInt(dataIndex);
        }
        
        // Fallback: calculate from position
        const questions = Array.from(this.questionsContainer.querySelectorAll('.question-card'));
        return questions.indexOf(questionCard);
    }

    getOptionIndex(optionCard) {
        const options = Array.from(optionCard.closest('.options-container').querySelectorAll('.option-card'));
        return options.indexOf(optionCard);
    }

    updateQuestionTypeVisuals(questionCard, questionType) {
        const optionsContainer = questionCard.querySelector('.options-container');
        
        // Remove existing type classes
        optionsContainer.classList.remove('single-choice-mode', 'multiple-choice-mode');
        
        // Add appropriate class for styling
        if (questionType === 'single_choice') {
            optionsContainer.classList.add('single-choice-mode');
        } else if (questionType === 'multiple_choice') {
            optionsContainer.classList.add('multiple-choice-mode');
        }
    }

    // ENHANCED: Real-time validation
    validateQuestionInRealTime(questionCard) {
        const questionIndex = this.getQuestionArrayIndex(questionCard);
        const questionNumber = questionIndex + 1;
        
        // Clear previous validation states
        this.clearQuestionValidationErrors(questionCard);
        
        const errors = [];
        
        // Validate question fields
        const questionTextEn = questionCard.querySelector('.question-text-en');
        const questionTextAr = questionCard.querySelector('.question-text-ar');
        const questionType = questionCard.querySelector('.question-type');
        const questionPoints = questionCard.querySelector('.question-points');
        
        if (!questionTextEn?.value.trim()) {
            questionTextEn?.classList.add('is-invalid');
            errors.push('English text required');
        }
        
        if (!questionTextAr?.value.trim()) {
            questionTextAr?.classList.add('is-invalid');
            errors.push('Arabic text required');
        }
        
        if (!questionType?.value) {
            questionType?.classList.add('is-invalid');
            errors.push('Question type required');
        }
        
        if (!questionPoints?.value || questionPoints.value < 1) {
            questionPoints?.classList.add('is-invalid');
            errors.push('Points must be at least 1');
        }
        
        // Validate options
        const options = questionCard.querySelectorAll('.option-card');
        let hasCorrectAnswer = false;
        
        if (options.length < 2) {
            errors.push('At least 2 options required');
        }
        
        options.forEach((option, optionIndex) => {
            const optionTextEn = option.querySelector('.option-text-en');
            const optionTextAr = option.querySelector('.option-text-ar');
            const isCorrect = option.querySelector('.is-correct');
            
            if (!optionTextEn?.value.trim()) {
                optionTextEn?.classList.add('is-invalid');
            }
            
            if (!optionTextAr?.value.trim()) {
                optionTextAr?.classList.add('is-invalid');
            }
            
            if (isCorrect?.checked) {
                hasCorrectAnswer = true;
            }
        });
        
        // Validate correct answers based on question type
        if (questionType?.value) {
            if (questionType.value === 'single_choice') {
                const checkedRadios = questionCard.querySelectorAll('.is-correct:checked');
                if (checkedRadios.length === 0) {
                    errors.push('Select one correct answer');
                    this.highlightCorrectAnswerError(questionCard, 'single');
                } else if (checkedRadios.length > 1) {
                    errors.push('Only one correct answer allowed');
                    this.highlightCorrectAnswerError(questionCard, 'single');
                }
            } else if (questionType.value === 'multiple_choice') {
                if (!hasCorrectAnswer) {
                    errors.push('Select at least one correct answer');
                    this.highlightCorrectAnswerError(questionCard, 'multiple');
                }
            }
        }
        
        // Update question validation state
        if (errors.length === 0) {
            questionCard.classList.remove('has-errors');
            questionCard.classList.add('validated');
        } else {
            questionCard.classList.add('has-errors');
            questionCard.classList.remove('validated');
        }
        
        return errors.length === 0;
    }

    clearQuestionValidationErrors(questionCard) {
        // Remove validation classes
        const invalidElements = questionCard.querySelectorAll('.is-invalid');
        invalidElements.forEach(element => {
            element.classList.remove('is-invalid');
        });
        
        // Remove error highlighting
        const optionsContainer = questionCard.querySelector('.options-container');
        optionsContainer.classList.remove('correct-answer-error');
        optionsContainer.removeAttribute('data-error');
        
        questionCard.classList.remove('has-errors');
    }

    highlightCorrectAnswerError(questionCard, type) {
        const optionsContainer = questionCard.querySelector('.options-container');
        
        // Add error highlighting
        optionsContainer.classList.add('correct-answer-error');
        
        // Add visual indicator for the specific type of error
        if (type === 'single') {
            optionsContainer.setAttribute('data-error', 'Select exactly one correct answer');
        } else if (type === 'multiple') {
            optionsContainer.setAttribute('data-error', 'Select at least one correct answer');
        }
        
        // Remove highlighting after 5 seconds
        setTimeout(() => {
            optionsContainer.classList.remove('correct-answer-error');
            optionsContainer.removeAttribute('data-error');
        }, 5000);
    }

    showEmptyState() {
        if (!document.querySelector('.empty-questions') && this.emptyTemplate) {
            const templateContent = this.emptyTemplate.content.cloneNode(true);
            this.questionsContainer.appendChild(templateContent);
        }
    }

    hideEmptyState() {
        const emptyState = document.querySelector('.empty-questions');
        if (emptyState) {
            emptyState.remove();
        }
    }

    handleFormSubmit(e) {
        e.preventDefault();
        
        if (!this.validateForm()) {
            return;
        }
        
        // Show loading state
        this.setSubmitButtonLoading(true);
        
        // Log form data for debugging
        this.logFormData();
        
        // Submit form after short delay to show loading
        setTimeout(() => {
            this.form.submit();
        }, 500);
    }

    // ENHANCED: Comprehensive form validation
    validateForm() {
        let isValid = true;
        const errors = [];
        
        // Clear previous validation states
        this.clearValidationStates();
        
        // Validate basic exam info
        isValid = this.validateBasicInfo(errors) && isValid;
        
        // Validate questions
        const questions = this.questionsContainer.querySelectorAll('.question-card');
        
        if (questions.length === 0) {
            errors.push(this.translations.validationErrors?.questionsRequired || 'At least one question is required.');
            isValid = false;
        }
        
        questions.forEach((question, index) => {
            const questionValid = this.validateQuestionInRealTime(question);
            if (!questionValid) {
                isValid = false;
            }
        });
        
        // Show errors if any
        if (!isValid) {
            this.showValidationErrors(errors);
        }
        
        return isValid;
    }

    validateBasicInfo(errors) {
        let isValid = true;
        
        const titleEn = document.getElementById('title_en');
        const titleAr = document.getElementById('title_ar');
        const duration = document.getElementById('duration');
        
        if (!titleEn?.value.trim()) {
            errors.push('English title is required.');
            titleEn?.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!titleAr?.value.trim()) {
            errors.push('Arabic title is required.');
            titleAr?.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!duration?.value || duration.value < 1) {
            errors.push('Duration must be at least 1 minute.');
            duration?.classList.add('is-invalid');
            isValid = false;
        }
        
        return isValid;
    }

    clearValidationStates() {
        // Remove all is-invalid classes
        const invalidElements = document.querySelectorAll('.is-invalid');
        invalidElements.forEach(element => {
            element.classList.remove('is-invalid');
        });
        
        // Remove existing error alerts
        const existingAlerts = document.querySelectorAll('.validation-alert');
        existingAlerts.forEach(alert => alert.remove());
        
        // Clear question validation states
        const questions = this.questionsContainer.querySelectorAll('.question-card');
        questions.forEach(question => {
            this.clearQuestionValidationErrors(question);
        });
    }

    showValidationErrors(errors) {
        if (errors.length === 0) return;
        
        const errorAlert = document.createElement('div');
        errorAlert.className = 'alert alert-danger alert-dismissible fade show validation-alert';
        errorAlert.innerHTML = `
            <div class="alert-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h6 class="alert-title">Please correct the following errors:</h6>
            </div>
            <ul class="alert-list">
                ${errors.map(error => `<li>${error}</li>`).join('')}
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Insert at the top of the container
        const container = document.querySelector('.exam-create-container');
        if (container) {
            container.insertBefore(errorAlert, container.firstChild);
            
            // Scroll to top smoothly
            container.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Debug helper: Log form data structure
    logFormData() {
        if (console && console.log) {
            const formData = new FormData(this.form);
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            console.log('Form Data Structure:', data);
            
            // Also log questions structure specifically
            const questions = this.questionsContainer.querySelectorAll('.question-card');
            questions.forEach((question, index) => {
                console.log(`Question ${index + 1} structure:`, {
                    questionIndex: this.getQuestionArrayIndex(question),
                    type: question.querySelector('.question-type')?.value,
                    optionsCount: question.querySelectorAll('.option-card').length,
                    correctAnswerInputs: Array.from(question.querySelectorAll('.is-correct')).map(input => ({
                        type: input.type,
                        name: input.name,
                        value: input.value,
                        checked: input.checked
                    }))
                });
            });
        }
    }

    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification-toast');
        existingNotifications.forEach(notification => notification.remove());
        
        const notification = document.createElement('div');
        notification.className = `notification-toast notification-${type}`;
        
        // Set notification content
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${this.getNotificationIcon(type)}"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="notification-close">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Style the notification
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            minWidth: '300px',
            maxWidth: '500px',
            padding: '1rem',
            borderRadius: 'var(--border-radius)',
            color: 'white',
            fontWeight: '500',
            zIndex: '9999',
            opacity: '0',
            transform: 'translateX(100%)',
            transition: 'all 0.3s ease',
            boxShadow: 'var(--shadow-md)'
        });
        
        // Set background color based on type
        const colors = {
            success: 'var(--success-green)',
            error: 'var(--danger-red)',
            warning: 'var(--warning-amber)',
            info: 'var(--primary-blue)'
        };
        notification.style.background = colors[type] || colors.info;
        
        // Add close functionality
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => {
            this.hideNotification(notification);
        });
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                this.hideNotification(notification);
            }
        }, 5000);
    }

    hideNotification(notification) {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }

    getNotificationIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || icons.info;
    }

    setSubmitButtonLoading(loading) {
        const submitBtn = this.form?.querySelector('button[type="submit"]');
        
        if (!submitBtn) return;
        
        if (loading) {
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        } else {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
        }
    }

    setupUnsavedChangesWarning() {
        let hasUnsavedChanges = false;
        
        // Track form changes
        if (this.form) {
            this.form.addEventListener('input', () => {
                hasUnsavedChanges = true;
            });
            
            // Clear flag on successful submit
            this.form.addEventListener('submit', () => {
                hasUnsavedChanges = false;
            });
        }
        
        // Warn before leaving
        window.addEventListener('beforeunload', (e) => {
            if (hasUnsavedChanges) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            }
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing Enhanced Exam Question Manager...');
    new ExamQuestionManager();
});

// Export for global access if needed
window.ExamQuestionManager = ExamQuestionManager;