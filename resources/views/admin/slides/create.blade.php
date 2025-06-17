@extends('layouts.admin')

@section('content')
<div>
    <!-- No surplus words or unnecessary actions. - Marcus Aurelius -->
</div>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Slide</h1>
        <a href="{{ route('admin.slides') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Slide Details</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.slides.store') }}">
                @csrf
                <div class="form-group">
                    <label for="text">Slide Title</label>
                    <input type="text" class="form-control @error('text') is-invalid @enderror" id="text" name="text" value="{{ old('text') }}" required>
                    @error('text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="5" required>{{ old('content') }}</textarea>
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
                        <option value="{{ $domain->id }}">{{ $domain->text }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="chapter_id">Chapter</label>
                    <select class="form-control" id="chapter_id" name="chapter_id" required>
                        @foreach($chapters as $chapter)
                        <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="count">Initial Count</label>
                    <input type="number" class="form-control" id="count" name="count" value="{{ old('count', 0) }}" min="0">
                </div>
                
                <button type="submit" class="btn btn-primary">Create Slide</button>
            </form>
        </div>
    </div>
</div>
@endsection