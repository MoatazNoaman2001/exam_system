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
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('lang.all_users') }}</h6>
        </div>
        <div class="card-body">
            <!-- Desktop Table -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                            <td><span class="badge badge-{{ $user->role === 'admin' ? 'danger' : 'primary' }} text-black">{{ ucfirst($user->role) }}</span></td>
                            <td>{!! $user->verified ? '<span class="badge badge-success text-success">' . __('lang.yes') . '</span>' : '<span class="badge badge-warning text-warning">' . __('lang.no') . '</span>' !!}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info"><i class="far fa-eye" style="color: #075d9f;"></i></a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;">
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
                @foreach($users as $user)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 font-weight-bold text-gray-800">{{ $user->username }}</h6>
                            <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : 'primary' }} text-black">{{ ucfirst($user->role) }}</span>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-2">
                                <small class="text-muted">{{ __('lang.id') }}</small><br>
                                <span>{{ substr($user->id, 20) }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">{{ __('lang.verified') }}</small><br>
                                {!! $user->verified ? '<span class="badge badge-success text-success">' . __('lang.yes') . '</span>' : '<span class="badge badge-warning text-warning">' . __('lang.no') . '</span>' !!}
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
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info flex-fill"><i class="far fa-eye" style="color: #075d9f;"></i> {{ __('lang.view') }}</a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary flex-fill"><i class="fas fa-edit"></i> {{ __('lang.edit') }}</a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="flex-fill">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('{{ __('lang.confirm_delete') }}')"><i class="fas fa-trash"></i> {{ __('lang.delete') }}</button>
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