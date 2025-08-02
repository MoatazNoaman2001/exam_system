// Enhanced ExamQuestionManager for Edit Mode
class EditExamQuestionManager extends ExamQuestionManager {
    constructor() {
        super();
        this.isEditMode = true;
        this.initEditMode();
    }

    initEditMode() {
        console.log('Initializing Edit Mode for Exam Question Manager...');
        
        // Get existing questions count
        const existingQuestions = this.questionsContainer.querySelectorAll('.question-card');
        this.questionCount = existingQuestions.length;
        
        // Setup existing questions
        this.setupExistingQuestions();
        
        // Hide empty state if we have questions
        if (this.questionCount > 0) {
            this.hideEmptyState();
        }
        
        console.log(`Edit mode initialized with ${this.questionCount} existing questions`);
    }

    setupExistingQuestions() {
        const existingQuestions = this.questionsContainer.querySelectorAll('.question-card');
        
        existingQuestions.forEach((questionCard, index) => {
            // Ensure proper data attributes
            questionCard.setAttribute('data-question-index', index);
            
            // Setup event listeners for existing questions
            this.setupQuestionEvents(questionCard);
            
            // Setup character counters for existing options
            const options = questionCard.querySelectorAll('.option-card');
            options.forEach(option => {
                this.setupCharacterCounters(option);
                
                // Setup remove option button for existing options
                const removeButton = option.querySelector('.remove-option');
                if (removeButton) {
                    removeButton.addEventListener('click', () => {
                        this.removeOption(option, questionCard);
                    });
                }
            });
            
            // Validate existing questions
            this.validateQuestionInRealTime(questionCard);
        });
    }

    // Override addQuestion to handle edit mode properly
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

        console.log(`Added new question ${this.questionCount} in edit mode`);
    }

    // Override to handle existing questions properly during reindexing
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
            
            // Update all field names in this question ONLY if they don't already have the correct index
            const questionTextEn = question.querySelector('.question-text-en');
            if (questionTextEn && !questionTextEn.name.includes(`questions[${newArrayIndex}]`)) {
                this.setupQuestionFieldNames(question, newDisplayNumber);
                this.reindexOptions(question);
            }
        });
        
        this.questionCount = questions.length;
    }

    // Enhanced validation for edit mode
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
                errors.push(`Question ${index + 1} has validation errors.`);
            }
        });
        
        // Show errors if any
        if (!isValid) {
            this.showValidationErrors(errors);
        }
        
        return isValid;
    }

    // Enhanced form data logging for edit mode
    logFormData() {
        if (console && console.log) {
            const formData = new FormData(this.form);
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            console.log('Edit Form Data Structure:', data);
            
            // Also log questions structure specifically
            const questions = this.questionsContainer.querySelectorAll('.question-card');
            questions.forEach((question, index) => {
                const questionType = question.querySelector('.question-type')?.value;
                const correctAnswerInputs = Array.from(question.querySelectorAll('.is-correct')).map(input => ({
                    type: input.type,
                    name: input.name,
                    value: input.value,
                    checked: input.checked
                }));
                
                console.log(`Edit Question ${index + 1} structure:`, {
                    questionIndex: this.getQuestionArrayIndex(question),
                    type: questionType,
                    optionsCount: question.querySelectorAll('.option-card').length,
                    correctAnswerInputs: correctAnswerInputs,
                    hasExistingData: question.querySelector('.question-text-en')?.value?.trim().length > 0
                });
            });
        }
    }

    // Show notification with edit-specific styling
    showEditNotification(message, type = 'info') {
        this.showNotification(`Edit Mode: ${message}`, type);
    }

    // Override setup to handle edit mode warnings
    setupUnsavedChangesWarning() {
        let hasUnsavedChanges = false;
        let originalFormState = null;
        
        // Capture initial form state
        if (this.form) {
            originalFormState = new FormData(this.form);
        }
        
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
                e.returnValue = 'You have unsaved changes to this exam. Are you sure you want to leave?';
            }
        });
    }

    // Method to compare form states (useful for edit mode)
    hasFormChanged() {
        if (!this.originalFormState) return false;
        
        const currentFormData = new FormData(this.form);
        
        // Compare form data
        for (let [key, value] of currentFormData.entries()) {
            if (this.originalFormState.get(key) !== value) {
                return true;
            }
        }
        
        return false;
    }

    // Save current form state as original (for comparison)
    saveOriginalFormState() {
        if (this.form) {
            this.originalFormState = new FormData(this.form);
        }
    }

    // Method to handle question deletion with confirmation in edit mode
    removeQuestion(questionCard) {
        // Get question info for confirmation
        const questionNumber = questionCard.querySelector('.question-number')?.textContent || 'Unknown';
        const questionText = questionCard.querySelector('.question-text-en')?.value?.trim() || 'Untitled Question';
        
        // Show confirmation dialog for existing questions with content
        if (questionText !== 'Untitled Question' && questionText.length > 0) {
            const confirmMessage = `Are you sure you want to delete Question ${questionNumber}? This action cannot be undone.\n\nQuestion: "${questionText.substring(0, 50)}${questionText.length > 50 ? '...' : ''}"`;
            
            if (!confirm(confirmMessage)) {
                return;
            }
        }
        
        // Prevent removing if only 1 question left
        const currentQuestions = this.questionsContainer.querySelectorAll('.question-card');
        
        if (currentQuestions.length <= 1) {
            this.showEditNotification(
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
            
            this.showEditNotification(`Question ${questionNumber} has been removed.`, 'info');
        }, 300);
    }

    // Enhanced option removal with confirmation for edit mode
    removeOption(optionCard, questionCard) {
        // Prevent removing if only 2 options left
        const optionsContainer = questionCard.querySelector('.options-container');
        const currentOptions = optionsContainer.querySelectorAll('.option-card');
        
        if (currentOptions.length <= 2) {
            this.showEditNotification(
                this.translations.validationErrors?.minOptions || 'Each question must have at least 2 options.',
                'warning'
            );
            return;
        }
        
        // Check if this is a correct answer
        const isCorrect = optionCard.querySelector('.is-correct')?.checked;
        const optionText = optionCard.querySelector('.option-text-en')?.value?.trim() || 'Empty option';
        
        if (isCorrect && optionText !== 'Empty option') {
            const confirmMessage = `You are about to delete a correct answer option: "${optionText.substring(0, 50)}${optionText.length > 50 ? '...' : ''}"\n\nAre you sure you want to continue?`;
            
            if (!confirm(confirmMessage)) {
                return;
            }
        }
        
        // Add fade out animation
        optionCard.classList.add('fade-out');
        
        setTimeout(() => {
            optionCard.remove();
            this.reindexOptions(questionCard);
            this.showEditNotification('Option has been removed.', 'info');
        }, 300);
    }

    // Method to reset form to original state
    resetFormToOriginal() {
        if (confirm('Are you sure you want to reset all changes? This will restore the form to its original state.')) {
            location.reload();
        }
    }

    // Method to auto-save form data (could be useful for edit mode)
    autoSaveFormData() {
        if (!this.form) return;
        
        try {
            const formData = new FormData(this.form);
            const dataObj = {};
            
            for (let [key, value] of formData.entries()) {
                dataObj[key] = value;
            }
            
            // Save to localStorage with exam ID if available
            const examId = document.querySelector('meta[name="exam-id"]')?.content || 'draft';
            localStorage.setItem(`exam_edit_draft_${examId}`, JSON.stringify(dataObj));
            
            console.log('Form data auto-saved');
        } catch (error) {
            console.warn('Could not auto-save form data:', error);
        }
    }

    // Method to load auto-saved data
    loadAutoSavedData() {
        try {
            const examId = document.querySelector('meta[name="exam-id"]')?.content || 'draft';
            const savedData = localStorage.getItem(`exam_edit_draft_${examId}`);
            
            if (savedData) {
                const dataObj = JSON.parse(savedData);
                console.log('Found auto-saved data:', dataObj);
                
                // You could implement logic here to restore form fields
                // This would be more complex and might need user confirmation
            }
        } catch (error) {
            console.warn('Could not load auto-saved data:', error);
        }
    }

    // Clear auto-saved data after successful submission
    clearAutoSavedData() {
        try {
            const examId = document.querySelector('meta[name="exam-id"]')?.content || 'draft';
            localStorage.removeItem(`exam_edit_draft_${examId}`);
            console.log('Auto-saved data cleared');
        } catch (error) {
            console.warn('Could not clear auto-saved data:', error);
        }
    }

    // Enhanced form submission for edit mode
    handleFormSubmit(e) {
        e.preventDefault();
        
        if (!this.validateForm()) {
            return;
        }
        
        // Show loading state
        this.setSubmitButtonLoading(true);
        
        // Log form data for debugging
        this.logFormData();
        
        // Clear auto-saved data since we're submitting
        this.clearAutoSavedData();
        
        // Submit form after short delay to show loading
        setTimeout(() => {
            this.form.submit();
        }, 500);
    }
}

