@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-column flex-sm-row">
        <h1 class="h3 mb-3 mb-sm-0 text-gray-800">{{ __('lang.users_management') }}</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary shadow-sm mt-2 mt-sm-0">
            <i class="fas fa-plus fa-sm text-white-50"></i> {{ __('lang.create_new_user') }}
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 text-primary">
            <h6 class="m-0 font-weight-bold">{{ __('lang.all_users') }}</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users') }}" class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4 col-sm-12">
                        <label for="search" class="form-label">{{ __('lang.search') }}</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="{{ __('lang.search_by_username_or_email') }}" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label for="role" class="form-label">{{ __('lang.role') }}</label>
                        <select name="role" id="role" class="form-select">
                            <option value="">{{ __('lang.all') }}</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>{{ __('lang.admin') }}</option>
                            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>{{ __('lang.user') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label for="verified" class="form-label">{{ __('lang.verified') }}</label>
                        <select name="verified" id="verified" class="form-select">
                            <option value="">{{ __('lang.all') }}</option>
                            <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>{{ __('lang.yes') }}</option>
                            <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>{{ __('lang.no') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <button type="submit" class="btn btn-primary w-100 transition-all">{{ __('lang.filter') }}</button>
                    </div>
                </div>
            </form>

            <!-- Desktop Table -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('lang.id') }}</th>
                            <th>{{ __('lang.username') }}</th>
                            <th>{{ __('lang.email') }}</th>
                            <th>{{ __('lang.phone') }}</th>
                            <th>{{ __('lang.role') }}</th>
                            <th>{{ __('lang.verified') }}</th>
                            <th>{{ __('lang.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ substr($user->id, 20) }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>+{{ $user->phone }}</td>
                            <td><span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }} text-white">{{ ucfirst($user->role) }}</span></td>
                            <td>{!! $user->verified ? '<span class="badge bg-success text-white">' . __('lang.yes') . '</span>' : '<span class="badge bg-warning text-white">' . __('lang.no') . '</span>' !!}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info transition-all"><i class="far fa-eye"></i></a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary transition-all"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger transition-all" onclick="return confirm('{{ __('lang.confirm_delete') }}')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="d-md-none">
                @foreach($users as $user)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 font-weight-bold text-gray-800">{{ $user->username }}</h6>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }} text-white">{{ ucfirst($user->role) }}</span>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-2">
                                <small class="text-muted">{{ __('lang.id') }}</small><br>
                                <span>{{ substr($user->id, 20) }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">{{ __('lang.verified') }}</small><br>
                                {!! $user->verified ? '<span class="badge bg-success text-white">' . __('lang.yes') . '</span>' : '<span class="badge bg-warning text-white">' . __('lang.no') . '</span>' !!}
                            </div>
                            <div class="col-12 mb-2">
                                <small class="text-muted">{{ __('lang.email') }}</small><br>
                                <span>{{ $user->email }}</span>
                            </div>
                            <div class="col-12 mb-2">
                                <small class="text-muted">{{ __('lang.phone') }}</small><br>
                                <span>+{{ $user->phone }}</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info flex-fill transition-all"><i class="far fa-eye"></i> {{ __('lang.view') }}</a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary flex-fill transition-all"><i class="fas fa-edit"></i> {{ __('lang.edit') }}</a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="flex-fill">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger w-100 transition-all" onclick="return confirm('{{ __('lang.confirm_delete') }}')"><i class="fas fa-trash"></i> {{ __('lang.delete') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{ $users->links() }}
        </div>
    </div>
</div>

@push('styles')
<style>
    :root {
        --primary: #4e73df;
        --success: #1cc88a;
        --info: #36b9cc;
        --warning: #f6c23e;
        --danger: #e74a3b;
        --gray-800: #5a5c69;
        --sidebar-bg: #2c3e50;
        --transition-speed: 0.3s;
    }

    .card {
        border: none;
        border-radius: 10px;
        transition: transform var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
    }

    .card-header {
        background: linear-gradient(135deg, var(--sidebar-bg), #3a506b) !important;
        border-radius: 10px 10px 0 0 !important;
        padding: 1rem 1.5rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .table {
        border-radius: 8px;
        overflow: hidden;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        border-radius: 6px;
        transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .btn-sm:hover {
        transform: translateY(-2px);
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.5em 0.8em;
    }

    .form-control, .form-select {
        border-radius: 6px;
        transition: border-color 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

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

        .form-label {
            font-size: 0.9rem;
        }

        .form-control, .form-select {
            font-size: 0.9rem;
        }
    }

    [dir="rtl"] .d-flex.gap-2 {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .text-start {
        text-align: end !important;
    }
</style>
@endpush
@endsection