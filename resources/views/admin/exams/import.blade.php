@extends('layouts.admin')

@section('title', __('Import Exam from Excel'))

@section('page-title', __('Import Exam'))

@section('content')

<link rel="stylesheet" href="{{ asset('css/exam-create.css') }}">
<link rel="stylesheet" href=" {{asset('css/import.css')}} ">
<div class="exam-create-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="page-title">{{ __('Import Exam from Excel') }}</h1>
                <p class="page-subtitle">Upload an Excel file to create a new exam with questions</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.exams.generate-template') }}" class="btn btn-warning">
                    <i class="fas fa-cog"></i>
                    {{ __('Generate Template') }}
                </a>
                <a href="{{ route('admin.exams.download-template') }}" class="btn btn-success" target="_blank">
                    <i class="fas fa-download"></i>
                    {{ __('Download Template') }}
                </a>
                <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
                    {{ __('Back to Exams') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h6 class="alert-title">{{ __('Please correct the following errors:') }}</h6>
            </div>
            <ul class="alert-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-header">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @endif

    <!-- Instructions Card -->
    <div class="form-card info-card">
        <div class="card-header">
            <div class="card-header-content">
                <i class="fas fa-info-circle"></i>
                <h3 class="card-title">{{ __('Import Instructions') }}</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="instructions-grid">
                <div class="instruction-item">
                    <div class="instruction-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="instruction-content">
                        <h6>{{ __('Step 1: Download Template') }}</h6>
                        <p>{{ __('Download the Excel template and fill in your exam data following the provided format.') }}</p>
                    </div>
                </div>
                <div class="instruction-item">
                    <div class="instruction-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="instruction-content">
                        <h6>{{ __('Step 2: Fill Your Data') }}</h6>
                        <p>{{ __('Add exam information, questions, and answer options in both English and Arabic.') }}</p>
                    </div>
                </div>
                <div class="instruction-item">
                    <div class="instruction-icon">
                        <i class="fas fa-upload"></i>
                    </div>
                    <div class="instruction-content">
                        <h6>{{ __('Step 3: Upload File') }}</h6>
                        <p>{{ __('Upload your completed Excel file to automatically create the exam.') }}</p>
                    </div>
                </div>
            </div>

            <div class="format-requirements">
                <h6>{{ __('Format Requirements:') }}</h6>
                <ul>
                    <li>{{ __('Exam title and duration are required') }}</li>
                    <li>{{ __('Each question must have at least 2 answer options') }}</li>
                    <li>{{ __('Mark correct answers with "1" in the correct columns') }}</li>
                    <li>{{ __('Question types: "single_choice" or "multiple_choice"') }}</li>
                    <li>{{ __('Single choice questions can only have one correct answer') }}</li>
                    <li>{{ __('File formats: .xlsx, .xls, .csv (max 10MB)') }}</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Template Download Card -->
    <div class="form-card">
        <div class="card-header">
            <div class="card-header-content">
                <i class="fas fa-file-excel"></i>
                <h3 class="card-title">{{ __('Excel Template') }}</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="template-download">
                <div class="template-preview">
                    <div class="template-icon">
                        <i class="fas fa-file-excel"></i>
                    </div>
                    <div class="template-info">
                        <h5>{{ __('Exam Import Template') }}</h5>
                        <p>{{ __('Pre-formatted Excel template with sample data and instructions') }}</p>
                        <div class="template-features">
                            <span class="feature-tag">{{ __('Bilingual Support') }}</span>
                            <span class="feature-tag">{{ __('Sample Questions') }}</span>
                            <span class="feature-tag">{{ __('Format Guide') }}</span>
                        </div>
                        <div class="template-note">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                {{ __('Generate creates a new template file. Download gets the existing template.') }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="template-actions">
                    <a href="{{ route('admin.exams.generate-template') }}" 
                       class="btn btn-warning btn-lg" 
                       onclick="return confirm('{{ __('This will create a fresh template file. Continue?') }}')">
                        <i class="fas fa-cog"></i>
                        {{ __('Generate New Template') }}
                    </a>
                    <a href="{{ route('admin.exams.download-template') }}" 
                       class="btn btn-success btn-lg" 
                       target="_blank">
                        <i class="fas fa-download"></i>
                        {{ __('Download Template') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Form Card -->
    <div class="form-card">
        <div class="card-header">
            <div class="card-header-content">
                <i class="fas fa-upload"></i>
                <h3 class="card-title">{{ __('Upload Excel File') }}</h3>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.exams.import') }}" enctype="multipart/form-data" id="importForm">
                @csrf
                
                <!-- File Upload Area -->
                <div class="file-upload-section">
                    <div class="file-upload-area" id="fileUploadArea">
                        <input type="file" 
                               class="file-input" 
                               id="excel_file" 
                               name="excel_file" 
                               required 
                               accept=".xlsx,.xls,.csv">
                        <div class="upload-placeholder" id="uploadPlaceholder">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <h5>{{ __('Drop Excel file here or click to browse') }}</h5>
                            <p>{{ __('Supported formats: .xlsx, .xls, .csv') }}</p>
                            <small>{{ __('Maximum file size: 10MB') }}</small>
                        </div>
                    </div>
                </div>

                <!-- File Preview -->
                <div id="filePreview" class="file-preview" style="display: none;">
                    <div class="preview-header">
                        <i class="fas fa-file-alt"></i>
                        <h6>{{ __('Selected File') }}</h6>
                    </div>
                    <div id="fileInfo" class="file-info"></div>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="removeFile">
                        <i class="fas fa-times"></i>
                        {{ __('Remove File') }}
                    </button>
                </div>

                <!-- Progress Bar -->
                <div id="uploadProgress" class="upload-progress" style="display: none;">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" 
                             style="width: 0%">
                            <span class="progress-text">{{ __('Processing...') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg" id="importBtn" disabled>
                        <i class="fas fa-upload"></i>
                        {{ __('Import Exam') }}
                    </button>
                    <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i>
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('excel_file');
    const fileUploadArea = document.getElementById('fileUploadArea');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const filePreview = document.getElementById('filePreview');
    const fileInfo = document.getElementById('fileInfo');
    const removeFileBtn = document.getElementById('removeFile');
    const importBtn = document.getElementById('importBtn');
    const importForm = document.getElementById('importForm');
    const uploadProgress = document.getElementById('uploadProgress');
    
    // File input change handler
    fileInput.addEventListener('change', function(e) {
        handleFileSelect(e.target.files[0]);
    });
    
    // Drag and drop handlers
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        fileUploadArea.classList.add('dragover');
    });
    
    fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        fileUploadArea.classList.remove('dragover');
    });
    
    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        fileUploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileSelect(files[0]);
        }
    });
    
    // Remove file handler
    removeFileBtn.addEventListener('click', function() {
        clearFile();
    });
    
    // Form submit handler
    importForm.addEventListener('submit', function(e) {
        if (!fileInput.files[0]) {
            e.preventDefault();
            alert('Please select a file to import.');
            return;
        }
        
        // Show progress
        importBtn.disabled = true;
        uploadProgress.style.display = 'block';
        
        // Simulate progress (you can replace this with actual upload progress)
        let progress = 0;
        const progressBar = uploadProgress.querySelector('.progress-bar');
        const progressText = uploadProgress.querySelector('.progress-text');
        
        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) {
                progress = 90;
                clearInterval(interval);
                progressText.textContent = 'Processing file...';
            }
            progressBar.style.width = progress + '%';
        }, 200);
    });
    
    function handleFileSelect(file) {
        if (!file) return;
        
        // Validate file type
        const allowedTypes = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
            'application/vnd.ms-excel', // .xls
            'text/csv' // .csv
        ];
        
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a valid Excel file (.xlsx, .xls, or .csv)');
            return;
        }
        
        // Validate file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB');
            return;
        }
        
        // Update file input
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
        
        // Show file info
        showFilePreview(file);
        
        // Enable import button
        importBtn.disabled = false;
    }
    
    function showFilePreview(file) {
        uploadPlaceholder.style.display = 'none';
        filePreview.style.display = 'block';
        
        fileInfo.innerHTML = `
            <div><strong>File Name:</strong> ${file.name}</div>
            <div><strong>File Size:</strong> ${formatFileSize(file.size)}</div>
            <div><strong>File Type:</strong> ${file.type}</div>
            <div><strong>Last Modified:</strong> ${new Date(file.lastModified).toLocaleString()}</div>
        `;
    }
    
    function clearFile() {
        fileInput.value = '';
        uploadPlaceholder.style.display = 'block';
        filePreview.style.display = 'none';
        importBtn.disabled = true;
        uploadProgress.style.display = 'none';
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>
@endsection