// Initialize Edit Mode when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, checking for edit mode...');
    
    // Check if we're in edit mode by looking for existing questions
    const existingQuestions = document.querySelectorAll('.question-card');
    
    if (existingQuestions.length > 0) {
        console.log('Edit mode detected, initializing EditExamQuestionManager...');
        window.examQuestionManager = new EditExamQuestionManager();
        
        // Setup auto-save every 30 seconds
        setInterval(() => {
            if (window.examQuestionManager) {
                window.examQuestionManager.autoSaveFormData();
            }
        }, 30000);
        
    } else {
        console.log('Create mode detected, using standard ExamQuestionManager...');
        window.examQuestionManager = new ExamQuestionManager();
    }
});

// Export for global access
window.EditExamQuestionManager = EditExamQuestionManager;

// Additional utility functions for edit mode
window.ExamEditUtils = {
    // Function to duplicate a question
    duplicateQuestion: function(questionCard) {
        if (window.examQuestionManager && typeof window.examQuestionManager.addQuestion === 'function') {
            // Get the question data
            const questionData = {
                text_en: questionCard.querySelector('.question-text-en')?.value || '',
                text_ar: questionCard.querySelector('.question-text-ar')?.value || '',
                type: questionCard.querySelector('.question-type')?.value || 'single_choice',
                points: questionCard.querySelector('.question-points')?.value || 1,
                options: []
            };
            
            // Get options data
            const options = questionCard.querySelectorAll('.option-card');
            options.forEach(option => {
                questionData.options.push({
                    text_en: option.querySelector('.option-text-en')?.value || '',
                    text_ar: option.querySelector('.option-text-ar')?.value || '',
                    reason: option.querySelector('.reason-text-en')?.value || '',
                    reason_ar: option.querySelector('.reason-text-ar')?.value || '',
                    is_correct: option.querySelector('.is-correct')?.checked || false
                });
            });
            
            // Add new question
            window.examQuestionManager.addQuestion();
            
            // Fill with duplicated data
            const newQuestions = document.querySelectorAll('.question-card');
            const newQuestion = newQuestions[newQuestions.length - 1];
            
            if (newQuestion) {
                // Fill question data
                const newQuestionTextEn = newQuestion.querySelector('.question-text-en');
                const newQuestionTextAr = newQuestion.querySelector('.question-text-ar');
                const newQuestionType = newQuestion.querySelector('.question-type');
                const newQuestionPoints = newQuestion.querySelector('.question-points');
                
                if (newQuestionTextEn) newQuestionTextEn.value = questionData.text_en + ' (Copy)';
                if (newQuestionTextAr) newQuestionTextAr.value = questionData.text_ar + ' (نسخة)';
                if (newQuestionType) newQuestionType.value = questionData.type;
                if (newQuestionPoints) newQuestionPoints.value = questionData.points;
                
                // Trigger type change to setup correct answer inputs
                if (newQuestionType) {
                    newQuestionType.dispatchEvent(new Event('change'));
                }
                
                console.log('Question duplicated successfully');
                window.examQuestionManager.showEditNotification('Question duplicated successfully!', 'success');
            }
        }
    },
    
    // Function to move question up
    moveQuestionUp: function(questionCard) {
        const prevQuestion = questionCard.previousElementSibling;
        if (prevQuestion && prevQuestion.classList.contains('question-card')) {
            questionCard.parentNode.insertBefore(questionCard, prevQuestion);
            window.examQuestionManager.updateQuestionNumbers();
            window.examQuestionManager.showEditNotification('Question moved up.', 'info');
        }
    },
    
    // Function to move question down
    moveQuestionDown: function(questionCard) {
        const nextQuestion = questionCard.nextElementSibling;
        if (nextQuestion && nextQuestion.classList.contains('question-card')) {
            questionCard.parentNode.insertBefore(nextQuestion, questionCard);
            window.examQuestionManager.updateQuestionNumbers();
            window.examQuestionManager.showEditNotification('Question moved down.', 'info');
        }
    }
};