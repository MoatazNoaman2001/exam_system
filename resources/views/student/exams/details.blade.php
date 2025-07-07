@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    :root {
        --pmp-primary: #2563eb;
        --pmp-primary-light: #3b82f6;
        --pmp-primary-bg: #eff6ff;
        --pmp-success: #059669;
        --pmp-success-light: #34d399;
        --pmp-success-bg: #ecfdf5;
        --pmp-warning: #d97706;
        --pmp-warning-light: #fbbf24;
        --pmp-warning-bg: #fffbeb;
        --pmp-danger: #dc2626;
        --pmp-danger-light: #f87171;
        --pmp-danger-bg: #fef2f2;
        --pmp-gray-50: #f9fafb;
        --pmp-gray-100: #f3f4f6;
        --pmp-gray-200: #e5e7eb;
        --pmp-gray-300: #d1d5db;
        --pmp-gray-400: #9ca3af;
        --pmp-gray-500: #6b7280;
        --pmp-gray-600: #4b5563;
        --pmp-gray-700: #374151;
        --pmp-gray-800: #1f2937;
        --pmp-gray-900: #111827;
    }

    body {
        font-family: 'Tajawal', 'Cairo', sans-serif;
        background: linear-gradient(135deg, var(--pmp-gray-50) 0%, #e0f2fe 100%);
        min-height: 100vh;
    }

    .exam-details-container {
        padding: 2rem 0;
        max-width: 1400px;
        margin: 0 auto;
    }

    .exam-hero {
        background: linear-gradient(135deg, var(--pmp-primary) 0%, var(--pmp-primary-light) 100%);
        border-radius: 2rem;
        padding: 3rem;
        color: white;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }

    .exam-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
        animation: float 20s linear infinite;
    }

    @keyframes float {
        0% { transform: translateY(0) rotate(0deg); }
        100% { transform: translateY(-100px) rotate(360deg); }
    }

    .exam-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        position: relative;
        z-index: 2;
    }

    .exam-subtitle {
        font-size: 1.25rem;
        opacity: 0.9;
        position: relative;
        z-index: 2;
    }

    .back-button {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        position: relative;
        z-index: 2;
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
        transform: translateY(-2px);
    }

    .status-badge {
        position: absolute;
        top: 2rem;
        right: 2rem;
        z-index: 3;
    }

    .badge-active {
        background: var(--pmp-success);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        animation: pulse 2s infinite;
    }

    .badge-paused {
        background: var(--pmp-warning);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
    }

    .card {
        border: none;
        border-radius: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        background: white;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background: linear-gradient(135deg, var(--pmp-gray-50) 0%, var(--pmp-gray-100) 100%);
        border-bottom: 2px solid var(--pmp-gray-200);
        border-radius: 1.5rem 1.5rem 0 0 !important;
        padding: 1.5rem 2rem;
    }

    .card-header h5 {
        color: var(--pmp-gray-800);
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-body {
        padding: 2rem;
    }

    .stat-card {
        background: linear-gradient(135deg, var(--pmp-primary-bg) 0%, rgba(239, 246, 255, 0.5) 100%);
        border: 2px solid var(--pmp-primary);
        border-radius: 1.5rem;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(37, 99, 235, 0.1), transparent);
        transform: rotate(45deg);
        transition: all 0.3s ease;
        opacity: 0;
    }

    .stat-card:hover::before {
        animation: shimmer 1.5s ease-in-out;
        opacity: 1;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    .stat-card:hover {
        transform: translateY(-8px);
        border-color: var(--pmp-primary-light);
        box-shadow: 0 15px 35px rgba(37, 99, 235, 0.2);
    }

    .stat-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--pmp-primary), var(--pmp-primary-light));
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 2rem;
        position: relative;
        z-index: 2;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--pmp-primary);
        line-height: 1;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
    }

    .stat-label {
        font-size: 1rem;
        color: var(--pmp-gray-600);
        font-weight: 500;
        position: relative;
        z-index: 2;
    }

    .instructions-list {
        display: grid;
        gap: 1rem;
    }

    .instruction-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, var(--pmp-success-bg) 0%, rgba(236, 253, 245, 0.5) 100%);
        border: 2px solid var(--pmp-success);
        border-radius: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .instruction-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: linear-gradient(to bottom, var(--pmp-success), var(--pmp-success-light));
    }

    .instruction-item:hover {
        transform: translateX(8px);
        border-color: var(--pmp-success-light);
        box-shadow: 0 8px 25px rgba(5, 150, 105, 0.15);
    }

    .instruction-item i {
        font-size: 1.5rem;
        color: var(--pmp-success);
        min-width: 24px;
    }

    .instruction-item span {
        color: var(--pmp-gray-700);
        font-weight: 500;
        font-size: 1.1rem;
    }

    .start-exam-card {
        position: sticky;
        top: 2rem;
        background: linear-gradient(135deg, white 0%, var(--pmp-gray-50) 100%);
        border: 3px solid var(--pmp-primary);
        box-shadow: 0 15px 40px rgba(37, 99, 235, 0.15);
    }

    .start-exam-card .card-header {
        background: linear-gradient(135deg, var(--pmp-primary) 0%, var(--pmp-primary-light) 100%);
        color: white;
        border: none;
    }

    .exam-summary {
        display: grid;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: var(--pmp-gray-50);
        border-radius: 0.75rem;
        border: 1px solid var(--pmp-gray-200);
        transition: all 0.3s ease;
    }

    .summary-item:hover {
        background: var(--pmp-primary-bg);
        border-color: var(--pmp-primary);
    }

    .summary-item strong {
        color: var(--pmp-gray-800);
        font-weight: 600;
    }

    .summary-item span {
        color: var(--pmp-primary);
        font-weight: 600;
    }

    .btn-start-exam {
        background: linear-gradient(135deg, var(--pmp-success) 0%, var(--pmp-success-light) 100%);
        border: none;
        padding: 1rem 2rem;
        font-size: 1.25rem;
        font-weight: 600;
        border-radius: 1rem;
        color: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-start-exam:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(5, 150, 105, 0.3);
        color: white;
    }

    .btn-start-exam:active {
        transform: translateY(-1px);
    }

    .btn-resume-exam {
        background: linear-gradient(135deg, var(--pmp-warning) 0%, var(--pmp-warning-light) 100%);
        border: none;
        padding: 1rem 2rem;
        font-size: 1.25rem;
        font-weight: 600;
        border-radius: 1rem;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-resume-exam:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(217, 119, 6, 0.3);
        color: white;
    }

    .btn-outline-primary {
        border: 2px solid var(--pmp-primary);
        color: var(--pmp-primary);
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background: var(--pmp-primary);
        color: white;
        transform: translateY(-2px);
    }

    .alert {
        border: none;
        border-radius: 1rem;
        padding: 1.5rem;
        font-weight: 500;
    }

    .alert-info {
        background: linear-gradient(135deg, var(--pmp-primary-bg) 0%, rgba(239, 246, 255, 0.7) 100%);
        color: var(--pmp-primary);
        border: 2px solid var(--pmp-primary);
    }

    .alert-warning {
        background: linear-gradient(135deg, var(--pmp-warning-bg) 0%, rgba(255, 251, 235, 0.7) 100%);
        color: var(--pmp-warning);
        border: 2px solid var(--pmp-warning);
    }

    .table {
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .table thead th {
        background: linear-gradient(135deg, var(--pmp-gray-100) 0%, var(--pmp-gray-200) 100%);
        border: none;
        font-weight: 600;
        color: var(--pmp-gray-800);
        padding: 1rem;
    }

    .table tbody td {
        padding: 1rem;
        border-color: var(--pmp-gray-200);
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: var(--pmp-primary-bg);
    }

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .modal-content {
        border: none;
        border-radius: 1.5rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, var(--pmp-primary) 0%, var(--pmp-primary-light) 100%);
        color: white;
        border-radius: 1.5rem 1.5rem 0 0;
        border: none;
        padding: 2rem;
    }

    .question-preview {
        background: linear-gradient(135deg, var(--pmp-gray-50) 0%, rgba(243, 244, 246, 0.5) 100%);
        border: 2px solid var(--pmp-primary);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .question-preview::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 6px;
        background: linear-gradient(to bottom, var(--pmp-primary), var(--pmp-primary-light));
        border-radius: 3px;
    }

    .question-number {
        color: var(--pmp-primary);
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }

    .question-text {
        color: var(--pmp-gray-700);
        font-weight: 500;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .options-preview {
        display: grid;
        gap: 0.75rem;
    }

    .option-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: white;
        border: 2px solid var(--pmp-gray-200);
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }

    .option-item:hover {
        border-color: var(--pmp-primary);
        background: var(--pmp-primary-bg);
    }

    .option-letter {
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, var(--pmp-primary) 0%, var(--pmp-primary-light) 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
    }

    .option-text {
        color: var(--pmp-gray-700);
        font-weight: 500;
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
    }

    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 6px solid var(--pmp-gray-200);
        border-top: 6px solid var(--pmp-primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .hidden { display: none !important; }

    @media (max-width: 768px) {
        .exam-hero {
            padding: 2rem;
            text-align: center;
        }

        .exam-title {
            font-size: 2rem;
        }

        .exam-subtitle {
            font-size: 1.1rem;
        }

        .status-badge {
            position: static;
            margin-bottom: 1rem;
        }

        .stat-card {
            padding: 1.5rem;
        }

        .stat-number {
            font-size: 2rem;
        }

        .start-exam-card {
            position: static;
            margin-top: 2rem;
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }

    .animate-slide-up {
        animation: slideUp 0.8s ease-out forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container-fluid" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="exam-details-container">
        <!-- Hero Section -->
        <div class="exam-hero animate-fade-in">
            @if($activeSession)
                <div class="status-badge">
                    <span class="badge {{ $activeSession->status === 'in_progress' ? 'badge-active' : 'badge-paused' }}">
                        <i class="fas fa-{{ $activeSession->status === 'in_progress' ? 'play' : 'pause' }} me-2"></i>
                        {{ $activeSession->status === 'in_progress' ? __('lang.exam_in_progress') : __('lang.exam_paused') }}
                    </span>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="flex-grow-1">
                    <h1 class="exam-title">{{ $exam->title }}</h1>
                    <p class="exam-subtitle">{{ $exam->description_localized }}</p>
                </div>
                <a href="{{ route('student.exams.index') }}" class="back-button">
                    <i class="fas fa-arrow-left me-2"></i>
                    {{ __('lang.back_to_exams') }}
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Exam Statistics -->
                <div class="card mb-4 animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="card-header">
                        <h5>
                            <i class="fas fa-chart-bar"></i>
                            {{ __('lang.exam_statistics') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-question-circle"></i>
                                    </div>
                                    <div class="stat-number">{{ $exam->number_of_questions }}</div>
                                    <div class="stat-label">{{ __('lang.total_questions') }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="stat-number">{{ $exam->time }}</div>
                                    <div class="stat-label">{{ __('lang.minutes') }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-redo"></i>
                                    </div>
                                    <div class="stat-number">{{ $previousSessions->count() }}</div>
                                    <div class="stat-label">{{ __('lang.attempts_made') }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <div class="stat-number">{{ $previousSessions->max('score') ?? 0 }}%</div>
                                    <div class="stat-label">{{ __('lang.best_score') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Exam Instructions -->
                <div class="card mb-4 animate-slide-up" style="animation-delay: 0.4s;">
                    <div class="card-header">
                        <h5>
                            <i class="fas fa-info-circle"></i>
                            {{ __('lang.exam_instructions') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="instructions-list">
                            <div class="instruction-item">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ __('lang.exam_duration_180_minutes') }}</span>
                            </div>
                            <div class="instruction-item">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ __('lang.can_pause_and_resume') }}</span>
                            </div>
                            <div class="instruction-item">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ __('lang.auto_save_answers') }}</span>
                            </div>
                            <div class="instruction-item">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ __('lang.navigate_between_questions') }}</span>
                            </div>
                            <div class="instruction-item">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ __('lang.ensure_stable_internet') }}</span>
                            </div>
                            <div class="instruction-item">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ __('lang.exam_auto_submit_time_up') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Previous Sessions -->
                @if($previousSessions->count() > 0)
                <div class="card mb-4 animate-slide-up" style="animation-delay: 0.6s;">
                    <div class="card-header">
                        <h5>
                            <i class="fas fa-history"></i>
                            {{ __('lang.previous_attempts') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('lang.date') }}</th>
                                        <th>{{ __('lang.score') }}</th>
                                        <th>{{ __('lang.duration') }}</th>
                                        <th>{{ __('lang.status') }}</th>
                                        <th>{{ __('lang.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($previousSessions as $session)
                                    <tr>
                                        <td>{{ $session->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            @if($session->score !== null)
                                                <span class="badge bg-{{ $session->score >= 70 ? 'success' : ($session->score >= 50 ? 'warning' : 'danger') }}">
                                                    {{ number_format($session->score, 1) }}%
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('lang.incomplete') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ gmdate('H:i:s', $session->total_time_spent) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $session->status === 'completed' ? 'success' : ($session->status === 'in_progress' ? 'primary' : 'warning') }}">
                                                {{ __('lang.' . $session->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($session->status === 'completed')
                                                <a href="{{ route('student.exams.result', $session->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i>
                                                    {{ __('lang.view_results') }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Start Exam Card -->
                <div class="card start-exam-card animate-slide-up" style="animation-delay: 0.8s;">
                    <div class="card-header">
                        <h5>
                            <i class="fas fa-{{ $activeSession ? 'play' : 'rocket' }}"></i>
                            {{ $activeSession ? __('lang.continue_exam') : __('lang.ready_to_start') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($activeSession)
                            <!-- Active Session Info -->
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>{{ __('lang.active_session_found') }}</strong><br>
                                <small>{{ __('lang.started') }}: {{ $activeSession->started_at->format('M d, Y H:i') }}</small><br>
                                <small>{{ __('lang.time_remaining') }}: {{ gmdate('H:i:s', $activeSession->remaining_time) }}</small>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('student.exams.take', $activeSession->id) }}" class="btn btn-resume-exam btn-lg">
                                    <i class="fas fa-play me-2"></i>
                                    {{ $activeSession->status === 'paused' ? __('lang.resume_exam') : __('lang.continue_exam') }}
                                </a>
                                
                                <form action="{{ route('student.exams.delete-session', $activeSession->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('{{ __('lang.confirm_delete_session') }}')">
                                        <i class="fas fa-trash me-2"></i>
                                        {{ __('lang.delete_session') }}
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- New Exam -->
                            <div class="exam-summary mb-4">
                                <div class="summary-item">
                                    <strong>{{ __('lang.exam_duration') }}:</strong>
                                    <span>{{ $exam->time }} {{ __('lang.minutes') }}</span>
                                </div>
                                <div class="summary-item">
                                    <strong>{{ __('lang.total_questions') }}:</strong>
                                    <span>{{ $exam->number_of_questions }}</span>
                                </div>
                                <div class="summary-item">
                                    <strong>{{ __('lang.passing_score') }}:</strong>
                                    <span>70%</span>
                                </div>
                                <div class="summary-item">
                                    <strong>{{ __('lang.question_types') }}:</strong>
                                    <span>{{ __('lang.mixed') }}</span>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-start-exam btn-lg" onclick="startExam()" id="startExamBtn">
                                    <i class="fas fa-rocket me-2"></i>
                                    {{ __('lang.start_exam') }}
                                </button>
                                <button class="btn btn-outline-primary" onclick="showExamPreview()">
                                    <i class="fas fa-eye me-2"></i>
                                    {{ __('lang.preview_exam') }}
                                </button>
                            </div>

                            <div class="alert alert-info mt-3">
                                <i class="fas fa-lightbulb me-2"></i>
                                <small>{{ __('lang.exam_start_info') }}</small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card mt-4 animate-slide-up" style="animation-delay: 1s;">
                    <div class="card-header">
                        <h6>
                            <i class="fas fa-tachometer-alt"></i>
                            {{ __('lang.quick_stats') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border-end">
                                    <h4 class="text-primary mb-0">{{ $exam->questions()->count() }}</h4>
                                    <small class="text-muted">{{ __('lang.questions_available') }}</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <h4 class="text-success mb-0">{{ number_format($exam->time / $exam->number_of_questions, 1) }}</h4>
                                <small class="text-muted">{{ __('lang.min_per_question') }}</small>
                            </div>
                        </div>
                        
                        @if($previousSessions->count() > 0)
                        <div class="row text-center mt-3 pt-3 border-top">
                            <div class="col-6">
                                <h5 class="text-warning mb-0">{{ number_format($previousSessions->avg('total_time_spent') / 60, 0) }}m</h5>
                                <small class="text-muted">{{ __('lang.avg_time') }}</small>
                            </div>
                            <div class="col-6">
                                <h5 class="text-info mb-0">{{ number_format($previousSessions->avg('score'), 1) }}%</h5>
                                <small class="text-muted">{{ __('lang.avg_score') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Exam Preview Modal -->
<div class="modal fade" id="examPreviewModal" tabindex="-1" aria-labelledby="examPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="examPreviewModalLabel">
                    <i class="fas fa-eye me-2"></i>
                    {{ __('lang.exam_preview') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="exam-preview-content">
                    @foreach($exam->questions->take(3) as $index => $question)
                    <div class="question-preview">
                        <h6 class="question-number">
                            <i class="fas fa-question-circle me-2"></i>
                            {{ __('lang.question') }} {{ $index + 1 }}
                        </h6>
                        <p class="question-text">{{ $question->question_text }}</p>
                        <div class="options-preview">
                            @foreach($question->answers->take(4) as $answer)
                            <div class="option-item">
                                <span class="option-letter">{{ chr(65 + $loop->index) }}</span>
                                <span class="option-text">{{ $answer->answer_text }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="text-center mt-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ __('lang.preview_showing_sample_questions') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    {{ __('lang.close') }}
                </button>
                <button type="button" class="btn btn-success" onclick="startExam()" data-bs-dismiss="modal">
                    <i class="fas fa-rocket me-2"></i>
                    {{ __('lang.start_exam') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay hidden" id="loadingOverlay">
    <div class="text-center">
        <div class="loading-spinner"></div>
        <p class="mt-3 fw-bold text-primary">{{ __('lang.starting_exam') }}...</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function startExam() {
        // Show confirmation dialog
        if (confirm('{{ __("lang.confirm_start_exam_message") }}')) {
            // Create and submit form to start exam
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("student.exams.start", $exam->id) }}';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Show loading overlay
            showLoading();
            
            // Add form to document and submit
            document.body.appendChild(form);
            form.submit();
        }
    }

    function showExamPreview() {
        const modal = new bootstrap.Modal(document.getElementById('examPreviewModal'));
        modal.show();
    }

    function showLoading() {
        document.getElementById('loadingOverlay').classList.remove('hidden');
        document.getElementById('startExamBtn').disabled = true;
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').classList.add('hidden');
        document.getElementById('startExamBtn').disabled = false;
    }

    // Add loading animation on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Add staggered animation to stat cards
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            card.style.animationDelay = (0.1 * index) + 's';
            card.classList.add('animate-fade-in');
        });

        // Add animation to instruction items
        const instructionItems = document.querySelectorAll('.instruction-item');
        instructionItems.forEach((item, index) => {
            setTimeout(() => {
                item.style.transform = 'translateX(0)';
                item.style.opacity = '1';
            }, 100 * index);
        });

        // Add CSRF token to meta tag if not present
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const token = document.createElement('meta');
            token.name = 'csrf-token';
            token.content = '{{ csrf_token() }}';
            document.head.appendChild(token);
        }

        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 'Enter':
                        e.preventDefault();
                        @if($activeSession)
                            window.location.href = '{{ route("student.exams.take", $activeSession->id) }}';
                        @else
                            startExam();
                        @endif
                        break;
                    case 'p':
                        e.preventDefault();
                        showExamPreview();
                        break;
                }
            }
        });

        // Add intersection observer for animations
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

        // Observe elements for scroll animations
        document.querySelectorAll('.card, .instruction-item').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });
    });

    // Add hover effects for better interactivity
    document.addEventListener('DOMContentLoaded', function() {
        // Add ripple effect to buttons
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    left: ${x}px;
                    top: ${y}px;
                    pointer-events: none;
                `;
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    });

    // Auto-refresh session status if there's an active session
    @if($activeSession)
    setInterval(function() {
        fetch('{{ route("student.exams.progress", $activeSession->id) }}')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const remainingTime = data.data.remaining_time;
                    const timeElement = document.querySelector('.alert-warning small:last-child');
                    if (timeElement && remainingTime <= 0) {
                        location.reload(); // Refresh if time expired
                    }
                }
            })
            .catch(error => console.error('Error checking session status:', error));
    }, 60000); // Check every minute
    @endif
</script>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
        alert.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 5000);
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show position-fixed';
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
        alert.innerHTML = `
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 5000);
    });
</script>
@endif
@endsection