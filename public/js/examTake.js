class ExamManager {
    constructor(routes, config) {
        console.log('=== EXAMMANAGER CONSTRUCTOR START ===');
        
        this.routes = routes;
        this.sessionId = config.sessionId;
        this.currentQuestionIndex = config.currentQuestionIndex;
        this.totalQuestions = config.totalQuestions;
        this.remainingTime = config.remainingTime;
        this.userAnswers = config.userAnswers || [];
        this.questionStartTime = Date.now();
        this.questionTimeSpent = 0;
        this.hasUnsavedChanges = false;
        this.lastSavedAnswers = new Set(this.userAnswers);
        this.isNavigating = false;
        this.isSaving = false;
        this.mainTimerInterval = null;
        this.questionTimerInterval = null;
        this.autoSaveInterval = null;
        this.activityUpdateInterval = null;
        this.statusCheckInterval = null;

        console.log('Config received:', config);
        console.log('Routes received:', routes);
        console.log('Initial userAnswers:', this.userAnswers);
        console.log('Remaining time:', this.remainingTime, 'seconds');

        // Check critical elements exist
        this.checkRequiredElements();
        
        this.initializeEventListeners();
        this.startTimers();
        this.restoreAnswers();
        this.startSmartAutoSave();
        this.startActivityUpdates();
        
        console.log('=== EXAMMANAGER CONSTRUCTOR END ===');
    }

    checkRequiredElements() {
        console.log('=== CHECKING REQUIRED ELEMENTS ===');
        
        const required = [
            'timeRemaining',
            'examTimer',
            'questionTime', 
            'statusBanner',
            'statusMessage',
            'prevBtn',
            'nextBtn',
            'saveAnswerBtn',
            'clearAnswerBtn'
        ];

        const missing = [];
        required.forEach(id => {
            const element = document.getElementById(id);
            if (!element) {
                missing.push(id);
                console.error(`❌ Missing element: #${id}`);
            } else {
                console.log(`✅ Found element: #${id}`);
            }
        });

        // Check answer options
        const answerOptions = document.querySelectorAll('.answer-option');
        console.log(`Found ${answerOptions.length} answer options`);
        
        answerOptions.forEach((option, index) => {
            const uuid = option.dataset.answerUuid;
            const input = option.querySelector('.answer-input');
            const indicator = option.querySelector('.checkbox-indicator, .radio-indicator');
            
            console.log(`Answer ${index + 1}:`);
            console.log(`  UUID: ${uuid}`);
            console.log(`  Input: ${input ? '✅' : '❌'}`);
            console.log(`  Indicator: ${indicator ? '✅' : '❌'}`);
            
            if (!uuid) console.error(`❌ Answer ${index + 1} missing UUID`);
            if (!input) console.error(`❌ Answer ${index + 1} missing input`);
        });

        // Check CSRF token
        const token = document.querySelector('[name="_token"]');
        if (!token) {
            console.error('❌ Missing CSRF token');
        } else {
            console.log('✅ CSRF token found:', token.value.substring(0, 10) + '...');
        }

        // Check question ID
        const questionId = document.querySelector('[name="question_id"]');
        if (!questionId) {
            console.error('❌ Missing question_id');
        } else {
            console.log('✅ Question ID found:', questionId.value);
        }

        if (missing.length > 0) {
            console.error('❌ Missing required elements:', missing);
        }
    }

    initializeEventListeners() {
        console.log('=== INITIALIZING EVENT LISTENERS ===');
        
        // Answer selection
        const answerOptions = document.querySelectorAll('.answer-option');
        console.log(`Setting up listeners for ${answerOptions.length} answer options`);
        
        answerOptions.forEach((option, index) => {
            option.addEventListener('click', (e) => {
                e.preventDefault();
                console.log(`Answer option ${index + 1} clicked`);
                this.selectAnswer(option);
            });

            option.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    console.log(`Answer option ${index + 1} selected via keyboard`);
                    this.selectAnswer(option);
                }
            });
        });

        // Navigation and action buttons
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const saveBtn = document.getElementById('saveAnswerBtn');
        const clearBtn = document.getElementById('clearAnswerBtn');

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                console.log('Previous button clicked');
                this.previousQuestion();
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                console.log('Next button clicked');
                this.nextQuestion();
            });
        }
        
        if (saveBtn) {
            saveBtn.addEventListener('click', () => {
                console.log('Save button clicked');
                this.saveCurrentAnswer(true);
            });
        }
        
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                console.log('Clear button clicked');
                this.clearCurrentAnswer();
            });
        }

        // Page leave warning
        window.addEventListener('beforeunload', (e) => {
            if (this.hasUnsavedChanges && !this.isNavigating) {
                console.log('Page unload with unsaved changes');
                e.preventDefault();
                return 'You have unsaved changes. Are you sure you want to leave?';
            }
        });

        // Visibility change handling
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && this.hasUnsavedChanges) {
                console.log('Page hidden, auto-saving...');
                this.saveCurrentAnswer(false);
            } else if (!document.hidden) {
                console.log('Page visible, resetting question timer');
                this.questionStartTime = Date.now();
            }
        });

        console.log('✅ Event listeners initialized');
    }

    selectAnswer(selectedOption) {
        console.log('=== SELECT ANSWER START ===');
        console.log('Selected option:', selectedOption);
        
        const questionTypeElement = document.querySelector('.question-type');
        const questionType = questionTypeElement ? questionTypeElement.textContent.trim() : '';
        const answerUuid = selectedOption.dataset.answerUuid;
        const input = selectedOption.querySelector('.answer-input');

        console.log('Question type:', questionType);
        console.log('Answer UUID:', answerUuid);
        console.log('Input element:', input);

        if (!answerUuid) {
            console.error('❌ Missing UUID for answer option');
            console.error('Option element:', selectedOption);
            console.error('Dataset:', selectedOption.dataset);
            this.showStatus('Error: Missing answer UUID', 'error', 3000);
            return;
        }

        if (!input) {
            console.error('❌ Missing input element for answer option');
            console.error('Option element:', selectedOption);
            this.showStatus('Error: Missing input element', 'error', 3000);
            return;
        }

        console.log('Processing answer selection...');

        if (questionType.includes('multiple_choice')) {
            console.log('🔄 Processing multiple choice question');
            const wasChecked = input.checked;
            input.checked = !input.checked;
            selectedOption.classList.toggle('selected', input.checked);
            
            const indicator = selectedOption.querySelector('.checkbox-indicator');
            if (indicator) {
                indicator.classList.toggle('checked', input.checked);
            }
            
            input.setAttribute('aria-checked', input.checked.toString());
            
            console.log(`Answer UUID ${answerUuid}: ${wasChecked ? 'unchecked' : 'checked'} → ${input.checked ? 'checked' : 'unchecked'}`);
        } else {
            console.log('🔄 Processing single choice question');
            
            // Clear all other selections first
            document.querySelectorAll('.answer-option').forEach(option => {
                option.classList.remove('selected');
                const optionInput = option.querySelector('.answer-input');
                const optionIndicator = option.querySelector('.radio-indicator');
                if (optionInput) {
                    optionInput.checked = false;
                    optionInput.setAttribute('aria-checked', 'false');
                }
                if (optionIndicator) {
                    optionIndicator.classList.remove('checked');
                }
            });

            // Set the selected option
            selectedOption.classList.add('selected');
            input.checked = true;
            input.setAttribute('aria-checked', 'true');
            
            const indicator = selectedOption.querySelector('.radio-indicator');
            if (indicator) {
                indicator.classList.add('checked');
            }
            
            console.log(`Answer UUID ${answerUuid} selected as single choice`);
        }

        this.hasUnsavedChanges = true;
        this.updateSaveButtonState();
        this.showStatus('Answer selected', 'info', 2000);
        
        // Log current selection state
        this.logCurrentSelections();
        console.log('=== SELECT ANSWER END ===');
    }

    logCurrentSelections() {
        console.log('=== CURRENT SELECTIONS ===');
        const selectedAnswers = [];
        const checkedInputs = document.querySelectorAll('.answer-input:checked');
        
        console.log(`Found ${checkedInputs.length} checked inputs`);
        
        checkedInputs.forEach((input, index) => {
            const uuid = input.dataset.answerUuid;
            const value = input.value;
            selectedAnswers.push(uuid);
            console.log(`Selection ${index + 1}: UUID=${uuid}, Value=${value}`);
        });
        
        console.log('Selected UUIDs array:', selectedAnswers);
        console.log('=== END CURRENT SELECTIONS ===');
        
        return selectedAnswers;
    }

    clearCurrentAnswer() {
        console.log('=== CLEARING ANSWERS ===');
        
        document.querySelectorAll('.answer-option').forEach((option, index) => {
            option.classList.remove('selected');
            const input = option.querySelector('.answer-input');
            if (input) {
                const wasChecked = input.checked;
                input.checked = false;
                input.setAttribute('aria-checked', 'false');
                if (wasChecked) {
                    console.log(`Cleared answer ${index + 1}: UUID=${input.dataset.answerUuid}`);
                }
            }
            const indicator = option.querySelector('.checkbox-indicator, .radio-indicator');
            if (indicator) {
                indicator.classList.remove('checked');
            }
        });

        this.hasUnsavedChanges = true;
        this.updateSaveButtonState();
        this.showStatus('Answer cleared', 'warning', 2000);
        this.logCurrentSelections();
    }

    restoreAnswers() {
        console.log('=== RESTORING ANSWERS ===');
        console.log('User answers to restore:', this.userAnswers);
        
        try {
            if (Array.isArray(this.userAnswers)) {
                this.userAnswers.forEach((answerUuid, index) => {
                    console.log(`Restoring answer ${index + 1}: UUID=${answerUuid}`);
                    
                    const option = document.querySelector(`[data-answer-uuid="${answerUuid}"]`);
                    if (option) {
                        console.log(`✅ Found option for UUID ${answerUuid}`);
                        
                        option.classList.add('selected');
                        const input = option.querySelector('.answer-input');
                        if (input) {
                            input.checked = true;
                            input.setAttribute('aria-checked', 'true');
                            this.lastSavedAnswers.add(answerUuid);
                            console.log(`✅ Restored input for UUID ${answerUuid}`);
                        } else {
                            console.error(`❌ No input found for UUID ${answerUuid}`);
                        }
                        
                        const indicator = option.querySelector('.checkbox-indicator, .radio-indicator');
                        if (indicator) {
                            indicator.classList.add('checked');
                            console.log(`✅ Restored indicator for UUID ${answerUuid}`);
                        }
                    } else {
                        console.error(`❌ No option found for UUID: ${answerUuid}`);
                        // Log all available UUIDs for debugging
                        const allOptions = document.querySelectorAll('[data-answer-uuid]');
                        console.log('Available UUIDs:', Array.from(allOptions).map(opt => opt.dataset.answerUuid));
                    }
                });
            } else {
                console.log('userAnswers is not an array:', typeof this.userAnswers, this.userAnswers);
            }
            
            this.hasUnsavedChanges = false;
            this.updateSaveButtonState();
            this.logCurrentSelections();
            console.log('=== RESTORE COMPLETE ===');
            
        } catch (error) {
            console.error('❌ Error restoring answers:', error);
            this.showStatus('Error restoring answers', 'error', 3000);
        }
    }

    async saveCurrentAnswer(showFeedback = true) {
        if (this.isSaving) {
            console.log('Already saving, skipping...');
            return false;
        }

        console.log('=== SAVE CURRENT ANSWER START ===');
        
        const selectedAnswers = this.logCurrentSelections();
        
        const questionIdElement = document.querySelector('[name="question_id"]');
        const questionId = questionIdElement ? questionIdElement.value : '';
        const timeSpent = Math.floor(this.questionTimeSpent / 1000);

        console.log('Question ID:', questionId);
        console.log('Selected answers UUIDs:', selectedAnswers);
        console.log('Time spent:', timeSpent, 'seconds');

        const currentAnswers = new Set(selectedAnswers);
        if (this.setsAreEqual(currentAnswers, this.lastSavedAnswers)) {
            console.log('No changes detected, skipping save');
            return true;
        }

        this.isSaving = true;
        if (showFeedback) this.showLoading();

        try {
            const payload = {
                question_id: questionId,
                selected_answers: selectedAnswers,
                time_spent: timeSpent
            };

            console.log('=== SENDING TO SERVER ===');
            console.log('URL:', this.routes.submitAnswer);
            console.log('Payload:', JSON.stringify(payload, null, 2));

            const response = await fetch(this.routes.submitAnswer, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]')?.value || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);

            const result = await response.json();
            console.log('=== SERVER RESPONSE ===');
            console.log('Response data:', JSON.stringify(result, null, 2));

            if (!response.ok) {
                console.error('❌ HTTP error:', response.status, response.statusText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            if (!result.success) {
                console.log('❌ Server returned success=false');
                if (showFeedback) {
                    this.showStatus('Exam time expired', 'error', 5000);
                }
                setTimeout(() => {
                    window.location.href = this.routes.result;
                }, 2000);
                return false;
            }

            console.log('✅ Answer saved successfully');
            this.hasUnsavedChanges = false;
            this.lastSavedAnswers = new Set(selectedAnswers);
            this.updateSaveButtonState();

            const navBtn = document.querySelector(`[data-question-index="${this.currentQuestionIndex}"]`);
            if (navBtn) {
                navBtn.classList.add('answered');
                console.log('✅ Updated navigation button');
            }

            this.updateProgress(result);

            if (showFeedback) {
                this.showStatus('Answer saved successfully', 'success', 2000);
            } else {
                this.showAutoSaveIndicator();
            }

            console.log('=== SAVE CURRENT ANSWER END ===');
            return true;
            
        } catch (error) {
            console.error('❌ Error saving answer:', error);
            if (showFeedback) {
                this.showStatus('Error saving answer', 'error', 3000);
            }
            return false;
        } finally {
            this.isSaving = false;
            if (showFeedback) this.hideLoading();
        }
    }

    updateProgress(result) {
        console.log('=== UPDATING PROGRESS ===');
        console.log('Progress data:', result);
        
        const answeredCount = document.getElementById('answeredCount');
        const progressFill = document.getElementById('progressFill');

        if (answeredCount && result.answered_questions) {
            answeredCount.textContent = result.answered_questions.length;
            console.log('✅ Updated answered count:', result.answered_questions.length);
        }
        
        if (progressFill && result.answered_questions) {
            const percentage = (result.answered_questions.length / this.totalQuestions) * 100;
            progressFill.style.width = percentage + '%';
            console.log('✅ Updated progress bar:', percentage + '%');
        }
    }

    startSmartAutoSave() {
        console.log('=== STARTING AUTO-SAVE (30 second interval) ===');
        this.autoSaveInterval = setInterval(() => {
            if (this.hasUnsavedChanges && !this.isSaving) {
                console.log('🔄 Auto-saving unsaved changes...');
                this.saveCurrentAnswer(false);
            }
        }, 30000);
    }

    startActivityUpdates() {
        console.log('=== STARTING ACTIVITY UPDATES (30 second interval) ===');
        this.activityUpdateInterval = setInterval(async () => {
            try {
                console.log('🔄 Sending activity update...');
                const response = await fetch(this.routes.updateActivity, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('[name="_token"]')?.value || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        time_spent: 30
                    })
                });

                const result = await response.json();
                console.log('Activity update response:', result);

                if (result.status === 'expired') {
                    console.log('❌ Session expired');
                    this.timeExpired();
                } else if (result.status === 'success') {
                    this.remainingTime = result.remaining_time;
                    console.log('✅ Activity updated, remaining time:', this.remainingTime);
                }
            } catch (error) {
                console.error('❌ Error updating activity:', error);
            }
        }, 30000);
    }

    startTimers() {
        console.log('=== STARTING TIMERS ===');
        console.log('Initial remaining time:', this.remainingTime, 'seconds');
        
        // Clear existing timers first
        if (this.mainTimerInterval) {
            clearInterval(this.mainTimerInterval);
            console.log('Cleared existing main timer');
        }
        if (this.questionTimerInterval) {
            clearInterval(this.questionTimerInterval);
            console.log('Cleared existing question timer');
        }

        // Verify timer elements exist
        const timeRemaining = document.getElementById('timeRemaining');
        const questionTime = document.getElementById('questionTime');
        
        if (!timeRemaining) {
            console.error('❌ Timer element #timeRemaining not found!');
            return;
        }
        
        if (!questionTime) {
            console.error('❌ Timer element #questionTime not found!');
            return;
        }

        console.log('✅ Timer elements found');

        // Start main exam timer
        this.mainTimerInterval = setInterval(() => {
            this.remainingTime--;
            this.updateTimerDisplay();

            if (this.remainingTime <= 0) {
                console.log('⏰ Time expired!');
                this.timeExpired();
            }
        }, 1000);

        // Start question timer
        this.questionTimerInterval = setInterval(() => {
            this.questionTimeSpent = Date.now() - this.questionStartTime;
            this.updateQuestionTimer();
        }, 1000);

        console.log('✅ Timers started successfully');
        
        // Initial display update
        this.updateTimerDisplay();
        this.updateQuestionTimer();
    }

    updateTimerDisplay() {
        const timer = document.getElementById('timeRemaining');
        const timerContainer = document.getElementById('examTimer');

        if (!timer) {
            console.error('❌ Timer element #timeRemaining not found');
            return;
        }

        if (!timerContainer) {
            console.error('❌ Timer container #examTimer not found');
            return;
        }

        const hours = Math.floor(this.remainingTime / 3600);
        const minutes = Math.floor((this.remainingTime % 3600) / 60);
        const seconds = this.remainingTime % 60;

        const timeString = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        timer.textContent = timeString;

        // Log every 60 seconds to avoid spam
        if (this.remainingTime % 60 === 0) {
            console.log(`⏰ Timer: ${timeString} (${this.remainingTime}s remaining)`);
        }

        if (this.remainingTime <= 600 && !timerContainer.classList.contains('timer-warning')) {
            timerContainer.classList.add('timer-warning');
            console.log('⚠️ Timer warning activated (10 minutes remaining)');
        }

        if (this.remainingTime <= 300 && !timerContainer.classList.contains('final-warning')) {
            timerContainer.classList.add('final-warning');
            this.showStatus('Warning: 5 minutes remaining!', 'warning', 8000);
            console.log('🚨 Final warning activated (5 minutes remaining)');
        }
    }

    updateQuestionTimer() {
        const timer = document.getElementById('questionTime');
        if (!timer) {
            // Don't spam console for this one
            return;
        }

        const totalSeconds = Math.floor(this.questionTimeSpent / 1000);
        const minutes = Math.floor(totalSeconds / 60);
        const seconds = totalSeconds % 60;

        const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        timer.textContent = timeString;
    }

    async previousQuestion() {
        console.log('=== PREVIOUS QUESTION ===');
        if (this.currentQuestionIndex > 0) {
            await this.navigateToQuestion(this.currentQuestionIndex - 1);
        } else {
            console.log('Already at first question');
        }
    }

    async nextQuestion() {
        console.log('=== NEXT QUESTION ===');
        if (this.currentQuestionIndex + 1 >= this.totalQuestions) {
            console.log('At last question, prompting to submit exam');
            if (confirm('Are you sure you want to submit the exam?')) {
                await this.submitExam();
            }
        } else {
            await this.navigateToQuestion(this.currentQuestionIndex + 1);
        }
    }

    async navigateToQuestion(questionIndex) {
        if (this.isNavigating) {
            console.log('Already navigating, skipping...');
            return;
        }

        console.log('=== NAVIGATE TO QUESTION ===');
        console.log(`Navigating from question ${this.currentQuestionIndex} to ${questionIndex}`);
        
        this.isNavigating = true;

        if (this.hasUnsavedChanges) {
            console.log('Saving current answer before navigation...');
            const saved = await this.saveCurrentAnswer(false);
            if (!saved) {
                console.log('❌ Failed to save current answer, aborting navigation');
                this.isNavigating = false;
                return;
            }
        }

        this.showLoading();

        try {
            const response = await fetch(this.routes.navigate, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]')?.value || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    question_index: questionIndex
                })
            });

            console.log('Navigation response status:', response.status);

            if (response.ok) {
                console.log('✅ Navigation successful, reloading page...');
                this.questionStartTime = Date.now();
                this.hasUnsavedChanges = false;
                window.location.reload();
            } else {
                throw new Error(`Navigation error: ${response.status}`);
            }
        } catch (error) {
            console.error('❌ Navigation error:', error);
            this.showStatus('Navigation error', 'error', 3000);
        } finally {
            this.hideLoading();
            this.isNavigating = false;
        }
    }

    async submitExam() {
        if (!confirm('Are you sure you want to submit the exam? This action cannot be undone.')) {
            console.log('Exam submission cancelled by user');
            return;
        }

        console.log('=== SUBMITTING EXAM ===');
        this.showLoading();

        try {
            if (this.hasUnsavedChanges) {
                console.log('Saving final answer before submission...');
                await this.saveCurrentAnswer(false);
            }

            const response = await fetch(this.routes.complete, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]')?.value || '',
                    'Accept': 'application/json'
                }
            });

            console.log('Submit response status:', response.status);

            if (response.ok) {
                console.log('✅ Exam submitted successfully');
                this.clearAllIntervals();
                this.hasUnsavedChanges = false;

                this.showStatus('Exam submitted successfully', 'success', 3000);

                setTimeout(() => {
                    window.location.href = this.routes.result;
                }, 2000);
            } else {
                throw new Error(`Submit error: ${response.status}`);
            }
        } catch (error) {
            console.error('❌ Submit error:', error);
            this.showStatus('Error submitting exam', 'error', 3000);
        } finally {
            this.hideLoading();
        }
    }

    async pauseExam() {
        if (!confirm('Are you sure you want to pause the exam?')) {
            console.log('Exam pause cancelled by user');
            return;
        }

        console.log('=== PAUSING EXAM ===');
        this.showLoading();

        try {
            if (this.hasUnsavedChanges) {
                console.log('Saving current answer before pausing...');
                await this.saveCurrentAnswer(false);
            }

            const response = await fetch(this.routes.pause, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]')?.value || '',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                console.log('✅ Exam paused successfully');
                this.clearAllIntervals();
                this.hasUnsavedChanges = false;

                this.showStatus('Exam paused successfully', 'success', 2000);

                setTimeout(() => {
                    window.location.href = this.routes.examsIndex;
                }, 2000);
            } else {
                throw new Error(`Pause error: ${response.status}`);
            }
        } catch (error) {
            console.error('❌ Pause error:', error);
            this.showStatus('Error pausing exam', 'error', 3000);
        } finally {
            this.hideLoading();
        }
    }

    timeExpired() {
        console.log('=== TIME EXPIRED ===');
        this.clearAllIntervals();
        this.hasUnsavedChanges = false;

        this.showStatus('Time expired! Submitting exam...', 'error', 5000);

        setTimeout(() => {
            this.submitExam();
        }, 3000);
    }

    clearAllIntervals() {
        console.log('=== CLEARING ALL INTERVALS ===');
        
        const intervals = [
            { name: 'autoSave', ref: this.autoSaveInterval },
            { name: 'activityUpdate', ref: this.activityUpdateInterval },
            { name: 'mainTimer', ref: this.mainTimerInterval },
            { name: 'questionTimer', ref: this.questionTimerInterval },
            { name: 'statusCheck', ref: this.statusCheckInterval }
        ];

        intervals.forEach(interval => {
            if (interval.ref) {
                clearInterval(interval.ref);
                console.log(`✅ Cleared ${interval.name} interval`);
            }
        });

        this.autoSaveInterval = null;
        this.activityUpdateInterval = null;
        this.mainTimerInterval = null;
        this.questionTimerInterval = null;
        this.statusCheckInterval = null;
    }

    setsAreEqual(set1, set2) {
        if (set1.size !== set2.size) return false;
        for (let item of set1) {
            if (!set2.has(item)) return false;
        }
        return true;
    }

    updateSaveButtonState() {
        const saveBtn = document.getElementById('saveAnswerBtn');
        const autoSaveStatus = document.getElementById('autoSaveStatus');

        if (saveBtn) {
            if (this.hasUnsavedChanges) {
                saveBtn.classList.add('has-changes');
                saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Save Changes';
            } else {
                saveBtn.classList.remove('has-changes');
                saveBtn.innerHTML = '<i class="fas fa-check me-2"></i>Saved';
            }
        }

        if (autoSaveStatus) {
            autoSaveStatus.style.opacity = this.hasUnsavedChanges ? '0.5' : '1';
        }
    }

    showAutoSaveIndicator() {
        const autoSaveStatus = document.getElementById('autoSaveStatus');
        if (autoSaveStatus) {
            autoSaveStatus.classList.add('just-saved');
            setTimeout(() => {
                autoSaveStatus.classList.remove('just-saved');
            }, 2000);
        }
    }

    showStatus(message, type, duration = 3000) {
        const statusBanner = document.getElementById('statusBanner');
        const statusMessage = document.getElementById('statusMessage');

        if (!statusBanner || !statusMessage) {
            console.warn('❌ Status banner elements not found');
            return;
        }

        statusBanner.className = `notification status-${type}`;
        statusMessage.textContent = message;
        statusBanner.classList.remove('hidden');
        statusBanner.classList.add('show');

        console.log(`📢 Status: ${message} (${type})`);

        setTimeout(() => {
            statusBanner.classList.remove('show');
            statusBanner.classList.add('hidden');
        }, duration);
    }

    showLoading() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.classList.remove('hidden');
            console.log('⏳ Loading overlay shown');
        }
    }

    hideLoading() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
            console.log('✅ Loading overlay hidden');
        }
    }

    async reviewAnswers() {
        console.log('=== REVIEW ANSWERS ===');
        try {
            const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
            const reviewContent = document.getElementById('reviewContent');

            reviewContent.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
            modal.show();

            let summaryHtml = '<div class="review-summary">';
            summaryHtml += `<p><strong>Progress:</strong> ${document.getElementById('answeredCount')?.textContent || '0'} / ${this.totalQuestions} questions answered</p>`;
            summaryHtml += `<p><strong>Time Remaining:</strong> ${document.getElementById('timeRemaining')?.textContent || '00:00:00'}</p>`;
            summaryHtml += '</div>';

            reviewContent.innerHTML = summaryHtml;
            console.log('✅ Review modal opened');
        } catch (error) {
            console.error('❌ Error showing review:', error);
            this.showStatus('Error showing review', 'error', 3000);
        }
    }
}

// Global functions for HTML onclick events
function navigateToQuestion(index) {
    console.log(`Global navigateToQuestion called: ${index}`);
    if (window.examManager) {
        window.examManager.navigateToQuestion(index);
    } else {
        console.error('❌ ExamManager not found');
    }
}

function submitExam() {
    console.log('Global submitExam called');
    if (window.examManager) {
        window.examManager.submitExam();
    } else {
        console.error('❌ ExamManager not found');
    }
}

function pauseExam() {
    console.log('Global pauseExam called');
    if (window.examManager) {
        window.examManager.pauseExam();
    } else {
        console.error('❌ ExamManager not found');
    }
}

function reviewAnswers() {
    console.log('Global reviewAnswers called');
    if (window.examManager) {
        window.examManager.reviewAnswers();
    } else {
        console.error('❌ ExamManager not found');
    }
}

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    console.log('=== PAGE UNLOAD ===');
    if (window.examManager) {
        window.examManager.clearAllIntervals();
    }
});