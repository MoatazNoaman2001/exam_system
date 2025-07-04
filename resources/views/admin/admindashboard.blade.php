@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">{{__('lang.admin_dashboard')}}</h1>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                        <i class="fas fa-calendar"></i> This Month
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">{{__('lang.this_week')}}</a></li>
                        <li><a class="dropdown-item" href="#">{{__('lang.this_month')}}</a></li>
                        <li><a class="dropdown-item" href="#">{{__('lang.last_month')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{__('lang.total_users')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers ?? '1,234' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{__('lang.active_learners')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeLearners ?? '892' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{__('lang.quiz_attempts')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $quizAttempts ?? '3,456' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{__('lang.download_pdfs')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jobApplications ?? '0' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Learning Progress Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{__('lang.learning_progress_overview')}}</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">

                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">{{__('lang.export_data')}}</a></li>
                            <li><a class="dropdown-item" href="#">{{__('lang.view_details')}}</a></li>
                            <li><a class="dropdown-item" href="#">{{__('lang.this_week')}}</a></li>
                            <li><a class="dropdown-item" href="#">{{__('lang.this_month')}}</a></li>
                            <li><a class="dropdown-item" href="#">{{__('lang.this_month')}}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="learningProgressChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Domain Completion Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__('lang.domain_completion')}}</h6>
                </div>
                <div class="card-body">
                    <canvas id="domainChart" width="300" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables Row -->
    <div class="row">
        <!-- Recent Users -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__('lang.recent_users')}}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="recentUsersTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{__('lang.name')}}</th>
                                    <th>{{__('lang.email')}}</th>
                                    <th>{{__('lang.role')}}</th>
                                    <th>{{__('lang.status')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers ?? [] as $user)
                                <tr>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : 'info' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->verified)
                                            <span class="badge badge-success">Verified</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No recent users found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.users') }}" class="btn btn-primary btn-sm">{{__('lang.view_all_users')}}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Quiz Attempts -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__('lang.recent_quiz_attempts')}}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="recentQuizzesTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{__('lang.user')}}</th>
                                    <th>{{__('lang.quiz')}}</th>
                                    <th>{{__('lang.score')}}</th>
                                    <th>{{__('lang.Date')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentQuizAttempts ?? [] as $attempt)
                                <tr>
                                    <td>{{ $attempt->user->username ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($attempt->quiz->question ?? 'Quiz', 30) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $attempt->score >= 80 ? 'success' : ($attempt->score >= 60 ? 'warning' : 'danger') }}">
                                            {{ $attempt->score }}%
                                        </span>
                                    </td>
                                    <td>{{ $attempt->created_at ? $attempt->created_at->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">{{__('lang.no_recent_quiz_attempts')}}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.quiz-attempts') }}" class="btn btn-primary btn-sm">{{__('lang.view_all_attempts')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status & Quick Actions -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__('lang.system_overview')}}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="border-left-primary p-3">
                                <div class="text-primary font-weight-bold">{{__('lang.total_domains')}}</div>
                                <div class="h4">{{ $totalDomains ?? '12' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border-left-success p-3">
                                <div class="text-success font-weight-bold">{{__('lang.total_slides')}}</div>
                                <div class="h4">{{ $totalSlides ?? '456' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border-left-info p-3">
                                <div class="text-info font-weight-bold">{{__('lang.active_exams')}}</div>
                                <div class="h4">{{ $activeExams ?? '8' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__('lang.quick_actions')}}</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus"></i> {{__('lang.add_new_user')}}
                        </a>
                        <a href="{{ route('admin.domains.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> {{__('lang.create_domain')}}
                        </a>
                        <a href="{{ route('admin.chapters.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> {{__('lang.create_chapter')}}
                        </a>
                        <a href="{{ route('admin.slides.create') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-file-powerpoint"></i> {{__('lang.add_slide')}}
                        </a>
                        <a href="{{ route('admin.exams.create') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-clipboard-check"></i> {{__('lang.create_exam')}}
                        </a>
                        <a href="{{ route('admin.notifications.create') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-bell"></i> {{__('lang.send_notification')}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Notifications -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__('lang.recent_system_notifications')}}</h6>
                </div>
                <div class="card-body">
                    @forelse($recentNotifications ?? [] as $notification)
                    <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                        <div class="mr-3">
                            <i class="fas fa-bell text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="font-weight-bold">{{ $notification->text }}</div>
                            @if($notification->subtext)
                            <div class="text-muted small">{{ $notification->subtext }}</div>
                            @endif
                            <div class="text-muted small">{{ $notification->created_at ? $notification->created_at->diffForHumans() : 'Recently' }}</div>
                        </div>
                        <div>
                            @if(!$notification->is_seen)
                            <span class="badge badge-primary">New</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted">
                        <i class="fas fa-bell-slash fa-2x mb-2"></i>
                        <p>{{__('lang.no_recent_notifications')}}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Learning Progress Chart
    const ctx1 = document.getElementById('learningProgressChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Quiz Attempts',
                data: [65, 78, 90, 81, 96, 105],
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.3
            }, {
                label: 'Test Completions',
                data: [45, 52, 68, 61, 72, 85],
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Domain Completion Chart
    const ctx2 = document.getElementById('domainChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['الفصول', 'المجالات', 'الاختبارات'],
            datasets: [{
                data: [40, 25, 20],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>

@push('styles')
<style>
    :root{
        --sidebar-bg: #2c3e50;
    }
    .card-header{
        background-color: var(--sidebar-bg) !important;
    }
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
</style>
@endpush
@endsection