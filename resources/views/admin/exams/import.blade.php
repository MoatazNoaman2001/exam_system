<!-- Debug: Add this at the very top of your blade file to check routes -->
@if(config('app.debug'))
<div class="alert alert-info small">
    <strong>Debug Info:</strong><br>
    Import Route: {{ route('admin.exams.import') ?? 'NOT FOUND' }}<br>
    Template Route: {{ route('admin.exams.download-template') ?? 'NOT FOUND' }}
</div>
@endif

<!-- Your existing content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Exams Management</h1>
        <div>
            <a href="{{ route('admin.exams.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-plus fa-sm text-white-50"></i> Create New Exam
            </a>
            <!-- Debug: Check if button triggers modal -->
            <button type="button" 
                    class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm" 
                    data-toggle="modal" 
                    data-target="#importModal"
                    onclick="console.log('Import button clicked')">
                <i class="fas fa-file-excel fa-sm text-white-50"></i> Import from Excel
            </button>
        </div>
    </div>

    <!-- Display any session messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Exams</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Questions</th>
                            <th>Duration (min)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exams as $exam)
                        <tr>
                            <td>{{ Str::limit($exam->id, 8) }}</td>
                            <td>{{ $exam->text }}</td>
                            <td>{{ $exam->number_of_questions }}</td>
                            <td>{{ $exam->time }}</td>
                            <td>
                                @if($exam->is_completed)
                                    <span class="badge badge-success">Completed</span>
                                @else
                                    <span class="badge badge-warning">Active</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.exams.edit', $exam) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.exams.destroy', $exam) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $exams->links() }}
        </div>
    </div>
</div>

<!-- FIXED Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="fas fa-file-excel mr-2"></i>
                    Import Exam from Excel
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="{{ route('admin.exams.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="modal-body">
                    <!-- Instructions -->
                    <div class="alert alert-info border-left-info">
                        <div class="text-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Instructions:</strong>
                        </div>
                        <ul class="mb-0 mt-2">
                            <li>Download the template file and fill in your exam data</li>
                            <li>Each question can have 2-6 answer options</li>
                            <li>Mark correct answers with "1" in the is_correct columns</li>
                            <li>All questions must belong to the same exam</li>
                            <li>Supported formats: .xlsx, .xls, .csv (max 10MB)</li>
                        </ul>
                    </div>

                    <!-- Template Download -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-left-success">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="text-success font-weight-bold mb-1">
                                                <i class="fas fa-download mr-2"></i>
                                                Excel Template
                                            </h6>
                                            <p class="text-muted mb-0">
                                                Download the template file with example data and detailed instructions
                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <a href="{{ route('admin.exams.download-template') }}" 
                                               class="btn btn-success btn-sm shadow-sm"
                                               target="_blank">
                                                <i class="fas fa-download mr-1"></i>
                                                Download Template
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="form-group">
                        <label for="excel_file" class="font-weight-bold text-gray-800">
                            <i class="fas fa-upload mr-2"></i>
                            Select Excel File
                        </label>
                        <div class="custom-file">
                            <input type="file" 
                                   class="custom-file-input" 
                                   id="excel_file" 
                                   name="excel_file" 
                                   required 
                                   accept=".xlsx,.xls,.csv">
                            <label class="custom-file-label" for="excel_file">
                                Choose file...
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Maximum file size: 10MB. Supported formats: Excel (.xlsx, .xls) and CSV (.csv)
                        </small>
                    </div>

                    <!-- File Preview (will be populated by JavaScript) -->
                    <div id="filePreview" class="d-none">
                        <div class="card border-left-primary">
                            <div class="card-body">
                                <h6 class="text-primary font-weight-bold mb-2">
                                    <i class="fas fa-file-alt mr-2"></i>
                                    Selected File
                                </h6>
                                <div id="fileInfo"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar (hidden by default) -->
                    <div id="uploadProgress" class="d-none">
                        <div class="progress mb-3">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 style="width: 0%">
                                Uploading...
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="importBtn">
                        <i class="fas fa-upload mr-1"></i>
                        Import Exam
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DEBUGGING: Add at the very end before closing body tag -->
<script>
// Debug: Check if jQuery and Bootstrap are loaded
console.log('jQuery loaded:', typeof $ !== 'undefined');
console.log('Bootstrap loaded:', typeof $.fn.modal !== 'undefined');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    console.log('Modal element exists:', document.getElementById('importModal') !== null);
    
    const fileInput = document.getElementById('excel_file');
    const fileLabel = document.querySelector('.custom-file-label');
    const filePreview = document.getElementById('filePreview');
    const fileInfo = document.getElementById('fileInfo');
    const importForm = document.getElementById('importForm');
    const importBtn = document.getElementById('importBtn');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = document.querySelector('.progress-bar');

    // Debug modal show/hide events
    $('#importModal').on('show.bs.modal', function (e) {
        console.log('Modal is showing');
    });

    $('#importModal').on('shown.bs.modal', function (e) {
        console.log('Modal is shown');
    });

    $('#importModal').on('hide.bs.modal', function (e) {
        console.log('Modal is hiding');
    });

    // Handle file selection
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                console.log('File selected:', file.name);
                
                // Update label
                fileLabel.textContent = file.name;
                
                // Show file preview
                filePreview.classList.remove('d-none');
                
                // Format file size
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                const fileSizeClass = fileSize > 5 ? 'text-warning' : 'text-success';
                
                fileInfo.innerHTML = `
                    <div class="row">
                        <div class="col-sm-6">
                            <strong>Name:</strong> ${file.name}
                        </div>
                        <div class="col-sm-6">
                            <strong>Size:</strong> <span class="${fileSizeClass}">${fileSize} MB</span>
                        </div>
                        <div class="col-sm-6">
                            <strong>Type:</strong> ${file.type || 'Unknown'}
                        </div>
                        <div class="col-sm-6">
                            <strong>Last Modified:</strong> ${new Date(file.lastModified).toLocaleDateString()}
                        </div>
                    </div>
                `;
            } else {
                // Reset if no file
                fileLabel.textContent = 'Choose file...';
                filePreview.classList.add('d-none');
            }
        });
    }

    // Handle form submission
    if (importForm) {
        importForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submitted');
            
            const formData = new FormData(this);
            
            // Show loading state
            importBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Importing...';
            importBtn.disabled = true;
            uploadProgress.classList.remove('d-none');
            
            // Submit form normally (not using fetch for debugging)
            this.submit();
        });
    }

    // Reset form when modal is closed
    $('#importModal').on('hidden.bs.modal', function() {
        console.log('Modal closed, resetting form');
        if (importForm) {
            importForm.reset();
            fileLabel.textContent = 'Choose file...';
            filePreview.classList.add('d-none');
            uploadProgress.classList.add('d-none');
            progressBar.style.width = '0%';
            importBtn.innerHTML = '<i class="fas fa-upload mr-1"></i> Import Exam';
            importBtn.disabled = false;
        }
    });
});

