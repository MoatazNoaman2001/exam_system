@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Details</h1>
        <a href="{{ route('admin.users') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile</h6>
                </div>
                <div class="card-body text-center">
                    <img class="img-profile rounded-circle mb-3" src="https://source.unsplash.com/QAB-WJcbgJk/200x200" width="120">
                    <h4>{{ $user->username }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    
                    <div class="row text-left mt-4">
                        <div class="col-6">
                            <p class="mb-0"><strong>Role:</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-0">{{ ucfirst($user->role) }}</p>
                        </div>
                    </div>
                    <div class="row text-left">
                        <div class="col-6">
                            <p class="mb-0"><strong>Phone:</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-0">{{ $user->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row text-left">
                        <div class="col-6">
                            <p class="mb-0"><strong>Language:</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-0">{{ strtoupper($user->preferred_language) }}</p>
                        </div>
                    </div>
                    <div class="row text-left">
                        <div class="col-6">
                            <p class="mb-0"><strong>Verified:</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-0">{!! $user->verified ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-warning">No</span>' !!}</p>
                        </div>
                    </div>
                    <div class="row text-left">
                        <div class="col-6">
                            <p class="mb-0"><strong>Joined:</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Quiz Attempts</h6>
                    <span class="badge badge-primary">{{ $user->quizAttempts->count() }}</span>
                </div>
                <div class="card-body">
                    @if($user->quizAttempts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Quiz</th>
                                    <th>Score</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->quizAttempts as $attempt)
                                <tr>
                                    <td>{{ $attempt->quiz->text ?? 'N/A' }}</td>
                                    <td>{{ $attempt->score }}%</td>
                                    <td>{{ $attempt->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">No quiz attempts yet.</p>
                    @endif
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Notifications</h6>
                    <span class="badge badge-primary">{{ $user->notifications->count() }}</span>
                </div>
                <div class="card-body">
                    @if($user->notifications->count() > 0)
                    <div class="list-group">
                        @foreach($user->notifications as $notification)
                        <div class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $notification->text }}</h6>
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ $notification->subtext }}</p>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted">No notifications yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection