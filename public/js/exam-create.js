// Exam Create JavaScript - Dashboard Style Compatible
'use strict';

class ExamQuestionManager {
    constructor() {
        console.log('Initializing Exam Question Manager...');
        
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
        
        console.log('Exam Question Manager initialized successfully');
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
        
        // Set up form field names
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
        const options = questionCard.querySelectorAll('.option-card');
        
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
        const optionCard = templateContent.querySelector('.option-card');
        
        // Get current option index
        const currentOptions = optionsContainer.querySelectorAll('.option-card');
        const optionIndex = currentOptions.length;
        
        // Setup field names
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
        
        // Update question type behavior
        const questionType = questionCard.querySelector('.question-type').value;
        if (questionType) {
            this.handleQuestionTypeChange(questionCard, questionType);
        }
        
        // Update unique IDs for form controls
        this.updateOptionIds(optionCard, questionIndex, optionIndex);

        console.log(`Added option ${optionIndex + 1} to question ${questionIndex}`);
    }

    setupOptionFieldNames(optionCard, questionIndex, optionIndex) {
        const optionTextEn = optionCard.querySelector('.option-text-en');
        const optionTextAr = optionCard.querySelector('.option-text-ar');
        const reasonTextEn = optionCard.querySelector('.reason-text-en');
        const reasonTextAr = optionCard.querySelector('.reason-text-ar');
        const isCorrect = optionCard.querySelector('.is-correct');
        
        if (optionTextEn) optionTextEn.name = `questions[${questionIndex}][options][${optionIndex}][text_en]`;
        if (optionTextAr) optionTextAr.name = `questions[${questionIndex}][options][${optionIndex}][text_ar]`;
        if (reasonTextEn) reasonTextEn.name = `questions[${questionIndex}][options][${optionIndex}][reason]`;
        if (reasonTextAr) reasonTextAr.name = `questions[${questionIndex}][options][${optionIndex}][reason_ar]`;
        if (isCorrect) isCorrect.name = `questions[${questionIndex}][options][${optionIndex}][is_correct]`;
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

    reindexOptions(questionCard) {
        const questionIndex = this.getQuestionIndex(questionCard);
        const options = questionCard.querySelectorAll('.option-card');
        
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

    updateQuestionNumbers() {
        const questions = this.questionsContainer.querySelectorAll('.question-card');
        
        questions.forEach((question, index) => {
            const questionNumber = question.querySelector('.question-number');
            const questionCount = question.querySelector('.question-count');
            const newIndex = index + 1;
            
            if (questionNumber) questionNumber.textContent = newIndex;
            if (questionCount) questionCount.textContent = newIndex;
            
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

    getOptionIndex(optionCard) {
        const options = Array.from(optionCard.closest('.options-container').querySelectorAll('.option-card'));
        return options.indexOf(optionCard);
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
        
        // Submit form after short delay to show loading
        setTimeout(() => {
            this.form.submit();
        }, 500);
    }

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
            const questionValid = this.validateQuestion(question, index + 1, errors);
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

    validateQuestion(questionCard, questionNumber, errors) {
        let isValid = true;
        
        // Validate question text
        const questionTextEn = questionCard.querySelector('.question-text-en');
        const questionTextAr = questionCard.querySelector('.question-text-ar');
        const questionType = questionCard.querySelector('.question-type');
        const questionPoints = questionCard.querySelector('.question-points');
        
        if (!questionTextEn?.value.trim()) {
            errors.push(`Question ${questionNumber}: English text is required.`);
            questionTextEn?.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!questionTextAr?.value.trim()) {
            errors.push(`Question ${questionNumber}: Arabic text is required.`);
            questionTextAr?.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!questionType?.value) {
            errors.push(`Question ${questionNumber}: Question type is required.`);
            questionType?.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!questionPoints?.value || questionPoints.value < 1) {
            errors.push(`Question ${questionNumber}: Points must be at least 1.`);
            questionPoints?.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validate options
        const options = questionCard.querySelectorAll('.option-card');
        
        if (options.length < 2) {
            errors.push(`Question ${questionNumber}: At least 2 options are required.`);
            isValid = false;
        }
        
        let hasCorrectAnswer = false;
        let optionTextsValid = true;
        
        options.forEach((option, optionIndex) => {
            const optionTextEn = option.querySelector('.option-text-en');
            const optionTextAr = option.querySelector('.option-text-ar');
            const isCorrect = option.querySelector('.is-correct');
            
            if (!optionTextEn?.value.trim()) {
                errors.push(`Question ${questionNumber}, Option ${optionIndex + 1}: English text is required.`);
                optionTextEn?.classList.add('is-invalid');
                optionTextsValid = false;
            }
            
            if (!optionTextAr?.value.trim()) {
                errors.push(`Question ${questionNumber}, Option ${optionIndex + 1}: Arabic text is required.`);
                optionTextAr?.classList.add('is-invalid');
                optionTextsValid = false;
            }
            
            if (isCorrect?.checked) {
                hasCorrectAnswer = true;
            }
        });
        
        if (!optionTextsValid) {
            isValid = false;
        }
        
        if (!hasCorrectAnswer) {
            errors.push(`Question ${questionNumber}: At least one option must be marked as correct.`);
            isValid = false;
        }
        
        // Validate single choice has only one correct answer
        if (questionType?.value === 'single_choice') {
            const correctAnswers = questionCard.querySelectorAll('.is-correct:checked');
            if (correctAnswers.length > 1) {
                errors.push(`Question ${questionNumber}: Single choice questions can have only one correct answer.`);
                isValid = false;
            }
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

// Additional CSS for notifications (injected dynamically)
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
    .notification-toast {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
    }
    
    .notification-content i {
        font-size: 1.125rem;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        transition: background-color 0.2s ease;
    }
    
    .notification-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    
    .character-counter {
        font-size: 0.75rem;
        color: var(--gray-500);
        text-align: right;
        margin-top: 0.25rem;
        font-weight: 500;
    }
    
    [dir="rtl"] .character-counter {
        text-align: left;
    }
    
    /* Enhanced form validation styles */
    .form-control.is-invalid {
        border-color: var(--danger-red);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    .form-control.is-invalid:focus {
        border-color: var(--danger-red);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
    }
    
    /* Smooth transitions for validation states */
    .form-control {
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    
    /* Enhanced button loading state */
    .btn.loading {
        position: relative;
        color: transparent !important;
        pointer-events: none;
    }
    
    .btn.loading::after {
        content: "";
        position: absolute;
        width: 1rem;
        height: 1rem;
        top: 50%;
        left: 50%;
        margin-left: -0.5rem;
        margin-top: -0.5rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spinner 0.8s linear infinite;
    }
    
    @keyframes spinner {
        to {
            transform: rotate(360deg);
        }
    }
    
    /* RTL support for notifications */
    [dir="rtl"] .notification-toast {
        right: auto;
        left: 20px;
        transform: translateX(-100%);
    }
    
    [dir="rtl"] .notification-toast.show {
        transform: translateX(0);
    }
`;
document.head.appendChild(notificationStyles);

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Error handling
window.addEventListener('error', function(e) {
    console.error('Exam Create Error:', e.error);
});

// Performance monitoring
if ('performance' in window) {
    window.addEventListener('load', function() {
        setTimeout(function() {
            try {
                const perfData = performance.getEntriesByType('navigation')[0];
                const loadTime = perfData.loadEventEnd - perfData.loadEventStart;
                console.log('Exam Create Page Load Time:', loadTime + 'ms');
            } catch (error) {
                console.warn('Performance monitoring failed:', error);
            }
        }, 0);
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing Exam Question Manager...');
    new ExamQuestionManager();
});

// Export for global access if needed
window.ExamQuestionManager = ExamQuestionManager;