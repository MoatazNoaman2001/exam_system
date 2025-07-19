<div>
    <!-- Smile, breathe, and go slowly. - Thich Nhat Hanh -->
</div>
@extends('layouts.app')

@section('title', __('lang.exam_results') . ' - ' . $session->exam->title)

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    :root {
        --pmp-primary: #2563eb;
        --pmp-success: #059669;
        --pmp-warning: #d97706;
        --pmp-danger: #dc2626;
        --pmp-info: #0891b2;
        --pmp-gray-50: #f9fafb;
        --pmp-gray-100: #f3f4f6;
        --pmp-gray-200: #e5e7eb;
        --pmp-gray-700: #374151;
        --pmp-gray-800: #1f2937;
        --pmp-gray-900: #111827;
    }

    body {
        font-family: 'Tajawal', 'Cairo', sans-serif;
        background-color: var(--pmp-gray-50);
    }

    .results-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .result-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .score-circle {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        position: relative;
        background: conic-gradient(var(--pmp-success) 0deg, var(--pmp-success) var(--score-angle, 0deg), var(--pmp-gray-200) var(--score-angle, 0deg));
    }

    .score-inner {
        width: 160px;
        height: 160px;
        background: white;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .score-value {
        font-size: 3rem;
        font-weight: 700;
        color: var(--pmp-gray-800);
    }

    .score-label {
        font-size: 1rem;
        color: var(--pmp-gray-600);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .result-status {
        padding: 1rem 2rem;
        border-radius: 50px;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 2rem;
        display: inline-block;
    }

    .status-excellent {
        background: var(--pmp-success);
        color: white;
    }

    .status-good {
        background: var(--pmp-primary);
        color: white;
    }

    .status-needs-improvement {
        background: var(--pmp-warning);
        color: white;
    }

    .status-failed {
        background: var(--pmp-danger);
        color: white;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--pmp-gray-200);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--pmp-gray-800);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--pmp-gray-600);
        font-weight: 500;
    }

    .icon-total {
        background: var(--pmp-primary);
    }

    .icon-correct {
        background: var(--pmp-success);
    }

    .icon-incorrect {
        background: var(--pmp-danger);
    }

    .icon-unanswered {
        background: var(--pmp-warning);
    }

    .icon-time {
        background: var(--pmp-info);
    }

    .icon-accuracy {
        background: var(--pmp-primary);
    }

    .detailed-results {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--pmp-gray-800);
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--pmp-gray-200);
    }

    .question-summary {
        border: 1px solid var(--pmp-gray-200);
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .question-header {
        background: var(--pmp-gray-50);
        padding: 1rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .question-header:hover {
        background: var(--pmp-gray-100);
    }

    .question-title {
        font-weight: 600;
        color: var(--pmp-gray-800);
    }

    .question-status {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-correct {
        background: #d1fae5;
        color: var(--pmp-success);
    }

    .status-incorrect {
        background: #fee2e2;
        color: var(--pmp-danger);
    }

    .status-not-answered {
        background: #fef3c7;
        color: var(--pmp-warning);
    }

    .question-details {
        padding: 1.5rem;
        border-top: 1px solid var(--pmp-gray-200);
        display: none;
    }

    .question-details.show {
        display: block;
    }

    .question-text {
        margin-bottom: 1rem;
        color: var(--pmp-gray-700);
        line-height: 1.6;
    }

    .answers-list {
        margin: 1rem 0;
    }

    .answer-item {
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-radius: 0.5rem;
        border: 1px solid var(--pmp-gray-200);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .answer-correct {
        background: #d1fae5;
        border-color: var(--pmp-success);
    }

    .answer-selected {
        background: #dbeafe;
        border-color: var(--pmp-primary);
    }

    .answer-wrong {
        background: #fee2e2;
        border-color: var(--pmp-danger);
    }

    .answer-icon {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        color: white;
        flex-shrink: 0;
    }

    .icon-check {
        background: var(--pmp-success);
    }

    .icon-cross {
        background: var(--pmp-danger);
    }

    .icon-selected {
        background: var(--pmp-primary);
    }

    .time-spent {
        font-size: 0.875rem;
        color: var(--pmp-gray-600);
        margin-top: 0.5rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 3rem;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: var(--pmp-primary);
        color: white;
    }

    .btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
    }

    .btn-success {
        background: var(--pmp-success);
        color: white;
    }

    .btn-success:hover {
        background: #047857;
        transform: translateY(-2px);
    }

    .btn-info {
        background: var(--pmp-info);
        color: white;
    }

    .btn-info:hover {
        background: #0e7490;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: var(--pmp-gray-600);
        color: white;
    }

    .btn-secondary:hover {
        background: var(--pmp-gray-700);
        transform: translateY(-2px);
    }

    .recommendations {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .recommendation-item {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid;
    }

    .recommendation-success {
        background: #d1fae5;
        border-color: var(--pmp-success);
    }

    .recommendation-warning {
        background: #fef3c7;
        border-color: var(--pmp-warning);
    }

    .recommendation-danger {
        background: #fee2e2;
        border-color: var(--pmp-danger);
    }

    .recommendation-info {
        background: #e0f2fe;
        border-color: var(--pmp-info);
    }

    @media (max-width: 768px) {
        .results-container {
            padding: 1rem;
        }

        .score-circle {
            width: 150px;
            height: 150px;
        }

        .score-inner {
            width: 120px;
            height: 120px;
        }

        .score-value {
            font-size: 2rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
            align-items: center;
        }

        .btn-action {
            width: 100%;
            max-width: 300px;
            justify-content: center;
        }
    }
</style>

<div class="results-container" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Result Header -->
    <div class="result-header">
        <div class="score-circle" style="--score-angle: {{ ($results['statistics']['final_score'] / 100) * 360 }}deg;">
            <div class="score-inner">
                <div class="score-value">{{ number_format($results['statistics']['final_score'], 1) }}%</div>
                <div class="score-label">{{ __('lang.final_score') }}</div>
            </div>
        </div>

        @php
        $scoreStatus = 'failed';
        $statusText = __('lang.failed');
        if ($results['statistics']['final_score'] >= 85) {
        $scoreStatus = 'excellent';
        $statusText = __('lang.excellent_performance');
        } elseif ($results['statistics']['final_score'] >= 70) {
        $scoreStatus = 'good';
        $statusText = __('lang.good_performance');
        } elseif ($results['statistics']['final_score'] >= 60) {
        $scoreStatus = 'needs-improvement';
        $statusText = __('lang.needs_improvement');
        }
        @endphp

        <div class="result-status status-{{ $scoreStatus }}">
            <i class="fas fa-{{ $scoreStatus === 'excellent' ? 'trophy' : ($scoreStatus === 'good' ? 'medal' : ($scoreStatus === 'needs-improvement' ? 'exclamation-triangle' : 'times-circle')) }} me-2"></i>
            {{ $statusText }}
        </div>

        <h2 class="mt-3">{{ $session->exam->title }}</h2>
        <p class="text-muted">{{ __('lang.completed_on') }}: {{ $session->completed_at->format('d M Y, H:i') }}</p>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-total">
                <i class="fas fa-list-ol"></i>
            </div>
            <div class="stat-value">{{ $results['statistics']['total_questions'] }}</div>
            <div class="stat-label">{{ __('lang.total_questions') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-correct">
                <i class="fas fa-check"></i>
            </div>
            <div class="stat-value">{{ $results['statistics']['correct_answers'] }}</div>
            <div class="stat-label">{{ __('lang.correct_answers') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-incorrect">
                <i class="fas fa-times"></i>
            </div>
            <div class="stat-value">{{ $results['statistics']['incorrect_answers'] }}</div>
            <div class="stat-label">{{ __('lang.incorrect_answers') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-unanswered">
                <i class="fas fa-question"></i>
            </div>
            <div class="stat-value">{{ $results['statistics']['unanswered_questions'] }}</div>
            <div class="stat-label">{{ __('lang.unanswered_questions') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-time">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">{{ gmdate('H:i:s', $results['statistics']['total_time_spent']) }}</div>
            <div class="stat-label">{{ __('lang.time_spent') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-accuracy">
                <i class="fas fa-percentage"></i>
            </div>
            <div class="stat-value">{{ number_format($results['statistics']['accuracy_percentage'], 1) }}%</div>
            <div class="stat-label">{{ __('lang.accuracy') }}</div>
        </div>
    </div>

    <!-- Recommendations Section -->
    @if(isset($recommendations) && count($recommendations) > 0)
    <div class="recommendations">
        <h3 class="section-title">
            <i class="fas fa-lightbulb me-2"></i>
            {{ __('lang.recommendations') }}
        </h3>

        @foreach($recommendations as $recommendation)
        <div class="recommendation-item recommendation-{{ $recommendation['type'] }}">
            <h5>
                <i class="fas fa-{{ $recommendation['type'] === 'success' ? 'check-circle' : ($recommendation['type'] === 'warning' ? 'exclamation-triangle' : ($recommendation['type'] === 'danger' ? 'times-circle' : 'info-circle')) }} me-2"></i>
                {{ $recommendation['title'] }}
            </h5>
            <p class="mb-0">{{ $recommendation['message'] }}</p>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Question by Question Results -->
    <div class="detailed-results">
        <h3 class="section-title">
            <i class="fas fa-list-alt me-2"></i>
            {{ __('lang.detailed_results') }}
        </h3>

        <div id="questionResults">
            @foreach($results['results'] as $index => $result)
            <div class="question-summary">
                <div class="question-header" onclick="toggleQuestionDetails({{ $index }})">
                    <div class="question-title">
                        {{ __('lang.question') }} {{ $result['question_number'] }}
                        @if($result['question']->type)
                        <small class="text-muted">({{ ucfirst(str_replace('_', ' ', $result['question']->type)) }})</small>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($result['answered'])
                        @if($result['is_correct'])
                        <span class="question-status status-correct">
                            <i class="fas fa-check me-1"></i>{{ __('lang.correct') }}
                        </span>
                        @else
                        <span class="question-status status-incorrect">
                            <i class="fas fa-times me-1"></i>{{ __('lang.incorrect') }}
                        </span>
                        @endif
                        @else
                        <span class="question-status status-not-answered">
                            <i class="fas fa-question me-1"></i>{{ __('lang.not_answered') }}
                        </span>
                        @endif
                        <i class="fas fa-chevron-down toggle-icon"></i>
                    </div>
                </div>

                <div class="question-details" id="details-{{ $index }}">
                    <div class="question-text">
                        <strong>{{ __('lang.question') }}:</strong><br>
                        {!! nl2br(e($result['question']->question_text)) !!}
                    </div>

                    <div class="answers-list">
                        <strong>{{ __('lang.answers') }}:</strong>
                    @foreach($result['question']->answers as $answer)
                        @php
                            $isCorrect = in_array($answer->id, $result['correct_answers']);

                            $selectedAnswers = [];
                            if ($result['user_answer'] && is_string($result['user_answer']->selected_answers)) {
                            $selectedAnswers = json_decode($result['user_answer']->selected_answers, true) ?? [];
                            }

                            $isSelected = in_array($answer->id, $selectedAnswers);

                            $answerClass = '';
                            $iconClass = '';
                            if ($isCorrect) {
                            $answerClass = 'answer-correct';
                            $iconClass = 'icon-check';
                            } elseif ($isSelected && !$isCorrect) {
                            $answerClass = 'answer-wrong';
                            $iconClass = 'icon-cross';
                            }
                        @endphp



                        <div class="answer-item {{ $answerClass }}">
                            @if($iconClass)
                            <div class="answer-icon {{ $iconClass }}">
                                <i class="fas fa-{{ $isCorrect ? 'check' : ($isSelected ? 'user' : 'times') }}"></i>
                            </div>
                            @endif
                            <div class="flex-grow-1">
                                {{ $answer->answer_text }}
                                @if($isCorrect)
                                <small class="text-success d-block">{{ __('lang.correct_answer') . ' : ' . $answer->answer }} </small>
                                @elseif($isSelected && !$isCorrect)
                                <small class="text-danger d-block">{{ __('lang.your_answer') . ' : ' . $answer->answer }}</small>
                                @elseif($isSelected)
                                <small class="text-primary d-block">{{ __('lang.your_answer') . ' : ' . $answer->answer }}</small>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($result['answered'])
                    <div class="time-spent">
                        <i class="fas fa-clock me-1"></i>
                        {{ __('lang.time_spent_on_question') }}: {{ gmdate('i:s', $result['time_spent']) }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('student.exams.index') }}" class="btn-action btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('lang.back_to_exams') }}
        </a>

        @if($results['statistics']['final_score'] < 70)
            <a href="{{ route('student.exams.index', $session->exam->id) }}" class="btn-action btn-success">
            <i class="fas fa-redo"></i>
            {{ __('lang.retake_exam') }}
            </a>
            @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleQuestionDetails(index) {
        const details = document.getElementById(`details-${index}`);
        const icon = details.parentElement.querySelector('.toggle-icon');

        if (details.classList.contains('show')) {
            details.classList.remove('show');
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        } else {
            details.classList.add('show');
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        }
    }

    // Auto-expand first few incorrect answers
    document.addEventListener('DOMContentLoaded', function() {
        const incorrectQuestions = document.querySelectorAll('.status-incorrect, .status-not-answered');

        // Expand first 3 incorrect/unanswered questions
        for (let i = 0; i < Math.min(3, incorrectQuestions.length); i++) {
            const questionHeader = incorrectQuestions[i].closest('.question-header');
            const index = Array.from(document.querySelectorAll('.question-header')).indexOf(questionHeader);
            if (index !== -1) {
                toggleQuestionDetails(index);
            }
        }
    });

    // Add smooth scroll to question details
    document.querySelectorAll('.question-header').forEach(header => {
        header.addEventListener('click', function() {
            setTimeout(() => {
                this.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }, 300);
        });
    });

    // Print functionality
    function printResults() {
        window.print();
    }

    // Share results functionality
    function shareResults() {
        const shareData = {
            title: '{{ __("lang.exam_results") }} - {{ $session->exam->title }}',
            text: `{{ __("lang.scored") }} {{ number_format($results['statistics']['final_score'], 1) }}% {{ __("lang.in_exam") }}`,
            url: window.location.href
        };

        if (navigator.share) {
            navigator.share(shareData);
        } else {
            // Fallback - copy to clipboard
            navigator.clipboard.writeText(`${shareData.title}\n${shareData.text}\n${shareData.url}`);
            alert('{{ __("lang.results_copied_to_clipboard") }}');
        }
    }

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch (e.key) {
                case 'p':
                    e.preventDefault();
                    printResults();
                    break;
                case 'r':
                    e.preventDefault();
                    window.location.href = '{{ route("student.exams.review", $session->id) }}';
                    break;
                case 'b':
                    e.preventDefault();
                    window.location.href = '{{ route("student.exams.index") }}';
                    break;
            }
        }
    });

    // Add animation effects
    window.addEventListener('load', function() {
        // Animate score circle
        const scoreCircle = document.querySelector('.score-circle');
        scoreCircle.style.background = 'conic-gradient(var(--pmp-success) 0deg, var(--pmp-success) 0deg, var(--pmp-gray-200) 0deg)';

        setTimeout(() => {
            scoreCircle.style.transition = 'background 2s ease-in-out';
            scoreCircle.style.background = `conic-gradient(var(--pmp-success) 0deg, var(--pmp-success) {{ ($results['statistics']['final_score'] / 100) * 360 }}deg, var(--pmp-gray-200) {{ ($results['statistics']['final_score'] / 100) * 360 }}deg)`;
        }, 500);

        // Animate stat cards
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 200 * index);
        });
    });
</script>

<style media="print">
    .action-buttons {
        display: none !important;
    }

    .question-details {
        display: block !important;
    }

    .toggle-icon {
        display: none !important;
    }

    .question-header {
        cursor: default !important;
    }

    .question-header:hover {
        background: var(--pmp-gray-50) !important;
    }
</style>
@endsection