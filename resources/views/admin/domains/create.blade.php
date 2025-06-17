@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Domain</h1>
        <a href="{{ route('admin.domains') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Domain Details</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.domains.store') }}">
                @csrf
                <div class="form-group">
                    <label for="text">Domain Name</label>
                    <input type="text" class="form-control @error('text') is-invalid @enderror" id="text" name="text" value="{{ old('text') }}" required>
                    @error('text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="icon">Icon (Font Awesome class)</label>
                    <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon') }}" placeholder="e.g. fas fa-code">
                </div>
                
                <div class="form-group">
                    <label for="count">Initial Count</label>
                    <input type="number" class="form-control" id="count" name="count" value="{{ old('count', 0) }}" min="0">
                </div>
                
                <button type="submit" class="btn btn-primary">Create Domain</button>
            </form>
        </div>
    </div>
</div>
@endsection