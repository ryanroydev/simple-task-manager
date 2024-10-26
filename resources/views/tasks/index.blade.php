@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="card bg-white">
                <div class="card-body">
                    <form method="GET" action="{{ route('tasks.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search by title"
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}"
                                            {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="limit" class="form-select" onchange="this.form.submit()">
                                    @foreach ([10, 20, 30, 40, 50] as $limit)
                                        <option value="{{ $limit }}"
                                            {{ request('limit') == $limit ? 'selected' : '' }}>{{ $limit }} per page
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Filter</button>
                    </form>

                    <a class="btn btn-primary float-end" href="{{ route('tasks.create') }}">ADD</a>
                    <h1 class="h2 mb-4 card-title">Task List</h1>
                    <div class="table-responsive">
                        <table id="tasksTable" class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th><a href="{{ route('tasks.index', array_merge(request()->all(), ['order_by' => 'title', 'order_direction' => request('order_direction') == 'asc' ? 'desc' : 'asc'])) }}">Title</a></th>
                                    <th>Content</th>
                                    <th>Status</th>
                                    <th><a href="{{ route('tasks.index', array_merge(request()->all(), ['order_by' => 'created_at', 'order_direction' => request('order_direction') == 'asc' ? 'desc' : 'asc'])) }}">Created At</a></th>
                                    <th>Completed Subtask</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($tasks) <= 0)
                                    <tr class="task-row ">
                                        <td colspan="6" class="text-center"> No Records Found</td>
                                    </tr>
                                @endif
                                @foreach ($tasks as $task)
                                    <tr class="task-row ">
                                        <td>{{ $task->title }}</td>
                                        <td>{{ $task->content }}</td>
                                        <td>{{ $task->status }}</td>
                                        <td>{{ $task->created_at?->format('F j, Y, g:i A') }}</td>
                                        <td>
                                            @php
                                                $subtaskCount = $task->countCompletedSubtasks();
                                            @endphp
                                            {{ $subtaskCount['completed'] }}/{{ $subtaskCount['total'] }} completed
                                            @if ($subtaskCount['total'] != 0)
                                                <button class="btn btn-primary btn-sm toggle-subtasks"
                                                    data-toggle="{{ $task->id }}" title="Show Sub Tasks">Show</button>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($task->file_path)
                                                <a href="#" class="btn btn-primary btn-sm"
                                                    onclick="window.open('{{ asset('storage/' . $task->file_path) }}','popUpWindow','height=500,width=400,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');"
                                                    title="View Image"><i class="fas fa-eye"></i></a>
                                            @endif
                                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm"
                                                title="Edit Tasks"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('tasks.trash', $task->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" title="Remove Tasks"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>

                                        </td>
                                    </tr>
                                    <tr class="subtasks-row subtasks-{{ $task->id }} d-none">
                                        <td colspan="6">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr class="table-info">
                                                        <th colspan="2">Sub task Title</th>
                                                        <th>Sub task Content</th>
                                                        <th>Status</th>
                                                        <th>Created At</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($task->subtasks as $subtask)
                                                        <tr class="table-info">
                                                            <td colspan="2">{{ $subtask->title }}</td>
                                                            <td>{{ $subtask->content }}</td>
                                                            <td>
                                                                <form
                                                                    action="{{ route('tasks.updateStatus', $subtask->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <select name="status" class="form-select"
                                                                        onchange="this.form.submit()">
                                                                        @foreach ($statuses as $status)
                                                                            <option value="{{ $status }}"
                                                                                {{ $subtask->status === $status ? 'selected' : '' }}>
                                                                                {{ $status }}</option>
                                                                        @endforeach

                                                                    </select>
                                                                </form>
                                                            </td>
                                                            <td>{{ $subtask->created_at?->format('F j, Y, g:i A') }}</td>
                                                            <td>
                                                                @if ($subtask->file_path)
                                                                    <a href="#" class="btn btn-primary btn-sm"
                                                                        onclick="window.open('{{ asset('storage/' . $subtask->file_path) }}','popUpWindow','height=500,width=400,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');"
                                                                        title="View Image"><i
                                                                            class="fas fa-eye fa-sm"></i></a>
                                                                @endif
                                                                <a href="{{ route('tasks.edit', $subtask->id) }}"
                                                                    class="btn btn-warning btn-sm" title="Edit Task"><i
                                                                        class="fas fa-edit fa-sm"></i></a>
                                                                <form action="{{ route('tasks.trash', $subtask->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                                        title="Remove Task"><i
                                                                            class="fas fa-trash fa-sm"></i></button>
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
                    </div>
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
