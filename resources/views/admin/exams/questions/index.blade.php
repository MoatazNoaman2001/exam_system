@extends('layouts.admin')

@section('title', __('Manage Questions') . ' - ' . $exam->text)

@section('page-title', __('Manage Questions'))

@section('content')

<link rel="stylesheet" href="{{ asset('css/questions-index.css') }}">

<div class="dashboard-container">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="page-title">{{ __('Question Bank') }}</h1>
                <div class="breadcrumb-nav">
                    <a href="{{ route('admin.exams.index') }}" class="breadcrumb-link">{{ __('Exams') }}</a>
                    <i class="fas fa-chevron-right breadcrumb-separator"></i>
                    <span class="exam-title">{{ $exam->text }}</span>
                    <i class="fas fa-chevron-right breadcrumb-separator"></i>
                    <span class="current">{{ __('Questions') }}</span>
                </div>
                <div class="header-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $exam->examQuestions->count() }}</span>
                        <span class="stat-label">{{ __('Total Questions') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $exam->examQuestions->sum('marks') }}</span>
                        <span class="stat-label">{{ __('Total Points') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $exam->time }}</span>
                        <span class="stat-label">{{ __('Minutes') }}</span>
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.exams.questions.create', $exam->id) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('Add New Question') }}
                </a>
                <a href="{{ route('admin.exams.edit', $exam->id) }}" class="btn btn-success">
                    <i class="fas fa-edit"></i>
                    {{ __('Edit Exam Info') }}
                </a>
                <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
                    {{ __('Back to Exams') }}
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

    <!-- Exam Info Card -->
    <div class="info-card">
        <div class="card-header">
            <div class="card-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="card-title-section">
                <h3 class="card-title">{{ __('Exam Information') }}</h3>
                <p class="card-subtitle">{{ __('Overview of exam details and settings') }}</p>
            </div>
            <div class="card-status">
                @if($exam->examQuestions->count() > 0)
                    <span class="status-badge status-ready">
                        <i class="fas fa-check-circle"></i>
                        {{ __('Ready') }}
                    </span>
                @else
                    <span class="status-badge status-draft">
                        <i class="fas fa-edit"></i>
                        {{ __('Draft') }}
                    </span>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="exam-details-grid">
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-language"></i>
                    </div>
                    <div class="detail-content">
                        <label class="detail-label">{{ __('Title (EN)') }}</label>
                        <p class="detail-value">{{ $exam->text }}</p>
                    </div>
                </div>
                
                @if($exam->{'text-ar'})
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="detail-content">
                        <label class="detail-label">{{ __('Title (AR)') }}</label>
                        <p class="detail-value arabic-text">{{ $exam->{'text-ar'} }}</p>
                    </div>
                </div>
                @endif

                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="detail-content">
                        <label class="detail-label">{{ __('Duration') }}</label>
                        <p class="detail-value">{{ $exam->time }} {{ __('minutes') }}</p>
                    </div>
                </div>

                @if($exam->certificate)
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div class="detail-content">
                        <label class="detail-label">{{ __('Certificate') }}</label>
                        <p class="detail-value">
                            <span class="certificate-badge" style="background: {{ $exam->certificate->color }}20; color: {{ $exam->certificate->color }};">
                                {{ $exam->certificate->code }}
                            </span>
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Search and Controls -->
    <div class="controls-section">
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="{{ __('Search questions by text or answer options...') }}">
        </div>
        <div class="filters">
            <select class="filter-select" id="typeFilter">
                <option value="">{{ __('All Types') }}</option>
                <option value="single_choice">{{ __('Single Choice') }}</option>
                <option value="multiple_choice">{{ __('Multiple Choice') }}</option>
            </select>
            <select class="filter-select" id="pointsFilter">
                <option value="">{{ __('All Points') }}</option>
                <option value="1">1 {{ __('Point') }}</option>
                <option value="2">2 {{ __('Points') }}</option>
                <option value="3">3 {{ __('Points') }}</option>
                <option value="5">5 {{ __('Points') }}</option>
            </select>
            <div class="view-toggle">
                <button class="view-btn active" onclick="toggleView('cards')">
                    <i class="fas fa-th-large"></i>
                </button>
                <button class="view-btn" onclick="toggleView('list')">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>

    @if($exam->examQuestions->count() > 0)
        <!-- Questions Container -->
        <div class="questions-container" id="questionsContainer">
            @foreach($exam->examQuestions as $index => $question)
                <div class="question-card" 
                     data-type="{{ $question->type }}" 
                     data-points="{{ $question->marks }}">
                    <div class="question-header">
                        <div class="question-number">
                            <span class="number">{{ $index + 1 }}</span>
                        </div>
                        <div class="question-meta">
                            <div class="meta-badges">
                                <span class="badge badge-{{ $question->type === 'single_choice' ? 'primary' : 'info' }}">
                                    <i class="fas fa-{{ $question->type === 'single_choice' ? 'dot-circle' : 'check-square' }}"></i>
                                    {{ $question->type === 'single_choice' ? __('Single Choice') : __('Multiple Choice') }}
                                </span>
                                <span class="badge badge-points">
                                    <i class="fas fa-star"></i>
                                    {{ $question->marks }} {{ $question->marks == 1 ? __('Point') : __('Points') }}
                                </span>
                                <span class="badge badge-options">
                                    <i class="fas fa-list-ul"></i>
                                    {{ $question->answers->count() }} {{ __('Options') }}
                                </span>
                            </div>
                            <div class="question-actions">
                                <a href="{{ route('admin.exams.questions.edit', [$exam->id, $question->id]) }}" 
                                   class="action-btn btn-edit" 
                                   title="{{ __('Edit Question') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('admin.exams.questions.destroy', [$exam->id, $question->id]) }}" 
                                      method="POST" 
                                      style="display: inline;"
                                      onsubmit="return confirmDelete('{{ addslashes(Str::limit($question->question, 30)) }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="action-btn btn-delete" 
                                            title="{{ __('Delete Question') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-content">
                        <div class="question-text">
                            <h4 class="text-primary">{{ $question->question }}</h4>
                            @if($question->{'question-ar'})
                                <p class="text-secondary arabic-text">{{ $question->{'question-ar'} }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Answer Options -->
                    <div class="answers-section">
                        <h5 class="answers-title">
                            <i class="fas fa-list-ul"></i>
                            {{ __('Answer Options') }}
                        </h5>
                        <div class="answers-grid">
                            @foreach($question->answers as $answerIndex => $answer)
                                <div class="answer-option {{ $answer->is_correct ? 'correct' : '' }}">
                                    <div class="option-marker">
                                        <span class="option-letter">{{ chr(65 + $answerIndex) }}</span>
                                        @if($answer->is_correct)
                                            <div class="correct-indicator">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="option-content">
                                        <p class="option-text">{{ $answer->answer }}</p>
                                        @if($answer->{'answer-ar'})
                                            <p class="option-text-ar arabic-text">{{ $answer->{'answer-ar'} }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="question-footer">
                        <div class="created-info">
                            <i class="fas fa-calendar"></i>
                            <span>{{ __('Created') }}: {{ $question->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="correct-count">
                            <i class="fas fa-check-double"></i>
                            <span>{{ $question->answers->where('is_correct', true)->count() }} {{ __('correct answer(s)') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State for Filtered Results -->
        <div class="empty-state" id="emptyFilterState" style="display: none;">
            <div class="empty-icon">
                <i class="fas fa-search"></i>
            </div>
            <h2 class="empty-title">{{ __('No questions found') }}</h2>
            <p class="empty-text">{{ __('Try adjusting your search criteria or filters to find the questions you\'re looking for.') }}</p>
            <div class="empty-actions">
                <button class="btn btn-primary" onclick="clearFilters()">
                    <i class="fas fa-filter"></i>
                    {{ __('Clear Filters') }}
                </button>
            </div>
        </div>
    @else
        <!-- No Questions State -->
        <div class="empty-state" id="noQuestionsState">
            <div class="empty-icon">
                <i class="fas fa-question-circle"></i>
            </div>
            <h2 class="empty-title">{{ __('No Questions Added Yet') }}</h2>
            <p class="empty-text">{{ __('Start building your exam by adding questions. Each question can have multiple answer options with explanations.') }}</p>
            <div class="empty-actions">
                <a href="{{ route('admin.exams.questions.create', $exam->id) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('Add First Question') }}
                </a>
            </div>
        </div>
    @endif
</div>

<script src="{{ asset('js/questions-index.js') }}"></script>

@endsection