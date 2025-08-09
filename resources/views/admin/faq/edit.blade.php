@extends('layouts.admin')

@section('title', 'Edit FAQ')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Edit FAQ #{{ $faq->id }}</h3>
                    <div>
                        <a href="{{ route('admin.faq.show', $faq) }}" class="btn btn-info me-2">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to FAQs
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input:
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.faq.update', $faq) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- English Question -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="question_en" class="form-label">
                                        Question (English) <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="question_en" name="question_en" rows="3" 
                                              required placeholder="Enter question in English">{{ old('question_en', $faq->question['en'] ?? '') }}</textarea>
                                </div>
                            </div>

                            <!-- Arabic Question -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="question_ar" class="form-label">Question (Arabic)</label>
                                    <textarea class="form-control" id="question_ar" name="question_ar" rows="3" 
                                              dir="rtl" placeholder="أدخل السؤال باللغة العربية">{{ old('question_ar', $faq->question['ar'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- English Answer -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="answer_en" class="form-label">
                                        Answer (English) <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="answer_en" name="answer_en" rows="8" 
                                              required placeholder="Enter detailed answer in English">{{ old('answer_en', $faq->answer['en'] ?? '') }}</textarea>
                                    <div class="form-text">You can use line breaks for formatting.</div>
                                </div>
                            </div>

                            <!-- Arabic Answer -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="answer_ar" class="form-label">Answer (Arabic)</label>
                                    <textarea class="form-control" id="answer_ar" name="answer_ar" rows="8" 
                                              dir="rtl" placeholder="أدخل الإجابة التفصيلية باللغة العربية">{{ old('answer_ar', $faq->answer['ar'] ?? '') }}</textarea>
                                    <div class="form-text">يمكنك استخدام فواصل الأسطر للتنسيق.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Order -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="order" class="form-label">Display Order</label>
                                    <input type="number" class="form-control" id="order" name="order" 
                                           value="{{ old('order', $faq->order) }}" min="0" placeholder="0">
                                    <div class="form-text">Lower numbers appear first</div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" 
                                               name="is_active" value="1" {{ old('is_active', $faq->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active (visible to users)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Metadata -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Metadata</label>
                                    <div class="small text-muted">
                                        <div><strong>Created:</strong> {{ $faq->created_at->format('M d, Y H:i') }}</div>
                                        <div><strong>Updated:</strong> {{ $faq->updated_at->format('M d, Y H:i') }}</div>
                                        <div><strong>ID:</strong> {{ $faq->id }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="card bg-light mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-eye"></i> Live Preview
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="flag-icon flag-icon-us"></i> English Version:</h6>
                                        <div class="faq-preview-item border rounded p-3">
                                            <div class="preview-question-en mb-2 fw-bold"></div>
                                            <div class="preview-answer-en text-muted"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="flag-icon flag-icon-sa"></i> Arabic Version:</h6>
                                        <div class="faq-preview-item border rounded p-3" dir="rtl">
                                            <div class="preview-question-ar mb-2 fw-bold"></div>
                                            <div class="preview-answer-ar text-muted"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="button" class="btn btn-danger ms-2" onclick="deleteFaq()">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update FAQ
                            </button>
                        </div>
                    </form>
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
                <form action="{{ route('admin.faq.destroy', $faq) }}" method="POST" style="display: inline;">
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
    document.addEventListener('DOMContentLoaded', function() {
        // Live preview functionality
        const questionEn = document.getElementById('question_en');
        const questionAr = document.getElementById('question_ar');
        const answerEn = document.getElementById('answer_en');
        const answerAr = document.getElementById('answer_ar');

        const previewQuestionEn = document.querySelector('.preview-question-en');
        const previewQuestionAr = document.querySelector('.preview-question-ar');
        const previewAnswerEn = document.querySelector('.preview-answer-en');
        const previewAnswerAr = document.querySelector('.preview-answer-ar');

        function updatePreview() {
            previewQuestionEn.textContent = questionEn.value || 'Question will appear here...';
            previewQuestionAr.textContent = questionAr.value || 'السؤال سيظهر هنا...';
            previewAnswerEn.innerHTML = (answerEn.value || 'Answer will appear here...').replace(/\n/g, '<br>');
            previewAnswerAr.innerHTML = (answerAr.value || 'الإجابة ستظهر هنا...').replace(/\n/g, '<br>');
        }

        questionEn.addEventListener('input', updatePreview);
        questionAr.addEventListener('input', updatePreview);
        answerEn.addEventListener('input', updatePreview);
        answerAr.addEventListener('input', updatePreview);

        // Initial preview
        updatePreview();

        // Character counter
        function addCharacterCounter(textarea, maxLength) {
            const counter = document.createElement('div');
            counter.className = 'form-text text-end';
            textarea.parentNode.appendChild(counter);

            function updateCounter() {
                const remaining = maxLength - textarea.value.length;
                counter.textContent = `${textarea.value.length}/${maxLength} characters`;
                counter.className = remaining < 50 ? 'form-text text-end text-danger' : 'form-text text-end text-muted';
            }

            textarea.addEventListener('input', updateCounter);
            updateCounter();
        }

        addCharacterCounter(questionEn, 500);
        addCharacterCounter(questionAr, 500);
        addCharacterCounter(answerEn, 2000);
        addCharacterCounter(answerAr, 2000);
    });

    function deleteFaq() {
        const modal = new bootstrap.Modal(document.getElementById('deleteFaqModal'));
        modal.show();
    }
</script>
@endsection