// Test modal manually
function testModal() {
    $('#importModal').modal('show');
}

// Add test button (remove after debugging)
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.container-fluid')) {
        const testBtn = document.createElement('button');
        testBtn.innerHTML = 'Test Modal';
        testBtn.className = 'btn btn-warning btn-sm';
        testBtn.onclick = testModal;
        document.querySelector('.container-fluid').prepend(testBtn);
    }
});
</script>

<style>
/* Your existing styles */
.modal-lg {
    max-width: 800px;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.border-left-success {
    border-left: 4px solid #1cc88a !important;
}

.border-left-primary {
    border-left: 4px solid #4e73df !important;
}

.custom-file-label::after {
    content: "Browse";
    background: #4e73df;
    border-color: #4e73df;
    color: white;
}

.progress {
    height: 0.5rem;
}

.modal-content {
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
    border-radius: 0.5rem 0.5rem 0 0;
}

.custom-file {
    position: relative;
    display: inline-block;
    width: 100%;
    height: calc(1.5em + 0.75rem + 2px);
    margin-bottom: 0;
}

.custom-file-input:focus ~ .custom-file-label {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.custom-file-label {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    z-index: 1;
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-weight: 400;
    line-height: 1.5;
    color: #6e707e;
    background-color: #fff;
    border: 1px solid #d1d3e2;
    border-radius: 0.35rem;
    cursor: pointer;
}

@media (max-width: 768px) {
    .modal-lg {
        max-width: 90%;
        margin: 1rem auto;
    }
    
    .modal-dialog {
        margin: 1rem;
    }
    
    #fileInfo .row .col-sm-6 {
        margin-bottom: 0.5rem;
    }
}

#filePreview {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>