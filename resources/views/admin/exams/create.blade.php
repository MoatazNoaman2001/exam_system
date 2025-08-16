@extends('layouts.admin')

@section('title', __('exams.create.page_title'))

@section('page-title', __('exams.create.page_title'))

@section('content')

<link rel="stylesheet" href="{{ asset('css/exam-create.css') }}">
<div class="exam-create-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="page-title">{{ __('Create New Exam') }}</h1>
                <p class="page-subtitle">Start by setting up the basic exam information</p>
            </div>
            <div class="header-actions">
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

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="alert-header">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @endif

    <!-- Main Form -->
    <form method="POST" action="{{ route('admin.exams.store') }}" id="examForm" class="exam-form" novalidate>
        @csrf

        <!-- Basic Information Card -->
        <div class="form-card">
            <div class="card-header">
                <div class="card-header-content">
                    <i class="fas fa-info-circle"></i>
                    <h3 class="card-title">{{ __('Basic Information') }}</h3>
                </div>
            </div>
            <div class="card-body">
                <!-- Certificate Selection -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="certificate_id" class="form-label required">
                            {{ __('Certificate') }}
                        </label>
                        <select class="form-control @error('certificate_id') is-invalid @enderror" 
                                id="certificate_id" 
                                name="certificate_id" 
                                required>
                            <option value="">{{ __('Select a certificate...') }}</option>
                            @foreach($certificates as $certificate)
                                <option value="{{ $certificate->id }}" 
                                        {{ old('certificate_id') == $certificate->id ? 'selected' : '' }}
                                        data-color="{{ $certificate->color }}">
                                    {{ app()->getLocale() == 'ar' ? $certificate->name_ar : $certificate->name }} 
                                    ({{ $certificate->code }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-help">{{ __('Select the certificate this exam belongs to') }}</div>
                        @error('certificate_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Bilingual Titles -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="title_en" class="form-label required">
                            {{ __('Title (English)') }}
                        </label>
                        <input type="text" 
                               class="form-control @error('title_en') is-invalid @enderror"
                               id="title_en" 
                               name="title_en" 
                               value="{{ old('title_en') }}"
                               placeholder="{{ __('Enter exam title in English') }}" 
                               required>
                        @error('title_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="title_ar" class="form-label required">
                            {{ __('Title (Arabic)') }}
                        </label>
                        <input type="text" 
                               class="form-control @error('title_ar') is-invalid @enderror"
                               id="title_ar" 
                               name="title_ar" 
                               value="{{ old('title_ar') }}"
                               placeholder="{{ __('Enter exam title in Arabic') }}" 
                               dir="rtl" 
                               required>
                        @error('title_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Bilingual Descriptions -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="description_en" class="form-label">
                            {{ __('Description (English)') }}
                        </label>
                        <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                  id="description_en" 
                                  name="description_en"
                                  rows="4" 
                                  placeholder="{{ __('Enter exam description in English (optional)') }}">{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description_ar" class="form-label">
                            {{ __('Description (Arabic)') }}
                        </label>
                        <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                  id="description_ar" 
                                  name="description_ar"
                                  rows="4" 
                                  placeholder="{{ __('Enter exam description in Arabic (optional)') }}" 
                                  dir="rtl">{{ old('description_ar') }}</textarea>
                        @error('description_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Duration -->
                <div class="form-row">
                    <div class="form-group form-group-quarter">
                        <label for="duration" class="form-label required">
                            {{ __('Duration') }}
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control @error('duration') is-invalid @enderror"
                                   id="duration" 
                                   name="duration" 
                                   value="{{ old('duration', 30) }}" 
                                   min="1"
                                   max="300" 
                                   placeholder="30" 
                                   required>
                            <span class="input-group-text">{{ __('minutes') }}</span>
                        </div>
                        <div class="form-help">{{ __('Set the time limit for this exam (1-300 minutes)') }}</div>
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificate Preview Card (will appear when certificate is selected) -->
        <div class="form-card certificate-preview" id="certificatePreview" style="display: none;">
            <div class="card-header">
                <div class="card-header-content">
                    <i class="fas fa-certificate"></i>
                    <h3 class="card-title">{{ __('Selected Certificate') }}</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="certificate-info">
                    <div class="certificate-badge">
                        <div class="certificate-icon" id="certificateIcon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div class="certificate-details">
                            <h4 id="certificateName">-</h4>
                            <p id="certificateCode">-</p>
                            <p id="certificateDescription">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Steps Info -->
        <div class="form-card info-card">
            <div class="card-header">
                <div class="card-header-content">
                    <i class="fas fa-lightbulb"></i>
                    <h3 class="card-title">{{ __('What\'s Next?') }}</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="next-steps">
                    <div class="step">
                        <div class="step-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="step-content">
                            <h6>{{ __('Step 1: Create Exam') }}</h6>
                            <p>{{ __('Set up basic exam information (certificate, title, description, duration)') }}</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <div class="step-content">
                            <h6>{{ __('Step 2: Add Questions') }}</h6>
                            <p>{{ __('After creating the exam, you\'ll be redirected to add questions one by one') }}</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-icon">
                            <i class="fas fa-play"></i>
                        </div>
                        <div class="step-content">
                            <h6>{{ __('Step 3: Publish') }}</h6>
                            <p>{{ __('Once you\'ve added all questions, your exam will be ready for students') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Section -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-arrow-right"></i>
                {{ __('Create Exam & Add Questions') }}
            </button>
            <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i>
                {{ __('Cancel') }}
            </a>
        </div>
    </form>
</div>

<style>
.info-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
}

.certificate-preview {
    background: linear-gradient(135deg, #fff8f0 0%, #fff2e6 100%);
    border: 2px solid #ffc107;
}

.certificate-info {
    display: flex;
    justify-content: center;
}

.certificate-badge {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    max-width: 500px;
    width: 100%;
}

.certificate-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #ffc107;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.certificate-details h4 {
    margin: 0 0 0.25rem 0;
    color: #333;
    font-weight: 600;
}

.certificate-details p {
    margin: 0 0 0.25rem 0;
    color: #666;
    font-size: 0.9rem;
}

.certificate-details p:last-child {
    margin-bottom: 0;
}

.next-steps {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.step {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.step-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #0d6efd;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.step-content h6 {
    margin: 0 0 0.5rem 0;
    color: #333;
    font-weight: 600;
}

.step-content p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

@media (min-width: 768px) {
    .next-steps {
        flex-direction: row;
        align-items: flex-start;
    }
    
    .step {
        flex: 1;
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const certificateSelect = document.getElementById('certificate_id');
    const certificatePreview = document.getElementById('certificatePreview');
    const certificateIcon = document.getElementById('certificateIcon');
    const certificateName = document.getElementById('certificateName');
    const certificateCode = document.getElementById('certificateCode');
    const certificateDescription = document.getElementById('certificateDescription');

    // Certificate data from PHP
    const certificates = @json($certificates->keyBy('id'));

    certificateSelect.addEventListener('change', function() {
        const selectedId = this.value;
        
        if (selectedId && certificates[selectedId]) {
            const certificate = certificates[selectedId];
            const locale = '{{ app()->getLocale() }}';
            
            // Update preview
            certificateIcon.style.background = certificate.color || '#ffc107';
            certificateName.textContent = locale === 'ar' ? certificate.name_ar : certificate.name;
            certificateCode.textContent = certificate.code;
            certificateDescription.textContent = locale === 'ar' ? certificate.description_ar : certificate.description;
            
            // Show preview
            certificatePreview.style.display = 'block';
            certificatePreview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            // Hide preview
            certificatePreview.style.display = 'none';
        }
    });

    // Trigger change event if there's a pre-selected value
    if (certificateSelect.value) {
        certificateSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection