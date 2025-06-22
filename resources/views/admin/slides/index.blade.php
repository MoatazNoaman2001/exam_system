@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-column flex-sm-row">
        <h1 class="h3 mb-3 mb-sm-0 text-gray-800">{{ __('lang.slides_management') }}</h1>
        <a href="{{ route('admin.slides.create') }}" class="btn btn-sm btn-primary shadow-sm mt-2 mt-sm-0">
            <i class="fas fa-plus fa-sm text-white-50"></i> {{ __('lang.create_new_slide') }}
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('lang.all_slides') }}</h6>
        </div>
        <div class="card-body">
            <!-- Desktop Table -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('lang.id') }}</th>
                            <th>{{ __('lang.title') }}</th>
                            <th>{{ __('lang.domain') }}</th>
                            <th>{{ __('lang.chapter') }}</th>
                            <th>{{ __('lang.attempts_number') }}</th>
                            <th>{{ __('lang.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($slides as $slide)
                        <tr>
                            <td>{{ substr($slide->id, 20) }}</td>
                            <td>{{ $slide->text }}</td>
                            <td>{{ $slide->domain->text ?? __('lang.na') }}</td>
                            <td>{{ $slide->chapter->text ?? __('lang.na') }}</td>
                            <td>{{ $slide->slideAttempts->count() }}</td>
                            <td>
                                <a href="{{ route('admin.slides.edit', $slide) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.slides.destroy', $slide) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('lang.confirm_delete') }}')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="d-md-none">
                @foreach($slides as $slide)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 font-weight-bold text-gray-800">{{ $slide->text }}</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-2">
                                <small class="text-muted">{{ __('lang.id') }}</small><br>
                                <span>{{ substr($slide->id, 20) }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">{{ __('lang.attempts_number') }}</small><br>
                                <span>{{ $slide->slideAttempts->count() }}</span>
                            </div>
                            <div class="col-12 mb-2">
                                <small class="text-muted">{{ __('lang.domain') }}</small><br>
                                <span>{{ $slide->domain->text ?? __('lang.na') }}</span>
                            </div>
                            <div class="col-12 mb-2">
                                <small class="text-muted">{{ __('lang.chapter') }}</small><br>
                                <span>{{ $slide->chapter->text ?? __('lang.na') }}</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('admin.slides.edit', $slide) }}" class="btn btn-sm btn-primary flex-fill"><i class="fas fa-edit"></i> {{ __('lang.edit') }}</a>
                            <form action="{{ route('admin.slides.destroy', $slide) }}" method="POST" class="flex-fill">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('{{ __('lang.confirm_delete') }}')"><i class="fas fa-trash"></i> {{ __('lang.delete') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{ $slides->links() }}
        </div>
    </div>
</div>

<style>
    @media (max-width: 767px) {
        .card.mb-3 {
            border-radius: 8px;
            overflow: hidden;
        }
        .card-body {
            font-size: 0.9rem;
        }
        .btn-sm {
            padding: 0.4rem 0.6rem;
            font-size: 0.85rem;
        }
        .badge {
            font-size: 0.8rem;
            padding: 0.4em 0.6em;
        }
        .gap-2 {
            gap: 0.5rem !important;
        }
        .row {
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }
        .col-6, .col-12 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
    }
</style>
@endsection