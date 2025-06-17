@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Domain</h1>
        <a href="{{ route('admin.domains') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Domain Details</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.domains.update', $domain) }}">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="text">Domain Name</label>
                    <input type="text" class="form-control @error('text') is-invalid @enderror" id="text" name="text" value="{{ old('text', $domain->text) }}" required>
                    @error('text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="icon">Icon (Font Awesome class)</label>
                    <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon', $domain->icon) }}" placeholder="e.g. fas fa-code">
                </div>
                
                <div class="form-group">
                    <label for="count">Count</label>
                    <input type="number" class="form-control" id="count" name="count" value="{{ old('count', $domain->count) }}" min="0">
                </div>
                
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="is_completed" name="is_completed" value="1" {{ $domain->is_completed ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_completed">Mark as completed</label>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Domain</button>
            </form>
        </div>
    </div>
</div>
@endsection