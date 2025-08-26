class CertificateSelector {
    constructor() {
        this.loadingOverlay = document.getElementById('loadingOverlay');
        this.translations = window.translations || {};
        this.selectedCard = null;
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.initializeAnimations();
        this.setupAccessibility();
    }

    bindEvents() {
        // Handle card clicks
        document.addEventListener('click', (e) => {
            const card = e.target.closest('.certificate-card');
            if (card) {
                this.handleCardClick(card, e);
            }
        });

        // Handle keyboard navigation
        document.addEventListener('keydown', (e) => {
            const card = e.target.closest('.certificate-card');
            if (card && (e.key === 'Enter' || e.key === ' ')) {
                e.preventDefault();
                this.handleCardClick(card, e);
            }
        });

        // Handle form submissions
        document.addEventListener('submit', (e) => {
            if (e.target.classList.contains('certificate-form')) {
                this.handleFormSubmit(e);
            }
        });

        // Handle loading states
        window.addEventListener('beforeunload', () => {
            this.hideLoading();
        });
    }

    handleCardClick(card, event) {
        // Prevent double clicks
        if (card.classList.contains('processing')) {
            return;
        }

        // Visual feedback
        this.selectCard(card);

        // If clicked on button, let form submit naturally
        if (event.target.closest('.select-btn')) {
            return;
        }

        // Otherwise, trigger form submission
        const form = card.closest('.certificate-form');
        if (form) {
            // Add visual feedback
            this.showCardProcessing(card);
            
            // Submit after brief delay for UX
            setTimeout(() => {
                form.submit();
            }, 300);
        }
    }

    selectCard(card) {
        // Remove selection from other cards
        document.querySelectorAll('.certificate-card').forEach(c => {
            c.classList.remove('selected');
            c.setAttribute('aria-pressed', 'false');
        });

        // Select current card
        card.classList.add('selected');
        card.setAttribute('aria-pressed', 'true');
        this.selectedCard = card;

        // Update button text temporarily
        const btn = card.querySelector('.select-btn span');
        if (btn) {
            const originalText = btn.textContent;
            btn.textContent = this.translations.selectCertificate || 'Selected!';
            
            setTimeout(() => {
                if (btn.textContent === (this.translations.selectCertificate || 'Selected!')) {
                    btn.textContent = originalText;
                }
            }, 1500);
        }
    }

    showCardProcessing(card) {
        card.classList.add('processing');
        
        const btn = card.querySelector('.select-btn');
        if (btn) {
            const span = btn.querySelector('span');
            const icon = btn.querySelector('i');
            
            if (span) span.textContent = this.translations.processing || 'Processing...';
            if (icon) {
                icon.className = 'fas fa-spinner fa-spin';
            }
            
            btn.disabled = true;
        }
    }

    handleFormSubmit(event) {
        const form = event.target;
        const card = form.querySelector('.certificate-card');
        
        if (card && !card.classList.contains('processing')) {
            this.showCardProcessing(card);
            this.showLoading();
        }
    }

    showLoading() {
        if (this.loadingOverlay) {
            this.loadingOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    hideLoading() {
        if (this.loadingOverlay) {
            this.loadingOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    initializeAnimations() {
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);

        // Observe all animatable elements
        document.querySelectorAll('.certificate-card, .section-header').forEach(el => {
            observer.observe(el);
        });
    }

    setupAccessibility() {
        // Add ARIA labels to cards
        document.querySelectorAll('.certificate-card').forEach((card, index) => {
            const name = card.querySelector('.certificate-name')?.textContent || '';
            const code = card.querySelector('.certificate-code')?.textContent || '';
            
            card.setAttribute('role', 'button');
            card.setAttribute('aria-label', `Select ${name} certification program ${code}`);
            card.setAttribute('aria-pressed', 'false');
            card.setAttribute('tabindex', '0');
        });

        // Handle focus management
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                this.handleTabNavigation(e);
            }
        });
    }

    handleTabNavigation(event) {
        const cards = document.querySelectorAll('.certificate-card[tabindex="0"]');
        const currentIndex = Array.from(cards).indexOf(document.activeElement);
        
        if (currentIndex === -1) return;

        let nextIndex;
        
        if (event.shiftKey) {
            // Shift + Tab (backward)
            nextIndex = currentIndex > 0 ? currentIndex - 1 : cards.length - 1;
        } else {
            // Tab (forward)
            nextIndex = currentIndex < cards.length - 1 ? currentIndex + 1 : 0;
        }

        if (nextIndex !== currentIndex) {
            event.preventDefault();
            cards[nextIndex].focus();
        }
    }

    // Public methods for external use
    static selectCertificate(cardElement) {
        const instance = window.certificateSelector;
        if (instance && cardElement) {
            instance.handleCardClick(cardElement, { target: cardElement });
        }
    }

    static showLoading() {
        const instance = window.certificateSelector;
        if (instance) instance.showLoading();
    }

    static hideLoading() {
        const instance = window.certificateSelector;
        if (instance) instance.hideLoading();
    }
}

// Utility functions for global use
function selectCertificate(cardElement) {
    CertificateSelector.selectCertificate(cardElement);
}

function showLoading() {
    CertificateSelector.showLoading();
}

function hideLoading() {
    CertificateSelector.hideLoading();
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.certificateSelector = new CertificateSelector();
    
    // Add custom CSS animations
    const style = document.createElement('style');
    style.textContent = `
        .certificate-card.selected {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-2xl);
            border-color: var(--card-accent);
        }
        
        .certificate-card.processing {
            pointer-events: none;
            opacity: 0.7;
        }
        
        .certificate-card.animate-in {
            animation: slideInUp 0.6s ease-out forwards;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
});

// Handle page errors gracefully
window.addEventListener('error', (e) => {
    console.error('Certificate selection error:', e.error);
    hideLoading();
    
    // Show user-friendly error message
    // const errorMessage = document.createElement('div');
    // errorMessage.className = 'alert alert-danger';
    // errorMessage.style.cssText = `
    //     position: fixed;
    //     top: 20px;
    //     right: 20px;
    //     z-index: 10000;
    //     max-width: 400px;
    //     padding: 1rem;
    //     background: #fee;
    //     border: 1px solid #fcc;
    //     border-radius: 0.5rem;
    //     color: #c33;
    // `;
    // errorMessage.textContent = window.translations?.error || 'An error occurred. Please refresh the page and try again.';
    
    document.body.appendChild(errorMessage);
    
    setTimeout(() => {
        errorMessage.remove();
    }, 5000);
});
