@php
use Carbon\Carbon;
@endphp

@extends('layouts.app')

@section('title', __('lang.my_achievements'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/Achievement.css') }}">

<div class="achievement-dashboard">
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="dashboard-title">{{ __('lang.my_achievement') }} - <span class="username">{{ $user->username }}</span></h1>
        </div>
        <div class="header-ornament"></div>
    </div>

    <div class="dashboard-grid">
        <!-- Points Card -->
        @php
            $fillPercent = ($pointsToNext > 0 && $nextLevel) ? (($totalPoints / ($totalPoints + $pointsToNext)) * 100) : ($currentLevel === 'Legend' ? 100 : 0);
            $levelColors = [
                'Beginner' => '#28a745',
                'Intermediate' => '#ffc107',
                'Professional' => '#fd7e14',
                'Expert | Professional' => '#dc3545',
                'Legend' => '#6f42c1',
            ];
            $color = $levelColors[$currentLevel] ?? '#6c757d';
        @endphp

        <div class="dashboard-card points-card">
            <div class="card-decoration"></div>
            <div class="card-header">
                <span class="card-icon">🎯</span>
                <h2 class="card-title">{{ __('lang.your_points') }}</h2>
            </div>
            <div class="stat-item">
                <span class="stat-label">{{ __('lang.points') }}:</span>
                <span class="stat-value">{{ $totalPoints }} {{ __('lang.point') }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">{{ __('lang.current_level') }}:</span>
                <span class="stat-value">{{ $currentLevel }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">{{ __('lang.next_level') }}:</span>
                <span class="stat-value">
                    @if ($currentLevel === 'Legend')
                        🎉 {{ __('lang.congrats_max_level') }}
                    @else
                        {{ $nextLevel ?? __('lang.no_higher_level') }} – {{ __('lang.after') }} {{ $pointsToNext }} {{ __('lang.point') }}
                    @endif
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-track">
                    <div class="progress-fill" style="width: {{ $fillPercent }}%; background-color: {{ $color }}"></div>
                </div>
                <div class="level-indicator">
                    <span class="current-level">{{ $currentLevel }}</span>
                    <span class="next-level">
                        @if ($currentLevel !== 'Legend')
                            {{ __('lang.next_level') }}: {{ $nextLevel ?? __('lang.none') }}
                        @else
                            {{ __('lang.you_are_legend') }}
                        @endif
                    </span>
                </div>
            </div>
            <button class="action-btn">{{ __('lang.ways_to_earn_points') }}</button>
        </div>

        <!-- Time Plan Card -->
        @if ($planDuration > 0 && $planEndDate)
            @php
                $circleColor = ($progressPercent <= 33.33) ? '#28a745' : ($progressPercent <= 66.67 ? '#ffc107' : '#dc3545');
                $circumference = 2 * pi() * 45;
                $offset = $circumference * (1 - ($daysLeft / $planDuration));
            @endphp
            <div class="dashboard-card plan-card">
                <div class="card-header">
                    <span class="card-icon">⏳</span>
                    <h2 class="card-title">{{ __('lang.time_plan') }}</h2>
                </div>
                <div class="stat-item">
                    <span class="stat-label">{{ __('lang.days_left') }}:</span>
                    <span class="stat-value" id="daysLeft">{{ $daysLeft }}</span> {{ __('lang.of') }} {{ $planDuration }} {{ __('lang.day') }}
                </div>
                <div class="stat-item">
                    <span class="stat-label">{{ __('lang.end_date') }}:</span>
                    <span class="stat-value">{{ $planEndDate ? Carbon::parse($planEndDate)->format('Y-m-d') : __('lang.not_defined') }}</span>
                </div>
                <div class="circle-progress-container">
                    <svg width="100" height="100" class="circle-progress">
                        <circle class="circle-progress-bg" cx="50" cy="50" r="45"></circle>
                        <circle class="circle-progress-fill" cx="50" cy="50" r="45" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}" style="stroke: {{ $circleColor }}"></circle>
                    </svg>
                    <div class="circle-progress-text">{{ $daysLeft }}</div>
                </div>
                <button class="action-btn" onclick="showPlanForm()">{{ __('lang.edit_plan') }}</button>
                <div class="plan-form" id="planForm" style="display: none;">
                    <form method="POST" action="{{ route('achievement.index') }}">
                        @csrf
                        <input type="number" name="plan_duration" min="1" placeholder="{{ __('number_of_days') }}" required>
                        <button type="submit">{{ __('lang.confirm_plan') }}</button>
                    </form>
                </div>
            </div>
        @elseif ($allContentCompleted)
            <div class="dashboard-card plan-card">
                <div class="card-header">
                    <span class="card-icon">⏳</span>
                    <h2 class="card-title">{{ __('lang.time_plan') }}</h2>
                </div>
                <p>{{ __('lang.congrats_all_completed') }}</p>
                <button class="action-btn" onclick="showPlanForm()">{{ __('lang.choose_time_plan') }}</button>
                <div class="plan-form" id="planForm" style="display: none;">
                    <form method="POST" action="{{ route('achievement.index') }}">
                        @csrf
                        <input type="number" name="plan_duration" min="1" placeholder="{{ __('lang.number_of_days') }}" required>
                        <button type="submit">{{ __('lang.confirm_plan') }}</button>
                    </form>
                </div>
            </div>
        @else
            <div class="dashboard-card plan-card">
                <div class="card-header">
                    <span class="card-icon">⏳</span>
                    <h2 class="card-title">{{ __('lang.time_plan') }}</h2>
                </div>
                <p>{{ __('lang.not_started_tests') }}</p>
            </div>
        @endif

        <!-- Stats Card -->
        <div class="dashboard-card stats-card">
            <div class="card-header">
                <span class="card-icon">📊</span>
                <h2 class="card-title">{{ __('lang.progress_statistics') }}</h2>
            </div>
            <div class="stats-grid">
                <div class="stat-circle">
                    <div class="circle-progress">
                        <div class="stat-number">{{ $completedDomains }}</div>
                    </div>
                    <div class="stat-name">{{ __('lang.domains') }}</div>
                </div>
                <div class="stat-circle">
                    <div class="circle-progress">
                        <div class="stat-number">{{ $completedChapters }}</div>
                    </div>
                    <div class="stat-name">{{ __('lang.chapters') }}</div>
                </div>
                <div class="stat-circle">
                    <div class="circle-progress">
                        <div class="stat-number">{{ $completedExams }}</div>
                    </div>
                    <div class="stat-name">{{ __('lang.exams') }}</div>
                </div>
                <div class="stat-circle">
                    <div class="circle-progress">
                        <div class="stat-number">{{ $completedQuestions }}</div>
                    </div>
                    <div class="stat-name">{{ __('lang.questions') }}</div>
                </div>
            </div>
        </div>

        <!-- Achievements Card -->
        <div class="dashboard-card achievements-card">
            <div class="card-header">
                <span class="card-icon">🏆</span>
                <h2 class="card-title">{{ __('lang.my_achievements') }}</h2>
            </div>
            <div class="achievements-container">
                <div class="achievement-badge gold">
                    <span class="badge-icon">🥇</span>
                    <div class="badge-content">
                        <h3>{{ __('lang.domains') }}</h3>
                        <p>{{ __('lang.completed') }} {{ $completedDomains }} {{ __('lang.domain') }}</p>
                    </div>
                </div>
                <div class="achievement-badge silver">
                    <span class="badge-icon">📘</span>
                    <div class="badge-content">
                        <h3>{{ __('lang.chapters') }}</h3>
                        <p>{{ __('lang.finished') }} {{ $completedChapters }} {{ __('lang.chapter') }}</p>
                    </div>
                </div>
                <div class="achievement-badge bronze">
                    <span class="badge-icon">💡</span>
                    <div class="badge-content">
                        <h3>{{ __('lang.questions') }}</h3>
                        <p>{{ __('lang.answered') }} {{ $completedQuestions }} {{ __('lang.question') }}</p>
                    </div>
                </div>
                <div class="achievement-badge streak">
                    <span class="badge-icon">🔥</span>
                    <div class="badge-content">
                        <h3>{{ __('lang.continuity') }}</h3>
                        <p>{{ $streakDays }} {{ __('lang.consecutive_study_days') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateDaysLeft() {
            const endDate = "{{ $planEndDate ?? '' }}";
            const planDuration = {{ $planDuration ?? 1 }};
            if (endDate) {
                const end = new Date(endDate);
                const today = new Date();
                const diffTime = end - today;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const daysLeft = Math.max(0, diffDays);
                document.getElementById('daysLeft').textContent = daysLeft;

                const circumference = 2 * Math.PI * 45;
                const offset = circumference * (1 - (daysLeft / planDuration));
                const circle = document.querySelector('.circle-progress-fill');
                if (circle) {
                    circle.setAttribute('stroke-dashoffset', offset);
                    const progressPercent = (planDuration - daysLeft) / planDuration * 100;
                    if (progressPercent <= 33.33) {
                        circle.style.stroke = '#28a745';
                    } else if (progressPercent <= 66.67) {
                        circle.style.stroke = '#ffc107';
                    } else {
                        circle.style.stroke = '#dc3545';
                    }
                }
            } else {
                document.getElementById('daysLeft').textContent = '0';
            }
        }

        function showPlanForm() {
            const planForm = document.getElementById('planForm');
            if (planForm) {
                planForm.style.display = planForm.style.display === 'none' ? 'block' : 'none';
            }
        }

        @if ($planDuration > 0 && $planEndDate)
            updateDaysLeft();
            setInterval(updateDaysLeft, 24 * 60 * 60 * 1000);
        @endif
    </script>
</div>
@endsection
