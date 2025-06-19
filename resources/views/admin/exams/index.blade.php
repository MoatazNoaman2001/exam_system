<div>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
</div>
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Exams Management</h1>
        <div>
            <a href="{{ route('admin.exams.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-plus fa-sm text-white-50"></i> Create New Exam
            </a>
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm" data-toggle="modal" data-target="#importModal">
                <i class="fas fa-file-excel fa-sm text-white-50"></i> Import from Excel
            </button>
        </div>
    </div>

    <!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Exam from Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.exams.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="excel_file">Excel File</label>
                        <input type="file" class="form-control-file" id="excel_file" name="excel_file" required accept=".xlsx,.xls,.csv">
                        <small class="form-text text-muted">Please upload an Excel file with the correct format</small>
                    </div>
                    <div class="form-group">
                        <a href="{{ asset('templates/exam_import_template.xlsx') }}" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import Exam</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Exams</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Questions</th>
                            <th>Duration (min)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exams as $exam)
                        <tr>
                            <td>{{ $exam->id }}</td>
                            <td>{{ $exam->text }}</td>
                            <td>{{ $exam->number_of_questions }}</td>
                            <td>{{ $exam->time }}</td>
                            <td>
                                @if($exam->is_completed)
                                    <span class="badge badge-success text-success">Completed</span>
                                @else
                                    <span class="badge badge-warning text-info">Active</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.exams.edit', $exam) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.exams.destroy', $exam) }}" method="POST" style="display: inline;">
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
            {{ $exams->links() }}
        </div>
    </div>
</div>


@endsection