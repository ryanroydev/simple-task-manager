<!-- resources/views/tasks/create.blade.php -->


<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
   
        <div class="card bg-white shadow-sm rounded p-4">
            <div class="card-body">
               
                <h1 class="h3 mb-4">Create Task</h1>

                <form action="<?php echo e(route('tasks.store')); ?>" method="POST" enctype="multipart/form-data" >
                    <?php echo csrf_field(); ?>

                    <!-- Task Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('title')); ?>" >
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Task Content -->
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea id="content" name="content" class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"  ><?php echo e(old('content')); ?></textarea>
                        <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Task Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select" >
                           
                            <option value="to-do" <?php echo e(old('status') === 'to-do' ? 'selected' : ''); ?>>To Do</option>
                            <option value="in-progress" <?php echo e(old('status') === 'in-progress' ? 'selected' : ''); ?>>In Progress</option>
                            <option value="done" <?php echo e(old('status') === 'done' ? 'selected' : ''); ?>>Done</option>
                    
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
                        <?php $__currentLoopData = old('subtasks', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $subtask): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="subtask mb-3">
                                <div class="mb-2">
                                    <label for="subtask_title_<?php echo e($index); ?>" class="form-label">Subtask Title</label>
                                    <input type="text" id="subtask_title_<?php echo e($index); ?>" name="subtasks[<?php echo e($index); ?>][title]" class="form-control <?php $__errorArgs = ['subtasks.'.$index.'.title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('subtasks.'.$index.'.title')); ?>" >
                                    <?php $__errorArgs = ['subtasks.'.$index.'.title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="mb-2">
                                    <label for="subtask_content_<?php echo e($index); ?>" class="form-label">Subtask Content</label>
                                    <textarea id="subtask_content_<?php echo e($index); ?>" name="subtasks[<?php echo e($index); ?>][content]" class="form-control <?php $__errorArgs = ['subtasks.'.$index.'.content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" ><?php echo e(old('subtasks.'.$index.'.content')); ?></textarea>
                                    <?php $__errorArgs = ['subtasks.'.$index.'.content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <button type="button" id="addSubtask" class="btn btn-primary">Add Sub task</button>

                    <!-- Submit Button -->
                    <button type="button" class="btn btn-danger">Draft</button>
                    <button type="submit" class="btn btn-success">Publish</button>
                    
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
            <input type="text" id="subtask_title_${subtaskIndex}" name="subtasks[${subtaskIndex}][title]" class="form-control" >
        </div>
        <div class="form-group">
            <label for="subtask_content_${subtaskIndex}">Subtask Content</label>
            <textarea id="subtask_content_${subtaskIndex}" name="subtasks[${subtaskIndex}][content]" class="form-control" ></textarea>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\task-manager-app\resources\views/tasks/create.blade.php ENDPATH**/ ?>