@extends('layouts.admin')

@section('title', __('dashboard.title'))

@section('page-title', __('dashboard.title'))

@section('content')


<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<!-- Statistics Cards -->
<div class="stats-grid">
    <!-- Users Stats -->
    <div class="stat-card stat-card-blue">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ number_format($totalUsers) }}</h3>
            <p class="stat-label">{{ __('dashboard.stats.total_users') }}</p>
            <div class="stat-meta">
                <span class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    +{{ $newUsersThisMonth }}
                </span>
                <span class="stat-period">{{ __('dashboard.this_month') }}</span>
            </div>
        </div>
    </div>

    <!-- Active Users -->
    <div class="stat-card stat-card-green">
        <div class="stat-icon">
            <i class="fas fa-user-clock"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ number_format($activeUsers) }}</h3>
            <p class="stat-label">{{ __('dashboard.stats.active_users') }}</p>
            <div class="stat-meta">
                <span class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    {{ round(($activeUsers / max($totalUsers, 1)) * 100, 1) }}%
                </span>
                <span class="stat-period">{{ __('dashboard.last_7_days') }}</span>
            </div>
        </div>
    </div>

    <!-- Total Exams -->
    <div class="stat-card stat-card-purple">
        <div class="stat-icon">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ number_format($totalExams) }}</h3>
            <p class="stat-label">{{ __('dashboard.stats.total_exams') }}</p>
            <div class="stat-meta">
                <span class="stat-change">
                    {{ number_format($totalExamAttempts) }}
                </span>
                <span class="stat-period">{{ __('dashboard.attempts') }}</span>
            </div>
        </div>
    </div>

    <!-- Average Score -->
    <div class="stat-card stat-card-orange">
        <div class="stat-icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ number_format($avgExamScore, 1) }}%</h3>
            <p class="stat-label">{{ __('dashboard.stats.avg_score') }}</p>
            <div class="stat-meta">
                <span class="stat-change {{ $avgExamScore >= 70 ? 'positive' : 'negative' }}">
                    <i class="fas fa-{{ $avgExamScore >= 70 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ $avgExamScore >= 70 ? __('Good') : __('Needs Improvement') }}
                </span>
                <span class="stat-period">{{ __('Overall') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Analytics -->
<div class="charts-grid">
    <!-- User Registration Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">{{ __('dashboard.charts.user_registrations') }}</h3>
            <div class="chart-actions">
                <button class="btn-icon" onclick="downloadChart('registrationChart')">
                    <i class="fas fa-download"></i>
                </button>
            </div>
        </div>
        <div class="chart-content">
            <canvas id="registrationChart"></canvas>
        </div>
    </div>

    <!-- User Activity Levels -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">{{ __('dashboard.charts.user_activity') }}</h3>
        </div>
        <div class="chart-content">
            <canvas id="activityChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Activity and Performance -->
<div class="activity-grid">
    <!-- Recent Exam Sessions -->
    <div class="activity-card">
        <div class="activity-header">
            <h3 class="activity-title">{{ __('dashboard.recent_exams') }}</h3>
            <a href="#" class="view-all-link">{{ __('dashboard.view_all') }}</a>
        </div>
        <div class="activity-content">
            @forelse($recentExamSessions as $session)

            <div class="activity-item">
                <div class="activity-avatar">
                    
                    @if($session->user->image)
                        <img src="{{ $session->user->image ? asset('storage/avatars/' . $session->user->image) : asset('images/person_placeholder.png') }}" alt="{{ $session->user->username }}">
                    @else
                        <i class="fas fa-user-graduate"></i>
                    @endif
                </div>
                <div class="activity-details">
                    <p class="activity-user">{{ $session->user->username }}</p>
                    <p class="activity-description">
                        {{ app()->getLocale() == 'ar' && $session->exam->{'text-ar'} ? $session->exam->{'text-ar'} : $session->exam->text }}
                    </p>
                    <span class="activity-time">{{ $session->completed_at->diffForHumans() }}</span>
                </div>
                <div class="activity-score">
                    <span class="score-badge {{ $session->score >= 80 ? 'success' : ($session->score >= 60 ? 'warning' : 'danger') }}">
                        {{ number_format($session->score, 1) }}%
                    </span>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <p>{{ __('dashboard.no_recent_exams') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Domain Performance -->
    <div class="activity-card">
        <div class="activity-header">
            <h3 class="activity-title">{{ __('dashboard.domain_performance') }}</h3>
        </div>
        <div class="activity-content">
            @forelse($domainPerformance as $domain)
            <div class="performance-item">
                <div class="performance-info">
                    <h4 class="domain-name">{{ $domain['name'] }}</h4>
                    <p class="domain-attempts">{{ $domain['attempts'] }} {{ __('dashboard.attempts') }}</p>
                </div>
                <div class="performance-score">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $domain['avg_score'] }}%"></div>
                    </div>
                    <span class="score-text">{{ $domain['avg_score'] }}%</span>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-chart-bar"></i>
                <p>{{ __('dashboard.no_domain_data') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Quick Stats Summary -->
<div class="summary-grid">
    <div class="summary-card">
        <h4 class="summary-title">{{ __('dashboard.content_overview') }}</h4>
        <div class="summary-stats">
            <div class="summary-item">
                <span class="summary-label">{{ __('dashboard.chapters') }}</span>
                <span class="summary-value">{{ number_format($totalChapters) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">{{ __('dashboard.slides') }}</span>
                <span class="summary-value">{{ number_format($totalSlides) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">{{ __('dashboard.domains') }}</span>
                <span class="summary-value">{{ number_format($totalDomains) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">{{ __('dashboard.quizzes') }}</span>
                <span class="summary-value">{{ number_format($totalQuizzes) }}</span>
            </div>
        </div>
    </div>

    <div class="summary-card">
        <h4 class="summary-title">{{ __('dashboard.user_progress') }}</h4>
        <div class="summary-stats">
            <div class="summary-item">
                <span class="summary-label">{{ __('dashboard.avg_progress') }}</span>
                <span class="summary-value">{{ number_format($userProgressStats->avg_progress ?? 0, 1) }}%</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">{{ __('dashboard.avg_points') }}</span>
                <span class="summary-value">{{ number_format($userProgressStats->avg_points ?? 0) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">{{ __('dashboard.avg_streak') }}</span>
                <span class="summary-value">{{ number_format($userProgressStats->avg_streak ?? 0) }} {{ __('dashboard.days') }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">{{ __('dashboard.verified_users') }}</span>
                <span class="summary-value">{{ number_format($verifiedUsers) }}</span>
            </div>
        </div>
    </div>

    <!-- Recent Users Card -->
    <div class="summary-card">
        <h4 class="summary-title">{{ __('Recent Users') }}</h4>
        <div class="recent-users-list">
            @forelse($recentUsers as $user)
            <div class="recent-user-item">
                <div class="user-avatar-small">
                    @if($user->image)
                        <img src="{{ asset('storage/avatars/' . $user->image) }}" alt="{{ $user->username }}">
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                </div>
                <div class="user-info-small">
                    <p class="user-name-small">{{ $user->username }}</p>
                    <p class="user-date-small">{{ $user->created_at->diffForHumans() }}</p>
                </div>
                <div class="user-status-small">
                    <span class="status-badge {{ $user->verified ? 'verified' : 'unverified' }}">
                        <i class="fas fa-{{ $user->verified ? 'check-circle' : 'clock' }}"></i>
                    </span>
                </div>
            </div>
            @empty
            <div class="empty-state-small">
                <i class="fas fa-users"></i>
                <p>{{ __('No recent users') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- System Status Card -->
    <div class="summary-card">
        <h4 class="summary-title">{{ __('System Status') }}</h4>
        <div class="system-status">
            <div class="status-item">
                <span class="status-label">{{ __('Active Plans') }}</span>
                <span class="status-value">
                    {{ number_format($activePlans) }}/{{ number_format($totalPlans) }}
                    <span class="status-indicator {{ $activePlans > 0 ? 'active' : 'inactive' }}"></span>
                </span>
            </div>
            <div class="status-item">
                <span class="status-label">{{ __('Total Tests') }}</span>
                <span class="status-value">
                    {{ number_format($totalTests ?? 0) }}
                    <span class="status-indicator active"></span>
                </span>
            </div>
            <div class="status-item">
                <span class="status-label">{{ __('User Engagement') }}</span>
                <span class="status-value">
                    {{ $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0 }}%
                    <span class="status-indicator {{ $totalUsers > 0 && ($activeUsers / $totalUsers) > 0.3 ? 'active' : 'warning' }}"></span>
                </span>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    // Pass data to JavaScript
    window.dashboardData = {
        chartData: @json($chartData),
        userActivityLevels: @json($userActivityLevels),
        locale: '{{ app()->getLocale() }}'
    };

    // Initialize dashboard when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        initializeDashboard();
    });
</script>
@endsection

