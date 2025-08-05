@extends('layouts.app')

@section('title', $session->exam->title . ' - ' . __('lang.exam'))

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/examTake.css') }}">

<div class="exam-container" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Exam Header -->
    <div class="page-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-6">
                    <h4 class="page-title">
                        <span class="icon"><i class="fas fa-file-alt"></i></span>
                        {{ $session->exam->title }}
                    </h4>
                </div>
                <div class="col-lg-8 col-md-6">
                    <div class="progress-info">
                        <div class="timer-display" id="examTimer">
                            <i class="fas fa-clock"></i>
                            <span id="timeRemaining">{{ gmdate('H:i:s', $progress['remaining_time']) }}</span>
                        </div>
                        <div class="progress-bar-container">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted">{{ __('lang.progress') }}</small>
                                <small class="text-muted">
                                    <span id="answeredCount">{{ $progress['answered_count'] }}</span> / 
                                    <span id="totalQuestions">{{ $progress['total_questions'] }}</span>
                                </small>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" id="progressFill" 
                                     style="width: {{ $progress['progress_percentage'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Banner -->
    <div id="statusBanner" class="notification hidden">
        <div class="status-content">
            <i class="fas fa-info-circle"></i>
            <span id="statusMessage"></span>
        </div>
    </div>

    <!-- Exam Body -->
    <div class="exam-body">
        <!-- Question Panel -->
        <div class="question-panel settings-card">
            <div class="question-meta">
                <div class="question-timer" id="questionTimer">
                    <i class="fas fa-stopwatch me-1"></i>
                    <span id="questionTime">00:00</span>
                </div>
                <div class="auto-save-status" id="autoSaveStatus">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>{{ __('lang.auto_saved') }}</span>
                </div>
            </div>

            <div class="question-header">
                <span class="question-number">
                    {{ __('lang.question') }} {{ $progress['current_index'] + 1 }}
                </span>
                <span class="question-type">
                    @switch($currentQuestion->type)
                        @case('single_choice')
                            {{ __('lang.single_choice') }}
                            @break
                        @case('multiple_choice') 
                            {{ __('lang.multiple_choice') }}
                            @break
                        @case('true_false')
                            {{ __('lang.true_false') }}
                            @break
                        @case('matching')
                            {{ __('lang.matching') }}
                            @break
                    @endswitch
                </span>
            </div>

            <div class="question-text">
                {!! nl2br(e($currentQuestion->question_text)) !!}
            </div>

            <!-- CSRF Token and Question ID (CRITICAL) -->
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="question_id" value="{{ $currentQuestion->id }}">

        <div class="answers-container">
            @if($currentQuestion->type === 'matching')
                <div class="matching-container">
                    <p class="text-info">{{ __('lang.match_items_instruction') }}</p>
                    <!-- Matching functionality placeholder -->
                </div>
            @else
                @foreach($currentQuestion->answers as $index => $answer)
                    @php
                        // Debug: Log the answer data
                        \Log::info('Answer Debug', [
                            'index' => $index,
                            'answer_id' => $answer->id,
                            'answer_uuid' => $answer->uuid ?? 'NO_UUID',
                            'answer_text' => $answer->answer_text,
                            'question_type' => $currentQuestion->type
                        ]);
                        
                        // Ensure we have a UUID
                        $answerUuid = $answer->uuid ?? $answer->id ?? 'answer_' . $index;
                    @endphp
                    
                    <div class="answer-option" 
                         data-answer-uuid="{{ $answerUuid }}" 
                         data-answer-id="{{ $answer->id }}"
                         data-debug-index="{{ $index }}"
                         role="button"
                         tabindex="0"
                         aria-label="{{ __('lang.select_answer') }} {{ $answer->answer_text }}">
                        
                        <div class="answer-content">
                            <div class="answer-indicator">
                                @if($currentQuestion->type === 'multiple_choice')
                                    <div class="checkbox-indicator">
                                        <i class="fas fa-check"></i>
                                    </div>
                                @else
                                    <div class="radio-indicator">
                                        <div class="radio-dot"></div>
                                    </div>
                                @endif
                            </div>
                            <div class="answer-text">
                                {{ $answer->answer_text }}
                                <!-- Debug info (remove in production) -->
                                {{-- <small class="text-muted" style="display: block; font-size: 10px;">
                                    UUID: {{ $answerUuid }} | ID: {{ $answer->id }}
                                </small> --}}
                            </div>
                        </div>
                        
                        <!-- CRITICAL: Fixed input with proper UUID -->
                        <input type="{{ $currentQuestion->type === 'multiple_choice' ? 'checkbox' : 'radio' }}" 
                               name="{{ $currentQuestion->type === 'multiple_choice' ? 'answers[]' : 'answers' }}" 
                               value="{{ $answerUuid }}" 
                               class="d-none answer-input"
                               data-answer-uuid="{{ $answerUuid }}"
                               data-answer-id="{{ $answer->id }}"
                               {{ isset($userAnswer) && $userAnswer->selected_answers && in_array($answerUuid, json_decode($userAnswer->selected_answers, true) ?? []) ? 'checked' : '' }}
                               aria-checked="{{ isset($userAnswer) && $userAnswer->selected_answers && in_array($answerUuid, json_decode($userAnswer->selected_answers, true) ?? []) ? 'true' : 'false' }}">
                    </div>
                @endforeach
                
                <!-- Debug Section (remove in production) -->
                {{-- <div class="mt-3 p-2 bg-light rounded" style="font-size: 12px;">
                    <strong>Debug Info:</strong><br>
                    Question ID: {{ $currentQuestion->id }}<br>
                    Question Type: {{ $currentQuestion->type }}<br>
                    Answers Count: {{ $currentQuestion->answers->count() }}<br>
                    User Answers: {{ isset($userAnswer) ? $userAnswer->selected_answers : 'None' }}<br>
                    
                    <div class="mt-2">
                        <strong>Answer UUIDs:</strong>
                        @foreach($currentQuestion->answers as $answer)
                            <br>{{ $loop->iteration }}. UUID: "{{ $answer->uuid ?? 'NO_UUID' }}" | ID: {{ $answer->id }}
                        @endforeach
                    </div>
                </div> --}}
            @endif
        </div>

            <div class="navigation-actions">
                <div class="nav-buttons">
                    <button type="button" class="btn btn-outline-primary btn-previous" id="prevBtn" 
                            {{ $progress['current_index'] == 0 ? 'disabled' : '' }}
                            aria-label="{{ __('lang.previous_question') }}">
                        <i class="fas fa-chevron-left"></i>
                        {{ __('lang.previous') }}
                    </button>
                    <button type="button" class="btn btn-primary btn-next" id="nextBtn"
                            aria-label="{{ $progress['current_index'] + 1 >= $progress['total_questions'] ? __('lang.finish_exam') : __('lang.next_question') }}">
                        @if($progress['current_index'] + 1 >= $progress['total_questions'])
                            <i class="fas fa-flag-checkered"></i>
                            {{ __('lang.finish') }}
                        @else
                            {{ __('lang.next') }}
                            <i class="fas fa-chevron-right"></i>
                        @endif
                    </button>
                </div>
                <div class="action-buttons">
                    <button type="button" class="btn btn-outline-primary btn-secondary-action" id="clearAnswerBtn"
                            aria-label="{{ __('lang.clear_answer') }}">
                        <i class="fas fa-eraser me-2"></i>
                        {{ __('lang.clear') }}
                    </button>
                    <button type="button" class="btn btn-primary btn-primary-action" id="saveAnswerBtn"
                            aria-label="{{ __('lang.save_answer') }}">
                        <i class="fas fa-save me-2"></i>
                        {{ __('lang.save_answer') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Question Navigation Panel -->
        <div class="question-navigation settings-card">
            <div class="card-header">
                <span class="card-icon"><i class="fas fa-list-ol"></i></span>
                <h5 class="card-title">{{ __('lang.question_overview') }}</h5>
            </div>
            <div class="card-body">
                <div class="questions-grid">
                    @for($i = 0; $i < $progress['total_questions']; $i++)
                        @php
                            $questionId = $session->question_order[$i] ?? null;
                            $isAnswered = in_array($questionId, $session->answered_questions ?? []);
                            $isCurrent = $i == $progress['current_index'];
                        @endphp
                        <button type="button" 
                                class="question-nav-btn {{ $isCurrent ? 'current' : '' }} {{ $isAnswered ? 'answered' : '' }}"
                                data-question-index="{{ $i }}"
                                onclick="navigateToQuestion({{ $i }})"
                                aria-label="{{ __('lang.question') }} {{ $i + 1 }} {{ $isAnswered ? __('lang.answered') : __('lang.not_answered') }}">
                            {{ $i + 1 }}
                        </button>
                    @endfor
                </div>

                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-box legend-current"></div>
                        <span>{{ __('lang.current_question') }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-box legend-answered"></div>
                        <span>{{ __('lang.answered') }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-box legend-unanswered"></div>
                        <span>{{ __('lang.not_answered') }}</span>
                    </div>
                </div>

                <div class="exam-actions mt-3">
                    <button type="button" class="btn btn-primary w-100 mb-2" onclick="submitExam()"
                            aria-label="{{ __('lang.submit_exam') }}">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ __('lang.submit_exam') }}
                    </button>
                    <button type="button" class="btn btn-warning w-100 mb-2" onclick="pauseExam()"
                            aria-label="{{ __('lang.pause_exam') }}">
                        <i class="fas fa-pause me-2"></i>
                        {{ __('lang.pause_exam') }}
                    </button>
                    <button type="button" class="btn btn-outline-primary w-100" onclick="reviewAnswers()"
                            aria-label="{{ __('lang.review_answers') }}">
                        <i class="fas fa-eye me-2"></i>
                        {{ __('lang.review_answers') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay hidden" id="loadingOverlay">
    <div class="text-center">
        <div class="loading-spinner"></div>
        <p class="mt-3 fw-bold">{{ __('lang.processing') }}...</p>
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">
                    <i class="fas fa-eye me-2"></i>
                    {{ __('lang.review_answers') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('lang.close') }}"></button>
            </div>
            <div class="modal-body" id="reviewContent">
                <!-- Review content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">
                    {{ __('lang.close') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="submitExam()" data-bs-dismiss="modal">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ __('lang.submit_exam') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/examTake.js') }}"></script>
<script>
    console.log('=== EXAM INITIALIZATION ===');
    
    // Define route URLs using Laravel's route() helper
    const routes = {
        submitAnswer: '{{ route("student.exams.submit-answer", $session->id) }}',
        updateActivity: '{{ route("student.exams.update-activity", $session->id) }}',
        progress: '{{ route("student.exams.progress", $session->id) }}',
        navigate: '{{ route("student.exams.navigate", $session->id) }}',
        complete: '{{ route("student.exams.complete", $session->id) }}',
        pause: '{{ route("student.exams.pause", $session->id) }}',
        result: '{{ route("student.exams.result", $session->id) }}',
        examsIndex: '{{ route("student.exams.index") }}'
    };

    console.log('Routes configured:', routes);

    // Initialize exam manager with routes
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded - Initializing ExamManager');
        
        const config = {
            sessionId: '{{ $session->id }}',
            currentQuestionIndex: {{ $progress['current_index'] }},
            totalQuestions: {{ $progress['total_questions'] }},
            remainingTime: {{ $progress['remaining_time'] }},
            userAnswers: @json($userAnswer->selected_answers ?? [])
        };
        
        console.log('ExamManager config:', config);
        
        // Check if ExamManager class is available
        if (typeof ExamManager === 'undefined') {
            console.error('ExamManager class not found! Make sure examTake.js is loaded correctly.');
            return;
        }
        
        // Check if required elements exist
        const requiredElements = [
            'timeRemaining',
            'examTimer', 
            'questionTime',
            'statusBanner',
            'statusMessage'
        ];
        
        const missingElements = requiredElements.filter(id => !document.getElementById(id));
        if (missingElements.length > 0) {
            console.error('Missing required elements:', missingElements);
        }
        
        // Check if answer options exist
        const answerOptions = document.querySelectorAll('.answer-option');
        console.log('Found answer options:', answerOptions.length);
        
        answerOptions.forEach((option, index) => {
            const uuid = option.dataset.answerUuid;
            const input = option.querySelector('.answer-input');
            console.log(`Answer ${index + 1}: UUID=${uuid}, Input exists=${!!input}`);
        });
        
        try {
            window.examManager = new ExamManager(routes, config);
            console.log('ExamManager initialized successfully');
        } catch (error) {
            console.error('Error initializing ExamManager:', error);
        }
    });
</script>
@endsection