@extends('layouts.admin')

@section('title', 'Contact Message Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        Contact Message #{{ $contact->id }}
                        <span class="badge {{ $contact->getStatusBadgeClass() }} ms-2">
                            {{ ucfirst($contact->status) }}
                        </span>
                    </h3>
                    <div>
                        @if($contact->status != 'read')
                            <form action="{{ route('admin.contact.mark-read', $contact) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-check"></i> Mark as Read
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.contact.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Messages
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card-body">
                    <div class="row">
                        <!-- Contact Details -->
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-envelope"></i> Message Details
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-3"><strong>From:</strong></div>
                                        <div class="col-sm-9">
                                            <strong>{{ $contact->name }}</strong>
                                            <br>
                                            <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                                <i class="fas fa-envelope"></i> {{ $contact->email }}
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-sm-3"><strong>Subject:</strong></div>
                                        <div class="col-sm-9">
                                            <span class="badge bg-secondary fs-6">{{ $contact->subject }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-sm-3"><strong>Received:</strong></div>
                                        <div class="col-sm-9">
                                            {{ $contact->created_at->format('F d, Y \a\t h:i A') }}
                                            <small class="text-muted">({{ $contact->created_at->diffForHumans() }})</small>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-3"><strong>Status:</strong></div>
                                        <div class="col-sm-9">
                                            <span class="badge {{ $contact->getStatusBadgeClass() }}">
                                                @switch($contact->status)
                                                    @case('unread')
                                                        <i class="fas fa-envelope"></i> Unread
                                                        @break
                                                    @case('read')
                                                        <i class="fas fa-eye"></i> Read
                                                        @break
                                                    @case('replied')
                                                        <i class="fas fa-reply"></i> Replied
                                                        @break
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Message Content -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-comment"></i> Message Content
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="message-content p-3 bg-light rounded">
                                        {!! nl2br(e($contact->message)) !!}
                                    </div>
                                    <div class="mt-2 text-muted small">
                                        <i class="fas fa-info-circle"></i>
                                        Message length: {{ strlen($contact->message) }} characters
                                    </div>
                                </div>
                            </div>

                            <!-- Reply Section -->
                            @if($contact->status === 'replied' && $contact->admin_reply)
                                <div class="card mb-4">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-reply"></i> Admin Reply
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="reply-content p-3 bg-light rounded">
                                            {!! nl2br(e($contact->admin_reply)) !!}
                                        </div>
                                        <div class="mt-3 text-muted small">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <i class="fas fa-user"></i>
                                                    Replied by: <strong>{{ $contact->repliedBy->name ?? 'Unknown' }}</strong>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <i class="fas fa-clock"></i>
                                                    {{ $contact->replied_at?->format('F d, Y \a\t h:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Reply Form -->
                            @if($contact->status !== 'replied')
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-reply"></i> Send Reply
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <strong>Whoops!</strong> There were some problems with your reply:
                                                <ul class="mb-0 mt-2">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <form action="{{ route('admin.contact.reply', $contact) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="reply" class="form-label">Your Reply:</label>
                                                <textarea class="form-control" id="reply" name="reply" rows="6" 
                                                          required placeholder="Enter your reply to {{ $contact->name }}...">{{ old('reply') }}</textarea>
                                                <div class="form-text">
                                                    This reply will be sent to {{ $contact->email }} and the status will be marked as "Replied".
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between">
                                                <button type="button" class="btn btn-outline-secondary" onclick="insertTemplate()">
                                                    <i class="fas fa-file-alt"></i> Use Template
                                                </button>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-paper-plane"></i> Send Reply
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Sidebar -->
                        <div class="col-md-4">
                            <!-- Quick Actions -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-bolt"></i> Quick Actions
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-envelope"></i> Reply via Email Client
                                        </a>
                                        
                                        @if($contact->status !== 'read')
                                            <form action="{{ route('admin.contact.mark-read', $contact) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-check"></i> Mark as Read
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteContact()">
                                            <i class="fas fa-trash"></i> Delete Message
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-user"></i> Contact Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>Name:</strong><br>
                                        {{ $contact->name }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Email:</strong><br>
                                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Subject Category:</strong><br>
                                        <span class="badge bg-secondary">{{ $contact->subject }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Message ID:</strong><br>
                                        #{{ $contact->id }}
                                    </div>
                                </div>
                            </div>

                            <!-- Message Stats -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chart-bar"></i> Message Statistics
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>Received:</strong><br>
                                        <small>{{ $contact->created_at->format('M d, Y H:i:s') }}</small>
                                    </div>
                                    @if($contact->status === 'replied')
                                        <div class="mb-2">
                                            <strong>Response Time:</strong><br>
                                            <small>{{ $contact->created_at->diffForHumans($contact->replied_at, true) }}</small>
                                        </div>
                                    @else
                                        <div class="mb-2">
                                            <strong>Waiting Time:</strong><br>
                                            <small>{{ $contact->created_at->diffForHumans() }}</small>
                                        </div>
                                    @endif
                                    <div class="mb-2">
                                        <strong>Characters:</strong><br>
                                        <small>{{ number_format(strlen($contact->message)) }}</small>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Words:</strong><br>
                                        <small>{{ str_word_count($contact->message) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Contact Modal -->
<div class="modal fade" id="deleteContactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Contact Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this contact message from <strong>{{ $contact->name }}</strong>?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.contact.destroy', $contact) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function deleteContact() {
        const modal = new bootstrap.Modal(document.getElementById('deleteContactModal'));
        modal.show();
    }

    function insertTemplate() {
        const templates = {
            'general': `Hello {{ $contact->name }},

Thank you for contacting Sprint Skills. We have received your message and will get back to you shortly.

Best regards,
Sprint Skills Support Team`,
            
            'technical': `Hello {{ $contact->name }},

Thank you for reporting the technical issue. Our development team is looking into this matter and we will provide an update soon.

If you need immediate assistance, please don't hesitate to contact us.

Best regards,
Sprint Skills Technical Support`,
            
            'billing': `Hello {{ $contact->name }},

Thank you for your billing inquiry. Our accounts team will review your request and respond within 24 hours.

Best regards,
Sprint Skills Billing Department`
        };

        const subject = '{{ $contact->subject }}';
        let template = templates.general;
        
        if (subject.toLowerCase().includes('technical') || subject.toLowerCase().includes('support')) {
            template = templates.technical;
        } else if (subject.toLowerCase().includes('billing') || subject.toLowerCase().includes('payment')) {
            template = templates.billing;
        }

        const replyTextarea = document.getElementById('reply');
        if (confirm('This will replace any existing text in the reply field. Continue?')) {
            replyTextarea.value = template;
            replyTextarea.focus();
        }
    }

    // Character counter for reply textarea
    document.addEventListener('DOMContentLoaded', function() {
        const replyTextarea = document.getElementById('reply');
        if (replyTextarea) {
            const counter = document.createElement('div');
            counter.className = 'form-text text-end';
            replyTextarea.parentNode.appendChild(counter);

            function updateCounter() {
                const length = replyTextarea.value.length;
                const maxLength = 2000;
                counter.textContent = `${length}/${maxLength} characters`;
                counter.className = length > maxLength - 100 ? 'form-text text-end text-danger' : 'form-text text-end text-muted';
            }

            replyTextarea.addEventListener('input', updateCounter);
            updateCounter();
        }
    });
</script>
@endsection