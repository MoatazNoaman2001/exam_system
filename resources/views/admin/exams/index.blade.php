@extends('layouts.admin')

@section('title', 'Exams Management')

@section('page-title', 'Exams Management')

@section('content')

<link rel="stylesheet" href="{{ asset('css/exam-index.css') }}">
<div class="exams-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="page-title">Exams Management</h1>
                <p class="page-subtitle">Manage and organize all your exams</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.exams.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create New Exam
                </a>
                <a href="{{ route('admin.exams.import.form') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i>
                    Import from Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="alert-header">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-header">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h6 class="alert-title">Please correct the following errors:</h6>
            </div>
            <ul class="alert-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">&times;</button>
        </div>
    @endif

    <!-- Exams Table Card -->
    <div class="table-card">
        <div class="card-header">
            <div class="card-header-content">
                <i class="fas fa-list"></i>
                <h3 class="card-title">All Exams</h3>
            </div>
            <div class="card-actions">
                <div class="search-box">
                    <input type="text" id="searchExams" placeholder="Search exams..." class="form-control form-control-sm">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($exams->count() > 0)
                <div class="table-responsive">
                    <table class="table exams-table" id="examsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Questions</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exams as $exam)
                            <tr>
                                <td>
                                    <span class="exam-id">{{ Str::limit($exam->id, 8) }}</span>
                                </td>
                                <td>
                                    <div class="exam-title-cell">
                                        <h6 class="exam-title">{{ $exam->text }}</h6>
                                        @if($exam->{'text-ar'})
                                            <p class="exam-title-ar">{{ $exam->{'text-ar'} }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="questions-info">
                                        <span class="badge badge-{{ $exam->number_of_questions > 0 ? 'info' : 'warning' }}">
                                            {{ $exam->number_of_questions }} {{ __('Questions') }}
                                        </span>
                                        @if($exam->number_of_questions == 0)
                                            <small class="text-muted d-block">{{ __('No questions added') }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="duration-badge">
                                        <i class="fas fa-clock"></i>
                                        {{ $exam->time }} {{ __('min') }}
                                    </span>
                                </td>
                                <td>
                                    @if($exam->number_of_questions > 0)
                                        <span class="status-badge status-active">
                                            <i class="fas fa-check-circle"></i>
                                            {{ __('Ready') }}
                                        </span>
                                    @else
                                        <span class="status-badge status-draft">
                                            <i class="fas fa-edit"></i>
                                            {{ __('Draft') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="created-date">{{ $exam->created_at->format('M d, Y') }}</span>
                                    <small class="created-time">{{ $exam->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.exams.questions.index', $exam->id) }}" 
                                           class="btn btn-sm btn-success" 
                                           title="Manage Questions">
                                            <i class="fas fa-question-circle"></i>
                                        </a>
                                        <a href="{{ route('admin.exams.edit', $exam->id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Edit Exam Info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.exams.show', $exam->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="View Exam">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.exams.destroy', $exam->id) }}" 
                                              method="POST" 
                                              style="display: inline;"
                                              onsubmit="return confirmDelete('{{ $exam->text }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Delete Exam">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $exams->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h4 class="empty-title">No Exams Found</h4>
                    <p class="empty-text">You haven't created any exams yet. Start by creating your first exam or importing from Excel.</p>
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
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="fas fa-file-excel"></i>
                    Import Exam from Excel
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="{{ route('admin.exams.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="modal-body">
                    <!-- Instructions -->
                    <div class="import-instructions">
                        <div class="instruction-header">
                            <i class="fas fa-info-circle"></i>
                            <h6>Import Instructions</h6>
                        </div>
                        <ul class="instruction-list">
                            <li>Download the Excel template and fill in your exam data</li>
                            <li>Each question can have 2-6 answer options</li>
                            <li>Mark correct answers with "1" in the is_correct columns</li>
                            <li>All questions must belong to the same exam</li>
                            <li>Supported formats: .xlsx, .xls, .csv (max 10MB)</li>
                        </ul>
                    </div>

                    <!-- Template Download -->
                    <div class="template-download">
                        <div class="download-card">
                            <div class="download-content">
                                <div class="download-icon">
                                    <i class="fas fa-download"></i>
                                </div>
                                <div class="download-info">
                                    <h6>Excel Template</h6>
                                    <p>Download the template with example data and instructions</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.exams.download-template') }}" 
                               class="btn btn-success btn-sm"
                               target="_blank">
                                <i class="fas fa-download"></i>
                                Download
                            </a>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="file-upload-section">
                        <label for="excel_file" class="form-label">
                            <i class="fas fa-upload"></i>
                            Select Excel File
                        </label>
                        <div class="file-upload-area" id="fileUploadArea">
                            <input type="file" 
                                   class="file-input" 
                                   id="excel_file" 
                                   name="excel_file" 
                                   required 
                                   accept=".xlsx,.xls,.csv">
                            <div class="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Click to select file or drag and drop</p>
                                <small>Maximum /admin/file size: 10MB. Formats: .xlsx, .xls, .csv</small>
                            </div>
                        </div>
                    </div>

                    <!-- File Preview -->
                    <div id="filePreview" class="file-preview" style="display: none;">
                        <div class="preview-header">
                            <i class="fas fa-file-alt"></i>
                            <h6>Selected File</h6>
                        </div>
                        <div id="fileInfo" class="file-info"></div>
                    </div>

                    <!-- Progress Bar -->
                    <div id="uploadProgress" class="upload-progress" style="display: none;">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 style="width: 0%">
                                <span class="progress-text">Uploading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="importBtn">
                        <i class="fas fa-upload"></i>
                        Import Exam
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.questions-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.status-badge.status-draft {
    background-color: #ffc107;
    color: #212529;
}

.status-badge.status-active {
    background-color: #28a745;
    color: white;
}

.action-buttons {
    display: flex;
    gap: 0.25rem;
    flex-wrap: wrap;
}

.action-buttons .btn {
    min-width: 32px;
}

@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn {
        width: 100%;
        justify-content: flex-start;
    }
}
</style>

<script src="{{ asset('js/exam-index.js') }}"></script>
<script>
function confirmDelete(examTitle) {
    return confirm(`Are you sure you want to delete the exam: "${examTitle}"?\n\nThis will also delete all questions and cannot be undone.`);
}

// Search functionality
document.getElementById('searchExams').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const tableRows = document.querySelectorAll('#examsTable tbody tr');
    
    tableRows.forEach(row => {
        const examTitle = row.querySelector('.exam-title').textContent.toLowerCase();
        const examTitleAr = row.querySelector('.exam-title-ar')?.textContent.toLowerCase() || '';
        
        if (examTitle.includes(searchTerm) || examTitleAr.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endsection