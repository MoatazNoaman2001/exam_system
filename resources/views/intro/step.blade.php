@extends('layouts.app')

@section('content')
<div class="container my-5" style="max-width: 800px;">
    <div class="bg-white p-5 rounded-4 shadow border">

        {{-- خطوات الاستبيان --}}
        <div class="d-flex justify-content-center align-items-center mb-4 fs-5 fw-semibold text-secondary">
            <div class="step-circle">١</div>
            <span class="mx-2">—</span>
            <div class="step-circle">٢</div>
            <span class="mx-2">—</span>
            <div class="step-circle">٣</div>
            <span class="mx-2">—</span>
            <div class="step-circle">٤</div>
        </div>

        {{-- السؤال --}}
        <h3 class="mb-5 text-center">{{ $question->text }}</h3>

        {{-- Display validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- الإجابات --}}
        <form method="POST" action="{{ route('intro.store', $step) }}">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <input type="hidden" name="question_id" value="{{ $question->id }}">

            <div class="d-flex flex-column align-items-start mb-4">
                @foreach($question->introSelections as $selection)
                    {{-- المربع لكل اختيار --}}
                    <div class="w-100 border p-3 rounded mb-2 selection-option" 
                         data-has-extra-text="{{ $selection->has_extra_text ? 'true' : 'false' }}"
                         data-selection-id="{{ $selection->id }}">
                        <label class="form-check-label d-flex align-items-center" for="selection{{ $selection->id }}">
                            <input class="form-check-input me-2" 
                                   type="radio" 
                                   name="selection_id" 
                                   id="selection{{ $selection->id }}" 
                                   value="{{ $selection->id }}" 
                                   required
                                   @if(old('selection_id') == $selection->id) checked @endif>
                            {{ $selection->text }}
                        </label>
                    </div>
                @endforeach
            </div>

            {{-- Extra text field (displayed conditionally) --}}
            <div class="form-group w-100 mb-3" id="extra-text-container" style="display: none;">
                <label for="extra_text" class="mb-2 fw-medium">التفاصيل الإضافية</label>
                <input type="text" 
                       name="extra_text" 
                       id="extra_text" 
                       class="form-control" 
                       placeholder="برجاء التحديد"
                       value="{{ old('extra_text') }}">
                <small class="form-text text-muted">هذا الحقل مطلوب لهذا الاختيار</small>
            </div>

            {{-- زر التالي --}}
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5 py-2">التالي</button>
            </div>
        </form>
    </div>
</div>

<style>
    .step-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #f8f9fa;
        color: #495057;
        text-align: center;
        line-height: 35px;
        border: 1px solid #ced4da;
        transition: all 0.3s ease;
    }
    
    .step-circle.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
        transform: scale(1.1);
    }
    
    body {
        background-color: #f8f9fa !important;
    }
    
    .selection-option {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .selection-option:hover {
        background-color: #f1f8ff;
        border-color: #86b7fe;
    }
    
    .selection-option.selected {
        background-color: #e7f1ff;
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activate current step
        const stepCircles = document.querySelectorAll('.step-circle');
        if (stepCircles.length >= {{ $step }}) {
            stepCircles[{{ $step }} - 1].classList.add('active');
        }
        
        // Handle selection and extra text visibility
        const selectionOptions = document.querySelectorAll('.selection-option');
        const extraTextContainer = document.getElementById('extra-text-container');
        const extraTextInput = document.getElementById('extra_text');
        
        // Set initial state if returning with errors
        const selectedOption = document.querySelector('input[name="selection_id"]:checked');
        if (selectedOption) {
            const selectionDiv = selectedOption.closest('.selection-option');
            selectionDiv.classList.add('selected');
            
            const hasExtraText = selectionDiv.dataset.hasExtraText === 'true';
            extraTextContainer.style.display = hasExtraText ? 'block' : 'none';
            
            if (hasExtraText) {
                extraTextInput.required = true;
            }
        }
        
        // Add event listeners to radio buttons
        selectionOptions.forEach(option => {
            const radio = option.querySelector('input[type="radio"]');
            
            radio.addEventListener('change', function() {
                // Remove selected class from all options
                selectionOptions.forEach(opt => {
                    opt.classList.remove('selected');
                });
                
                // Add selected class to current option
                option.classList.add('selected');
                
                // Check if extra text is needed
                const hasExtraText = option.dataset.hasExtraText === 'true';
                
                // Toggle extra text field
                if (hasExtraText) {
                    extraTextContainer.style.display = 'block';
                    extraTextInput.required = true;
                } else {
                    extraTextContainer.style.display = 'none';
                    extraTextInput.required = false;
                    extraTextInput.value = '';
                }
            });
            
            // Also make the whole div clickable
            option.addEventListener('click', function(e) {
                if (e.target.tagName !== 'INPUT') {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change'));
                }
            });
        });
    });
</script>
@endsection