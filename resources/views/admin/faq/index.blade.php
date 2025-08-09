@extends('layouts.admin')

@section('title', 'Manage FAQs')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Manage FAQs</h3>
                    <a href="{{ route('admin.faq.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New FAQ
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card-body">
                    @if($faqs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-white">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Question (EN)</th>
                                        <th>Question (AR)</th>
                                        <th width="100">Order</th>
                                        <th width="100">Status</th>
                                        <th width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="faq-sortable">
                                    @foreach($faqs as $faq)
                                        <tr data-id="{{ $faq->id }}">
                                            <td>
                                                <i class="fas fa-grip-vertical text-muted handle" style="cursor: move;"></i>
                                                {{ $faq->id }}
                                            </td>
                                            <td>
                                                <strong>{{ Str::limit($faq->question['en'] ?? 'N/A', 60) }}</strong>
                                            </td>
                                            <td>
                                                {{ Str::limit($faq->question['ar'] ?? 'N/A', 60) }}
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $faq->order }}</span>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.faq.toggle-status', $faq) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $faq->is_active ? 'btn-success' : 'btn-danger' }}">
                                                        <i class="fas {{ $faq->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                                        {{ $faq->is_active ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    
                                                    <a href="{{ route('admin.faq.edit', $faq) }}" class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger" title="Delete" 
                                                            onclick="deleteFaq({{ $faq->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-primary" onclick="saveOrder()">
                                <i class="fas fa-save"></i> Save Order
                            </button>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No FAQs found</h5>
                            <p class="text-muted">Start by creating your first FAQ</p>
                            <a href="{{ route('admin.faq.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create FAQ
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete FAQ Modal -->
<div class="modal fade" id="deleteFaqModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this FAQ? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteFaqForm" method="POST" style="display: inline;">
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
<!-- Sortable JS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    // Initialize sortable
    document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.getElementById('faq-sortable');
        if (tbody) {
            new Sortable(tbody, {
                animation: 150,
                handle: '.handle',
                onEnd: function() {
                    // Update order numbers visually
                    updateOrderNumbers();
                }
            });
        }
    });

    function updateOrderNumbers() {
        const rows = document.querySelectorAll('#faq-sortable tr');
        rows.forEach((row, index) => {
            const orderBadge = row.querySelector('.badge');
            if (orderBadge) {
                orderBadge.textContent = index;
            }
        });
    }

    function saveOrder() {
        const rows = document.querySelectorAll('#faq-sortable tr');
        const faqs = [];
        
        rows.forEach((row, index) => {
            faqs.push({
                id: row.dataset.id,
                order: index
            });
        });

        fetch('{{ route("admin.faq.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                faqs: faqs
            })
        })
        .then(response => response.json())
        .then(data => {
            location.reload();
        })
        .catch(error => {
            alert('Error saving order. Please try again.');
        });
    }

    function deleteFaq(faqId) {
        const form = document.getElementById('deleteFaqForm');
        form.action = `{{ url('admin/faq') }}/${faqId}`;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteFaqModal'));
        modal.show();
    }
</script>
@endsection