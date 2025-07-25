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

    /* Performance Diagram Styles */
    .performance-diagram {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .performance-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .performance-status {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--pmp-gray-800);
    }

    .status-badge {
        padding: 0.5rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1rem;
    }

    .status-passed {
        background: var(--pmp-success);
        color: white;
    }

    .status-failed {
        background: var(--pmp-danger);
        color: white;
    }

    .performance-bar-container {
        position: relative;
        height: 40px;
        background: var(--pmp-gray-200);
        border-radius: 20px;
        overflow: hidden;
        margin: 1.5rem 0;
    }

    .performance-sections {
        display: flex;
        height: 100%;
        position: relative;
    }

    .performance-section {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .section-need-improvement {
        background: #ff6b35;
    }

    .section-below-target {
        background: #f7b733;
    }

    .section-target {
        background: #20bf6b;
    }

    .section-above-target {
        background: #0fb9b1;
    }

    .performance-indicator {
        position: absolute;
        top: -10px;
        bottom: -10px;
        width: 4px;
        background: var(--pmp-gray-800);
        border-radius: 2px;
        z-index: 10;
    }

    .performance-indicator::after {
        content: attr(data-category);
        position: absolute;
        top: -35px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--pmp-gray-800);
        color: white;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .performance-indicator::before {
        content: '';
        position: absolute;
        top: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 6px solid var(--pmp-gray-800);
    }

    .performance-labels {
        display: flex;
        justify-content: space-between;
        margin-top: 1rem;
        font-size: 0.9rem;
        color: var(--pmp-gray-600);
    }

    .performance-explanation {
        margin-top: 1.5rem;
        padding: 1.5rem;
        background: var(--pmp-gray-50);
        border-radius: 0.5rem;
        border-left: 4px solid var(--pmp-primary);
    }

    /* Answer Explanation Styles */
    .answer-explanation {
        margin-top: 1rem;
        padding: 1rem;
        border-radius: 0.5rem;
        border-left: 4px solid;
    }

    .explanation-correct {
        background: #d1fae5;
        border-color: var(--pmp-success);
    }

    .explanation-incorrect {
        background: #fee2e2;
        border-color: var(--pmp-danger);
    }

    .explanation-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .explanation-text {
        margin-bottom: 0;
        line-height: 1.6;
    }

    /* Enhanced Answer Item Styles */
    .answer-item {
        padding: 1rem;
        margin-bottom: 0.75rem;
        border-radius: 0.75rem;
        border: 2px solid;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .answer-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .answer-correct {
        background: #d1fae5;
        border-color: var(--pmp-success);
    }

    .answer-selected-correct {
        background: #d1fae5;
        border-color: var(--pmp-success);
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2);
    }

    .answer-selected-incorrect {
        background: #fee2e2;
        border-color: var(--pmp-danger);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2);
    }

    .answer-unselected {
        background: white;
        border-color: var(--pmp-gray-200);
    }

    .answer-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .icon-correct {
        background: var(--pmp-success);
    }

    .icon-incorrect {
        background: var(--pmp-danger);
    }

    .icon-neutral {
        background: var(--pmp-gray-400);
    }

    .answer-content {
        flex: 1;
    }

    .answer-text {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: var(--pmp-gray-800);
    }

    .answer-label {
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .label-correct {
        color: var(--pmp-success);
    }

    .label-your-answer {
        color: var(--pmp-primary);
    }

    .label-wrong {
        color: var(--pmp-danger);
    }

    /* Rest of your existing styles... */
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
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .question-summary:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .question-header {
        background: var(--pmp-gray-50);
        padding: 1.25rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.3s ease;
    }

    .question-header:hover {
        background: var(--pmp-gray-100);
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
        margin-bottom: 1.5rem;
        color: var(--pmp-gray-700);
        line-height: 1.6;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .results-container {
            padding: 1rem;
        }

        .performance-sections {
            flex-direction: column;
            height: auto;
        }

        .performance-section {
            height: 40px;
        }

        .performance-indicator {
            display: none;
        }

        .answer-item {
            flex-direction: column;
            gap: 0.75rem;
        }

        .answer-icon {
            align-self: flex-start;
        }
    }
</style>

<div class="results-container" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Performance Diagram - Fully Localized -->
    <div class="performance-diagram">
        <div class="performance-header">
            <span class="performance-status">{{ __('lang.your_overall_performance') }}</span>
            <span class="status-badge {{ $results['statistics']['final_score'] >= 60 ? 'status-passed' : 'status-failed' }}">
                {{ $results['statistics']['final_score'] >= 60 ? __('lang.passed') : __('lang.failed') }}
            </span>
        </div>

        @if($results['statistics']['final_score'] >= 60)
            <p class="mb-3">{{ __('lang.you_have_passed_congratulations') }}</p>
        @else
            <p class="mb-3">{{ __('lang.you_did_not_pass_additional_preparation') }}</p>
        @endif

        <div class="performance-bar-container">
            <div class="performance-sections">
                <div class="performance-section section-need-improvement" style="width: 25%;">
                    {{ __('lang.need_improvement') }}
                    <small style="display: block; font-size: 0.7rem; opacity: 0.8;">{{ __('lang.need_improvement_range') }}</small>
                </div>
                <div class="performance-section section-below-target" style="width: 25%;">
                    {{ __('lang.below_target') }}
                    <small style="display: block; font-size: 0.7rem; opacity: 0.8;">{{ __('lang.below_target_range') }}</small>
                </div>
                <div class="performance-section section-target" style="width: 25%;">
                    {{ __('lang.target') }}
                    <small style="display: block; font-size: 0.7rem; opacity: 0.8;">{{ __('lang.target_range') }}</small>
                </div>
                <div class="performance-section section-above-target" style="width: 25%;">
                    {{ __('lang.above_target') }}
                    <small style="display: block; font-size: 0.7rem; opacity: 0.8;">{{ __('lang.above_target_range') }}</small>
                </div>
            </div>
            @php
                $score = $results['statistics']['final_score'];
                $position = min(100, max(0, $score));

                // Determine which category the user falls into
                $userCategory = '';
                if ($score >= 80) {
                    $userCategory = __('lang.above_target');
                } elseif ($score >= 60) {
                    $userCategory = __('lang.target');
                } elseif ($score >= 41) {
                    $userCategory = __('lang.below_target');
                } else {
                    $userCategory = __('lang.need_improvement');
                }
            @endphp
            <div class="performance-indicator" style="left: {{ $position }}%;" data-category="{{ $userCategory }}"></div>
        </div>

        <div class="performance-labels">
            <span>← {{ __('lang.failing') }}</span>
            <span>{{ __('lang.passing') }} →</span>
        </div>

        <div class="performance-explanation">
            <h5><i class="fas fa-info-circle me-2"></i>{{ __('lang.what_does_this_diagram_mean') }}</h5>
            <p class="mb-3">{{ __('lang.diagram_explanation') }}</p>

            <div class="row">
                <div class="col-md-12">
                    <strong>{{ __('lang.performance_rating_categories') }}</strong>
                    <ul class="mt-2">
                        <li><strong>{{ __('lang.above_target_label') }}</strong> {{ __('lang.above_target_description') }}</li>
                        <li><strong>{{ __('lang.target_label') }}</strong> {{ __('lang.target_description') }}</li>

                        <li><strong>{{ __('lang.below_target_label') }}</strong> {{ __('lang.below_target_description') }}</li>
                        <li><strong>{{ __('lang.need_improvement_label') }}</strong> {{ __('lang.need_improvement_description') }}</li>
                    </ul>
                </div>
            </div>

            @php
                $score = $results['statistics']['final_score'];
                $userStatus = '';
                $statusColor = '';
                $userMessage = '';

                if ($score >= 80) {
                    $userStatus = __('lang.above_target');
                    $statusColor = 'var(--pmp-success)';
                    $userMessage = __('lang.above_target_message');
                } elseif ($score >= 60) {
                    $userStatus = __('lang.target');
                    $statusColor = 'var(--pmp-success)';
                    $userMessage = __('lang.target_message');
                } elseif ($score >= 41) {
                    $userStatus = __('lang.below_target');
                    $statusColor = 'var(--pmp-warning)';
                    $userMessage = __('lang.below_target_message');
                } else {
                    $userStatus = __('lang.need_improvement');
                    $statusColor = 'var(--pmp-danger)';
                    $userMessage = __('lang.need_improvement_message_detailed');
                }
            @endphp

            <div class="mt-3 p-3 rounded" style="background: rgba(37, 99, 235, 0.1); border-left: 4px solid {{ $statusColor }};">
                <strong style="color: {{ $statusColor }};">{{ __('lang.your_performance') }}: {{ $userStatus }}</strong>
                <p class="mb-0 mt-1">{{ $userMessage }}</p>
            </div>

            <small class="text-muted mt-3 d-block">{{ __('lang.categories_disclaimer_prefix') }}{{ __('lang.categories_disclaimer') }}</small>
        </div>
    </div>

    <!-- Result Header with Score Circle -->
    <div class="result-header">
        <div class="score-circle" style="--score-angle: {{ ($results['statistics']['final_score'] / 100) * 360 }}deg;">
            <div class="score-inner">
                <div class="score-value">{{ number_format($results['statistics']['final_score'], 1) }}%</div>
                <div class="score-label">{{ __('lang.final_score') }}</div>
            </div>
        </div>

        <h2 class="mt-3">{{ $session->exam->title }}</h2>
        <p class="text-muted">{{ __('lang.completed_on') }}: {{ $session->completed_at->format('d M Y, H:i') }}</p>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-total" style="width: 60px; height: 60px; background: var(--pmp-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                <i class="fas fa-list-ol"></i>
            </div>
            <div class="stat-value" style="font-size: 2rem; font-weight: 700; color: var(--pmp-gray-800); margin-bottom: 0.5rem;">{{ $results['statistics']['total_questions'] }}</div>
            <div class="stat-label" style="color: var(--pmp-gray-600); font-weight: 500;">{{ __('lang.total_questions') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-correct" style="width: 60px; height: 60px; background: var(--pmp-success); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                <i class="fas fa-check"></i>
            </div>
            <div class="stat-value" style="font-size: 2rem; font-weight: 700; color: var(--pmp-gray-800); margin-bottom: 0.5rem;">{{ $results['statistics']['correct_answers'] }}</div>
            <div class="stat-label" style="color: var(--pmp-gray-600); font-weight: 500;">{{ __('lang.correct_answers') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-incorrect" style="width: 60px; height: 60px; background: var(--pmp-danger); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                <i class="fas fa-times"></i>
            </div>
            <div class="stat-value" style="font-size: 2rem; font-weight: 700; color: var(--pmp-gray-800); margin-bottom: 0.5rem;">{{ $results['statistics']['incorrect_answers'] }}</div>
            <div class="stat-label" style="color: var(--pmp-gray-600); font-weight: 500;">{{ __('lang.incorrect_answers') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-time" style="width: 60px; height: 60px; background: var(--pmp-info); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value" style="font-size: 2rem; font-weight: 700; color: var(--pmp-gray-800); margin-bottom: 0.5rem;">{{ gmdate('H:i:s', $results['statistics']['total_time_spent']) }}</div>
            <div class="stat-label" style="color: var(--pmp-gray-600); font-weight: 500;">{{ __('lang.time_spent') }}</div>
        </div>
    </div>

    <!-- Question by Question Results with Explanations -->
    <div class="detailed-results">
        <h3 class="section-title">
            <i class="fas fa-list-alt me-2"></i>
            {{ __('lang.detailed_results') }}
        </h3>

        <div id="questionResults">
            @foreach($results['results'] as $index => $result)
            <div class="question-summary">
                <div class="question-header" onclick="toggleQuestionDetails({{ $index }})">
                    <div class="question-title" style="font-weight: 600; color: var(--pmp-gray-800);">
                        {{ __('lang.question') }} {{ $result['question_number'] }}
                        @if($result['question']->type)
                        <small class="text-muted">({{ ucfirst(str_replace('_', ' ', $result['question']->type)) }})</small>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($result['answered'])
                            @if($result['is_correct'])
                            <span class="question-status" style="padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.875rem; font-weight: 500; background: #d1fae5; color: var(--pmp-success);">
                                <i class="fas fa-check me-1"></i>{{ __('lang.correct') }}
                            </span>
                            @else
                            <span class="question-status" style="padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.875rem; font-weight: 500; background: #fee2e2; color: var(--pmp-danger);">
                                <i class="fas fa-times me-1"></i>{{ __('lang.incorrect') }}
                            </span>
                            @endif
                        @else
                        <span class="question-status" style="padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.875rem; font-weight: 500; background: #fef3c7; color: var(--pmp-warning);">
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
                                // Get user's selected answers
                                $selectedAnswers = [];
                                if ($result['user_answer'] && $result['user_answer']->selected_answers) {
                                    $selectedAnswers = is_string($result['user_answer']->selected_answers) 
                                        ? json_decode($result['user_answer']->selected_answers, true) ?? []
                                        : $result['user_answer']->selected_answers;
                                }

                                $isCorrect = $answer->is_correct;
                                $isSelected = in_array($answer->uuid, $selectedAnswers) || in_array($answer->id, $selectedAnswers);
                                
                                // Determine answer styling
                                $answerClass = '';
                                $iconClass = '';
                                $labelClass = '';
                                $labelText = '';
                                
                                if ($isCorrect) {
                                    $answerClass = $isSelected ? 'answer-selected-correct' : 'answer-correct';
                                    $iconClass = 'icon-correct';
                                    $labelClass = 'label-correct';
                                    $labelText = __('lang.correct_answer');
                                } elseif ($isSelected) {
                                    $answerClass = 'answer-selected-incorrect';
                                    $iconClass = 'icon-incorrect';
                                    $labelClass = 'label-wrong';
                                    $labelText = __('lang.your_answer');
                                } else {
                                    $answerClass = 'answer-unselected';
                                    $iconClass = 'icon-neutral';
                                }
                            @endphp

                            <div class="answer-item {{ $answerClass }}">
                                <div class="answer-icon {{ $iconClass }}">
                                    @if($isCorrect)
                                        <i class="fas fa-check"></i>
                                    @elseif($isSelected)
                                        <i class="fas fa-user"></i>
                                    @else
                                        <i class="fas fa-circle"></i>
                                    @endif
                                </div>
                                <div class="answer-content">
                                    @if($labelText)
                                        <div class="answer-label {{ $labelClass }}">{{ $labelText }}</div>
                                    @endif
                                    <div class="answer-text">
                                        {{ app()->getLocale() === 'ar' ? ($answer->{'answer-ar'} ?? $answer->answer) : $answer->answer }}
                                    </div>
                                    
                                    {{-- Show explanation if answer is correct or was selected incorrectly --}}
                                    @if(($isCorrect || $isSelected) && ($answer->reason || $answer->{'reason-ar'}))
                                        <div class="answer-explanation {{ $isCorrect ? 'explanation-correct' : 'explanation-incorrect' }}">
                                            <div class="explanation-title">
                                                @if($isCorrect)
                                                    <i class="fas fa-lightbulb"></i>
                                                    {{ __('lang.why_this_is_correct') }}
                                                @else
                                                    <i class="fas fa-info-circle"></i>
                                                    {{ __('lang.why_this_is_incorrect') }}
                                                @endif
                                            </div>
                                            <div class="explanation-text">
                                                {{ app()->getLocale() === 'ar' ? ($answer->{'reason-ar'} ?? $answer->reason) : $answer->reason }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($result['answered'])
                    <div class="time-spent" style="font-size: 0.875rem; color: var(--pmp-gray-600); margin-top: 1rem;">
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
    <div class="action-buttons" style="display: flex; gap: 1rem; justify-content: center; margin-top: 3rem; flex-wrap: wrap;">
        <a href="{{ route('student.exams.index') }}" class="btn-action btn-secondary" style="padding: 0.75rem 2rem; border: none; border-radius: 0.75rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; background: var(--pmp-gray-600); color: white;">
            <i class="fas fa-arrow-left"></i>
            {{ __('lang.back_to_exams') }}
        </a>

        @if($results['statistics']['final_score'] < 60)
            <a href="{{ route('student.exams.show', $session->exam->id) }}" class="btn-action btn-success" style="padding: 0.75rem 2rem; border: none; border-radius: 0.75rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; background: var(--pmp-success); color: white;">
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

        // Animate performance indicator
        const indicator = document.querySelector('.performance-indicator');
        if (indicator) {
            indicator.style.opacity = '0';
            indicator.style.transform = 'translateX(-20px)';
            setTimeout(() => {
                indicator.style.transition = 'all 1s ease';
                indicator.style.opacity = '1';
                indicator.style.transform = 'translateX(0)';
            }, 500);
        }

        // Animate score circle
        const scoreCircle = document.querySelector('.score-circle');
        if (scoreCircle) {
            scoreCircle.style.background = 'conic-gradient(var(--pmp-success) 0deg, var(--pmp-success) 0deg, var(--pmp-gray-200) 0deg)';
            setTimeout(() => {
                scoreCircle.style.transition = 'background 2s ease-in-out';
                const scoreAngle = {{ ($results['statistics']['final_score'] / 100) * 360 }};
                scoreCircle.style.background = `conic-gradient(var(--pmp-success) 0deg, var(--pmp-success) ${scoreAngle}deg, var(--pmp-gray-200) ${scoreAngle}deg)`;
            }, 800);
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

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch (e.key) {
                case 'r':
                    e.preventDefault();
                    location.reload();
                    break;
                case 'b':
                    e.preventDefault();
                    window.location.href = '{{ route("student.exams.index") }}';
                    break;
            }
        }
    });
</script>

@endsection