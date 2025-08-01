@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Chapter</h1>
        <a href="{{ route('admin.chapters') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Chapter Details</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.chapters.store') }}">
                @csrf
                <div class="form-group">
                    <label for="text">Chapter Name</label>
                    <input type="text" class="form-control @error('text') is-invalid @enderror" id="text" name="text" value="{{ old('text') }}" required>
                    @error('text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <br/>

            
                <button type="submit" class="btn btn-primary">Create Chapter</button>
            </form>
        </div>
    </div>
</div>
@endsection