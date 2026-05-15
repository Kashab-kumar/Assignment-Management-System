@extends('teacher.layout')

@section('content')
<div class="p-4 max-w-3xl">
    <h2 class="text-xl font-bold mb-4">Edit Question</h2>

    <form method="POST" action="{{ route('teacher.questions.update', $question) }}">
        @csrf
        @method('PUT')
        <div class="mb-2">
            <label>Topic</label>
            <input name="topic" class="input" value="{{ old('topic', $question->topic) }}">
        </div>
        <div class="mb-2">
            <label>Question Type</label>
            <input name="question_type" class="input" value="{{ old('question_type', $question->question_type) }}">
        </div>
        <div class="mb-2">
            <label>Question Text</label>
            <textarea name="question_text" class="textarea">{{ old('question_text', $question->question_text) }}</textarea>
        </div>
        <div class="mb-2">
            <label>Options (JSON or comma-separated)</label>
            <input name="options" class="input" value="{{ is_array($question->options) ? json_encode($question->options) : $question->options }}">
        </div>
        <div class="mb-2">
            <label>Answer</label>
            <input name="answer" class="input" value="{{ old('answer', $question->answer) }}">
        </div>
        <div class="mb-2">
            <label>Marks</label>
            <input name="marks" type="number" step="0.1" class="input" value="{{ old('marks', $question->marks) }}">
        </div>
        <div class="mb-2">
            <label>Tags (comma separated)</label>
            <input name="tags" class="input" value="{{ implode(',', $question->tags ?? []) }}">
        </div>

        <div class="mt-4">
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('teacher.questions.index') }}" class="btn">Cancel</a>
        </div>
    </form>
</div>
@endsection
