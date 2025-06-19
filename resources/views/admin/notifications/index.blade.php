<div>
    <!-- Simplicity is an acquired taste. - Katharine Gerould -->
</div>
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Notifications</h1>
        <a href="{{ route('admin.notifications.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create Notification
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Notifications</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifications as $notification)
                        <tr>
                            <td>{{ $notification->id }}</td>
                            <td>{{ $notification->text }}</td>
                            <td>{{ $notification->user->username ?? 'All Users' }}</td>
                            <td>
                                @if($notification->is_seen)
                                    <span class="badge badge-success text-primary">Seen</span>
                                @else
                                    <span class="badge badge-warning text-muted">Unread</span>
                                @endif
                            </td>
                            <td>{{ $notification->created_at->format('M d, Y') }}</td>
                            <td>
                                <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection