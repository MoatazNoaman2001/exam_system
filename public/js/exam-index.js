// Exams Index JavaScript - Dashboard Style Compatible
'use strict';

document.addEventListener('DOMContentLoaded', function() {
    console.log('Exams Index initialized');
    
    // Initialize all components
    initializeSearch();
    initializeFileUpload();
    initializeModals();
    initializeTableInteractions();
});

// Search functionality
function initializeSearch() {
    const searchInput = document.getElementById('searchExams');
    const tableRows = document.querySelectorAll('#examsTable tbody tr');
    
    if (!searchInput || !tableRows.length) return;
    
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const shouldShow = text.includes(searchTerm);
            
            row.style.display = shouldShow ? '' : 'none';
            
            // Add highlight effect
            if (shouldShow && searchTerm) {
                row.classList.add('search-highlight');
            } else {
                row.classList.remove('search-highlight');
            }
        });
        
        // Show/hide empty state
        updateEmptyState();
    });
}

// File upload functionality
function initializeFileUpload() {
    const fileInput = document.getElementById('excel_file');
    const fileUploadArea = document.getElementById('fileUploadArea');
    const filePreview = document.getElementById('filePreview');
    const fileInfo = document.getElementById('fileInfo');
    const importForm = document.getElementById('importForm');
    const importBtn = document.getElementById('importBtn');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = document.querySelector('.progress-bar');
    
    if (!fileInput || !fileUploadArea) return;
    
    // File input change handler
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        handleFileSelection(file);
    });
    
    // Drag and drop handlers
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileUploadArea.classList.add('dragover');
    });
    
    fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileUploadArea.classList.remove('dragover');
    });
    
    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileUploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelection(files[0]);
        }
    });
    
    // Form submission handler
    if (importForm) {
        importForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleFormSubmission();
        });
    }
    
    function handleFileSelection(file) {
        if (!file) {
            hideFilePreview();
            return;
        }
        
        console.log('File selected:', file.name);
        
        // Validate file
        const validation = validateFile(file);
        if (!validation.valid) {
            showNotification(validation.message, 'error');
            fileInput.value = '';
            hideFilePreview();
            return;
        }
        
        // Show file preview
        showFilePreview(file);
    }
    
    function validateFile(file) {
        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                              'application/vnd.ms-excel', 
                              'text/csv'];
        const allowedExtensions = ['.xlsx', '.xls', '.csv'];
        
        // Check file size
        if (file.size > maxSize) {
            return {
                valid: false,
                message: 'File size exceeds 10MB limit. Please choose a smaller file.'
            };
        }
        
        // Check file extension
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        if (!allowedExtensions.includes(fileExtension)) {
            return {
                valid: false,
                message: 'Invalid file type. Please upload an Excel (.xlsx, .xls) or CSV (.csv) file.'
            };
        }
        
        return { valid: true };
    }
    
    function showFilePreview(file) {
        if (!filePreview || !fileInfo) return;
        
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        const fileSizeClass = fileSize > 5 ? 'text-warning' : 'text-success';
        
        fileInfo.innerHTML = `
            <div class="file-info-item">
                <div class="file-info-label">File Name</div>
                <div class="file-info-value">${file.name}</div>
            </div>
            <div class="file-info-item">
                <div class="file-info-label">File Size</div>
                <div class="file-info-value ${fileSizeClass}">${fileSize} MB</div>
            </div>
            <div class="file-info-item">
                <div class="file-info-label">File Type</div>
                <div class="file-info-value">${file.type || 'Unknown'}</div>
            </div>
            <div class="file-info-item">
                <div class="file-info-label">Last Modified</div>
                <div class="file-info-value">${new Date(file.lastModified).toLocaleDateString()}</div>
            </div>
        `;
        
        filePreview.style.display = 'block';
        filePreview.classList.add('fade-in');
    }
    
    function hideFilePreview() {
        if (filePreview) {
            filePreview.style.display = 'none';
            filePreview.classList.remove('fade-in');
        }
    }
    
    function handleFormSubmission() {
        if (!fileInput.files || fileInput.files.length === 0) {
            showNotification('Please select a file to import.', 'error');
            return;
        }
        
        // Show loading state
        setImportButtonLoading(true);
        showUploadProgress();
        
        // Simulate progress (since we can't track real upload progress easily)
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            updateProgressBar(progress);
        }, 200);
        
        // Submit form
        const formData = new FormData(importForm);
        
        fetch(importForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => {
            clearInterval(progressInterval);
            updateProgressBar(100);
            
            if (response.ok) {
                return response.text();
            }
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        })
        .then(html => {
            setTimeout(() => {
                showNotification('Exam imported successfully!', 'success');
                $('#importModal').modal('hide');
                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }, 500);
        })
        .catch(error => {
            console.error('Import error:', error);
            resetImportForm();
            showNotification('Import failed. Please check your file and try again.', 'error');
        });
    }
    
    function setImportButtonLoading(loading) {
        if (!importBtn) return;
        
        if (loading) {
            importBtn.classList.add('loading');
            importBtn.disabled = true;
            importBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importing...';
        } else {
            importBtn.classList.remove('loading');
            importBtn.disabled = false;
            importBtn.innerHTML = '<i class="fas fa-upload"></i> Import Exam';
        }
    }
    
    function showUploadProgress() {
        if (uploadProgress) {
            uploadProgress.style.display = 'block';
        }
    }
    
    function hideUploadProgress() {
        if (uploadProgress) {
            uploadProgress.style.display = 'none';
        }
    }
    
    function updateProgressBar(percentage) {
        if (progressBar) {
            progressBar.style.width = percentage + '%';
            const progressText = progressBar.querySelector('.progress-text');
            if (progressText) {
                if (percentage >= 100) {
                    progressText.textContent = 'Processing...';
                } else {
                    progressText.textContent = `Uploading... ${Math.round(percentage)}%`;
                }
            }
        }
    }
    
    function resetImportForm() {
        setImportButtonLoading(false);
        hideUploadProgress();
        updateProgressBar(0);
        hideFilePreview();
        if (fileInput) fileInput.value = '';
    }
    
    // Reset form when modal is closed
    $('#importModal').on('hidden.bs.modal', function() {
        resetImportForm();
    });
}

