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
            <form method="POST" action="{{ route('admin.domains.update', $domain->id) }}">
                @csrf
                @method("PUT")
                <div class="form-group">
                    <label for="text">Domain Name</label>
                    <input type="text" class="form-control @error('text') is-invalid @enderror" id="text" name="text" value="{{ old('text', $domain->text) }}" required>
                    @error('text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <br/>
                <div class="form-group">
                    <label for="description">Domain Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" style="resize: vertical; max-height: 100px;" required>{{ old('description', $domain->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <br/>
                <button type="submit" class="btn btn-primary">Create Domain</button>
            </form>
        </div>
    </div>
</div>
@endsection