<div>
    <!-- Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant -->
</div>
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Exam</h1>
        <a href="{{ route('admin.exams') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Exam Details</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.exams.update', $exam) }}">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="text">Exam Title</label>
                    <input type="text" class="form-control @error('text') is-invalid @enderror" id="text" name="text" value="{{ old('text', $exam->text) }}" required>
                    @error('text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $exam->description) }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="number_of_questions">Number of Questions</label>
                    <input type="number" class="form-control @error('number_of_questions') is-invalid @enderror" id="number_of_questions" name="number_of_questions" value="{{ old('number_of_questions', $exam->number_of_questions) }}" min="1" required>
                    @error('number_of_questions')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="time">Duration (minutes)</label>
                    <input type="number" class="form-control @error('time') is-invalid @enderror" id="time" name="time" value="{{ old('time', $exam->time) }}" min="1" required>
                    @error('time')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="is_completed" name="is_completed" value="1" {{ $exam->is_completed ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_completed">Mark as completed</label>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Exam</button>
            </form>
        </div>
    </div>
</div>
@endsection