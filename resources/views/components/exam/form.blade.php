<div>
    <!-- Do what you can, with what you have, where you are. - Theodore Roosevelt -->
</div>
@props(['exam', 'action', 'method' => 'POST'])

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Edit Exam') }}</h1>
        <a href="{{ route('admin.exams') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> {{ __('Back') }}
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Exam Details') }}</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ $action }}" id="examForm" x-data="examForm()">
                @csrf
                @if($method === 'PUT')
                    @method('PUT')
                @endif
                
                <x-exam.basic-info :exam="$exam" />
                
                <hr class="my-4">
                
                <x-exam.questions-section :exam="$exam" />
                
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('Update Exam') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function examForm() {
    return {
        questionCount: {{ count($exam->questions) }},
        
        init() {
            this.updateQuestionNumbers();
        },
        
        addQuestion() {
            this.questionCount++;
            const template = document.getElementById('question-template').innerHTML;
            const newQuestion = template.replace(/\[INDEX\]/g, this.questionCount - 1);
            document.getElementById('questions-container').insertAdjacentHTML('beforeend', newQuestion);
            this.updateQuestionNumbers();
        },
        
        removeQuestion(element) {
            element.closest('.question-card').remove();
            this.questionCount--;
            this.updateQuestionNumbers();
        },
        
        updateQuestionNumbers() {
            document.querySelectorAll('.question-card').forEach((question, index) => {
                question.querySelector('.question-number').textContent = index + 1;
                // Update all name attributes
                question.querySelectorAll('[name]').forEach(el => {
                    el.name = el.name.replace(/questions\[\d+\]/, `questions[${index}]`);
                });
            });
        }
    }
}
</script>