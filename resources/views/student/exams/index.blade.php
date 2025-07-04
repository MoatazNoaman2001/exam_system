@extends('layouts.app')

@section('title', __('lang.Tests'))

@section('content')
    <div class="container py-4" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3">{{ __('lang.Tests') }}</h1>
                    <a href="{{ route('student.sections') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('lang.back_to_sections') }}
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    @forelse($exams as $exam)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $exam->title }}</h5>
                                    <p class="card-text text-muted">{{ $exam->description }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-primary">{{ $exam->questions_count ?? 0 }} {{ __('lang.questions') }}</span>
                                        <span class="badge bg-secondary">{{ $exam->duration ?? 0 }} {{ __('lang.minutes') }}</span>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <a href="{{ route('student.exams.take', $exam->id) }}" class="btn btn-primary">
                                            <i class="fas fa-play"></i>
                                            {{ __('lang.start_exam') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">{{ __('lang.no_exams_available') }}</h4>
                                <p class="text-muted">{{ __('lang.no_exams_description') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection 