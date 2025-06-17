<div>
    <!-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead -->
</div>
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Application Details</h1>
        <a href="{{ route('admin.applications') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Application Information</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <p class="mb-0"><strong>Candidate:</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-0">{{ $application->candidate->username }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <p class="mb-0"><strong>Job:</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-0">{{ $application->job->title ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <p class="mb-0"><strong>Status:</strong></p>
                        </div>
                        <div class="col-6">
                            @if($application->status === 'accepted')
                                <span class="badge badge-success">Accepted</span>
                            @elseif($application->status === 'rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @elseif($application->status === 'reviewed')
                                <span class="badge badge-info">Reviewed</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <p class="mb-0"><strong>Applied Date:</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-0">{{ $application->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Update Status</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.applications.update-status', $application) }}">
                        @csrf
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="reviewed" {{ $application->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection