class ExamQuestionManager {
    constructor() {
        console.log('strating');
        
        this.questionCount = 0;
        this.questionsContainer = document.getElementById('questions-container');
        this.questionTemplate = document.getElementById('question-template');
        this.optionTemplate = document.getElementById('option-template');
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

        // Add initial question
        this.addQuestion();
        
        // Event listeners
        this.addQuestionBtn.addEventListener('click', () => {
            console.log('clicked');
            
            this.addQuestion()
        });
        this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        
        // Show empty state if no questions
        this.updateEmptyState();
        
        // Warn before leaving page with unsaved changes
        this.setupUnsavedChangesWarning();
    }

    addQuestion() {
        this.questionCount++;
        
        // Clone template content
        const templateContent = this.questionTemplate.content.cloneNode(true);
        const questionCard = templateContent.querySelector('.question-card');
        
        // Update question number
        const questionNumber = questionCard.querySelector('.question-number');
        questionNumber.textContent = this.questionCount;
        
        // Set up form field names
        this.setupQuestionFieldNames(questionCard, this.questionCount);
        
        // Add to container
        this.questionsContainer.appendChild(questionCard);
        
        // Setup event listeners for this question
        this.setupQuestionEvents(questionCard);
        
        // Add default options
        this.addDefaultOptions(questionCard);
        
        // Update empty state
        this.updateEmptyState();
        
        // Focus on question text
        const questionTextEn = questionCard.querySelector('.question-text-en');
        if (questionTextEn) {
            questionTextEn.focus();
        }
    }

    setupQuestionFieldNames(questionCard, questionIndex) {
        // Set names for question fields
        const questionTextEn = questionCard.querySelector('.question-text-en');
        const questionTextAr = questionCard.querySelector('.question-text-ar');
        const questionType = questionCard.querySelector('.question-type');
        const questionPoints = questionCard.querySelector('.question-points');
        
        if (questionTextEn) questionTextEn.name = `questions[${questionIndex}][text_en]`;
        if (questionTextAr) questionTextAr.name = `questions[${questionIndex}][text_ar]`;
        if (questionType) questionType.name = `questions[${questionIndex}][type]`;
        if (questionPoints) questionPoints.name = `questions[${questionIndex}][points]`;
    }

