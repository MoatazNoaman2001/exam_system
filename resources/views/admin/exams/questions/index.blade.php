@extends('layouts.admin')

@section('title', __('Manage Questions') . ' - ' . $exam->text)

@section('page-title', __('Manage Questions'))

@section('content')

<link rel="stylesheet" href="{{ asset('css/exam-index.css') }}">
<div class="exams-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="page-title">{{ __('Manage Questions') }}</h1>
                <div class="breadcrumb">
                    <span class="exam-title">{{ $exam->text }}</span>
                    <span class="separator">/</span>
                    <span class="current">{{ __('Questions') }}</span>
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

    <!-- Exam Info Summary -->
    <div class="form-card info-card mb-4">
        <div class="card-header">
            <div class="card-header-content">
                <i class="fas fa-info-circle"></i>
                <h3 class="card-title">{{ __('Exam Information') }}</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="exam-summary">
                <div class="summary-item">
                    <strong>{{ __('Title (EN)') }}:</strong> {{ $exam->text }}
                </div>
                <div class="summary-item">
                    <strong>{{ __('Title (AR)') }}:</strong> {{ $exam->{'text-ar'} }}
                </div>
                <div class="summary-item">
                    <strong>{{ __('Duration') }}:</strong> {{ $exam->time }} {{ __('minutes') }}
                </div>
                <div class="summary-item">
                    <strong>{{ __('Total Questions') }}:</strong> {{ $exam->examQuestions->count() }}
                </div>
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

    <!-- Questions Table Card -->
    <div class="table-card">
        <div class="card-header">
            <div class="card-header-content">
                <i class="fas fa-question-circle"></i>
                <h3 class="card-title">{{ __('Questions') }} ({{ $exam->examQuestions->count() }})</h3>
            </div>
        </div>
        <div class="card-body">
            @if($exam->examQuestions->count() > 0)
                <div class="questions-list">
                    @foreach($exam->examQuestions as $index => $question)
                        <div class="question-item">
                            <div class="question-header">
                                <div class="question-info">
                                    <div class="question-number">
                                        <span class="number">{{ $index + 1 }}</span>
                                    </div>
                                    <div class="question-content">
                                        <h5 class="question-text">{{ Str::limit($question->question, 100) }}</h5>
                                        <p class="question-text-ar">{{ Str::limit($question->{'question-ar'}, 100) }}</p>
                                        <div class="question-meta">
                                            <span class="badge badge-{{ $question->type === 'single_choice' ? 'primary' : 'info' }}">
                                                {{ $question->type === 'single_choice' ? __('Single Choice') : __('Multiple Choice') }}
                                            </span>
                                            <span class="badge badge-secondary">
                                                {{ $question->marks }} {{ __('Points') }}
                                            </span>
                                            <span class="badge badge-success">
                                                {{ $question->answers->count() }} {{ __('Options') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="question-actions">
                                    <a href="{{ route('admin.exams.questions.edit', [$exam->id, $question->id]) }}" 
                                       class="btn btn-action btn-edit" 
                                       title="{{ __('Edit Question') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.exams.questions.destroy', [$exam->id, $question->id]) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirmDelete('{{ Str::limit($question->question, 30) }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-action btn-delete" 
                                                title="{{ __('Delete Question') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Answer Options Preview -->
                            <div class="question-answers">
                                <h6>{{ __('Answer Options') }}:</h6>
                                <div class="answers-list">
                                    @foreach($question->answers as $answerIndex => $answer)
                                        <div class="answer-option {{ $answer->is_correct ? 'correct' : '' }}">
                                            <div class="option-marker">
                                                {{ chr(65 + $answerIndex) }}
                                                @if($answer->is_correct)
                                                    <i class="fas fa-check correct-icon"></i>
                                                @endif
                                            </div>
                                            <div class="option-content">
                                                <div class="option-text">{{ $answer->answer }}</div>
                                                <div class="option-text-ar">{{ $answer->{'answer-ar'} }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h4 class="empty-title">{{ __('No Questions Added Yet') }}</h4>
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
    </div>
</div>

<style>
.exam-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.summary-item {
    padding: 0.75rem;
    background: white;
    border-radius: 6px;
    border: 1px solid #e0e0e0;
}

.breadcrumb {
    font-size: 0.9rem;
    color: #666;
    margin-top: 0.5rem;
}

.separator {
    margin: 0 0.5rem;
}

.questions-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.question-item {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background: white;
    overflow: hidden;
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1.5rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    min-height: 80px;
}

.question-info {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    flex: 1;
}

.question-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #0d6efd;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    flex-shrink: 0;
    font-size: 1rem;
}

.question-content {
    flex: 1;
}

.question-text {
    margin: 0 0 0.5rem 0;
    font-weight: 600;
    color: #333;
}

.question-text-ar {
    margin: 0 0 1rem 0;
    color: #666;
    font-style: italic;
    direction: rtl;
}

.question-meta {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.question-actions {
    display: flex;
    gap: 0.75rem;
    flex-shrink: 0;
    align-items: center;
}

.btn-action {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    cursor: pointer;
    text-decoration: none;
}

.btn-edit {
    background: #0d6efd;
    color: white;
}

.btn-edit:hover {
    background: #0b5ed7;
    color: white;
    transform: scale(1.05);
}

.btn-delete {
    background: #dc3545;
    color: white;
}

.btn-delete:hover {
    background: #bb2d3b;
    color: white;
    transform: scale(1.05);
}

.question-answers {
    padding: 1.5rem;
}

.question-answers h6 {
    margin: 0 0 1rem 0;
    color: #333;
    font-weight: 600;
}

.answers-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.answer-option {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 0.75rem;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    background: #f9f9f9;
}

.answer-option.correct {
    background: #d4edda;
    border-color: #c3e6cb;
}

.option-marker {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #6c757d;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    flex-shrink: 0;
    position: relative;
    font-size: 0.85rem;
}

.answer-option.correct .option-marker {
    background: #28a745;
}

.correct-icon {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #28a745;
    border-radius: 50%;
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
}

.option-content {
    flex: 1;
}

.option-text {
    margin: 0 0 0.25rem 0;
    color: #333;
}

.option-text-ar {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
    direction: rtl;
}

.info-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
}

@media (max-width: 768px) {
    .question-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .question-actions {
        width: 100%;
        justify-content: flex-end;
        margin-top: 1rem;
    }
    
    .question-info {
        width: 100%;
    }
    
    .exam-summary {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function confirmDelete(questionText) {
    return confirm(`Are you sure you want to delete this question: "${questionText}"?\n\nThis action cannot be undone.`);
}
</script>
@endsection