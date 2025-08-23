// Global variables
let currentView = 'grid';
let allExams = [];
let searchTimeout = null;

// Initialize the dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    initializeSearch();
    initializeFilters();
    initializeDragAndDrop();
    autoHideAlerts();
    loadExamData();
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
    const statusFilter = document.getElementById('statusFilter');
    const certificateFilter = document.getElementById('certificateFilter');
    
    if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters);
    }
    
    if (certificateFilter) {
        certificateFilter.addEventListener('change', applyFilters);
    }

    // File input
    const fileInput = document.getElementById('excelFile');
    if (fileInput) {
        fileInput.addEventListener('change', handleFileSelect);
    }

    // Modal close on overlay click
    const modal = document.getElementById('importModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal || e.target.classList.contains('modal-overlay')) {
                closeImportModal();
            }
        });
    }

    // Escape key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('importModal');
            if (modal && modal.style.display !== 'none') {
                closeImportModal();
            }
        }
    });

    // Form submission
    const importForm = document.getElementById('importForm');
    if (importForm) {
        importForm.addEventListener('submit', handleImportSubmit);
    }
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
    // Already handled in initializeEventListeners
    console.log('Filters initialized');
}

/**
 * Initialize drag and drop functionality
 */
function initializeDragAndDrop() {
    const uploadArea = document.querySelector('.upload-area');
    if (!uploadArea) return;

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });

    // Handle dropped files
    uploadArea.addEventListener('drop', handleDrop, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        uploadArea.classList.add('dragover');
    }

    function unhighlight(e) {
        uploadArea.classList.remove('dragover');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            const fileInput = document.getElementById('excelFile');
            if (fileInput) {
                fileInput.files = files;
                handleFileSelect({ target: { files: files } });
            }
        }
    }
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
 * Apply all filters (search, status, certificate)
 */
function applyFilters() {
    const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const statusFilter = document.getElementById('statusFilter')?.value || '';
    const certificateFilter = document.getElementById('certificateFilter')?.value || '';

    const examCards = document.querySelectorAll('.exam-card:not(.loading-skeleton)');
    let visibleCount = 0;

    examCards.forEach(card => {
        const status = card.getAttribute('data-status');
        const certificate = card.getAttribute('data-certificate');
        const title = card.querySelector('.exam-title')?.textContent.toLowerCase() || '';
        const titleAr = card.querySelector('.exam-title-ar')?.textContent.toLowerCase() || '';

        const statusMatch = !statusFilter || status === statusFilter;
        const certificateMatch = !certificateFilter || certificate === certificateFilter;
        const searchMatch = !searchTerm || 
            title.includes(searchTerm) || 
            titleAr.includes(searchTerm);

        if (statusMatch && certificateMatch && searchMatch) {
            showCard(card);
            visibleCount++;
        } else {
            hideCard(card);
        }
    });

    toggleEmptyState(visibleCount === 0);
}

/**
 * Show exam card with animation
 * @param {HTMLElement} card - Card element to show
 */
function showCard(card) {
    card.style.display = '';
    card.style.animation = 'fadeIn 0.3s ease-out';
}

/**
 * Hide exam card
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
    const emptyState = document.getElementById('emptyState');
    const noExamsState = document.getElementById('noExamsState');
    
    if (emptyState) {
        if (show) {
            emptyState.style.display = 'block';
            emptyState.style.animation = 'fadeIn 0.5s ease-out';
        } else {
            emptyState.style.display = 'none';
        }
    }
    
    // Hide no exams state if we're filtering
    if (noExamsState && show) {
        noExamsState.style.display = 'none';
    }
}

/**
 * Clear all filters
 */
function clearFilters() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const certificateFilter = document.getElementById('certificateFilter');

    if (searchInput) searchInput.value = '';
    if (statusFilter) statusFilter.value = '';
    if (certificateFilter) certificateFilter.value = '';

    applyFilters();

    // Reset search input styling
    if (searchInput) {
        searchInput.style.borderColor = 'var(--gray-200)';
        searchInput.style.backgroundColor = 'var(--gray-50)';
    }
}

/**
 * Toggle between grid and list view
 * @param {string} view - View type ('grid' or 'list')
 */
