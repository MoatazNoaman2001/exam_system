@extends('layouts.app')

@section('title', __('lang.Tests'))

@section('content')
    <!-- CSS and Font Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --edu-primary-blue: #3b82f6;
            --edu-primary-blue-light: #60a5fa;
            --edu-primary-blue-bg: #eff6ff;
            --edu-success-green: #10b981;
            --edu-success-green-light: #34d399;
            --edu-success-green-bg: #ecfdf5;
            --edu-warning-amber: #f59e0b;
            --edu-warning-amber-light: #fbbf24;
            --edu-warning-amber-bg: #fffbeb;
            --edu-danger-red: #ef4444;
            --edu-danger-red-light: #f87171;
            --edu-danger-red-bg: #fef2f2;
            --edu-gray-50: #f9fafb;
            --edu-gray-100: #f3f4f6;
            --edu-gray-200: #e5e7eb;
            --edu-gray-300: #d1d5db;
            --edu-gray-400: #9ca3af;
            --edu-gray-500: #6b7280;
            --edu-gray-600: #4b5563;
            --edu-gray-700: #374151;
            --edu-gray-800: #1f2937;
            --edu-gray-900: #111827;
        }

        body {
            font-family: 'Tajawal', 'Cairo', sans-serif;
            background: linear-gradient(135deg, var(--edu-gray-50) 0%, #e0f2fe 100%);
            min-height: 100vh;
        }

        .exams-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .exams-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--edu-gray-900);
            font-family: 'Cairo', 'Tajawal', sans-serif;
            margin: 0;
            position: relative;
        }

        .exams-title::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--edu-primary-blue), var(--edu-primary-blue-light));
            border-radius: 2px;
        }

        .back-button {
            background: linear-gradient(135deg, var(--edu-gray-100) 0%, var(--edu-gray-200) 100%);
            color: var(--edu-gray-700);
            border: 2px solid var(--edu-gray-300);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            backdrop-filter: blur(10px);
        }

        .back-button:hover {
            background: linear-gradient(135deg, var(--edu-gray-200) 0%, var(--edu-gray-300) 100%);
            color: var(--edu-gray-800);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .exams-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .exam-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border: 2px solid var(--edu-gray-100);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .main-content{
            height: 100vh;
        }

        .exam-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border-color: var(--edu-primary-blue);
        }

        .exam-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--edu-primary-blue), var(--edu-primary-blue-light));
            border-radius: 1.5rem 1.5rem 0 0;
        }

        .exam-status-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            z-index: 2;
        }

        .status-active {
            background: var(--edu-success-green);
            color: white;
            animation: pulse 2s infinite;
        }

        .status-paused {
            background: var(--edu-warning-amber);
            color: white;
        }

        .status-available {
            background: var(--edu-primary-blue-bg);
            color: var(--edu-primary-blue);
            border: 2px solid var(--edu-primary-blue);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .exam-header {
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding-top: 0.5rem;
        }

        .exam-icon {
            width: 80px;
            height: 80px;
            border-radius: 1rem;
            background: linear-gradient(135deg, var(--edu-primary-blue-bg) 0%, rgba(239, 246, 255, 0.5) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--edu-primary-blue);
            flex-shrink: 0;
            transition: all 0.3s ease;
            border: 2px solid var(--edu-primary-blue);
        }

        .exam-card:hover .exam-icon {
            transform: scale(1.1) rotate(5deg);
            background: linear-gradient(135deg, var(--edu-primary-blue), var(--edu-primary-blue-light));
            color: white;
        }

        .exam-title-section {
            flex: 1;
        }

        .exam-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--edu-gray-900);
            margin: 0 0 0.5rem 0;
            font-family: 'Cairo', 'Tajawal', sans-serif;
            line-height: 1.3;
        }

        .exam-description {
            color: var(--edu-gray-600);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .exam-stats {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-item {
            background: linear-gradient(135deg, var(--edu-gray-50) 0%, var(--edu-gray-100) 100%);
            padding: 1rem;
            border-radius: 1rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid var(--edu-gray-200);
        }

        .exam-card:hover .stat-item {
            background: linear-gradient(135deg, var(--edu-primary-blue-bg) 0%, rgba(239, 246, 255, 0.5) 100%);
            border-color: var(--edu-primary-blue);
        }

        .stat-number {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--edu-primary-blue);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--edu-gray-500);
            font-weight: 500;
            margin-top: 0.25rem;
        }

        .exam-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 2px solid var(--edu-gray-100);
            margin-top: auto;
            gap: 1rem;
        }

        .exam-difficulty {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            border: 2px solid;
        }

        .difficulty-easy {
            background: var(--edu-success-green-bg);
            color: var(--edu-success-green);
            border-color: var(--edu-success-green);
        }

        .difficulty-medium {
            background: var(--edu-warning-amber-bg);
            color: var(--edu-warning-amber);
            border-color: var(--edu-warning-amber);
        }

        .difficulty-hard {
            background: var(--edu-danger-red-bg);
            color: var(--edu-danger-red);
            border-color: var(--edu-danger-red);
        }

        .exam-action-btn {
            background: linear-gradient(135deg, var(--edu-primary-blue) 0%, var(--edu-primary-blue-light) 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .exam-action-btn:hover {
            background: linear-gradient(135deg, var(--edu-primary-blue-light) 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .btn-resume {
            background: linear-gradient(135deg, var(--edu-warning-amber) 0%, var(--edu-warning-amber-light) 100%);
        }

        .btn-resume:hover {
            background: linear-gradient(135deg, var(--edu-warning-amber-light) 0%, #d97706 100%);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
        }

        .btn-continue {
            background: linear-gradient(135deg, var(--edu-success-green) 0%, var(--edu-success-green-light) 100%);
        }

        .btn-continue:hover {
            background: linear-gradient(135deg, var(--edu-success-green-light) 0%, #047857 100%);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--edu-gray-500);
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: var(--edu-gray-400);
        }

        .session-info {
            background: linear-gradient(135deg, var(--edu-warning-amber-bg) 0%, rgba(255, 251, 235, 0.5) 100%);
            border: 2px solid var(--edu-warning-amber);
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        .session-info-title {
            font-weight: 600;
            color: var(--edu-warning-amber);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .session-info-details {
            color: var(--edu-gray-700);
            line-height: 1.4;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .exams-container {
                margin: 1rem;
            }

            .exams-title {
                font-size: 2rem;
            }

            .exams-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .exam-card {
                padding: 1.5rem;
            }

            .exam-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .exam-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
                margin: 0 auto;
            }

            .exam-stats {
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
            }

            .exam-footer {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .exam-action-btn {
                justify-content: center;
                width: 100%;
            }
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 1.5rem;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--edu-primary-blue) 0%, var(--edu-primary-blue-light) 100%);
            color: white;
            border-radius: 1.5rem 1.5rem 0 0;
            border: none;
            padding: 2rem;
        }

        .modal-body {
            padding: 2rem;
        }

        .exam-detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 1.5rem 0;
        }

        .detail-card {
            background: linear-gradient(135deg, var(--edu-gray-50) 0%, var(--edu-gray-100) 100%);
            padding: 1.5rem;
            border-radius: 1rem;
            text-align: center;
            border: 2px solid var(--edu-gray-200);
            transition: all 0.3s ease;
        }

        .detail-card:hover {
            border-color: var(--edu-primary-blue);
            background: linear-gradient(135deg, var(--edu-primary-blue-bg) 0%, rgba(239, 246, 255, 0.5) 100%);
        }

        .detail-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--edu-primary-blue-bg) 0%, var(--edu-primary-blue) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: var(--edu-primary-blue);
            font-size: 1.5rem;
        }

        .detail-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--edu-gray-900);
            margin-bottom: 0.25rem;
        }

        .detail-label {
            font-size: 0.875rem;
            color: var(--edu-gray-500);
            font-weight: 500;
        }

        .instructions-box {
            background: linear-gradient(135deg, var(--edu-warning-amber-bg) 0%, rgba(255, 251, 235, 0.5) 100%);
            border: 2px solid var(--edu-warning-amber);
            border-radius: 1rem;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .instructions-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--edu-warning-amber);
            margin-bottom: 1rem;
        }

        .instructions-text {
            color: var(--edu-gray-700);
            line-height: 1.7;
            font-size: 1rem;
        }

        .modal-footer {
            border-top: 2px solid var(--edu-gray-100);
            padding: 1.5rem 2rem 2rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-start-exam {
            background: linear-gradient(135deg, var(--edu-success-green) 0%, var(--edu-success-green-light) 100%);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-start-exam:hover {
            background: linear-gradient(135deg, var(--edu-success-green-light) 0%, #047857 100%);
            transform: translateY(-2px);
            color: white;
        }

        .btn-cancel {
            background: var(--edu-gray-100);
            color: var(--edu-gray-700);
            border: 2px solid var(--edu-gray-300);
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: var(--edu-gray-200);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .exam-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .exam-card:nth-child(1) { animation-delay: 0.1s; }
        .exam-card:nth-child(2) { animation-delay: 0.2s; }
        .exam-card:nth-child(3) { animation-delay: 0.3s; }
        .exam-card:nth-child(4) { animation-delay: 0.4s; }

        @media (prefers-reduced-motion: reduce) {
            .exam-card,
            .exam-icon,
            .exam-action-btn {
                transition: none !important;
                animation: none !important;
            }
        }
        .modal-backdrop {
            display: none;
        }

    </style>

    <div class="container my-4" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <div class="exams-container">
            <div class="exams-header">
                <h1 class="exams-title">{{ __('lang.Tests') }}</h1>
                <a href="{{ route('student.sections') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('lang.back_to_sections') }}
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="exams-grid">
                @forelse($exams as $index => $exam)
                    <div class="exam-card" 
                         data-exam-id="{{ $exam->id }}"
                         data-exam-title="{{ $exam->title }}"
                         data-exam-description="{{ $exam->description_localized }}"
                         data-exam-questions="{{ $exam->number_of_questions }}"
                         data-exam-duration="{{ $exam->time }}"
                         data-exam-difficulty="medium"
                         onclick="showExamDetails(this)">
                        
                        {{-- Status Badge --}}
                        @if($exam->has_active_session)
                            <div class="exam-status-badge status-{{ $exam->session_status }}">
                                <i class="fas fa-{{ $exam->session_status === 'in_progress' ? 'play' : 'pause' }} me-1"></i>
                                {{ $exam->session_status === 'in_progress' ? __('lang.in_progress') : __('lang.paused') }}
                            </div>
                        @else
                            <div class="exam-status-badge status-available">
                                <i class="fas fa-check me-1"></i>
                                {{ __('lang.available') }}
                            </div>
                        @endif
                        
                        <div class="exam-header">
                            <div class="exam-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="exam-title-section">
                                <h3 class="exam-title">{{ $exam->title }}</h3>
                            </div>
                        </div>
                        
                        <div class="exam-description">
                            {{ $exam->description_localized ?? __('lang.no_description_available') }}
                        </div>

                        {{-- Active Session Info --}}
                        @if($exam->has_active_session)
                            <div class="session-info">
                                <div class="session-info-title">
                                    <i class="fas fa-info-circle"></i>
                                    {{ __('lang.active_session') }}
                                </div>
                                <div class="session-info-details">
                                    {{ __('lang.you_can_continue_where_left_off') }}
                                </div>
                            </div>
                        @endif
                        
                        <div class="exam-stats">
                            <div class="stat-item">
                                <span class="stat-number">{{ $exam->number_of_questions }}</span>
                                <span class="stat-label">{{ __('lang.questions') }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $exam->time }}</span>
                                <span class="stat-label">{{ __('lang.minutes') }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ number_format($exam->time / $exam->number_of_questions, 1) }}</span>
                                <span class="stat-label">{{ __('lang.min_per_q') }}</span>
                            </div>
                        </div>
                        
                        <div class="exam-footer">
                            <span class="exam-difficulty difficulty-medium">
                                {{ __('lang.difficulty_medium') }}
                            </span>
                            
                            @if($exam->has_active_session)
                                <a href="{{ route('student.exams.take', $exam->active_session_id) }}" 
                                   class="exam-action-btn {{ $exam->session_status === 'in_progress' ? 'btn-continue' : 'btn-resume' }}"
                                   onclick="event.stopPropagation();">
                                    <i class="fas fa-{{ $exam->session_status === 'in_progress' ? 'play' : 'redo' }}"></i>
                                    {{ $exam->session_status === 'in_progress' ? __('lang.continue') : __('lang.resume') }}
                                </a>
                            @else
                                <button class="exam-action-btn" onclick="event.stopPropagation(); showExamDetails(this.closest('.exam-card'))">
                                    <i class="fas fa-info-circle"></i>
                                    {{ __('lang.view_details') }}
                                </button>
                            @endif
                        </div>
                    </div>
                    
                @empty
                    <div class="empty-state">
                        <i class="fas fa-file-alt"></i>
                        <h4>{{ __('lang.no_exams_available') }}</h4>
                        <p>{{ __('lang.no_exams_description') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Exam Details Modal -->
    <div class="modal fade" id="examDetailsModal" tabindex="-1" aria-labelledby="examDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="examDetailsModalLabel">{{ __('lang.exam_details') }}</h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="examDetailsContent">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">
                        {{ __('lang.cancel') }}
                    </button>
                    <button type="button" class="btn-start-exam" onclick="startExamFromModal()"> 
                        <i class="fas fa-rocket me-2"></i>
                        {{ __('lang.start_exam') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentExamId = null;
        let examDetailsModal = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap modal
            const modalElement = document.getElementById('examDetailsModal');
            examDetailsModal = new bootstrap.Modal(modalElement, {
                backdrop: true,
                keyboard: true,
                focus: true
            });

            // Modal event listeners
            modalElement.addEventListener('show.bs.modal', function() {
                const modalBody = this.querySelector('.modal-body');
                if (modalBody) {
                    modalBody.scrollTop = 0;
                }
            });

            modalElement.addEventListener('hidden.bs.modal', function() {
                currentExamId = null;
            });

            // Add CSRF token if not present
            if (!document.querySelector('meta[name="csrf-token"]')) {
                const token = document.createElement('meta');
                token.name = 'csrf-token';
                token.content = '{{ csrf_token() }}';
                document.head.appendChild(token);
            }

            // Add loading animation for cards
            const examCards = document.querySelectorAll('.exam-card');
            examCards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
            });

            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    try {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    } catch (e) {
                        alert.remove();
                    }
                }, 5000);
            });
        });

        function showExamDetails(examElement) {
            // Extract data from the card element's data attributes
            const examData = {
                id: examElement.dataset.examId,
                title: examElement.dataset.examTitle,
                description: examElement.dataset.examDescription,
                questions: parseInt(examElement.dataset.examQuestions) || 0,
                duration: parseInt(examElement.dataset.examDuration) || 0,
                difficulty: examElement.dataset.examDifficulty || 'medium'
            };

            currentExamId = examData.id;

            // Update modal title
            document.getElementById('examDetailsModalLabel').textContent = examData.title;

            // Calculate duration per question
            const durationPerQuestion = examData.questions > 0 ? Math.round(examData.duration / examData.questions) : 0;

            // Build modal content
            const modalContent = `
                <div class="exam-description mb-4">
                    <h5>{{ __('lang.description') }}</h5>
                    <p class="text-muted">${examData.description || '{{ __('lang.no_description_available') }}'}</p>
                </div>
            
                <div class="exam-detail-grid">
                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <div class="detail-value">${examData.questions}</div>
                        <div class="detail-label">{{ __('lang.total_questions') }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="detail-value">${examData.duration} {{ __('lang.min') }}</div>
                        <div class="detail-label">{{ __('lang.total_duration') }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="detail-value">${durationPerQuestion} {{ __('lang.min') }}</div>
                        <div class="detail-label">{{ __('lang.per_question') }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="detail-value">70%</div>
                        <div class="detail-label">{{ __('lang.passing_score') }}</div>
                    </div>
                </div>
            
                <div class="instructions-box">
                    <div class="instructions-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ __('lang.important_instructions') }}
                    </div>
                    <div class="instructions-text">
                        <ul class="mb-0">
                            <li>{{ __('lang.exam_duration_180_minutes') }}</li>
                            <li>{{ __('lang.can_pause_and_resume') }}</li>
                            <li>{{ __('lang.auto_save_answers') }}</li>
                            <li>{{ __('lang.navigate_between_questions') }}</li>
                            <li>{{ __('lang.ensure_stable_internet') }}</li>
                            <li>{{ __('lang.exam_auto_submit_time_up') }}</li>
                        </ul>
                    </div>
                </div>
            `;
            
            document.getElementById('examDetailsContent').innerHTML = modalContent;
            
            // Show modal
            examDetailsModal.show();
        }

        function startExamFromModal() {
            if (currentExamId) {
                // Close modal first
                examDetailsModal.hide();
                
                // Create and submit form to start exam
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/student/exams/${currentExamId}/start`;
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfToken);
                
                // Show loading overlay
                showLoadingOverlay();
                
                // Add form to document and submit
                document.body.appendChild(form);
                form.submit();
            }
        }

        function startExam(examId) {
            if (!examId) return;

            // Create and submit form to start exam
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/student/exams/${examId}/start`;
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);
            
            // Show loading overlay
            showLoadingOverlay();
            
            // Add form to document and submit
            document.body.appendChild(form);
            form.submit();
        }

        function showLoadingOverlay() {
            const loadingHtml = `
                <div class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" 
                     style="background: rgba(255, 255, 255, 0.9); z-index: 9999; backdrop-filter: blur(5px);" id="loadingOverlay">
                    <div class="text-center">
                        <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">{{ __('lang.loading') }}</span>
                        </div>
                        <h5 class="fw-bold text-primary">{{ __('lang.starting_exam') }}...</h5>
                        <p class="text-muted">{{ __('lang.please_wait') }}</p>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', loadingHtml);
        }

        function showAlert(message, type = 'info', duration = 5000) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; max-width: 400px;" role="alert">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', alertHtml);
            
            // Auto-dismiss after duration
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    if (alert.textContent.includes(message)) {
                        try {
                            const bsAlert = new bootstrap.Alert(alert);
                            bsAlert.close();
                        } catch (e) {
                            alert.remove();
                        }
                    }
                });
            }, duration);
        }

        // Handle direct exam start from cards (for active sessions)
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-continue, .btn-resume')) {
                e.preventDefault();
                e.stopPropagation();
                
                const link = e.target.closest('a');
                if (link && link.href) {
                    window.location.href = link.href;
                }
            }
        });

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 'r':
                        e.preventDefault();
                        window.location.reload();
                        break;
                }
            }
        });

        // Add intersection observer for scroll animations
        if ('IntersectionObserver' in window) {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);
            
            // Observe exam cards for scroll animations
            document.querySelectorAll('.exam-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });
        }

        // Remove the session status checking via fetch - let Laravel handle redirects
        document.addEventListener('DOMContentLoaded', function() {
            // Remove the interval for session checking via fetch
            // Laravel will handle session state through normal page loads and redirects
        });

        // Add smooth hover effects
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.exam-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.zIndex = '10';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.zIndex = '1';
                });
            });
        });
    </script>
@endsection