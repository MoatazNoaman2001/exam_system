<div>
    <!-- We must ship. - Taylor Otwell -->
</div>
@props(['question', 'index', 'template' => false])

<div class="true-false-container">
    <h6 class="mb-3">{{ __('True/False Options') }}</h6>
    
    @if(!$template && $question && $question->type === 'true_false')
        @foreach($question->answers as $optionIndex => $option)
            <div class="option-item mb-3 p-3 border rounded">
                <input type="hidden" name="questions[{{ $index }}][answers][{{ $optionIndex }}][id]" value="{{ $option->id }}">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <input type="text" class="form-control" 
                               name="questions[{{ $index }}][answers][{{ $optionIndex }}][answer]" 
                               value="{{ old("questions.$index.answers.$optionIndex.answer", $option->answer) }}" 
                               readonly>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" 
                               name="questions[{{ $index }}][answers][{{ $optionIndex }}][answer-ar]" 
                               value="{{ old("questions.$index.answers.$optionIndex.answer-ar", $option['answer-ar']) }}" 
                               dir="rtl" readonly>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" 
                                   name="questions[{{ $index }}][correct_answer]" 
                                   value="{{ $optionIndex }}"
                                   {{ $option->is_correct ? 'checked' : '' }}>
                            <label class="form-check-label">{{ __('Correct Answer') }}</label>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <!-- Template for True/False -->
        <div class="option-item mb-3 p-3 border rounded">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <input type="text" class="form-control" 
                           name="questions[{{ $index }}][answers][0][answer]" 
                           value="True" readonly>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" 
                           name="questions[{{ $index }}][answers][0][answer-ar]" 
                           value="صحيح" dir="rtl" readonly>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" 
                               name="questions[{{ $index }}][correct_answer]" 
                               value="0">
                        <label class="form-check-label">{{ __('Correct Answer') }}</label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="option-item mb-3 p-3 border rounded">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <input type="text" class="form-control" 
                           name="questions[{{ $index }}][answers][1][answer]" 
                           value="False" readonly>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" 
                           name="questions[{{ $index }}][answers][1][answer-ar]" 
                           value="خطأ" dir="rtl" readonly>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" 
                               name="questions[{{ $index }}][correct_answer]" 
                               value="1">
                        <label class="form-check-label">{{ __('Correct Answer') }}</label>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
// Add hidden fields for is_correct based on radio selection
document.addEventListener('change', function(e) {
    if (e.target.name && e.target.name.includes('[correct_answer]')) {
        const questionIndex = e.target.name.match(/questions\[(\d+)\]/)[1];
        const selectedValue = e.target.value;
        
        // Update hidden is_correct fields
        document.querySelectorAll(`input[name^="questions[${questionIndex}][answers]"][name$="[is_correct]"]`).forEach((input, index) => {
            input.value = index == selectedValue ? '1' : '0';
        });
    }
});
</script>