// Global variables
let currentView = 'cards';
let searchTimeout = null;

// Initialize the dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    initializeSearch();
    initializeFilters();
    autoHideAlerts();
    loadQuestionData();
});

/**
 * Initialize all event listeners
 */
function initializeEventListeners() {
    // Search input
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(handleSearch, 300));
    }

    // Filter selects
    const typeFilter = document.getElementById('typeFilter');
    const pointsFilter = document.getElementById('pointsFilter');
    
    if (typeFilter) {
        typeFilter.addEventListener('change', applyFilters);
    }
    
    if (pointsFilter) {
        pointsFilter.addEventListener('change', applyFilters);
    }

    // Escape key to close any modals (if added later)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Handle any open modals
        }
    });

    // Handle keyboard navigation
    document.addEventListener('keydown', handleKeyboardNavigation);
}

/**
 * Initialize search functionality
 */
function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;

    // Add search icon click functionality
    const searchIcon = document.querySelector('.search-icon');
    if (searchIcon) {
        searchIcon.addEventListener('click', function() {
            searchInput.focus();
        });
    }

    // Add clear search functionality (if input has value)
    searchInput.addEventListener('input', function() {
        if (this.value === '') {
            applyFilters();
        }
    });
}

/**
 * Initialize filter functionality
 */
function initializeFilters() {
    console.log('Filters initialized');
}

/**
 * Auto-hide alerts after specified time
 */
function autoHideAlerts() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (alert.style.display !== 'none') {
                hideAlert(alert.id);
            }
        }, 5000);
    });
}

/**
 * Debounce function for search input
 * @param {Function} func - Function to debounce
 * @param {number} wait - Wait time in milliseconds
 * @returns {Function} Debounced function
 */
function debounce(func, wait) {
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(searchTimeout);
            func(...args);
        };
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(later, wait);
    };
}

/**
 * Handle search input
 * @param {Event} e - Input event
 */
function handleSearch(e) {
    const searchTerm = e.target.value.toLowerCase();
    applyFilters();
    
    // Add visual feedback
    if (searchTerm.length > 0) {
        e.target.style.borderColor = 'var(--primary)';
        e.target.style.backgroundColor = 'var(--white)';
    } else {
        e.target.style.borderColor = 'var(--gray-200)';
        e.target.style.backgroundColor = 'var(--gray-50)';
    }
}

/**
 * Apply all filters (search, type, points)
 */
function applyFilters() {
    const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const typeFilter = document.getElementById('typeFilter')?.value || '';
    const pointsFilter = document.getElementById('pointsFilter')?.value || '';

    const questionCards = document.querySelectorAll('.question-card');
    let visibleCount = 0;

    questionCards.forEach(card => {
        const type = card.getAttribute('data-type');
        const points = card.getAttribute('data-points');
        const questionText = card.querySelector('.text-primary')?.textContent.toLowerCase() || '';
        const questionTextAr = card.querySelector('.text-secondary')?.textContent.toLowerCase() || '';
        
        // Get answer options text for search
        const answerOptions = Array.from(card.querySelectorAll('.option-text, .option-text-ar'))
            .map(el => el.textContent.toLowerCase()).join(' ');

        const typeMatch = !typeFilter || type === typeFilter;
        const pointsMatch = !pointsFilter || points === pointsFilter;
        const searchMatch = !searchTerm || 
            questionText.includes(searchTerm) || 
            questionTextAr.includes(searchTerm) ||
            answerOptions.includes(searchTerm);

        if (typeMatch && pointsMatch && searchMatch) {
            showCard(card);
            visibleCount++;
        } else {
            hideCard(card);
        }
    });

    toggleEmptyState(visibleCount === 0);
}

/**
 * Show question card with animation
 * @param {HTMLElement} card - Card element to show
 */
function showCard(card) {
    card.style.display = '';
    card.style.animation = 'fadeIn 0.3s ease-out';
}

/**
 * Hide question card
 * @param {HTMLElement} card - Card element to hide
 */
function hideCard(card) {
    card.style.display = 'none';
}

/**
 * Toggle empty state visibility
 * @param {boolean} show - Whether to show empty state
 */
function toggleEmptyState(show) {
    const emptyFilterState = document.getElementById('emptyFilterState');
    const noQuestionsState = document.getElementById('noQuestionsState');
    
    if (emptyFilterState) {
        if (show) {
            emptyFilterState.style.display = 'block';
            emptyFilterState.style.animation = 'fadeIn 0.5s ease-out';
        } else {
            emptyFilterState.style.display = 'none';
        }
    }
    
    // Hide no questions state if we're filtering
    if (noQuestionsState && show) {
        noQuestionsState.style.display = 'none';
    }
}

/**
 * Clear all filters
 */
function clearFilters() {
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const pointsFilter = document.getElementById('pointsFilter');

    if (searchInput) searchInput.value = '';
    if (typeFilter) typeFilter.value = '';
    if (pointsFilter) pointsFilter.value = '';

    applyFilters();

    // Reset search input styling
    if (searchInput) {
        searchInput.style.borderColor = 'var(--gray-200)';
        searchInput.style.backgroundColor = 'var(--gray-50)';
    }

    // Show no questions state if no questions exist
    const questionCards = document.querySelectorAll('.question-card');
    if (questionCards.length === 0) {
        const noQuestionsState = document.getElementById('noQuestionsState');
        if (noQuestionsState) {
            noQuestionsState.style.display = 'block';
        }
    }
}

/**
 * Toggle between cards and list view
 * @param {string} view - View type ('cards' or 'list')
 */
function toggleView(view) {
    currentView = view;
    const viewBtns = document.querySelectorAll('.view-btn');
    const questionsContainer = document.getElementById('questionsContainer');
    
    // Update active button
    viewBtns.forEach(btn => btn.classList.remove('active'));
    const activeBtn = document.querySelector(`[onclick="toggleView('${view}')"]`);
    if (activeBtn) {
        activeBtn.classList.add('active');
    }

    // Update container class
    if (questionsContainer) {
        if (view === 'list') {
            questionsContainer.classList.add('list-view');
        } else {
            questionsContainer.classList.remove('list-view');
        }
    }

    // Store preference in localStorage if available
    try {
        localStorage.setItem('questionViewPreference', view);
    } catch (e) {
        // Ignore localStorage errors
    }
}

/**
 * Show alert message
 * @param {string} type - Alert type ('success' or 'error')
 * @param {string} message - Alert message
 */
function showAlert(type, message) {
    const alertId = type + 'Alert';
    const alert = document.getElementById(alertId);
    
    if (alert) {
        const messageSpan = alert.querySelector('span');
        if (messageSpan) {
            messageSpan.textContent = message;
        }
        alert.style.display = 'flex';
        alert.style.animation = 'slideIn 0.5s ease-out';
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            hideAlert(alertId);
        }, 5000);
    }
}

/**
 * Hide alert message
 * @param {string} alertId - Alert element ID
 */
function hideAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
            alert.style.display = 'none';
            alert.style.animation = '';
        }, 300);
    }
}

/**
 * Confirm deletion with user
 * @param {string} questionText - Text of question to delete
 * @returns {boolean} User confirmation
 */
function confirmDelete(questionText) {
    const message = `Are you sure you want to delete this question: "${questionText}"?\n\nThis action cannot be undone.`;
    
    if (confirm(message)) {
        // Add loading state to delete button
        const deleteButton = event.target.closest('button');
        if (deleteButton) {
            deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            deleteButton.disabled = true;
        }
        return true;
    }
    return false;
}

/**
 * Load and initialize question data
 */
function loadQuestionData() {
    // Load view preference
    try {
        const savedView = localStorage.getItem('questionViewPreference');
        if (savedView && (savedView === 'cards' || savedView === 'list')) {
            toggleView(savedView);
        }
    } catch (e) {
        // Ignore localStorage errors
    }

    // Initialize tooltips if needed
    initializeTooltips();
    
    // Initialize question interactions
    initializeQuestionInteractions();
}

/**
 * Initialize tooltips for better UX
 */
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[title]');
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

/**
 * Show custom tooltip
 * @param {Event} e - Mouse enter event
 */
function showTooltip(e) {
    const element = e.target;
    const title = element.getAttribute('title');
    
    if (title) {
        element.setAttribute('data-original-title', title);
        element.removeAttribute('title');
        
        const tooltip = document.createElement('div');
        tooltip.className = 'custom-tooltip';
        tooltip.textContent = title;
        tooltip.style.cssText = `
            position: absolute;
            background: var(--dark);
            color: var(--white);
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius);
            font-size: 0.8125rem;
            white-space: nowrap;
            z-index: 1000;
            box-shadow: var(--shadow-lg);
            pointer-events: none;
        `;
        
        document.body.appendChild(tooltip);
        
        const rect = element.getBoundingClientRect();
        tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = (rect.top - tooltip.offsetHeight - 8) + 'px';
        
        element._tooltip = tooltip;
    }
}

/**
 * Hide custom tooltip
 * @param {Event} e - Mouse leave event
 */
function hideTooltip(e) {
    const element = e.target;
    const originalTitle = element.getAttribute('data-original-title');
    
    if (originalTitle) {
        element.setAttribute('title', originalTitle);
        element.removeAttribute('data-original-title');
    }
    
    if (element._tooltip) {
        document.body.removeChild(element._tooltip);
        element._tooltip = null;
    }
}

/**
 * Initialize question card interactions
 */
function initializeQuestionInteractions() {
    const questionCards = document.querySelectorAll('.question-card');
    
    questionCards.forEach(card => {
        // Add hover effects for better interactivity
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            if (currentView !== 'list') {
                this.style.transform = '';
            }
        });
        
        // Add keyboard navigation for accessibility
        const actionButtons = card.querySelectorAll('.action-btn');
        actionButtons.forEach(button => {
            button.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });
    });
}

/**
 * Handle keyboard navigation
 */
function handleKeyboardNavigation(e) {
    // Handle Enter key on search
    if (e.key === 'Enter' && e.target.id === 'searchInput') {
        e.preventDefault();
        applyFilters();
    }
    
    // Handle Ctrl+F for search focus
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }
}

/**
 * Animate statistics counters in header
 */
function animateStats() {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    statNumbers.forEach(stat => {
        const targetValue = parseInt(stat.textContent);
        stat.textContent = '0';
        
        const increment = Math.ceil(targetValue / 20);
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= targetValue) {
                current = targetValue;
                clearInterval(timer);
            }
            stat.textContent = current;
        }, 50);
    });
}

/**
 * Highlight search terms in question text
 * @param {string} searchTerm - Term to highlight
 */
function highlightSearchTerms(searchTerm) {
    if (!searchTerm) {
        removeHighlights();
        return;
    }
    
    const questionCards = document.querySelectorAll('.question-card:not([style*="display: none"])');
    
    questionCards.forEach(card => {
        const textElements = card.querySelectorAll('.text-primary, .text-secondary, .option-text, .option-text-ar');
        
        textElements.forEach(element => {
            const originalText = element.getAttribute('data-original-text') || element.textContent;
            element.setAttribute('data-original-text', originalText);
            
            const regex = new RegExp(`(${escapeRegex(searchTerm)})`, 'gi');
            const highlightedText = originalText.replace(regex, '<mark class="search-highlight">$1</mark>');
            element.innerHTML = highlightedText;
        });
    });
}

/**
 * Remove search term highlights
 */
function removeHighlights() {
    const highlightedElements = document.querySelectorAll('[data-original-text]');
    
    highlightedElements.forEach(element => {
        const originalText = element.getAttribute('data-original-text');
        if (originalText) {
            element.textContent = originalText;
            element.removeAttribute('data-original-text');
        }
    });
}

/**
 * Escape regex special characters
 * @param {string} string - String to escape
 * @returns {string} Escaped string
 */
