@extends('layouts.admin')

@section('title', 'رسائل تواصل معنا')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">رسائل تواصل معنا</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الموضوع</th>
                    <th>الرسالة</th>
                    <th>تاريخ الإرسال</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feedbacks as $feedback)
                <tr>
                    <td>{{ $feedback->id }}</td>
                    <td>{{ $feedback->name }}</td>
                    <td>{{ $feedback->email }}</td>
                    <td>{{ $feedback->subject }}</td>
                    <td style="max-width:300px; white-space:pre-wrap;">{{ $feedback->message }}</td>
                    <td>{{ $feedback->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">لا توجد رسائل بعد.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $feedbacks->links() }}
    </div>
</div>
@endsection 