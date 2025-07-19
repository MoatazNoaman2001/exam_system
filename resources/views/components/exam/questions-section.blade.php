<div>
    <!-- Let all your things have their places; let each part of your business have its time. - Benjamin Franklin -->
</div>
@props(['exam'])

<!-- Questions Section -->
<h4 class="mb-3">{{ __('Exam Questions') }}</h4>
<div id="questions-container">
    @foreach($exam->questions as $index => $question)
        <x-exam.question-card :question="$question" :index="$index" />
    @endforeach
</div>

<button type="button" class="btn btn-success mb-3" @click="addQuestion()">
    <i class="fas fa-plus"></i> {{ __('Add Question') }}
</button>

<!-- Question Template (Hidden) -->
<template id="question-template">
    <x-exam.question-card :question="null" :index="null" template="true" />
</template>