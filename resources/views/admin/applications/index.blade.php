<div>
    <!-- When there is no desire, all things are at peace. - Laozi -->
</div>
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Job Applications</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Applications</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Candidate</th>
                            <th>Job</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                        <tr>
                            <td>{{ $application->id }}</td>
                            <td>{{ $application->candidate->username }}</td>
                            <td>{{ $application->job->title ?? 'N/A' }}</td>
                            <td>
                                @if($application->status === 'accepted')
                                    <span class="badge badge-success">Accepted</span>
                                @elseif($application->status === 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @elseif($application->status === 'reviewed')
                                    <span class="badge badge-info">Reviewed</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{ $application->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection