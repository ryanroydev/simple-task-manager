@extends('layouts.app')

@section('content')

<div class="container my-4">
    <h1 class="h2 mb-4">Task List</h1>
    <table id="tasksTable" class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->content }}</td>
                    <td>{{ $task->status }}</td>
                    <td>{{ $task->created_at?->format('F j, Y, g:i A') }}</td>
                    <td>
                        @if ($task->file_path)
                            <a href="{{ asset('storage/' . $task->file_path) }}" target="_blank" class="text-primary">View File</a>
                        @endif
                        <a href="{{ route('tasks.edit', $task->id) }}" class="text-warning mx-2">Edit</a>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger p-0">Trash</button>
                        </form>
                    </td>
                </tr>
                @foreach ($task->subtasks as $subtask)
                    <tr>
                        <td>— {{ $subtask->title }}</td>
                        <td>{{ $subtask->content }}</td>
                        <td>{{ $subtask->status }}</td>
                        <td>{{ $subtask->created_at->format('F j, Y, g:i A') }}</td>
                        <td>
                            @if ($subtask->file_path)
                                <a href="{{ asset('storage/' . $subtask->file_path) }}" target="_blank" class="text-primary">View File</a>
                            @endif
                            <a href="{{ route('tasks.edit', $subtask->id) }}" class="text-warning mx-2">Edit</a>
                            <form action="{{ route('tasks.destroy', $subtask->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0">Trash</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    {{ $tasks->links() }}

</div>

@endsection
