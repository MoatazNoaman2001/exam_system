@extends('layouts.admin')

@section('title', __('Edit Exam'))

@section('page-title', __('Edit Exam'))

@section('content')

<link rel="stylesheet" href="{{ asset('css/exam-create.css') }}">
<div class="exam-create-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="page-title">{{ __('Edit Exam') }}</h1>
                <div class="breadcrumb">
                    <span class="exam-id">{{ __('Exam ID') }}: {{ Str::limit($exam->id, 8) }}</span>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.exams.questions.index', $exam->id) }}" class="btn btn-success">
                    <i class="fas fa-question-circle"></i>
                    {{ __('Manage Questions') }}
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
    <form method="POST" action="{{ route('admin.exams.update', $exam->id) }}" id="examForm" class="exam-form" novalidate>
        @csrf
        @method('PUT')

        <!-- Basic Information Card -->
        <div class="form-card">
            <div class="card-header">
                <div class="card-header-content">
                    <i class="fas fa-info-circle"></i>
                    <h3 class="card-title">{{ __('Basic Information') }}</h3>
                </div>
            </div>
            <div class="card-body">
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
                               value="{{ old('title_en', $exam->text) }}"
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
                               value="{{ old('title_ar', $exam->{'text-ar'}) }}"
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
                                  placeholder="{{ __('Enter exam description in English (optional)') }}">{{ old('description_en', $exam->description) }}</textarea>
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
                                  dir="rtl">{{ old('description_ar', $exam->{'description-ar'}) }}</textarea>
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
                                   value="{{ old('duration', $exam->time) }}" 
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

        <!-- Exam Statistics -->
        <div class="form-card info-card">
            <div class="card-header">
                <div class="card-header-content">
                    <i class="fas fa-chart-bar"></i>
                    <h3 class="card-title">{{ __('Exam Statistics') }}</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h4>{{ $exam->number_of_questions }}</h4>
                            <p>{{ __('Questions') }}</p>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="stat-content">
                            <h4>{{ $exam->created_at->format('M d, Y') }}</h4>
                            <p>{{ __('Created') }}</p>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="stat-content">
                            <h4>{{ $exam->updated_at->format('M d, Y') }}</h4>
                            <p>{{ __('Last Modified') }}</p>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-{{ $exam->is_completed ? 'check-circle' : 'play-circle' }}"></i>
                        </div>
                        <div class="stat-content">
                            <h4>{{ $exam->is_completed ? __('Completed') : __('Active') }}</h4>
                            <p>{{ __('Status') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Section -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i>
                {{ __('Update Exam') }}
            </button>
            <a href="{{ route('admin.exams.questions.index', $exam->id) }}" class="btn btn-success btn-lg">
                <i class="fas fa-question-circle"></i>
                {{ __('Manage Questions') }}
            </a>
            <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i>
                {{ __('Cancel') }}
            </a>
        </div>
    </form>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: white;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #0d6efd;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.2rem;
}

.stat-content h4 {
    margin: 0 0 0.25rem 0;
    color: #333;
    font-weight: 600;
    font-size: 1.5rem;
}

.stat-content p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.info-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
}
</style>
@endsection