<div>
    <!-- Because you are alive, everything is possible. - Thich Nhat Hanh -->
</div>
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Test Attempt Details</h1>
        <a href="{{ route('admin.test-attempts') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Attempt Information</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <p class="mb-0"><strong>User:</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-0">{{ $testAttempt->user->username }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <p class="mb-0"><strong>Test:</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-0">{{ $testAttempt->test->text ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <p class="mb-0"><strong>Score:</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-0">{{ $testAttempt->score }}%</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <p class="mb-0"><strong>Date:</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-0">{{ $testAttempt->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <p class="mb-0"><strong>Time Taken:</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-0">{{ $testAttempt->time_taken }} seconds</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Answers</h6>
                </div>
                <div class="card-body">
                    @if($testAttempt->test && $testAttempt->test->testAnswers->count() > 0)
                    <div class="list-group">
                        @foreach($testAttempt->test->testAnswers as $answer)
                        <div class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Question: {{ $answer->question }}</h6>
                                <span class="badge badge-{{ $answer->is_correct ? 'success' : 'danger' }}">
                                    {{ $answer->is_correct ? 'Correct' : 'Incorrect' }}
                                </span>
                            </div>
                            <p class="mb-1">User Answer: {{ $answer->user_answer }}</p>
                            <small>Correct Answer: {{ $answer->correct_answer }}</small>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted">No answer details available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection