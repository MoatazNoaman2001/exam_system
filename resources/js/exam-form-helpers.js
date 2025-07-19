// Exam Form Helper Functions
class ExamFormHelper {
    constructor() {
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Handle question type changes
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('question-type-select')) {
                this.handleQuestionTypeChange(e.target);
            }
        });

        // Handle form submission validation
        document.addEventListener('submit', (e) => {
            if (e.target.id === 'examForm') {
                if (!this.validateForm(e.target)) {
                    e.preventDefault();
                }
            }
        });

        // Handle radio button changes for single choice
        document.addEventListener('change', (e) => {
            if (e.target.type === 'radio' && e.target.name.includes('[is_correct]')) {
                this.handleSingleChoiceSelection(e.target);
            }
        });
    }

    handleQuestionTypeChange(selectElement) {
        const questionCard = selectElement.closest('.question-card');
        const optionsContainer = questionCard.querySelector('.options-container');
        const questionType = selectElement.value;
        
        // Update all checkbox/radio inputs based on question type
        const correctInputs = questionCard.querySelectorAll('.is-correct');
        correctInputs.forEach(input => {
            if (questionType === 'multiple_choice') {
                input.type = 'checkbox';
                input.name = input.name.replace(/\[is_correct\].*/, '[is_correct]');
            } else {
                input.type = 'radio';
                input.checked = false; // Reset selections
            }
        });

        // For true/false, ensure only 2 options exist
        if (questionType === 'true_false') {
            this.setupTrueFalseOptions(questionCard);
        }
    }

    setupTrueFalseOptions(questionCard) {
        const optionsContainer = questionCard.querySelector('.options-container');
        const existingOptions = optionsContainer.querySelectorAll('.option-item');
        
        // Remove extra options if more than 2
        if (existingOptions.length > 2) {
            for (let i = 2; i < existingOptions.length; i++) {
                existingOptions[i].remove();
            }
        }

        // Ensure we have exactly 2 options with True/False values
        const options = optionsContainer.querySelectorAll('.option-item');
        if (options.length >= 1) {
            const trueOption = options[0];
            trueOption.querySelector('.option-text-en').value = 'True';
            trueOption.querySelector('.option-text-ar').value = 'صحيح';
            trueOption.querySelector('.option-text-en').readOnly = true;
            trueOption.querySelector('.option-text-ar').readOnly = true;
        }
        
        if (options.length >= 2) {
            const falseOption = options[1];
            falseOption.querySelector('.option-text-en').value = 'False';
            falseOption.querySelector('.option-text-ar').value = 'خطأ';
            falseOption.querySelector('.option-text-en').readOnly = true;
            falseOption.querySelector('.option-text-ar').readOnly = true;
        }

        // Disable add/remove buttons for true/false
        const addBtn = questionCard.querySelector('.add-option');
        const removeButtons = questionCard.querySelectorAll('.remove-option');
        
        if (addBtn) addBtn.style.display = 'none';
        removeButtons.forEach(btn => btn.style.display = 'none');
    }

    handleSingleChoiceSelection(radioInput) {
        const questionCard = radioInput.closest('.question-card');
        const allRadios = questionCard.querySelectorAll('input[type="radio"][name*="[is_correct]"]');
        
        // Ensure only one radio is selected
        allRadios.forEach(radio => {
            if (radio !== radioInput) {
                radio.checked = false;
            }
        });
    }

    validateForm(form) {
        let isValid = true;
        const errors = [];

        // Validate basic form fields
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
            }
        });

        // Validate questions
        const questions = form.querySelectorAll('.question-card');
        questions.forEach((question, index) => {
            const questionType = question.querySelector('.question-type').value;
            const options = question.querySelectorAll('.option-item');
            const correctAnswers = question.querySelectorAll('.is-correct:checked');

            // Validate minimum options
            if (options.length < 2) {
                errors.push(`Question ${index + 1}: Must have at least 2 options`);
                isValid = false;
            }

            // Validate correct answers based on question type
            switch (questionType) {
                case 'single_choice':
                case 'true_false':
                    if (correctAnswers.length !== 1) {
                        errors.push(`Question ${index + 1}: Must have exactly one correct answer`);
                        isValid = false;
                    }
                    break;
                case 'multiple_choice':
                    if (correctAnswers.length < 1) {
                        errors.push(`Question ${index + 1}: Must have at least one correct answer`);
                        isValid = false;
                    }
                    break;
            }

            // Validate option texts
            options.forEach((option, optionIndex) => {
                const enText = option.querySelector('.option-text-en');
                const arText = option.querySelector('.option-text-ar');
                
                if (!enText.value.trim() || !arText.value.trim()) {
                    errors.push(`Question ${index + 1}, Option ${optionIndex + 1}: Both English and Arabic text required`);
                    isValid = false;
                }
            });
        });

        // Display errors if any
        if (!isValid) {
            this.showValidationErrors(errors);
        }

        return isValid;
    }

    showValidationErrors(errors) {
        // Remove existing error alerts
        const existingAlerts = document.querySelectorAll('.validation-alert');
        existingAlerts.forEach(alert => alert.remove());

        if (errors.length > 0) {
            const alertHtml = `
                <div class="alert alert-danger alert-dismissible fade show validation-alert" role="alert">
                    <h6><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</h6>
                    <ul class="mb-0">
                        ${errors.map(error => `<li>${error}</li>`).join('')}
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;
            
            const form = document.getElementById('examForm');
            form.insertAdjacentHTML('afterbegin', alertHtml);
            
            // Scroll to top to show errors
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    // Utility function to reindex all form elements
    reindexAllElements() {
        const questions = document.querySelectorAll('.question-card');
        questions.forEach((question, questionIndex) => {
            // Update question number
            const questionNumber = question.querySelector('.question-number');
            if (questionNumber) {
                questionNumber.textContent = questionIndex + 1;
            }

            // Update all name attributes in the question
            question.querySelectorAll('[name]').forEach(element => {
                const currentName = element.name;
                const newName = currentName.replace(/questions\[\d+\]/, `questions[${questionIndex}]`);
                element.name = newName;
            });

            // Update options within this question
            const options = question.querySelectorAll('.option-item');
            options.forEach((option, optionIndex) => {
                option.querySelectorAll('[name]').forEach(element => {
                    const currentName = element.name;
                    const newName = currentName.replace(
                        /questions\[\d+\]\[answers\]\[\d+\]/,
                        `questions[${questionIndex}][answers][${optionIndex}]`
                    );
                    element.name = newName;
                });
            });
        });
    }

    // Auto-save functionality (optional)
    enableAutoSave() {
        let autoSaveTimeout;
        const form = document.getElementById('examForm');
        
        form.addEventListener('input', () => {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                this.saveFormData();
            }, 3000); // Auto-save after 3 seconds of inactivity
        });
    }

    saveFormData() {
        const formData = new FormData(document.getElementById('examForm'));
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        
        localStorage.setItem('exam_form_data', JSON.stringify(data));
        
        // Show save indicator
        this.showSaveIndicator();
    }

    showSaveIndicator() {
        const indicator = document.createElement('div');
        indicator.className = 'alert alert-info position-fixed';
        indicator.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 200px;';
        indicator.innerHTML = '<i class="fas fa-save"></i> Draft saved automatically';
        
        document.body.appendChild(indicator);
        
        setTimeout(() => {
            indicator.remove();
        }, 2000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new ExamFormHelper();
});