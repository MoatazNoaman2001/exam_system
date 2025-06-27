<div>
    <!-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead -->
</div>

@extends('layouts.app')

@section('content')
<div class="home">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/default-profile.jpg') }}" alt="Profile" class="rounded-circle me-3" style="width: 50px; height: 50px;">
            <div>
                <h1 class="h4 mb-0">{{ Auth::user()->name }}</h1>
                <small class="text-muted">Student</small>
            </div>
        </div>
        <div class="position-relative">
            <i class="fas fa-bell fs-5 text-muted"></i>
            @if($notifications > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $notifications }}
                </span>
            @endif
        </div>
    </div>
    <!-- Plan Progress Widget -->
    <div class="card mb-4 shadow-sm">
    <div class="card-body">
            <h2 class="card-title h5">Plan Progress</h2>
            <div class="progress mb-2">
                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="text-muted mb-1">Progress: {{ $progress }}%</p>
            <p class="text-muted">Days Left: {{ $daysLeft }}</p>
        </div>
    </div>
    <!-- Today's Mission Component -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h2 class="card-title h5">Today's Mission</h2>
            <ul class="list-group list-group-flush">
                @foreach($todayMissions as $mission)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $mission->title }} <small class="text-muted">({{ $mission->type }})</small></span>
                        <span class="text-success">{{ $mission->status === 'completed' ? '✔ Completed' : 'Pending' }}</span>
                    </li>
                @endforeach
            </ul>
            <form class="mt-3">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Add new mission...">
                    <button type="submit" class="btn btn-primary">Add Mission</button>
                </div>
            </form>
        </div>
    </div>
    <!-- My Status Widget -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title h5">My Status</h2>
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-1">Slides Completed</p>
                    <h3 class="fw-bold">{{ $slidesCompleted }}</h3>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">Exams Completed</p>
                    <h3 class="fw-bold">{{ $examsCompleted }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media(max-width: 992px) {
        .home {
            margin: 0 30px;
        }
        
    }
    @media (min-width: 992px) {
        [dir='rtl'] .home {
            margin: 0 300px 0 20px;
        }
        [dir='ltr'] .home {
            margin: 0 20px 0 300px;
        }
    }

   

</style>
@endsection