    setupQuestionEvents(questionCard) {
        // Remove question button
        const removeButton = questionCard.querySelector('.remove-question');
        if (removeButton) {
            removeButton.addEventListener('click', () => {
                this.removeQuestion(questionCard);
            });
        }
        
        // Question type change
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
                const questionIndex = this.getQuestionIndex(questionCard);
                this.addOption(questionCard, questionIndex);
            });
        }
    }

    addDefaultOptions(questionCard) {
        const questionIndex = this.getQuestionIndex(questionCard);
        // Add 2 default options
        this.addOption(questionCard, questionIndex);
        this.addOption(questionCard, questionIndex);
    }

    handleQuestionTypeChange(questionCard, questionType) {
        const options = questionCard.querySelectorAll('.option-item');
        
        options.forEach(option => {
            const correctInput = option.querySelector('.is-correct');
            const questionIndex = this.getQuestionIndex(questionCard);
            const optionIndex = this.getOptionIndex(option);
            
            if (questionType === 'single_choice') {
                correctInput.type = 'radio';
                correctInput.name = `questions[${questionIndex}][correct_answer]`;
                correctInput.value = optionIndex;
            } else if (questionType === 'multiple_choice') {
                correctInput.type = 'checkbox';
                correctInput.name = `questions[${questionIndex}][options][${optionIndex}][is_correct]`;
                correctInput.value = '1';
            }
        });
    }

    addOption(questionCard, questionIndex) {
        const optionsContainer = questionCard.querySelector('.options-container');
        const templateContent = this.optionTemplate.content.cloneNode(true);
        const optionItem = templateContent.querySelector('.option-item');
        
        // Get current option index
        const currentOptions = optionsContainer.querySelectorAll('.option-item');
        const optionIndex = currentOptions.length;
        
        // Setup field names
        this.setupOptionFieldNames(optionItem, questionIndex, optionIndex);
        
        // Setup remove button
        const removeButton = optionItem.querySelector('.remove-option');
        if (removeButton) {
            removeButton.addEventListener('click', () => {
                this.removeOption(optionItem, questionCard);
            });
        }
        
        // Add to container
        optionsContainer.appendChild(optionItem);
        
        // Update question type behavior
        const questionType = questionCard.querySelector('.question-type').value;
        if (questionType) {
            this.handleQuestionTypeChange(questionCard, questionType);
        }
        
        // Update unique IDs for form controls
        this.updateOptionIds(optionItem, questionIndex, optionIndex);
    }

    setupOptionFieldNames(optionItem, questionIndex, optionIndex) {
        const optionTextEn = optionItem.querySelector('.option-text-en');
        const optionTextAr = optionItem.querySelector('.option-text-ar');
        const reasonTextEn = optionItem.querySelector('.reason-text-en');
        const reasonTextAr = optionItem.querySelector('.reason-text-ar');
        const isCorrect = optionItem.querySelector('.is-correct');
        
        if (optionTextEn) optionTextEn.name = `questions[${questionIndex}][options][${optionIndex}][text_en]`;
        if (optionTextAr) optionTextAr.name = `questions[${questionIndex}][options][${optionIndex}][text_ar]`;
        if (reasonTextEn) reasonTextEn.name = `questions[${questionIndex}][options][${optionIndex}][reason]`;
        if (reasonTextAr) reasonTextAr.name = `questions[${questionIndex}][options][${optionIndex}][reason_ar]`;
        if (isCorrect) isCorrect.name = `questions[${questionIndex}][options][${optionIndex}][is_correct]`;
    }

    updateOptionIds(optionItem, questionIndex, optionIndex) {
        const isCorrect = optionItem.querySelector('.is-correct');
        const label = optionItem.querySelector('.form-check-label');
        
        if (isCorrect && label) {
            const uniqueId = `correct-option-${questionIndex}-${optionIndex}`;
            isCorrect.id = uniqueId;
            label.setAttribute('for', uniqueId);
        }
    }

    removeOption(optionItem, questionCard) {
        // Prevent removing if only 2 options left
        const optionsContainer = questionCard.querySelector('.options-container');
        const currentOptions = optionsContainer.querySelectorAll('.option-item');
        
        if (currentOptions.length <= 2) {
            this.showAlert('warning', this.translations.validationErrors?.minOptions || 'Each question must have at least 2 options.');
            return;
        }
        
        optionItem.remove();
        
        // Reindex remaining options
        this.reindexOptions(questionCard);
    }

    reindexOptions(questionCard) {
        const questionIndex = this.getQuestionIndex(questionCard);
        const options = questionCard.querySelectorAll('.option-item');
        
        options.forEach((option, index) => {
            this.setupOptionFieldNames(option, questionIndex, index);
            this.updateOptionIds(option, questionIndex, index);
        });
        
        // Update question type behavior after reindexing
        const questionType = questionCard.querySelector('.question-type').value;
        if (questionType) {
            this.handleQuestionTypeChange(questionCard, questionType);
        }
    }

    removeQuestion(questionCard) {
        // Prevent removing if only 1 question left
        const currentQuestions = this.questionsContainer.querySelectorAll('.question-card');
        
        if (currentQuestions.length <= 1) {
            this.showAlert('warning', this.translations.validationErrors?.minQuestions || 'Exam must have at least 1 question.');
            return;
        }
        
        // Add fade out animation
        questionCard.style.animation = 'fadeOut 0.3s ease-out';
        
        setTimeout(() => {
            questionCard.remove();
            this.updateQuestionNumbers();
            this.updateEmptyState();
        }, 300);
    }

    updateQuestionNumbers() {
        const questions = this.questionsContainer.querySelectorAll('.question-card');
        
        questions.forEach((question, index) => {
            const questionNumber = question.querySelector('.question-number');
            const newIndex = index + 1;
            
            questionNumber.textContent = newIndex;
            
            // Update all field names in this question
            this.setupQuestionFieldNames(question, newIndex);
            
            // Update options for this question
            this.reindexOptions(question);
        });
        
        this.questionCount = questions.length;
    }

    getQuestionIndex(questionCard) {
        const questions = Array.from(this.questionsContainer.querySelectorAll('.question-card'));
        return questions.indexOf(questionCard) + 1;
    }

    getOptionIndex(optionItem) {
        const options = Array.from(optionItem.closest('.options-container').querySelectorAll('.option-item'));
        return options.indexOf(optionItem);
    }

    updateEmptyState() {
        const questions = this.questionsContainer.querySelectorAll('.question-card');
        
        if (questions.length === 0) {
            this.showEmptyState();
        } else {
            this.hideEmptyState();
        }
    }

    showEmptyState() {
        if (!document.querySelector('.empty-questions')) {
            const emptyState = document.createElement('div');
            emptyState.className = 'empty-questions';
            emptyState.innerHTML = `
                <i class="fas fa-question-circle"></i>
                <h5>${this.translations.noQuestionsTitle || 'No Questions Added Yet'}</h5>
                <p class="text-muted">${this.translations.noQuestionsText || 'Click "Add Question" to start building your exam.'}</p>
            `;
            this.questionsContainer.appendChild(emptyState);
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
        
        // Submit form
        this.form.submit();
    }

    validateForm() {
        let isValid = true;
        const errors = [];
        
        // Validate basic exam info
        const titleEn = document.getElementById('title_en');
        const titleAr = document.getElementById('title_ar');
        const duration = document.getElementById('duration');
        
        if (!titleEn.value.trim()) {
            errors.push(this.translations.validationErrors?.titleEnRequired || 'English title is required.');
            titleEn.classList.add('is-invalid');
            isValid = false;
        } else {
            titleEn.classList.remove('is-invalid');
        }
        
        if (!titleAr.value.trim()) {
            errors.push(this.translations.validationErrors?.titleArRequired || 'Arabic title is required.');
            titleAr.classList.add('is-invalid');
            isValid = false;
        } else {
            titleAr.classList.remove('is-invalid');
        }
        
        if (!duration.value || duration.value < 1) {
            errors.push(this.translations.validationErrors?.durationMin || 'Duration must be at least 1 minute.');
            duration.classList.add('is-invalid');
            isValid = false;
        } else {
            duration.classList.remove('is-invalid');
        }
        
        // Validate questions
        const questions = this.questionsContainer.querySelectorAll('.question-card');
        
        if (questions.length === 0) {
            errors.push(this.translations.validationErrors?.questionsRequired || 'At least one question is required.');
            isValid = false;
        }
        
        questions.forEach((question, index) => {
            const questionValid = this.validateQuestion(question, index + 1);
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

    validateQuestion(questionCard, questionNumber) {
        let isValid = true;
        const errors = [];
        
        // Validate question text
        const questionTextEn = questionCard.querySelector('.question-text-en');
        const questionTextAr = questionCard.querySelector('.question-text-ar');
        const questionType = questionCard.querySelector('.question-type');
        const questionPoints = questionCard.querySelector('.question-points');
        
        if (!questionTextEn.value.trim()) {
            errors.push(`${this.translations.question || 'Question'} ${questionNumber}: ${this.translations.validationErrors?.questionTextEnRequired || 'English text is required.'}`);
            questionTextEn.classList.add('is-invalid');
            isValid = false;
        } else {
            questionTextEn.classList.remove('is-invalid');
        }
        
        if (!questionTextAr.value.trim()) {
            errors.push(`${this.translations.question || 'Question'} ${questionNumber}: ${this.translations.validationErrors?.questionTextArRequired || 'Arabic text is required.'}`);
            questionTextAr.classList.add('is-invalid');
            isValid = false;
        } else {
            questionTextAr.classList.remove('is-invalid');
        }
        
        if (!questionType.value) {
            errors.push(`${this.translations.question || 'Question'} ${questionNumber}: ${this.translations.validationErrors?.questionTypeRequired || 'Question type is required.'}`);
            questionType.classList.add('is-invalid');
            isValid = false;
        } else {
            questionType.classList.remove('is-invalid');
        }
        
        if (!questionPoints.value || questionPoints.value < 1) {
            errors.push(`${this.translations.question || 'Question'} ${questionNumber}: ${this.translations.validationErrors?.pointsMin || 'Points must be at least 1.'}`);
            questionPoints.classList.add('is-invalid');
            isValid = false;
        } else {
            questionPoints.classList.remove('is-invalid');
        }
        
        // Validate options
        const options = questionCard.querySelectorAll('.option-item');
        
        if (options.length < 2) {
            errors.push(`${this.translations.question || 'Question'} ${questionNumber}: ${this.translations.validationErrors?.optionsMin || 'At least 2 options are required.'}`);
            isValid = false;
        }
        
        let hasCorrectAnswer = false;
        let optionTextsValid = true;
        
        options.forEach((option, optionIndex) => {
            const optionTextEn = option.querySelector('.option-text-en');
            const optionTextAr = option.querySelector('.option-text-ar');
            const isCorrect = option.querySelector('.is-correct');
            
            if (!optionTextEn.value.trim()) {
                errors.push(`${this.translations.question || 'Question'} ${questionNumber}, Option ${optionIndex + 1}: ${this.translations.validationErrors?.optionTextEnRequired || 'English text is required.'}`);
                optionTextEn.classList.add('is-invalid');
                optionTextsValid = false;
            } else {
                optionTextEn.classList.remove('is-invalid');
            }
            
            if (!optionTextAr.value.trim()) {
                errors.push(`${this.translations.question || 'Question'} ${questionNumber}, Option ${optionIndex + 1}: ${this.translations.validationErrors?.optionTextArRequired || 'Arabic text is required.'}`);
                optionTextAr.classList.add('is-invalid');
                optionTextsValid = false;
            } else {
                optionTextAr.classList.remove('is-invalid');
            }
            
            if (isCorrect.checked) {
                hasCorrectAnswer = true;
            }
        });
        
        if (!optionTextsValid) {
            isValid = false;
        }
        
        if (!hasCorrectAnswer) {
            errors.push(`${this.translations.question || 'Question'} ${questionNumber}: ${this.translations.validationErrors?.atLeastOneCorrect || 'At least one option must be marked as correct.'}`);
            isValid = false;
        }
        
        // Validate single choice has only one correct answer
        if (questionType.value === 'single_choice') {
            const correctAnswers = questionCard.querySelectorAll('.is-correct:checked');
            if (correctAnswers.length > 1) {
                errors.push(`${this.translations.question || 'Question'} ${questionNumber}: ${this.translations.validationErrors?.singleCorrectAnswer || 'Single choice questions can have only one correct answer.'}`);
                isValid = false;
            }
        }
        
        return isValid;
    }

    showValidationErrors(errors) {
        // Remove existing error alerts
        const existingAlerts = document.querySelectorAll('.validation-alert');
        existingAlerts.forEach(alert => alert.remove());
        
        if (errors.length > 0) {
            const errorAlert = document.createElement('div');
            errorAlert.className = 'alert alert-danger alert-dismissible fade show validation-alert';
            errorAlert.innerHTML = `
                <h6 class="alert-heading">${this.translations.validationErrorsTitle || 'Please correct the following errors:'}</h6>
                <ul class="mb-0">
                    ${errors.map(error => `<li>${error}</li>`).join('')}
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Insert at the top of the container
            const container = document.querySelector('.container-fluid');
            container.insertBefore(errorAlert, container.firstChild);
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    setSubmitButtonLoading(loading) {
        const submitBtn = this.form.querySelector('button[type="submit"]');
        
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
        this.form.addEventListener('input', () => {
            hasUnsavedChanges = true;
        });
        
        // Clear flag on successful submit
        this.form.addEventListener('submit', () => {
            hasUnsavedChanges = false;
        });
        
        // Warn before leaving
        window.addEventListener('beforeunload', (e) => {
            if (hasUnsavedChanges) {
                e.preventDefault();
                e.returnValue = this.translations.confirmations?.leavePage || 'You have unsaved changes. Are you sure you want to leave?';
            }
        });
    }
}

// Add CSS animation for fade out
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
`;
document.head.appendChild(style);

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new ExamQuestionManager();
});// public/js/exam-create.js
