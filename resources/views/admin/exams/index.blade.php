@extends('layouts.admin')

@section('title', 'Exams Management')

@section('page-title', 'Exams Management')

@section('content')

<link rel="stylesheet" href="{{ asset('css/exam-index.css') }}">

<div class="dashboard-container">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="page-title">Exam Management</h1>
                <p class="page-subtitle">Create, manage, and organize all your examinations</p>
                <div class="header-stats">
                    <div class="stat-item">
                        <span class="stat-number" id="totalExams">{{ $exams->count() }}</span>
                        <span class="stat-label">Total Exams</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="readyExams">{{ $exams->where('number_of_questions', '>', 0)->count() }}</span>
                        <span class="stat-label">Ready</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="draftExams">{{ $exams->where('number_of_questions', 0)->count() }}</span>
                        <span class="stat-label">Drafts</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="totalQuestions">{{ $exams->sum('number_of_questions') }}</span>
                        <span class="stat-label">Questions</span>
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.exams.import.form') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i>
                    Import from Excel
                </a>
                <a href="{{ route('admin.exams.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create New Exam
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success" id="successAlert">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button class="alert-dismiss" onclick="hideAlert('successAlert')">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" id="errorAlert">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
            <button class="alert-dismiss" onclick="hideAlert('errorAlert')">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" id="validationAlert">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Please correct the following errors:</strong>
                <ul class="error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button class="alert-dismiss" onclick="hideAlert('validationAlert')">&times;</button>
        </div>
    @endif

    <!-- Search and Controls -->
    <div class="controls-section">
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Search exams by title, certificate, or content...">
        </div>
        <div class="filters">
            <select class="filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="ready">Ready</option>
                <option value="draft">Draft</option>
            </select>
            <select class="filter-select" id="certificateFilter">
                <option value="">All Certificates</option>
                @foreach($certificates ?? [] as $certificate)
                    <option value="{{ $certificate->id }}">{{ $certificate->code }}</option>
                @endforeach
            </select>
            <div class="view-toggle">
                <button class="view-btn active" onclick="toggleView('grid')">
                    <i class="fas fa-th-large"></i>
                </button>
                <button class="view-btn" onclick="toggleView('list')">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>

    @if($exams->count() > 0)
        <!-- Exams Grid -->
        <div class="exams-grid" id="examsContainer">
            @foreach($exams as $exam)
                <div class="exam-card" 
                     data-status="{{ $exam->number_of_questions > 0 ? 'ready' : 'draft' }}" 
                     data-certificate="{{ $exam->certificate_id ?? '' }}">
                    <div class="exam-header">
                        <div>
                            <h3 class="exam-title">{{ $exam->text }}</h3>
                            @if($exam->{'text-ar'})
                                <p class="exam-title-ar">{{ $exam->{'text-ar'} }}</p>
                            @endif
                        </div>
                        <span class="exam-id">#{{ Str::upper(Str::limit($exam->id, 8, '')) }}</span>
                    </div>
                    
                    <div class="exam-info">
                        <div class="info-row">
                            <span class="info-label">Certificate</span>
                            @if($exam->certificate)
                                <span class="certificate-badge" style="background: {{ $exam->certificate->color }}20; color: {{ $exam->certificate->color }}; border: 1px solid {{ $exam->certificate->color }}30;">
                                    <i class="fas fa-certificate"></i>
                                    {{ $exam->certificate->code }}
                                </span>
                            @else
                                <span class="certificate-badge" style="background: rgba(107, 114, 128, 0.1); color: var(--gray-600); border: 1px solid rgba(107, 114, 128, 0.3);">
                                    <i class="fas fa-certificate"></i>
                                    No Certificate
                                </span>
                            @endif
                        </div>
                        <div class="info-row">
                            <span class="info-label">Questions</span>
                            <div class="questions-count">
                                <span class="questions-number">{{ $exam->number_of_questions }}</span>
                                <span class="info-value">questions</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Duration</span>
                            <div class="duration-display">
                                <i class="fas fa-clock"></i>
                                <span>{{ $exam->time }} minutes</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            @if($exam->number_of_questions > 0)
                                <span class="status-badge status-ready">
                                    <i class="fas fa-check-circle"></i>
                                    Ready
                                </span>
                            @else
                                <span class="status-badge status-draft">
                                    <i class="fas fa-edit"></i>
                                    Draft
                                </span>
                            @endif
                        </div>
                        <div class="info-row">
                            <span class="info-label">Created</span>
                            <div class="created-info">
                                <span class="created-date">{{ $exam->created_at->format('M d, Y') }}</span>
                                <small class="created-time">{{ $exam->created_at->format('H:i') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="exam-actions">
                        <a href="{{ route('admin.exams.questions.index', $exam->id) }}" class="action-btn primary">
                            <i class="fas fa-question-circle"></i>
                            {{ $exam->number_of_questions > 0 ? 'Manage Questions' : 'Add Questions' }}
                        </a>
                        <a href="{{ route('admin.exams.edit', $exam->id) }}" class="action-btn">
                            <i class="fas fa-edit"></i>
                            Edit
                        </a>
                        <a href="{{ route('admin.exams.show', $exam->id) }}" class="action-btn">
                            <i class="fas fa-eye"></i>
                            View
                        </a>
                        <form action="{{ route('admin.exams.destroy', $exam->id) }}" 
                              method="POST" 
                              style="display: inline;"
                              onsubmit="return confirmDelete('{{ addslashes($exam->text) }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete-btn">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

            <!-- Loading Skeletons (hidden by default) -->
            <div class="skeleton-card loading-skeleton" id="loadingSkeleton" style="display: none;">
                <div class="skeleton-line medium"></div>
                <div class="skeleton-line short"></div>
                <div class="skeleton-line long"></div>
                <div class="skeleton-line medium"></div>
            </div>
        </div>

        <!-- Pagination -->
        @if($exams->hasPages())
            <div class="pagination-wrapper">
                {{ $exams->links() }}
            </div>
        @endif

        <!-- Empty State (shown when no exams match filters) -->
        <div class="empty-state" id="emptyState" style="display: none;">
            <div class="empty-icon">
                <i class="fas fa-search"></i>
            </div>
            <h2 class="empty-title">No exams found</h2>
            <p class="empty-text">Try adjusting your search criteria or filters to find the exams you're looking for.</p>
            <div class="empty-actions">
                <button class="btn btn-primary" onclick="clearFilters()">
                    <i class="fas fa-filter"></i>
                    Clear Filters
                </button>
            </div>
        </div>
    @else
        <!-- No Exams State -->
        <div class="empty-state" id="noExamsState">
            <div class="empty-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <h2 class="empty-title">No exams created yet</h2>
            <p class="empty-text">Get started by creating your first exam or importing questions from an Excel file.</p>
            <div class="empty-actions">
                <a href="{{ route('admin.exams.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create First Exam
                </a>
                <a href="{{ route('admin.exams.import.form') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i>
                    Import from Excel
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Import Modal -->
<div id="importModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeImportModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-file-excel"></i>
                Import Exam from Excel
            </h3>
            <button class="modal-close" onclick="closeImportModal()">&times;</button>
        </div>
        
        <form action="{{ route('admin.exams.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf
            <div class="modal-body">
                <div class="import-steps">
                    <div class="step active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>Download Template</h4>
                            <p>Download our Excel template with example data and instructions</p>
                            <a href="{{ route('admin.exams.download-template') }}" 
                               class="btn btn-success btn-sm"
                               target="_blank">
                                <i class="fas fa-download"></i>
                                Download Template
                            </a>
                        </div>
                    </div>
                    
                    <div class="step" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>Prepare Your File</h4>
                            <p>Fill in your exam data following the template format</p>
                        </div>
                    </div>
                    
                    <div class="step" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>Upload & Import</h4>
                            <p>Select your completed Excel file to import</p>
                            <div class="file-upload" id="fileUpload">
                                <input type="file" 
                                       id="excelFile" 
                                       name="excel_file" 
                                       accept=".xlsx,.xls,.csv" 
                                       onchange="handleFileSelect(event)"
                                       required>
                                <div class="upload-area">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Click to select file or drag and drop</p>
                                    <small>Maximum file size: 10MB. Formats: .xlsx, .xls, .csv</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeImportModal()">
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary" id="importBtn" disabled>
                    <i class="fas fa-upload"></i>
                    Import Exam
                </button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/exam-management.js') }}"></script>

@endsection