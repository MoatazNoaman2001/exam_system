@extends('layouts.admin')

@section('title', 'Create FAQ')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Create New FAQ</h3>
                    <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to FAQs
                    </a>
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

                    <form action="{{ route('admin.faq.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- English Question -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="question_en" class="form-label">
                                        Question (English) <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="question_en" name="question_en" rows="3" 
                                              required placeholder="Enter question in English">{{ old('question_en') }}</textarea>
                                </div>
                            </div>

                            <!-- Arabic Question -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="question_ar" class="form-label">Question (Arabic)</label>
                                    <textarea class="form-control" id="question_ar" name="question_ar" rows="3" 
                                              dir="rtl" placeholder="أدخل السؤال باللغة العربية">{{ old('question_ar') }}</textarea>
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
                                    <textarea class="form-control" id="answer_en" name="answer_en" rows="6" 
                                              required placeholder="Enter detailed answer in English">{{ old('answer_en') }}</textarea>
                                    <div class="form-text">You can use line breaks for formatting.</div>
                                </div>
                            </div>

                            <!-- Arabic Answer -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="answer_ar" class="form-label">Answer (Arabic)</label>
                                    <textarea class="form-control" id="answer_ar" name="answer_ar" rows="6" 
                                              dir="rtl" placeholder="أدخل الإجابة التفصيلية باللغة العربية">{{ old('answer_ar') }}</textarea>
                                    <div class="form-text">يمكنك استخدام فواصل الأسطر للتنسيق.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Order -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="order" class="form-label">Display Order</label>
                                    <input type="number" class="form-control" id="order" name="order" 
                                           value="{{ old('order', 0) }}" min="0" placeholder="0">
                                    <div class="form-text">Lower numbers appear first (0 = first position)</div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" 
                                               name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active (visible to users)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="card bg-light mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-eye"></i> Preview
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>English Version:</h6>
                                        <div class="preview-question-en mb-2"></div>
                                        <div class="preview-answer-en text-muted"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Arabic Version:</h6>
                                        <div class="preview-question-ar mb-2" dir="rtl"></div>
                                        <div class="preview-answer-ar text-muted" dir="rtl"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create FAQ
                            </button>
                        </div>
                    </form>
                </div>
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
            previewQuestionEn.innerHTML = `<strong>${questionEn.value || 'Question will appear here...'}</strong>`;
            previewQuestionAr.innerHTML = `<strong>${questionAr.value || 'السؤال سيظهر هنا...'}</strong>`;
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
</script>
@endsection