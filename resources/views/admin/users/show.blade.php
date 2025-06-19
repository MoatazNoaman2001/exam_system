@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-circle mr-2"></i> User Profile: {{ $user->username }}
        </h1>
        <div>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm mr-2">
                <i class="fas fa-edit fa-sm"></i> Edit Profile
            </a>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Profile Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-id-card mr-1"></i> Profile Information
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block">
                        <img class="img-profile rounded-circle mb-3 border border-primary" 
                             src="{{ $user->image ? asset('storage/'.$user->image) : 'https://ui-avatars.com/api/?name='.urlencode($user->username).'&background=random' }}" 
                             width="140" height="140"
                             alt="{{ $user->username }} profile picture">
                
                    </div>
                    
                    <h4 class="font-weight-bold">{{ $user->username }}</h4>
                    <p class="text-muted mb-4">{{ $user->email }}</p>
                    
                    <div class="profile-details">
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-user-tag mr-2"></i>Role</span>
                            <span class="detail-value badge badge-{{ $user->role === 'admin' ? 'danger' : 'info' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-phone mr-2"></i>Phone</span>
                            <span class="detail-value">{{ $user->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-language mr-2"></i>Language</span>
                            <span class="detail-value">
                                {{ strtoupper($user->preferred_language) }}
                                <span class="flag-icon flag-icon-{{ strtolower($user->preferred_language) }} ml-1"></span>
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-envelope mr-2"></i>Email Status</span>
                            <span class="detail-value">
                                @if($user->verified)
                                    <span class="badge badge-success text-success">Verified</span>
                                @else
                                    <span class="badge badge-warning text-warning">Unverified</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-calendar-alt mr-2"></i>Member Since</span>
                            <span class="detail-value">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-clock mr-2"></i>Last Active</span>
                            <span class="detail-value">
                                {{ $user->updated_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-around">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope"></i> Message
                        </button>
                        <button class="btn btn-outline-info btn-sm">
                            <i class="fas fa-history"></i> Activity
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-chart-line"></i> Stats
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Account Status Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-{{ $user->deleted_at ? 'danger' : 'success' }} text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-{{ $user->deleted_at ? 'times-circle' : 'check-circle' }} mr-1"></i> 
                        Account Status
                    </h6>
                </div>
                <div class="card-body">
                    @if($user->deleted_at)
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Deactivated</strong> on {{ $user->deleted_at->format('M d, Y') }}
                        </div>
                        <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-user-check mr-2"></i> Reactivate Account
                            </button>
                        </form>
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle mr-2"></i>
                            <strong>Active</strong> account
                        </div>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-user-slash mr-2"></i> Deactivate Account
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Right Column - Activity Sections -->
        <div class="col-lg-8">
            <!-- Quiz Performance Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-tasks mr-1"></i> Quiz Performance
                    </h6>
                    <div class="badge badge-light rounded-pill">{{ $user->quizAttempts->count() }} attempts</div>
                </div>
                <div class="card-body">
                    @if($user->quizAttempts->count() > 0)
                        <div class="mb-4">
                            <h5 class="font-weight-bold mb-3">Performance Summary</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card border-left-primary h-100 py-2">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <i class="fas fa-star fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="text-xs font-weight-bold text-primary mb-1">
                                                        Highest Score
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        {{ $user->quizAttempts->max('score') }}%
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card border-left-success h-100 py-2">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <i class="fas fa-chart-line fa-2x text-success"></i>
                                                </div>
                                                <div>
                                                    <div class="text-xs font-weight-bold text-success mb-1">
                                                        Average Score
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        {{ round($user->quizAttempts->avg('score'), 1) }}%
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card border-left-warning h-100 py-2">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <i class="fas fa-clock fa-2x text-warning"></i>
                                                </div>
                                                <div>
                                                    <div class="text-xs font-weight-bold text-warning mb-1">
                                                        Last Attempt
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        {{ $user->quizAttempts->last()->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h5 class="font-weight-bold mb-3">Recent Attempts</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Quiz</th>
                                        <th>Score</th>
                                        <th>Time Spent</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->quizAttempts->take(5) as $attempt)
                                    <tr>
                                        <td>
                                            <a href="#">{{ $attempt->quiz->text ?? 'N/A' }}</a>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-{{ $attempt->score >= 70 ? 'success' : ($attempt->score >= 40 ? 'warning' : 'danger') }}" 
                                                     role="progressbar" style="width: {{ $attempt->score }}%">
                                                    {{ $attempt->score }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ rand(2, 15) }} mins</td>
                                        <td>{{ $attempt->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($user->quizAttempts->count() > 5)
                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-sm btn-outline-secondary">
                                    View All Attempts ({{ $user->quizAttempts->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No quiz attempts yet</h5>
                            <p class="text-muted">This user hasn't taken any quizzes.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Activity Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary  text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-history mr-1"></i> Recent Activity
                    </h6>
                    <div class="badge badge-light rounded-pill">{{ $user->notifications->count() }} events</div>
                </div>
                <div class="card-body">
                    @if($user->notifications->count() > 0)
                        <div class="timeline">
                            @foreach($user->notifications->take(6) as $notification)
                            <div class="timeline-item">
                                <div class="timeline-icon bg-{{ $notification->type === 'success' ? 'success' : ($notification->type === 'warning' ? 'warning' : 'primary') }}">
                                    <i class="fas fa-{{ $notification->icon ?? 'bell' }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="font-weight-bold mb-1">{{ $notification->text }}</h6>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="text-muted mb-0">{{ $notification->subtext }}</p>
                                    @if($notification->action_url)
                                        <a href="{{ $notification->action_url }}" class="btn btn-sm btn-outline-primary mt-2">
                                            {{ $notification->action_text ?? 'View' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($user->notifications->count() > 6)
                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-sm btn-outline-secondary">
                                    View Full Activity Log
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No recent activity</h5>
                            <p class="text-muted">This user hasn't generated any activity yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root{
        --sidebar-bg: #2c3e50;
    }
    .card-header{
        background-color: var(--sidebar-bg) !important;
    }
    .profile-details {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
    }
    

    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .detail-item i {
        margin: 0px 8px 0px 0px;  
    }
    
    .detail-item:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        font-weight: 600;
        color: #495057;
    }
    
    .detail-value {
        color: #212529;
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-icon {
        position: absolute;
        left: -45px;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .timeline-content {
        background: white;
        padding: 15px;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .flag-icon {
        width: 1em;
        height: 1em;
        vertical-align: middle;
    }
</style>
@endsection