@extends('layouts.app')

@section('title', $session->exam->title . ' - ' . __('lang.exam'))

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/examTake.css') }}">

<div class="exam-container" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Exam Header -->
    <div class="exam-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-file-alt me-2"></i>
                        {{ $session->exam->title }}
                    </h4>
                </div>
                <div class="col-md-8">
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

    <!-- Alert Banner -->
    <div id="alertBanner" class="alert alert-banner hidden"></div>

    <!-- Exam Body -->
    <div class="exam-body">
        <!-- Question Panel -->
        <div class="question-panel">
            <div class="question-timer" id="questionTimer">
                <i class="fas fa-stopwatch me-1"></i>
                <span id="questionTime">00:00</span>
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

            <form id="answerForm" class="answers-container">
                @csrf
                <input type="hidden" name="question_id" value="{{ $currentQuestion->id }}">
                <input type="hidden" name="time_spent" id="timeSpentInput" value="0">

                @if($currentQuestion->type === 'matching')
                    <div class="matching-container">
                        <p class="text-info">{{ __('lang.match_items_instruction') }}</p>
                    </div>
                @else
                    @foreach($currentQuestion->answers as $answer)
                        <div class="answer-option" data-answer-id="{{ $answer->id }}">
                            <div class="answer-content">
                                @if($currentQuestion->type === 'multiple_choice')
                                    <div class="answer-checkbox" data-type="checkbox"></div>
                                @else
                                    <div class="answer-radio" data-type="radio"></div>
                                @endif
                                <div class="answer-text">
                                    {{ App::getLocale() === 'ar' ? $answer['answer-ar'] : $answer->answer }}
                                </div>
                            </div>

                            <input type="{{ $currentQuestion->type === 'multiple_choice' ? 'checkbox' : 'radio' }}" 
                                   name="answers[]" 
                                   value="{{ $answer->id }}" 
                                   class="d-none answer-input"
                                {{ isset($userAnswer) && $userAnswer->selected_answers && in_array($answer->id, json_decode($userAnswer->selected_answers)) ? 'checked' : '' }}
                        </div>
                    @endforeach
                @endif
            </form>

            <div class="navigation-actions">
                <div class="nav-buttons">
                    <button type="button" class="btn-nav btn-previous" id="prevBtn" 
                            {{ $progress['current_index'] == 0 ? 'disabled' : '' }}>
                        <i class="fas fa-chevron-left"></i>
                        {{ __('lang.previous') }}
                    </button>
                    <button type="button" class="btn-nav btn-next" id="nextBtn">
                        @if($progress['current_index'] + 1 >= $progress['total_questions'])
                            <i class="fas fa-flag-checkered"></i>
                            {{ __('lang.finish') }}
                        @else
                            {{ __('lang.next') }}
                            <i class="fas fa-chevron-right"></i>
                        @endif
                    </button>
                </div>
                <button type="button" class="btn-primary-action" id="saveAnswerBtn">
                    <i class="fas fa-save me-2"></i>
                    {{ __('lang.save_answer') }}
                </button>
            </div>
        </div>

        <!-- Question Navigation Panel -->
        <div class="question-navigation">
            <h5 class="nav-title">
                <i class="fas fa-list-ol me-2"></i>
                {{ __('lang.question_overview') }}
            </h5>

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
                            onclick="navigateToQuestion({{ $i }})">
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

            <div class="mt-3">
                <button type="button" class="btn-primary-action" onclick="submitExam()">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ __('lang.submit_exam') }}
                </button>
                <button type="button" class="btn-danger mt-2" onclick="pauseExam()">
                    <i class="fas fa-pause me-2"></i>
                    {{ __('lang.pause_exam') }}
                </button>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
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

    class ExamManager {
        constructor() {
            this.sessionId = '{{ $session->id }}';
            this.currentQuestionIndex = {{ $progress['current_index'] }};
            this.totalQuestions = {{ $progress['total_questions'] }};
            this.remainingTime = {{ $progress['remaining_time'] }};
            this.questionStartTime = Date.now();
            this.questionTimeSpent = 0;
            this.autoSaveInterval = null;
            this.activityUpdateInterval = null;
            
            this.initializeEventListeners();
            this.startTimers();
            this.restoreAnswers();
            this.startAutoSave();
            this.startActivityUpdates();
            
            // Check session status every 30 seconds
            setInterval(() => this.checkSessionStatus(), 30000);
        }

        initializeEventListeners() {
            // Answer selection
            document.querySelectorAll('.answer-option').forEach(option => {
                option.addEventListener('click', () => this.selectAnswer(option));
            });

            // Navigation buttons
            document.getElementById('prevBtn')?.addEventListener('click', () => this.previousQuestion());
            document.getElementById('nextBtn')?.addEventListener('click', () => this.nextQuestion());
            document.getElementById('saveAnswerBtn')?.addEventListener('click', () => this.saveCurrentAnswer());

            // Prevent accidental page leave
            window.addEventListener('beforeunload', (e) => {
                if (this.hasUnsavedChanges()) {
                    e.preventDefault();
                    e.returnValue = '{{ __("lang.exam_leave_warning") }}';
                    return '{{ __("lang.exam_leave_warning") }}';
                }
            });

            // Handle visibility change (user switches tabs)
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    this.saveCurrentAnswer();
                } else {
                    // Reset question timer when user returns
                    this.questionStartTime = Date.now();
                }
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', (e) => {
                if (e.ctrlKey || e.metaKey) {
                    switch(e.key) {
                        case 's':
                            e.preventDefault();
                            this.saveCurrentAnswer();
                            break;
                        case 'ArrowLeft':
                            e.preventDefault();
                            this.previousQuestion();
                            break;
                        case 'ArrowRight':
                            e.preventDefault();
                            this.nextQuestion();
                            break;
                    }
                }
            });
        }

        selectAnswer(selectedOption) {
            const questionType = '{{ $currentQuestion->type }}';
            const answerId = selectedOption.dataset.answerId;
            const input = selectedOption.querySelector('.answer-input');
            const indicator = selectedOption.querySelector('.answer-radio, .answer-checkbox');

            if (questionType === 'multiple_choice') {
                // Multiple choice - toggle selection
                const isSelected = input.checked;
                input.checked = !isSelected;
                selectedOption.classList.toggle('selected', !isSelected);
                indicator.classList.toggle('checked', !isSelected);
            } else {
                // Single choice or true/false - clear others and select this one
                document.querySelectorAll('.answer-option').forEach(option => {
                    option.classList.remove('selected');
                    option.querySelector('.answer-input').checked = false;
                    option.querySelector('.answer-radio, .answer-checkbox').classList.remove('checked');
                });
                
                selectedOption.classList.add('selected');
                input.checked = true;
                indicator.classList.add('checked');
            }

            // Show visual feedback
            this.showAlert('{{ __("lang.answer_selected") }}', 'success', 1000);
        }

        restoreAnswers() {
            const userAnswers = @json($userAnswer->selected_answers ?? []);
            
            userAnswers.forEach(answerId => {
                const option = document.querySelector(`[data-answer-id="${answerId}"]`);
                if (option) {
                    option.classList.add('selected');
                    option.querySelector('.answer-input').checked = true;
                    option.querySelector('.answer-radio, .answer-checkbox').classList.add('checked');
                }
            });
        }

        async saveCurrentAnswer() {
            const formData = new FormData(document.getElementById('answerForm'));
            const selected_answers = [];
            
            document.querySelectorAll('.answer-input:checked').forEach(input => {
                selected_answers.push(parseInt(input.value));
            });

            if (selected_answers.length === 0) {
                this.showAlert('{{ __("lang.please_select_answer") }}', 'warning', 3000);
                return false;
            }

            console.log(`selected_answers: ${JSON.stringify(selected_answers)}`);
            
            this.showLoading();

            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', routes.submitAnswer, true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('[name="_token"]').value);

                xhr.onload = () => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                    
                        try {
                            const result = JSON.parse(xhr.responseText);
                            console.log(result);
                        
                            if (!result.success) {
                                this.showAlert('{{ __("lang.exam_time_expired") }}', 'danger', 5000);
                                setTimeout(() => {
                                    window.location.href = routes.result;
                                }, 2000);
                                resolve(false);
                            } else if (result.success) {
                                // Update UI to show question as answered
                                const navBtn = document.querySelector(`[data-question-index="${this.currentQuestionIndex}"]`);
                                if (navBtn) {
                                    navBtn.classList.add('answered');
                                }
                                
                                // Update progress
                                document.getElementById('answeredCount').textContent = (result.answered_questions?? []).length;
                                document.getElementById('progressFill').style.width = ((result.answered_questions?? []).length / this.totalQuestions) * 100 + '%';
                                
                                this.showAlert('{{ __("lang.answer_saved_successfully") }}', 'success', 2000);
                                resolve(true);
                            } else {
                                throw new Error('Unexpected response status');
                            }
                        } catch (error) {
                            console.error('Error parsing response:', error);
                            this.showAlert('{{ __("lang.error_saving_answer") }}', 'danger', 3000);
                            resolve(false);
                        }
                    } else {
                        console.error('Error saving answer:', xhr.statusText);
                        this.showAlert('{{ __("lang.error_saving_answer") }}', 'danger', 3000);
                        resolve(false);
                    }
                };

                xhr.onerror = () => {
                    console.error('Network error saving answer');
                    this.showAlert('{{ __("lang.error_saving_answer") }}', 'danger', 3000);
                    resolve(false);
                };

                xhr.send(JSON.stringify({
                    question_id: document.querySelector('[name="question_id"]').value,
                    selected_answers: selected_answers,
                    time_spent: Math.floor(this.questionTimeSpent / 1000)
                }));
            }).finally(() => {
                this.hideLoading();
            });
        }

        startAutoSave() {
            // Auto-save every 30 seconds if there are changes
            this.autoSaveInterval = setInterval(() => {
                if (this.hasAnswers()) {
                    this.saveCurrentAnswer();
                }
            }, 30000);
        }

        startActivityUpdates() {
            // Update activity time every 10 seconds
            this.activityUpdateInterval = setInterval(() => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', routes.updateActivity, true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('[name="_token"]').value);

                xhr.onload = () => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const result = JSON.parse(xhr.responseText);
                            
                            if (result.status === 'expired') {
                                this.timeExpired();
                            } else if (result.status === 'success') {
                                this.remainingTime = result.remaining_time;
                            }
                        } catch (error) {
                            console.error('Error parsing activity update:', error);
                        }
                    } else {
                        console.error('Error updating activity:', xhr.statusText);
                    }
                };

                xhr.onerror = () => {
                    console.error('Network error updating activity');
                };

                xhr.send(JSON.stringify({
                    time_spent: 10 // 10 seconds
                }));
            }, 10000);
        }

        async checkSessionStatus() {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', routes.progress, true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('[name="_token"]').value);

                xhr.onload = () => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const result = JSON.parse(xhr.responseText);
                            
                            if (result.status === 'success') {
                                this.remainingTime = result.data.remaining_time;
                                
                                if (result.data.status === 'completed') {
                                    window.location.href = routes.result;
                                } else if (result.data.status === 'expired') {
                                    this.timeExpired();
                                }
                                resolve();
                            } else {
                                throw new Error('Unexpected response status');
                            }
                        } catch (error) {
                            console.error('Error parsing session status:', error);
                            resolve();
                        }
                    } else {
                        console.error('Error checking session status:', xhr.statusText);
                        resolve();
                    }
                };

                xhr.onerror = () => {
                    console.error('Network error checking session status');
                    resolve();
                };

                xhr.send();
            });
        }

        hasAnswers() {
            return document.querySelectorAll('.answer-input:checked').length > 0;
        }

        hasUnsavedChanges() {
            return this.hasAnswers();
        }

        startTimers() {
            // Main exam timer
            setInterval(() => {
                this.remainingTime--;
                this.updateTimerDisplay();
                
                if (this.remainingTime <= 0) {
                    this.timeExpired();
                }
            }, 1000);

            // Question timer
            setInterval(() => {
                this.questionTimeSpent = Date.now() - this.questionStartTime;
                this.updateQuestionTimer();
            }, 1000);
        }

        updateTimerDisplay() {
            const timer = document.getElementById('timeRemaining');
            const timerContainer = document.getElementById('examTimer');
            
            const hours = Math.floor(this.remainingTime / 3600);
            const minutes = Math.floor((this.remainingTime % 3600) / 60);
            const seconds = this.remainingTime % 60;
            
            timer.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (this.remainingTime <= 600) { // 10 minutes
                timerContainer.classList.add('timer-warning');
            }
            
            if (this.remainingTime <= 300) { // 5 minutes
                this.showAlert('{{ __("lang.exam_time_warning") }}', 'warning', 5000);
            }
        }

        updateQuestionTimer() {
            const timer = document.getElementById('questionTime');
            const totalSeconds = Math.floor(this.questionTimeSpent / 1000);
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            
            timer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }

        async previousQuestion() {
            if (this.currentQuestionIndex > 0) {
                await this.saveCurrentAnswer();
                await this.navigateToQuestion(this.currentQuestionIndex - 1);
            }
        }

        async nextQuestion() {
            if (this.currentQuestionIndex + 1 >= this.totalQuestions) {
                if (confirm('{{ __("lang.submit_exam_confirmation") }}')) {
                    await this.submitExam();
                }
            } else {
                await this.saveCurrentAnswer();
                await this.navigateToQuestion(this.currentQuestionIndex + 1);
            }
        }

        async navigateToQuestion(questionIndex) {
            this.showLoading();
            
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', routes.navigate, true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('[name="_token"]').value);

                xhr.onload = () => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        this.questionStartTime = Date.now();
                        window.location.reload();
                        resolve();
                    } else {
                        console.error('Navigation error:', xhr.statusText);
                        this.showAlert('{{ __("lang.navigation_error") }}', 'danger', 3000);
                        resolve();
                    }
                };

                xhr.onerror = () => {
                    console.error('Network error during navigation');
                    this.showAlert('{{ __("lang.navigation_error") }}', 'danger', 3000);
                    resolve();
                };

                xhr.send(JSON.stringify({
                    question_index: questionIndex
                }));
            }).finally(() => {
                this.hideLoading();
            });
        }

        async submitExam() {
            if (!confirm('{{ __("lang.submit_exam_final_confirmation") }}')) {
                return;
            }

            this.showLoading();
            
            try {
                await this.saveCurrentAnswer();
                
                return new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', routes.complete, true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('[name="_token"]').value);

                    xhr.onload = () => {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            if (this.autoSaveInterval) clearInterval(this.autoSaveInterval);
                            if (this.activityUpdateInterval) clearInterval(this.activityUpdateInterval);
                            
                            this.showAlert('{{ __("lang.exam_submitted_successfully") }}', 'success', 3000);
                            
                            setTimeout(() => {
                                window.location.href = routes.result;
                            }, 2000);
                            resolve();
                        } else {
                            console.error('Submit error:', xhr.statusText);
                            this.showAlert('{{ __("lang.error_submitting_exam") }}', 'danger', 3000);
                            resolve();
                        }
                    };

                    xhr.onerror = () => {
                        console.error('Network error submitting exam');
                        this.showAlert('{{ __("lang.error_submitting_exam") }}', 'danger', 3000);
                        resolve();
                    };

                    xhr.send();
                });
            } finally {
                this.hideLoading();
            }
        }

        async pauseExam() {
            if (!confirm('{{ __("lang.pause_exam_confirmation") }}')) {
                return;
            }

            this.showLoading();
            
            try {
                await this.saveCurrentAnswer();
                
                return new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', routes.pause, true);
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('[name="_token"]').value);

                    xhr.onload = () => {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            if (this.autoSaveInterval) clearInterval(this.autoSaveInterval);
                            if (this.activityUpdateInterval) clearInterval(this.activityUpdateInterval);
                            
                            this.showAlert('{{ __("lang.exam_paused_successfully") }}', 'success', 2000);
                            
                            setTimeout(() => {
                                window.location.href = routes.examsIndex;
                            }, 2000);
                            resolve();
                        } else {
                            console.error('Pause error:', xhr.statusText);
                            this.showAlert('{{ __("lang.error_pausing_exam") }}', 'danger', 3000);
                            resolve();
                        }
                    };

                    xhr.onerror = () => {
                        console.error('Network error pausing exam');
                        this.showAlert('{{ __("lang.error_pausing_exam") }}', 'danger', 3000);
                        resolve();
                    };

                    xhr.send();
                });
            } finally {
                this.hideLoading();
            }
        }

        timeExpired() {
            if (this.autoSaveInterval) clearInterval(this.autoSaveInterval);
            if (this.activityUpdateInterval) clearInterval(this.activityUpdateInterval);
            
            this.showAlert('{{ __("lang.exam_time_expired") }}', 'danger', 5000);
            
            setTimeout(() => {
                this.submitExam();
            }, 3000);
        }

        showAlert(message, type, duration = 3000) {
            const alertBanner = document.getElementById('alertBanner');
            alertBanner.className = `alert alert-${type} alert-banner`;
            alertBanner.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : type === 'danger' ? 'times-circle' : 'info-circle'} me-2"></i>
                    ${message}
                </div>
            `;
            alertBanner.classList.remove('hidden');
            
            setTimeout(() => {
                alertBanner.classList.add('hidden');
            }, duration);
        }

        showLoading() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }
    }

    // Global functions for HTML onclick events
    function navigateToQuestion(index) {
        examManager.navigateToQuestion(index);
    }

    function submitExam() {
        examManager.submitExam();
    }

    function pauseExam() {
        examManager.pauseExam();
    }

    // Initialize the exam manager when page loads
    let examManager;
    document.addEventListener('DOMContentLoaded', function() {
        examManager = new ExamManager();
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (examManager && examManager.autoSaveInterval) {
            clearInterval(examManager.autoSaveInterval);
        }
        if (examManager && examManager.activityUpdateInterval) {
            clearInterval(examManager.activityUpdateInterval);
        }
    });
</script>
@endsection
