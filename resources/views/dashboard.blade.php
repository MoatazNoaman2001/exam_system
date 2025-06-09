<div class="col-md-9">
    <h2>Dashboard</h2>

    <!-- Statistics -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Exams</h5>
                    <p class="card-text">{{ $stats['total_exams'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Quizzes</h5>
                    <p class="card-text">{{ $stats['total_quizzes'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Tests</h5>
                    <p class="card-text">{{ $stats['total_tests'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Missions</h5>
                    <p class="card-text">{{ $stats['total_missions'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Domains</h5>
                    <p class="card-text">{{ $stats['total_domains'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Slides</h5>
                    <p class="card-text">{{ $stats['total_slides'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Chapters</h5>
                    <p class="card-text">{{ $stats['total_chapters'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Notifications</h5>
                    <p class="card-text">{{ $stats['total_notifications'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="mt-4">
        <h3>Recent Activity</h3>
        <h4 id="users">Recent Users</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recent_users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">Edit</a>
                            <a href="#" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4 id="exams">Recent Exams</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Text</th>
                    <th>Questions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recent_exams as $exam)
                    <tr>
                        <td>{{ $exam->id }}</td>
                        <td>{{ $exam->text }}</td>
                        <td>{{ $exam->number_of_questions }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">Edit</a>
                            <a href="#" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4 id="notifications">Recent Notifications</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Text</th>
                    <th>User</th>
                    <th>Seen</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recent_notifications as $notification)
                    <tr>
                        <td>{{ $notification->id }}</td>
                        <td>{{ $notification->text }}</td>
                        <td>{{ $notification->user->username }}</td>
                        <td>{{ $notification->is_seen ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">Edit</a>
                            <a href="#" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>