@extends('teacher.layout')

@section('content')
<div class="p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Question Bank</h2>
        <a href="{{ route('teacher.questions.create') }}" class="btn btn-primary">Add Question</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>ID</th>
                <th>Topic</th>
                <th>Type</th>
                <th>Marks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $q)
            <tr>
                <td>{{ $q->id }}</td>
                <td>{{ $q->topic }}</td>
                <td>{{ $q->question_type }}</td>
                <td>{{ $q->marks }}</td>
                <td>
                    <a href="{{ route('teacher.questions.edit', $q) }}" class="btn btn-sm">Edit</a>
                    <form action="{{ route('teacher.questions.destroy', $q) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this question?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $questions->links() }}</div>
</div>
@endsection