// Modal functionality
function initializeModals() {
    // Import modal event handlers
    $('#importModal').on('show.bs.modal', function() {
        console.log('Import modal showing');
    });
    
    $('#importModal').on('shown.bs.modal', function() {
        console.log('Import modal shown');
        // Focus on file input when modal is shown
        const fileInput = document.getElementById('excel_file');
        if (fileInput) {
            setTimeout(() => fileInput.focus(), 100);
        }
    });
    
    // View exam modal handler
    $('#viewExamModal').on('show.bs.modal', function() {
        const examDetailsContent = document.getElementById('examDetailsContent');
        if (examDetailsContent) {
            examDetailsContent.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
        }
    });
}

// Table interactions
function initializeTableInteractions() {
    // Add hover effects and animations
    const tableRows = document.querySelectorAll('#examsTable tbody tr');
    
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(4px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
}

// Utility functions
function updateEmptyState() {
    const tableRows = document.querySelectorAll('#examsTable tbody tr');
    const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
    
    const tbody = document.querySelector('#examsTable tbody');
    let emptyRow = tbody.querySelector('.search-empty-state');
    
    if (visibleRows.length === 0 && tableRows.length > 0) {
        // Show search empty state
        if (!emptyRow) {
            emptyRow = document.createElement('tr');
            emptyRow.className = 'search-empty-state';
            emptyRow.innerHTML = `
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-search text-muted mb-2" style="font-size: 2rem;"></i>
                    <p class="text-muted mb-0">No exams match your search criteria.</p>
                </td>
            `;
            tbody.appendChild(emptyRow);
        }
    } else if (emptyRow) {
        // Hide search empty state
        emptyRow.remove();
    }
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.toast-notification');
    existingNotifications.forEach(notification => notification.remove());
    
    const notification = document.createElement('div');
    notification.className = `toast-notification toast-${type}`;
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };
    
    const colors = {
        success: 'var(--success-green)',
        error: 'var(--danger-red)',
        warning: 'var(--warning-amber)',
        info: 'var(--primary-blue)'
    };
    
    notification.innerHTML = `
        <div class="toast-content">
            <i class="${icons[type] || icons.info}"></i>
            <span>${message}</span>
        </div>
        <button type="button" class="toast-close">
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
        boxShadow: 'var(--shadow-lg)',
        background: colors[type] || colors.info,
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        gap: '0.75rem'
    });
    
    // Add close functionality
    const closeBtn = notification.querySelector('.toast-close');
    closeBtn.addEventListener('click', () => {
        hideNotification(notification);
    });
    
    // Style close button
    Object.assign(closeBtn.style, {
        background: 'none',
        border: 'none',
        color: 'white',
        cursor: 'pointer',
        padding: '0.25rem',
        borderRadius: '4px',
        transition: 'background-color 0.2s ease'
    });
    
    closeBtn.addEventListener('mouseenter', () => {
        closeBtn.style.background = 'rgba(255, 255, 255, 0.2)';
    });
    
    closeBtn.addEventListener('mouseleave', () => {
        closeBtn.style.background = 'none';
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
            hideNotification(notification);
        }
    }, 5000);
}

function hideNotification(notification) {
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(100%)';
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300);
}

// Global functions for template usage
window.confirmDelete = function(examTitle) {
    return confirm(`Are you sure you want to delete the exam "${examTitle}"? This action cannot be undone.`);
};

window.viewExam = function(examId) {
    console.log('Viewing exam:', examId);
    // Implement view exam functionality
    $('#viewExamModal').modal('show');
    
    // You can implement AJAX call to load exam details here
    setTimeout(() => {
        const examDetailsContent = document.getElementById('examDetailsContent');
        if (examDetailsContent) {
            examDetailsContent.innerHTML = `
                <div class="text-center">
                    <p>Exam details for ID: ${examId}</p>
                    <p>This feature will be implemented to show exam details.</p>
                </div>
            `;
        }
    }, 500);
};

// Add search highlight styles
const searchStyles = document.createElement('style');
searchStyles.textContent = `
    .search-highlight {
        background-color: rgba(59, 130, 246, 0.1) !important;
        border-left: 3px solid var(--primary-blue) !important;
    }
    
    .toast-content {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
    }
    
    .fade-in {
        animation: fadeInUp 0.3s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(searchStyles);

// Error handling
window.addEventListener('error', function(e) {
    console.error('Exams Index Error:', e.error);
});

// Performance monitoring
if ('performance' in window) {
    window.addEventListener('load', function() {
        setTimeout(function() {
            try {
                const perfData = performance.getEntriesByType('navigation')[0];
                const loadTime = perfData.loadEventEnd - perfData.loadEventStart;
                console.log('Exams Index Load Time:', loadTime + 'ms');
            } catch (error) {
                console.warn('Performance monitoring failed:', error);
            }
        }, 0);
    });
}