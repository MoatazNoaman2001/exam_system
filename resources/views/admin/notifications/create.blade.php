<div>
    <!-- Be present above all else. - Naval Ravikant -->
</div>
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Notification</h1>
        <a href="{{ route('admin.notifications') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Notification Details</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.notifications.store') }}">
                @csrf
                <div class="form-group">
                    <label for="text">Title</label>
                    <input type="text" class="form-control @error('text') is-invalid @enderror" id="text" name="text" value="{{ old('text') }}" required>
                    @error('text')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="subtext">Content</label>
                    <textarea class="form-control" id="subtext" name="subtext" rows="3">{{ old('subtext') }}</textarea>
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="send_to_all" name="send_to_all" value="1">
                        <label class="form-check-label" for="send_to_all">
                            Send to all users
                        </label>
                    </div>
                </div>
                <br/>
                <div class="form-group" id="userSelectGroup">
                    <label for="user_id">Select User</label>
                    <select class="form-control" id="user_id" name="user_id">
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->username }}</option>
                        @endforeach
                    </select>
                </div>
                <br/>
                <button type="submit" class="btn btn-primary">Send Notification</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('send_to_all').addEventListener('change', function() {
        document.getElementById('userSelectGroup').style.display = this.checked ? 'none' : 'block';
    });
</script>
@endsection