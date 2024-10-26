<!-- resources/views/tasks/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
   
        <div class="card bg-white shadow-sm rounded p-4">
            <div class="card-body">
               
                <h1 class="h3 mb-4">Create Task</h1>

                <form id="taskForm" action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" >
                    @csrf

                    <!-- Task Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" >
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Task Content -->
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror"  >{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Task Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select" >
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" {{ old('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <label for="file" class="form-label">Upload File</label>
                        <input type="file" id="file" name="file" class="form-control @error('file') is-invalid @enderror" accept="image/*">
                        @error('file')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Subtasks Section -->
                    <h3 class="h5 mb-3">Subtasks</h3>
                    <div id="subtasks">
                        @foreach (old('subtasks', []) as $index => $subtask)
                            <div class="subtask mb-3">
                                <div class="mb-2">
                                    <label for="subtask_title_{{ $index }}" class="form-label">Subtask Title</label>
                                    <input type="text" id="subtask_title_{{ $index }}" name="subtasks[{{ $index }}][title]" class="form-control @error('subtasks.'.$index.'.title') is-invalid @enderror" value="{{ old('subtasks.'.$index.'.title') }}" >
                                    @error('subtasks.'.$index.'.title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-2">
                                    <label for="subtask_content_{{ $index }}" class="form-label">Subtask Content</label>
                                    <textarea id="subtask_content_{{ $index }}" name="subtasks[{{ $index }}][content]" class="form-control @error('subtasks.'.$index.'.content') is-invalid @enderror" >{{ old('subtasks.'.$index.'.content') }}</textarea>
                                    @error('subtasks.'.$index.'.content')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-2">
                                    <label for="subtask_file_{{ $index }}" class="form-label">Upload File</label>
                                    <input type="file" id="subtask_file_{{ $index }}" name="subtasks[{{ $index }}][file]" class="form-control @error('subtasks.'.$index.'.file') is-invalid @enderror" accept="image/*">
                                    @error('subtasks.'.$index.'.file')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="addSubtask" class="btn btn-primary">Add Sub task</button>

                     <!-- Hidden Input for Draft -->
                    <input type="hidden" name="is_draft" id="is_draft" value="0">

                    <!-- Submit Button -->
                    <button type="button" id="saveDraft" class="btn btn-danger">Draft</button>
                    <button type="submit" class="btn btn-success" id="submitTask">Publish</button>
            
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
        <div class="mb-2">
            <label for="subtask_title_${subtaskIndex}" class="form-label">Subtask Title</label>
            <input type="text" id="subtask_title_${subtaskIndex}" name="subtasks[${subtaskIndex}][title]" class="form-control" >
        </div>
        <div class="mb-2">
            <label for="subtask_content_${subtaskIndex}" class="form-label">Subtask Content</label>
            <textarea id="subtask_content_${subtaskIndex}" name="subtasks[${subtaskIndex}][content]" class="form-control" ></textarea>
        </div>
        <div class="mb-2">
            <label for="subtask_file_${subtaskIndex}" class="form-label">Upload File</label>
            <input type="file" id="subtask_file_${subtaskIndex}" name="subtasks[${subtaskIndex}][file]" class="form-control" accept="image/*">
        </div>
        <br>
    `);
    
    $('#subtasks').append(subtaskDiv);
    subtaskIndex++;
});
$('#saveDraft').on('click', function() {
    $('#is_draft').val('1'); // Set the value to 1 for drafts
    $('#taskForm').submit(); // Submit the form
});

$('#submitTask').on('click', function() {
    $('#is_draft').val('0'); // Set the value to 0 for submission
});

</script>
@endsection
