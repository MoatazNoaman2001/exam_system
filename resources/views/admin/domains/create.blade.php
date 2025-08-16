@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Domain</h1>
        <a href="{{ route('admin.domains') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
        </a>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-header">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Please correct the following errors:</strong>
            </div>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Domain Details</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.domains.store') }}">
                @csrf
                
                <!-- Domain Name Row -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="text" class="form-label required">
                                <i class="fas fa-globe"></i> Domain Name (English)
                            </label>
                            <input type="text" 
                                   class="form-control @error('text') is-invalid @enderror" 
                                   id="text" 
                                   name="text" 
                                   value="{{ old('text') }}" 
                                   placeholder="Enter domain name in English"
                                   required>
                            @error('text')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="text_ar" class="form-label">
                                <i class="fas fa-globe"></i> Domain Name (Arabic)
                            </label>
                            <input type="text" 
                                   class="form-control @error('text_ar') is-invalid @enderror" 
                                   id="text_ar" 
                                   name="text_ar" 
                                   value="{{ old('text_ar') }}" 
                                   placeholder="أدخل اسم المجال بالعربية"
                                   dir="rtl">
                            @error('text_ar')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Certificate Selection Row -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="certificate_id" class="form-label required">
                                <i class="fas fa-certificate"></i> Certificate
                            </label>
                            <select class="form-control @error('certificate_id') is-invalid @enderror" 
                                    id="certificate_id" 
                                    name="certificate_id" 
                                    required>
                                <option value="">Select a certificate</option>
                                @foreach($certificates as $certificate)
                                    <option value="{{ $certificate->id }}" 
                                            {{ old('certificate_id') == $certificate->id ? 'selected' : '' }}
                                            data-color="{{ $certificate->color }}">
                                        {{ $certificate->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('certificate_id')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-palette"></i> Certificate Color Preview
                            </label>
                            <div id="certificate-color-preview" class="p-3 border rounded" style="min-height: 60px; background-color: #f8f9fa;">
                                <small class="text-muted">Select a certificate to see its color</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Row -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description" class="form-label required">
                                <i class="fas fa-align-left"></i> Description (English)
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Enter domain description in English"
                                      style="resize: vertical; max-height: 120px;" 
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description_ar" class="form-label">
                                <i class="fas fa-align-right"></i> Description (Arabic)
                            </label>
                            <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                      id="description_ar" 
                                      name="description_ar" 
                                      rows="4" 
                                      placeholder="أدخل وصف المجال بالعربية"
                                      dir="rtl"
                                      style="resize: vertical; max-height: 120px;">{{ old('description_ar') }}</textarea>
                            @error('description_ar')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Create Domain
                    </button>
                    <a href="{{ route('admin.domains') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-label.required::after {
    content: " *";
    color: #dc3545;
}

.form-actions {
    border-top: 1px solid #e3e6f0;
    padding-top: 1.5rem;
    display: flex;
    gap: 1rem;
}

.alert-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: #5a5c69;
    margin-bottom: 0.5rem;
    display: block;
}

.form-label i {
    margin-right: 0.5rem;
    color: #858796;
}

.form-control {
    border-radius: 0.35rem;
    border: 1px solid #d1d3e2;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const certificateSelect = document.getElementById('certificate_id');
    const colorPreview = document.getElementById('certificate-color-preview');
    
    certificateSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const color = selectedOption.dataset.color;
        
        if (color) {
            colorPreview.style.backgroundColor = color + '20';
            colorPreview.style.borderColor = color;
            colorPreview.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="me-2" style="width: 20px; height: 20px; background-color: ${color}; border-radius: 4px;"></div>
                    <span style="color: ${color}; font-weight: 600;">${selectedOption.text}</span>
                </div>
            `;
        } else {
            colorPreview.style.backgroundColor = '#f8f9fa';
            colorPreview.style.borderColor = '#dee2e6';
            colorPreview.innerHTML = '<small class="text-muted">Select a certificate to see its color</small>';
        }
    });
});
</script>
@endsection