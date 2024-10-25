@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="card bg-white">
                <div class="card-body">
                    <a class="btn btn-primary float-end" href="{{ route('tasks.create') }}">ADD</a>
                    <h1 class="h2 mb-4 card-title">Task List</h1>
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
                            <tr class="task-row toggle-subtasks" data-toggle="{{ $task->id }}">
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->content }}</td>
                                <td>{{ $task->status }}</td>
                                <td>{{ $task->created_at?->format('F j, Y, g:i A') }}</td>
                                <td>
                                    @if ($task->file_path)
                                        <a href="#" class="btn btn-primary" onclick="window.open('{{ asset('storage/' . $task->file_path) }}','popUpWindow','height=500,width=400,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');" title="View Image"><i class="fas fa-eye"></i></a>
                                    @endif
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning mx-2" title="Edit Tasks"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('tasks.trash', $task->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger "  title="Remove Tasks"><i class="fas fa-trash"></i></button>
                                    </form>
                                  
                                </td>
                            </tr>
                                <tr class="subtasks-row subtasks-{{ $task->id }} d-none">
                                    <td colspan="5">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Sub task Title</th>
                                                    <th>Sub task Content</th>
                                                    <th>Status</th>
                                                    <th>Created At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($task->subtasks as $subtask)
                                                    <tr>
                                                        <td>{{ $subtask->title }}</td>
                                                        <td>{{ $subtask->content }}</td>
                                                        <td>{{ $subtask->status }}</td>
                                                        <td>{{ $subtask->created_at?->format('F j, Y, g:i A') }}</td>
                                                        <td>
                                                            @if ($subtask->file_path)
                                                            <a href="#" class="btn btn-primary" onclick="window.open('{{ asset('storage/' . $subtask->file_path) }}','popUpWindow','height=500,width=400,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');" title="View Image"><i class="fas fa-eye"></i></a>
                                                            @endif
                                                            <a href="{{ route('tasks.edit', $subtask->id) }}" class="btn btn-warning " title="Edit Task"><i class="fas fa-edit"></i></a>
                                                            <form action="{{ route('tasks.trash', $subtask->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger" title="Remove Task"><i class="fas fa-trash"></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('.btn').click(function(event) {
            // Prevent the click event from toggle row 
            event.stopPropagation();
            
        });
        $('.toggle-subtasks').click(function() {
            // Toggle row
            const taskId = $(this).data('toggle');
            const subtasksRow = $('.subtasks-' + taskId);
            
            subtasksRow.toggleClass('d-none');
        });
    });
    </script>
@endsection
