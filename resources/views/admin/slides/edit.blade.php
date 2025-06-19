@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Slide</h1>
        <a href="{{ route('admin.slides') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Slide Details</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.slides.update', $slide) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group mb-4">
                            <label for="text" class="font-weight-bold">Slide Title*</label>
                            <input type="text" class="form-control @error('text') is-invalid @enderror" 
                                   id="text" name="text" value="{{ $slide->text }}" required>
                            @error('text')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        

                        <div class="form-group mb-4">
                            <label for="content" class="font-weight-bold">PDF File</label>
                            
                            <!-- Current file display -->
                            @if($slide->content)
                            <div class="mb-2">
                                <a href="{{ Storage::url($slide->content) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-pdf mr-1"></i> View Current PDF
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger ml-2" onclick="document.getElementById('delete_pdf').value = '1'">
                                    <i class="fas fa-trash-alt mr-1"></i> Remove PDF
                                </button>
                                <input type="hidden" name="delete_pdf" id="delete_pdf" value="0">
                            </div>
                            @endif
                            
                            <!-- File input -->
                            <div class="mb-3">
                                <input class="form-control form-control-sm" id="formFileSm" type="file"
                                accept="application/pdf" name="content" placeholder="{{ $slide->content }}" value="{{ $slide->content }}">
                                @error('content')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Maximum file size: 5MB. Leave blank to keep current file.</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">

                        <div class="form-group mb-4">
                            <label for="domain_id" class="font-weight-bold">Domain (Optional)</label>
                            <select class="form-control select2" id="domain_id" name="domain_id">
                                <option value="">-- Select Domain --</option>
                                    @foreach($domains as $domain)
                                        <option value="{{ $domain->id }}" {{ $slide->domain->id ?? ""  == $domain->id ? 'selected' : null }}>
                                            {{ $domain->text }}
                                        </option>
                                    @endforeach
                            </select>
                        </div>
                        

                        <div class="form-group mb-4">
                            <label for="chapter_id" class="font-weight-bold">Chapter (Optional)</label>
                            <select class="form-control select2" id="chapter_id" name="chapter_id">
                                <option value="">-- Select Chapter --</option>
                                    @foreach($chapters as $chapter)
                                        <option value="{{ $chapter->id }}" {{ $slide->chapter->id ?? "" == $chapter->id ? 'selected' : null }}>
                                            {{ $chapter->text }}
                                        </option>
                                    @endforeach
                            </select>
                        </div>
                    
                    </div>
                </div>
                
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save mr-2"></i> Update Slide
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        border: 1px solid #d1d3e2;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + 0.75rem + 2px);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Initialize Select2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: $(this).data('placeholder'),
            allowClear: true
        });
        
        // Show selected file name
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@endpush