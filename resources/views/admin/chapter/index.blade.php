@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chapters Management</h1>
        <a href="{{ route('admin.chapter.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create New Domain
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Chapters</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Slides Count</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chapters as $chapter)
                        <tr>
                            <td>{{ $chapter->id }}</td>
                            <td>{{ $chapter->text }}</td>
                            <td>{{ $chapter->slides_count }}</td>
                            <td>
                                @if($chapter->is_completed)
                                    <span class="badge badge-success">Completed</span>
                                @else
                                    <span class="badge badge-warning">In Progress</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.chapter.edit', $chapter) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.chapter.destroy', $chapter) }}" method="POST" style="display: inline;">
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
            {{-- {{ $Chapters->links() }} --}}
        </div>
    </div>
</div>
@endsection