function toggleView(view) {
    currentView = view;
    const viewBtns = document.querySelectorAll('.view-btn');
    const examsContainer = document.getElementById('examsContainer');
    
    // Update active button
    viewBtns.forEach(btn => btn.classList.remove('active'));
    const activeBtn = document.querySelector(`[onclick="toggleView('${view}')"]`);
    if (activeBtn) {
        activeBtn.classList.add('active');
    }

    // Update container class
    if (examsContainer) {
        if (view === 'list') {
            examsContainer.classList.add('list-view');
        } else {
            examsContainer.classList.remove('list-view');
        }
    }

    // Store preference in localStorage if available
    try {
        localStorage.setItem('examViewPreference', view);
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
 * Open import modal
 */
function openImportModal() {
    const modal = document.getElementById('importModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Reset modal state
        resetImportModal();
        
        // Focus first interactive element
        const firstButton = modal.querySelector('button, input, select');
        if (firstButton) {
            setTimeout(() => firstButton.focus(), 100);
        }
    }
}

/**
 * Close import modal
 */
function closeImportModal() {
    const modal = document.getElementById('importModal');
    if (modal) {
        modal.style.animation = 'modalFadeOut 0.3s ease-out';
        setTimeout(() => {
            modal.style.display = 'none';
            modal.style.animation = '';
            document.body.style.overflow = 'auto';
            resetImportModal();
        }, 300);
    }
}

/**
 * Reset import modal to initial state
 */
function resetImportModal() {
    const fileInput = document.getElementById('excelFile');
    const importBtn = document.getElementById('importBtn');
    const uploadArea = document.querySelector('.upload-area');

    if (fileInput) {
        fileInput.value = '';
    }
    
    if (importBtn) {
        importBtn.disabled = true;
        importBtn.classList.remove('loading');
        importBtn.innerHTML = '<i class="fas fa-upload"></i> Import Exam';
    }
    
    if (uploadArea) {
        uploadArea.classList.remove('dragover');
        uploadArea.innerHTML = `
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Click to select file or drag and drop</p>
            <small>Maximum file size: 10MB. Formats: .xlsx, .xls, .csv</small>
        `;
    }

    // Reset steps
    const steps = document.querySelectorAll('.step');
    steps.forEach((step, index) => {
        if (index === 0) {
            step.classList.add('active');
        } else {
            step.classList.remove('active');
        }
    });
}

/**
 * Handle file selection
 * @param {Event} event - File input change event
 */
function handleFileSelect(event) {
    const file = event.target.files[0];
    const importBtn = document.getElementById('importBtn');
    const uploadArea = document.querySelector('.upload-area');

    if (!file) {
        resetImportModal();
        return;
    }

    // Validate file type
    const allowedTypes = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
        'application/vnd.ms-excel', // .xls
        'text/csv' // .csv
    ];
    
    if (!allowedTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls|csv)$/i)) {
        showAlert('error', 'Invalid file type. Please select an Excel (.xlsx, .xls) or CSV file.');
        resetImportModal();
        return;
    }

    // Validate file size (10MB limit)
    const maxSize = 10 * 1024 * 1024; // 10MB in bytes
    if (file.size > maxSize) {
        showAlert('error', 'File size exceeds 10MB limit. Please choose a smaller file.');
        resetImportModal();
        return;
    }

    // Update UI with file info
    const fileSize = (file.size / 1024 / 1024).toFixed(2);
    const fileName = file.name;
    
    if (uploadArea) {
        uploadArea.innerHTML = `
            <i class="fas fa-file-excel" style="color: var(--success); font-size: 2rem; margin-bottom: 1rem;"></i>
            <p><strong>${fileName}</strong></p>
            <small>Size: ${fileSize} MB</small>
            <div style="margin-top: 1rem;">
                <div style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 0.5rem; border-radius: var(--radius); display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check-circle"></i>
                    <span>File ready for import</span>
                </div>
            </div>
        `;
    }
    
    if (importBtn) {
        importBtn.disabled = false;
    }

    // Activate step 3
    const steps = document.querySelectorAll('.step');
    steps.forEach((step, index) => {
        if (index === 2) {
            step.classList.add('active');
        }
    });

    showAlert('success', 'File selected successfully. Click "Import Exam" to continue.');
}

/**
 * Handle import form submission
 * @param {Event} e - Form submit event
 */
function handleImportSubmit(e) {
    e.preventDefault();
    
    const importBtn = document.getElementById('importBtn');
    const fileInput = document.getElementById('excelFile');
    
    if (!fileInput || !fileInput.files[0]) {
        showAlert('error', 'Please select a file to import.');
        return;
    }

    // Show loading state
    if (importBtn) {
        importBtn.classList.add('loading');
        importBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importing...';
        importBtn.disabled = true;
    }

    // Create FormData for file upload
    const formData = new FormData();
    formData.append('excel_file', fileInput.files[0]);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');

    // Simulate import process (replace with actual fetch call)
    setTimeout(() => {
        // In a real application, replace this with:
        /*
        fetch(e.target.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeImportModal();
                showAlert('success', data.message || 'Exam imported successfully!');
                // Reload page or update UI
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Import failed');
            }
        })
        .catch(error => {
            console.error('Import error:', error);
            showAlert('error', error.message || 'Import failed. Please try again.');
        })
        .finally(() => {
            if (importBtn) {
                importBtn.classList.remove('loading');
                importBtn.innerHTML = '<i class="fas fa-upload"></i> Import Exam';
                importBtn.disabled = false;
            }
        });
        */
        
        // Simulated response
        closeImportModal();
        showAlert('success', 'Exam imported successfully! 25 questions were added.');
        updateStats();
        
    }, 3000);
}

/**
 * Download template file
 */
function downloadTemplate() {
    // In a real application, this would trigger the actual download
    showAlert('success', 'Template download started. Check your downloads folder.');
    
    // Simulate download delay
    setTimeout(() => {
        console.log('Template would be downloaded from server');
        // window.location.href = '/admin/exams/download-template';
    }, 500);
}

/**
 * Confirm deletion with user
 * @param {string} examTitle - Title of exam to delete
 * @returns {boolean} User confirmation
 */
function confirmDelete(examTitle) {
    const message = `Are you sure you want to delete "${examTitle}"?\n\nThis will also delete all questions and cannot be undone.`;
    
    if (confirm(message)) {
        // Add loading state to delete button
        const deleteButton = event.target.closest('button');
        if (deleteButton) {
            deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
            deleteButton.disabled = true;
        }
        return true;
    }
    return false;
}

/**
 * Update statistics in header
 */
function updateStats() {
    const examCards = document.querySelectorAll('.exam-card:not(.loading-skeleton)');
    const readyExams = document.querySelectorAll('.exam-card[data-status="ready"]');
    const draftExams = document.querySelectorAll('.exam-card[data-status="draft"]');
    
    let totalQuestions = 0;
    examCards.forEach(card => {
        const questionsNumber = parseInt(card.querySelector('.questions-number')?.textContent || '0');
        totalQuestions += questionsNumber;
    });

    // Animate counter updates
    animateCounter('totalExams', examCards.length);
    animateCounter('readyExams', readyExams.length);
    animateCounter('draftExams', draftExams.length);
    animateCounter('totalQuestions', totalQuestions);
}

/**
 * Animate counter to target value
 * @param {string} elementId - ID of counter element
 * @param {number} targetValue - Target value to animate to
 */
function animateCounter(elementId, targetValue) {
    const element = document.getElementById(elementId);
    if (!element) return;

    const currentValue = parseInt(element.textContent || '0');
    const increment = targetValue > currentValue ? 1 : -1;
    const duration = Math.abs(targetValue - currentValue) * 50; // 50ms per step
    
    if (currentValue !== targetValue && duration < 2000) { // Don't animate if it would take too long
        element.textContent = currentValue + increment;
        setTimeout(() => animateCounter(elementId, targetValue), 50);
    } else {
        element.textContent = targetValue;
    }
}

/**
 * Load and initialize exam data
 */
function loadExamData() {
    // Update stats based on current page data
    updateStats();
    
    // Load view preference
    try {
        const savedView = localStorage.getItem('examViewPreference');
        if (savedView && (savedView === 'grid' || savedView === 'list')) {
            toggleView(savedView);
        }
    } catch (e) {
        // Ignore localStorage errors
    }

    // Initialize tooltips if needed
    initializeTooltips();
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
 * Handle keyboard navigation
 */
function handleKeyboardNavigation(e) {
    // Handle Escape key for modals
    if (e.key === 'Escape') {
        const modal = document.getElementById('importModal');
        if (modal && modal.style.display !== 'none') {
            closeImportModal();
        }
    }
    
    // Handle Enter key on search
    if (e.key === 'Enter' && e.target.id === 'searchInput') {
        e.preventDefault();
        applyFilters();
    }
}

// Add keyboard event listener
document.addEventListener('keydown', handleKeyboardNavigation);

/**
 * Utility function to format file size
 * @param {number} bytes - File size in bytes
 * @returns {string} Formatted file size
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/**
 * Utility function to validate email format
 * @param {string} email - Email to validate
 * @returns {boolean} Is valid email
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Utility function to sanitize HTML
 * @param {string} str - String to sanitize
 * @returns {string} Sanitized string
 */
function sanitizeHTML(str) {
    const temp = document.createElement('div');
    temp.textContent = str;
    return temp.innerHTML;
}

/**
 * Show loading skeleton while data loads
 */
function showLoadingSkeleton() {
    const skeleton = document.getElementById('loadingSkeleton');
    if (skeleton) {
        skeleton.style.display = 'block';
    }
}

/**
 * Hide loading skeleton
 */
function hideLoadingSkeleton() {
    const skeleton = document.getElementById('loadingSkeleton');
    if (skeleton) {
        skeleton.style.display = 'none';
    }
}

/**
 * Refresh page data
 */
function refreshData() {
    showLoadingSkeleton();
    
    // Simulate data refresh
    setTimeout(() => {
        hideLoadingSkeleton();
        updateStats();
        showAlert('success', 'Data refreshed successfully!');
    }, 1000);
}

// Export functions for use in Blade template if needed
window.ExamManagement = {
    toggleView,
    clearFilters,
    showAlert,
    hideAlert,
    openImportModal,
    closeImportModal,
    confirmDelete,
    downloadTemplate,
    refreshData,
    updateStats
};

// Add slide out animation styles
const slideOutKeyframes = `
@keyframes slideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(-100%); opacity: 0; }
}

@keyframes modalFadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
}
`;

// Inject keyframes into page
const style = document.createElement('style');
style.textContent = slideOutKeyframes;
document.head.appendChild(style);

console.log('Exam Management Dashboard initialized successfully!');