<!-- resources/views/tasks/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="card bg-white shadow-sm rounded p-4">
            <div class="card-body">
                <h1 class="h3 mb-4">Create Task</h1>

                <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" >
                    @csrf

                    <!-- Task Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Task Content -->
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea id="content" name="content" class="form-control" required></textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Task Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="to-do">To Do</option>
                            <option value="in-progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <label for="file" class="form-label">Upload File</label>
                        <input type="file" id="file" name="file" class="form-control" accept="image/*">
                    </div>

                    <!-- Subtasks Section -->
                    <h3 class="h5 mb-3">Subtasks</h3>
                    <div id="subtasks">
                        <div class="subtask mb-3">
                            <div class="mb-2">
                                <label for="subtask_title_0" class="form-label">Subtask Title</label>
                                <input type="text" id="subtask_title_0" name="subtasks[0][title]" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <label for="subtask_content_0" class="form-label">Subtask Content</label>
                                <textarea id="subtask_content_0" name="subtasks[0][content]" class="form-control" required></textarea>
                            </div>
                            <div class="mb-2">
                                <label for="subtask_file_0" class="form-label">Upload File</label>
                                <input type="file" id="subtask_file_0" name="subtasks[0][file]" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <button type="button" id="addSubtask" class="btn btn-primary mb-4">Add Subtask</button>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success">Create Task</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
 let subtaskIndex = 1;

$('#addSubtask').on('click', function() {
    const subtaskDiv = $('<div>', { class: 'subtask mt-3' });
    subtaskDiv.html(`
        <hr>
        <div class="form-group">
            <label for="subtask_title_${subtaskIndex}">Subtask Title</label>
            <input type="text" id="subtask_title_${subtaskIndex}" name="subtasks[${subtaskIndex}][title]" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="subtask_content_${subtaskIndex}">Subtask Content</label>
            <textarea id="subtask_content_${subtaskIndex}" name="subtasks[${subtaskIndex}][content]" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="subtask_file_${subtaskIndex}">Upload File</label>
            <input type="file" id="subtask_file_${subtaskIndex}" name="subtasks[${subtaskIndex}][file]" class="form-control" accept="image/*">
        </div>
        
    `);
    
    $('#subtasks').append(subtaskDiv);
    subtaskIndex++;
});
</script>
@endsection
