@extends('layouts.admin')

@section('title', 'Contact Messages')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary text-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['total'] }}</h4>
                            <p class="card-text">Total Messages</p>
                        </div>
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger text-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['unread'] }}</h4>
                            <p class="card-text">Unread</p>
                        </div>
                        <i class="fas fa-envelope-open fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning text-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['read'] }}</h4>
                            <p class="card-text">Read</p>
                        </div>
                        <i class="fas fa-eye fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success text-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $stats['replied'] }}</h4>
                            <p class="card-text">Replied</p>
                        </div>
                        <i class="fas fa-reply fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h3 class="card-title">Contact Messages</h3>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.contact.export') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-download"></i> Export
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <form method="GET" action="{{ route('admin.contact.index') }}" class="d-flex gap-2">
                                <select name="status" class="form-select form-select-sm" style="width: auto;">
                                    <option value="">All Status</option>
                                    <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                                    <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                                    <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
                                </select>
                                <input type="text" name="search" class="form-control form-control-sm" 
                                       placeholder="Search name, email, subject..." value="{{ request('search') }}" style="width: 300px;">
                                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                <a href="{{ route('admin.contact.index') }}" class="btn btn-secondary btn-sm">Clear</a>
                            </form>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleBulkActions()">
                                <i class="fas fa-tasks"></i> Bulk Actions
                            </button>
                        </div>
                    </div>

                    <!-- Bulk Actions (Hidden by default) -->
                    <div id="bulk-actions" class="card bg-light mb-3" style="display: none;">
                        <div class="card-body py-2">
                            <form action="{{ route('admin.contact.bulk-action') }}" method="POST" class="d-flex align-items-center gap-2">
                                @csrf
                                <small class="text-muted">With selected:</small>
                                <select name="action" class="form-select form-select-sm" style="width: auto;" required>
                                    <option value="">Choose action...</option>
                                    <option value="mark_read">Mark as Read</option>
                                    <option value="delete">Delete</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                                <span id="selected-count" class="text-muted small">0 selected</span>
                            </form>
                        </div>
                    </div>

                    @if($contacts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-white">
                                    <tr>
                                        <th width="30">
                                            <input type="checkbox" id="select-all" class="form-check-input">
                                        </th>
                                        <th width="50">Status</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th width="120">Date</th>
                                        <th width="100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contacts as $contact)
                                        <tr class="{{ $contact->isNew() ? 'table-info' : '' }}">
                                            <td>
                                                <input type="checkbox" name="contacts[]" value="{{ $contact->id }}" 
                                                       class="form-check-input contact-checkbox">
                                            </td>
                                            <td>
                                                <span class="badge {{ $contact->getStatusBadgeClass() }} text-primary">
                                                    @switch($contact->status)
                                                        @case('unread')
                                                            <i class="fas fa-envelope"></i> New
                                                            @break
                                                        @case('read')
                                                            <i class="fas fa-eye"></i> Read
                                                            @break
                                                        @case('replied')
                                                            <i class="fas fa-reply"></i> Replied
                                                            @break
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $contact->name }}</strong>
                                                @if($contact->isNew())
                                                    <span class="badge bg-danger badge-sm ms-1">NEW</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                                    {{ $contact->email }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $contact->subject }}</span>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" title="{{ $contact->message }}">
                                                    {{ Str::limit($contact->message, 50) }}
                                                </div>
                                            </td>
                                            <td>
                                                <small>
                                                    {{ $contact->created_at->format('M d, Y') }}<br>
                                                    <span class="text-muted">{{ $contact->created_at->format('H:i') }}</span>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.contact.show', $contact) }}" 
                                                       class="btn btn-sm btn-outline-primary d-flex align-items-center" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($contact->status != 'read')
                                                        <form action="{{ route('admin.contact.mark-read', $contact) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-outline-warning d-flex align-items-center" title="Mark as Read">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <button type="button" class="btn btn-sm btn-outline-danger d-flex align-items-center" 
                                                            onclick="deleteContact({{ $contact->id }})" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $contacts->firstItem() }} to {{ $contacts->lastItem() }} of {{ $contacts->total() }} results
                            </div>
                            {{ $contacts->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No contact messages found</h5>
                            <p class="text-muted">
                                @if(request()->hasAny(['status', 'search']))
                                    Try adjusting your filters
                                @else
                                    Contact messages will appear here when users submit the contact form
                                @endif
                            </p>
                        </div>
                    @endif
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
                <p>Are you sure you want to delete this contact message? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteContactForm" method="POST" style="display: inline;">
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
    // Bulk actions functionality
    function toggleBulkActions() {
        const bulkActions = document.getElementById('bulk-actions');
        bulkActions.style.display = bulkActions.style.display === 'none' ? 'block' : 'none';
    }

    // Select all checkbox functionality
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.contact-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Individual checkbox functionality
    document.querySelectorAll('.contact-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    function updateSelectedCount() {
        const selectedCheckboxes = document.querySelectorAll('.contact-checkbox:checked');
        document.getElementById('selected-count').textContent = selectedCheckboxes.length + ' selected';
        
        // Add selected contact IDs to bulk action form
        const bulkForm = document.querySelector('#bulk-actions form');
        // Remove existing hidden inputs
        bulkForm.querySelectorAll('input[name="contacts[]"]').forEach(input => input.remove());
        
        // Add selected contact IDs
        selectedCheckboxes.forEach(checkbox => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'contacts[]';
            hiddenInput.value = checkbox.value;
            bulkForm.appendChild(hiddenInput);
        });
    }

    // Delete contact function
    function deleteContact(contactId) {
        const form = document.getElementById('deleteContactForm');
        form.action = `{{ url('admin/contacts') }}/${contactId}`;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteContactModal'));
        modal.show();
    }

    // Auto-refresh for new messages (optional)
    let refreshInterval;
    function startAutoRefresh() {
        refreshInterval = setInterval(() => {
            // Only refresh if no modals are open and no form is being filled
            if (!document.querySelector('.modal.show') && !document.activeElement.matches('input, textarea, select')) {
                window.location.reload();
            }
        }, 60000); // Refresh every minute
    }

    // Uncomment to enable auto-refresh
    // startAutoRefresh();
</script>
@endsection