class RegisterForm {
    constructor() {
        this.form = document.querySelector('.needs-validation');
        this.registerBtn = document.getElementById('registerBtn');
        this.emailInput = document.getElementById('email');
        this.emailFeedback = document.getElementById('email-feedback');
        this.passwordInput = document.getElementById('password');
        this.passwordConfirmationInput = document.getElementById('password_confirmation');
        this.phoneInput = document.getElementById('phone');
        this.usernameInput = document.getElementById('username');
        this.checkbox = document.getElementById('is_agree');
        this.emailCheckTimeout = null;
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.addEntranceAnimations();
        this.addParallaxEffect();
        this.addRippleEffect();
        this.autoFocusUsername();
    }

    bindEvents() {
        // Form submission
        this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        
        // Real-time validation
        this.bindInputValidation();
        
        // Password functionality
        this.bindPasswordToggle();
        this.bindPasswordValidation();
        
        // Input formatting
        this.bindPhoneFormatting();
        this.bindUsernameValidation();
        
        // Email checking
        this.bindEmailValidation();
        
        // Checkbox animation
        this.bindCheckboxAnimation();
    }

    handleFormSubmit(event) {
        if (!this.form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            this.focusFirstInvalidField();
        } else {
            this.showLoadingState();
        }
        
        this.form.classList.add('was-validated');
    }

    showLoadingState() {
        this.registerBtn.classList.add('loading');
        this.registerBtn.querySelector('.btn-text').innerHTML = 
            `<i class="fas fa-spinner fa-spin me-2"></i>${window.translations.creatingAccount}`;
    }

    focusFirstInvalidField() {
        const firstInvalidField = this.form.querySelector('.form-control-modern:invalid, .form-select-modern:invalid');
        if (firstInvalidField) {
            setTimeout(() => {
                firstInvalidField.focus();
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);
        }
    }

    bindInputValidation() {
        const inputs = document.querySelectorAll('.form-control-modern, .form-select-modern');
        
        inputs.forEach(input => {
            // Real-time validation feedback
            input.addEventListener('input', () => {
                if (input.checkValidity()) {
                    input.classList.remove('is-invalid');
                }
            });

            // Enhanced focus effects
            input.addEventListener('focus', () => {
                input.parentElement.style.transform = 'translateY(-2px)';
            });

            input.addEventListener('blur', () => {
                input.parentElement.style.transform = 'translateY(0)';
            });

            // Error handling
            input.addEventListener('invalid', () => {
                input.classList.add('is-invalid');
                setTimeout(() => {
                    input.focus();
                    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
            });
        });
    }

    bindPasswordToggle() {
        const passwordToggles = document.querySelectorAll('.password-toggle');
        
        passwordToggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                const targetId = toggle.getAttribute('data-target');
                const field = document.getElementById(targetId);
                
                if (field.type === 'password') {
                    field.type = 'text';
                    toggle.classList.remove('fa-eye');
                    toggle.classList.add('fa-eye-slash');
                } else {
                    field.type = 'password';
                    toggle.classList.remove('fa-eye-slash');
                    toggle.classList.add('fa-eye');
                }
            });
        });
    }

    bindPasswordValidation() {
        const validatePasswordMatch = () => {
            if (this.passwordConfirmationInput.value !== this.passwordInput.value) {
                this.passwordConfirmationInput.setCustomValidity(window.translations.passwordsMustMatch);
            } else {
                this.passwordConfirmationInput.setCustomValidity('');
            }
        };
        
        this.passwordInput.addEventListener('input', () => {
            validatePasswordMatch();
            this.updatePasswordStrength();
        });
        
        this.passwordConfirmationInput.addEventListener('input', validatePasswordMatch);
    }

    updatePasswordStrength() {
        const password = this.passwordInput.value;
        const strength = this.calculatePasswordStrength(password);
        
        // Remove existing strength indicator
        const existingIndicator = this.passwordInput.parentElement.parentElement.querySelector('.password-strength');
        if (existingIndicator) {
            existingIndicator.remove();
        }
        
        // Add strength indicator if password exists
        if (password.length > 0) {
            const strengthIndicator = document.createElement('div');
            strengthIndicator.className = 'password-strength';
            
            const strengthBar = document.createElement('div');
            strengthBar.className = 'strength-bar';
            strengthBar.style.cssText = `
                height: 100%;
                width: ${strength.percentage}%;
                background: ${strength.color};
                transition: all 0.3s ease;
                border-radius: 2px;
            `;
            
            strengthIndicator.appendChild(strengthBar);
            this.passwordInput.parentElement.parentElement.appendChild(strengthIndicator);
        }
    }

    calculatePasswordStrength(password) {
        let score = 0;
        
        // Length scoring
        if (password.length >= 8) score += 25;
        if (password.length >= 12) score += 25;
        
        // Character variety scoring
        if (/[a-z]/.test(password)) score += 12.5;
        if (/[A-Z]/.test(password)) score += 12.5;
        if (/[0-9]/.test(password)) score += 12.5;
        if (/[^a-zA-Z0-9]/.test(password)) score += 12.5;
        
        let color = '#ef4444'; // Red
        if (score >= 50) color = '#f59e0b'; // Orange
        if (score >= 75) color = '#10b981'; // Green
        
        return { percentage: Math.min(100, score), color };
    }

    bindPhoneFormatting() {
        if (this.phoneInput) {
            this.phoneInput.addEventListener('input', function() {
                // Remove all non-digit characters except + at the beginning
                let value = this.value.replace(/[^\d+]/g, '');
                
                // Ensure + is only at the beginning
                if (value.indexOf('+') > 0) {
                    value = value.replace(/\+/g, '');
                }
                
                this.value = value;
            });
        }
    }

    bindUsernameValidation() {
        if (this.usernameInput) {
            this.usernameInput.addEventListener('input', function() {
                // Allow only alphanumeric characters and underscores
                this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
            });
        }
    }

    bindEmailValidation() {
        if (this.emailInput && this.emailFeedback) {
            this.emailInput.addEventListener('input', () => {
                clearTimeout(this.emailCheckTimeout);
                const email = this.emailInput.value.trim();
                
                if (email.length > 0) {
                    // Debounce the request
                    this.emailCheckTimeout = setTimeout(() => {
                        this.checkEmailExists(email);
                    }, 500);
                } else {
                    this.clearEmailFeedback();
                }
            });
        }
    }

    async checkEmailExists(email) {
        // Show loading indicator
        this.showEmailFeedback('Checking email...', 'info');
        
        try {
            const response = await fetch(window.translations.emailCheckRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.translations.csrfToken
                },
                body: JSON.stringify({ email: email })
            });
            
            const data = await response.json();
            
            if (data.exists) {
                this.showEmailFeedback(data.message, 'error');
                this.emailInput.classList.add('is-invalid');
                this.emailInput.classList.remove('is-valid');
            } else {
                this.showEmailFeedback(data.message, 'success');
                this.emailInput.classList.add('is-valid');
                this.emailInput.classList.remove('is-invalid');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showEmailFeedback('Error checking email', 'error');
        }
    }

    showEmailFeedback(message, type) {
        if (this.emailFeedback) {
            this.emailFeedback.textContent = message;
            this.emailFeedback.className = `feedback feedback-${type}`;
            this.emailFeedback.style.display = 'block';
        }
    }

    clearEmailFeedback() {
        if (this.emailFeedback) {
            this.emailFeedback.style.display = 'none';
            this.emailInput.classList.remove('is-invalid', 'is-valid');
        }
    }

    bindCheckboxAnimation() {
        if (this.checkbox) {
            this.checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                }
            });
        }
    }

    addRippleEffect() {
        this.registerBtn.addEventListener('click', (e) => {
            // Ripple effect
            const ripple = document.createElement('span');
            const rect = this.registerBtn.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                left: ${x}px;
                top: ${y}px;
                pointer-events: none;
            `;
            
            this.registerBtn.style.position = 'relative';
            this.registerBtn.style.overflow = 'hidden';
            this.registerBtn.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    }

    addEntranceAnimations() {
        const formElements = document.querySelectorAll('.input-group-modern, .form-check-modern, .btn-register, .signin-link');
        
        formElements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'all 0.6s ease';
            
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, 200 + (index * 80));
        });
    }

    addParallaxEffect() {
        document.addEventListener('mousemove', (e) => {
            const shapes = document.querySelectorAll('.shape');
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;
            
            shapes.forEach((shape, index) => {
                const speed = (index + 1) * 0.5;
                const x = (mouseX - 0.5) * speed * 20;
                const y = (mouseY - 0.5) * speed * 20;
                
                shape.style.transform = `translate(${x}px, ${y}px)`;
            });
        });
    }

    autoFocusUsername() {
        setTimeout(() => {
            if (this.usernameInput && !this.usernameInput.value) {
                this.usernameInput.focus();
            }
        }, 500);
    }
}

// Utility functions
const togglePassword = (fieldId) => {
    const field = document.getElementById(fieldId);
    const toggle = field.parentElement.querySelector('.password-toggle');
    
    if (field.type === 'password') {
        field.type = 'text';
        toggle.classList.remove('fa-eye');
        toggle.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        toggle.classList.remove('fa-eye-slash');
        toggle.classList.add('fa-eye');
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Add necessary CSS animations if not exists
    if (!document.querySelector('#register-animations')) {
        const style = document.createElement('style');
        style.id = 'register-animations';
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            .form-control-modern, .form-select-modern {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .form-control-modern:focus, .form-select-modern:focus {
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .alert-modern {
                transition: all 0.3s ease;
            }
            
            .feature-badge {
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }
            
            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Initialize the registration form
    new RegisterForm();
});

// Export for potential module use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RegisterForm;
}