function escapeRegex(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\/**
 * Initialize question card interactions
 */
function initializeQuestionInteractions() {
    const questionCards = document.querySelectorAll('.question-card');
    
    questionCards.forEach(card => {
        // Add click to expand/collapse functionality (if needed)
        const questionContent = card.querySelector('.question-content');
        if (questionContent) {');
}

/**
 * Export questions data (if needed for reporting)
 */
function exportQuestions() {
    const questions = [];
    const questionCards = document.querySelectorAll('.question-card');
    
    questionCards.forEach((card, index) => {
        const questionText = card.querySelector('.text-primary')?.textContent || '';
        const questionTextAr = card.querySelector('.text-secondary')?.textContent || '';
        const type = card.getAttribute('data-type');
        const points = card.getAttribute('data-points');
        const answers = [];
        
        card.querySelectorAll('.answer-option').forEach((option, answerIndex) => {
            const answerText = option.querySelector('.option-text')?.textContent || '';
            const answerTextAr = option.querySelector('.option-text-ar')?.textContent || '';
            const isCorrect = option.classList.contains('correct');
            
            answers.push({
                letter: String.fromCharCode(65 + answerIndex),
                text: answerText,
                textAr: answerTextAr,
                correct: isCorrect
            });
        });
        
        questions.push({
            number: index + 1,
            question: questionText,
            questionAr: questionTextAr,
            type: type,
            points: parseInt(points),
            answers: answers
        });
    });
    
    return questions;
}

/**
 * Print questions for offline use
 */
function printQuestions() {
    // Hide non-printable elements
    const nonPrintElements = document.querySelectorAll('.header-actions, .controls-section, .question-actions, .alert');
    nonPrintElements.forEach(el => el.style.display = 'none');
    
    // Print
    window.print();
    
    // Restore elements
    setTimeout(() => {
        nonPrintElements.forEach(el => el.style.display = '');
    }, 1000);
}

/**
 * Get question statistics
 */
function getQuestionStats() {
    const questions = document.querySelectorAll('.question-card');
    const singleChoice = document.querySelectorAll('.question-card[data-type="single_choice"]');
    const multipleChoice = document.querySelectorAll('.question-card[data-type="multiple_choice"]');
    
    let totalPoints = 0;
    let totalAnswers = 0;
    
    questions.forEach(card => {
        const points = parseInt(card.getAttribute('data-points') || '0');
        const answers = card.querySelectorAll('.answer-option').length;
        
        totalPoints += points;
        totalAnswers += answers;
    });
    
    return {
        total: questions.length,
        singleChoice: singleChoice.length,
        multipleChoice: multipleChoice.length,
        totalPoints: totalPoints,
        totalAnswers: totalAnswers,
        averageAnswersPerQuestion: questions.length > 0 ? (totalAnswers / questions.length).toFixed(1) : 0
    };
}

/**
 * Show question statistics modal (if implemented)
 */
function showStats() {
    const stats = getQuestionStats();
    
    const message = `Question Bank Statistics:
    
Total Questions: ${stats.total}
Single Choice: ${stats.singleChoice}
Multiple Choice: ${stats.multipleChoice}
Total Points: ${stats.totalPoints}
Total Answer Options: ${stats.totalAnswers}
Average Options per Question: ${stats.averageAnswersPerQuestion}`;

    alert(message);
}

/**
 * Refresh page data
 */
function refreshData() {
    // Show loading state
    const questionsContainer = document.getElementById('questionsContainer');
    if (questionsContainer) {
        questionsContainer.style.opacity = '0.5';
        questionsContainer.style.pointerEvents = 'none';
    }
    
    // Simulate data refresh
    setTimeout(() => {
        if (questionsContainer) {
            questionsContainer.style.opacity = '';
            questionsContainer.style.pointerEvents = '';
        }
        showAlert('success', 'Questions refreshed successfully!');
    }, 1000);
}

/**
 * Advanced search functionality
 * @param {string} query - Search query with potential filters
 */
function advancedSearch(query) {
    const parts = query.split(' ');
    let searchTerm = '';
    let typeFilter = '';
    let pointsFilter = '';
    
    parts.forEach(part => {
        if (part.startsWith('type:')) {
            typeFilter = part.split(':')[1];
        } else if (part.startsWith('points:')) {
            pointsFilter = part.split(':')[1];
        } else {
            searchTerm += part + ' ';
        }
    });
    
    // Update filters
    const searchInput = document.getElementById('searchInput');
    const typeSelect = document.getElementById('typeFilter');
    const pointsSelect = document.getElementById('pointsFilter');
    
    if (searchInput) searchInput.value = searchTerm.trim();
    if (typeSelect && typeFilter) typeSelect.value = typeFilter;
    if (pointsSelect && pointsFilter) pointsSelect.value = pointsFilter;
    
    applyFilters();
}

// Export functions for use in Blade template if needed
window.QuestionsManagement = {
    toggleView,
    clearFilters,
    showAlert,
    hideAlert,
    confirmDelete,
    printQuestions,
    exportQuestions,
    showStats,
    refreshData,
    advancedSearch
};

// Add slide out animation styles
const slideOutKeyframes = `
@keyframes slideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(-100%); opacity: 0; }
}

.search-highlight {
    background: rgba(99, 102, 241, 0.2);
    color: var(--primary-dark);
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-weight: 600;
}

.loading-state {
    opacity: 0.6;
    pointer-events: none;
}

.fade-in {
    animation: fadeIn 0.3s ease-out;
}

.scale-in {
    animation: scaleIn 0.2s ease-out;
}

@keyframes scaleIn {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
`;

// Inject keyframes into page
const style = document.createElement('style');
style.textContent = slideOutKeyframes;
document.head.appendChild(style);

// Enhanced search with highlighting
const originalHandleSearch = handleSearch;
handleSearch = function(e) {
    originalHandleSearch(e);
    
    const searchTerm = e.target.value.toLowerCase();
    if (searchTerm.length > 2) {
        highlightSearchTerms(searchTerm);
    } else {
        removeHighlights();
    }
};

console.log('Questions Management Dashboard initialized successfully!');