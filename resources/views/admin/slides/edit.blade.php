<div>
    <!-- He who is contented is rich. - Laozi -->
</div>
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Slide</h1>
        <a href="{{ route('admin.slides') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Slide Details</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.slides.update', $slide) }}">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="text">Slide Title</label>
                    <input type="text" class="form-control @error('text') is-invalid @enderror" id="text" name="text" value="{{ old('text', $slide->text) }}" required>
                    @error('text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="5" required>{{ old('content', $slide->content) }}</textarea>
                    @error('content')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="domain_id">Domain</label>
                    <select class="form-control" id="domain_id" name="domain_id" required>
                        @foreach($domains as $domain)
                        <option value="{{ $domain->id }}" {{ $slide->domain_id == $domain->id ? 'selected' : '' }}>{{ $domain->text }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="chapter_id">Chapter</label>
                    <select class="form-control" id="chapter_id" name="chapter_id" required>
                        @foreach($chapters as $chapter)
                        <option value="{{ $chapter->id }}" {{ $slide->chapter_id == $chapter->id ? 'selected' : '' }}>{{ $chapter->text }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="count">Count</label>
                    <input type="number" class="form-control" id="count" name="count" value="{{ old('count', $slide->count) }}" min="0">
                </div>
                
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="is_completed" name="is_completed" value="1" {{ $slide->is_completed ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_completed">Mark as completed</label>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Slide</button>
            </form>
        </div>
    </div>
</div>
@endsection