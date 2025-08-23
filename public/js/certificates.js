class CertificateSelector {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.setupKeyboardSupport();
        this.handlePageShow();
    }

    selectCertificate(cardElement) {
        const form = cardElement.closest('.certificate-form');
        if (form) {
            // Show loading overlay
            this.showLoadingOverlay();
            
            // Add visual feedback to selected card
            cardElement.style.opacity = '0.7';
            cardElement.style.pointerEvents = 'none';
            
            // Submit form
            form.submit();
        }
    }

    showLoadingOverlay() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'flex';
        }
    }

    hideLoadingOverlay() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    }

    bindEvents() {
        // Handle form submission with loading states
        const forms = document.querySelectorAll('.certificate-form');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                const submitBtn = form.querySelector('.select-btn');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>' + this.getLoadingText();
                    submitBtn.disabled = true;
                }
            });
        });

        // Handle card clicks
        const cards = document.querySelectorAll('.certificate-card');
        cards.forEach(card => {
            card.addEventListener('click', (e) => {
                // Prevent double submission if button is clicked
                if (e.target.tagName === 'BUTTON') {
                    return;
                }
                this.selectCertificate(card);
            });
        });
    }

    setupKeyboardSupport() {
        const cards = document.querySelectorAll('.certificate-card');
        cards.forEach(card => {
            card.setAttribute('tabindex', '0');
            card.setAttribute('role', 'button');
            card.setAttribute('aria-label', this.getCardAriaLabel(card));
            
            card.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.selectCertificate(card);
                }
            });

            // Add focus styles
            card.addEventListener('focus', () => {
                card.style.outline = '2px solid var(--card-color)';
                card.style.outlineOffset = '2px';
            });

            card.addEventListener('blur', () => {
                card.style.outline = '';
                card.style.outlineOffset = '';
            });
        });
    }

    getCardAriaLabel(card) {
        const nameElement = card.querySelector('.certificate-name');
        const codeElement = card.querySelector('.certificate-code');
        const name = nameElement ? nameElement.textContent.trim() : '';
        const code = codeElement ? codeElement.textContent.trim() : '';
        return `Select ${name} (${code}) certificate`;
    }

    getLoadingText() {
        // This should be replaced with actual translation
        return 'Loading...';
    }

    handlePageShow() {
        // Hide loading overlay if page is refreshed/back button
        window.addEventListener('pageshow', () => {
            this.hideLoadingOverlay();
        });
    }

    // Utility method to equalize card heights (if needed)
    equalizeCardHeights() {
        const cards = document.querySelectorAll('.certificate-card');
        if (cards.length === 0) return;

        // Reset heights
        cards.forEach(card => {
            card.style.height = 'auto';
        });

        // Find max height
        let maxHeight = 0;
        cards.forEach(card => {
            const height = card.offsetHeight;
            if (height > maxHeight) {
                maxHeight = height;
            }
        });

        // Set all cards to max height
        cards.forEach(card => {
            card.style.height = maxHeight + 'px';
        });
    }

    // Handle responsive behavior
    handleResize() {
        // Re-equalize heights on window resize if needed
        clearTimeout(this.resizeTimeout);
        this.resizeTimeout = setTimeout(() => {
            this.equalizeCardHeights();
        }, 250);
    }
}

// Animation utilities
class CertificateAnimations {
    static fadeInCards() {
        const cards = document.querySelectorAll('.certificate-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    static addHoverEffects() {
        const cards = document.querySelectorAll('.certificate-card');
        cards.forEach(card => {
            const icon = card.querySelector('.certificate-icon i');
            
            card.addEventListener('mouseenter', () => {
                if (icon) {
                    icon.style.transform = 'scale(1.1) rotate(5deg)';
                    icon.style.transition = 'transform 0.3s ease';
                }
            });

            card.addEventListener('mouseleave', () => {
                if (icon) {
                    icon.style.transform = 'scale(1) rotate(0deg)';
                }
            });
        });
    }
}

// Error handling
class CertificateErrorHandler {
    static handleFormError(form, error) {
        console.error('Form submission error:', error);
        
        // Re-enable the submit button
        const submitBtn = form.querySelector('.select-btn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = submitBtn.getAttribute('data-original-text') || 'Start Learning';
        }

        // Hide loading overlay
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'none';
        }

        // Show error message
        this.showErrorMessage('Something went wrong. Please try again.');
    }

    static showErrorMessage(message) {
        // Create or update error toast
        let toast = document.getElementById('error-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'error-toast';
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #ef4444;
                color: white;
                padding: 1rem;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                z-index: 10000;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
            `;
            document.body.appendChild(toast);
        }

        toast.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i>${message}`;
        
        // Show toast
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
        }, 100);

        // Hide toast after 5 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
        }, 5000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const certificateSelector = new CertificateSelector();
    
    // Add animations
    CertificateAnimations.fadeInCards();
    CertificateAnimations.addHoverEffects();

    // Handle window resize
    window.addEventListener('resize', () => {
        certificateSelector.handleResize();
    });

    // Handle form errors
    document.querySelectorAll('.certificate-form').forEach(form => {
        form.addEventListener('error', (e) => {
            CertificateErrorHandler.handleFormError(form, e.detail);
        });
    });
});