<?php $__env->startSection('content'); ?>
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="card bg-white">
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('tasks.index')); ?>" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search by title"
                                    value="<?php echo e(request('search')); ?>">
                            </div>
                            <div class="col-md-4">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($status); ?>"
                                            <?php echo e(request('status') == $status ? 'selected' : ''); ?>><?php echo e($status); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="limit" class="form-select" onchange="this.form.submit()">
                                    <?php $__currentLoopData = [10, 20, 30, 40, 50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $limit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($limit); ?>"
                                            <?php echo e(request('limit') == $limit ? 'selected' : ''); ?>><?php echo e($limit); ?> per page
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Filter</button>
                    </form>

                    <a class="btn btn-primary float-end" href="<?php echo e(route('tasks.create')); ?>">ADD</a>
                    <h1 class="h2 mb-4 card-title">Task List</h1>
                    <div class="table-responsive">
                        <table id="tasksTable" class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Completed Subtask</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($tasks) <= 0): ?>
                                    <tr class="task-row ">
                                        <td colspan="6" class="text-center"> No Records Found</td>
                                    </tr>
                                <?php endif; ?>
                                <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="task-row ">
                                        <td><?php echo e($task->title); ?></td>
                                        <td><?php echo e($task->content); ?></td>
                                        <td><?php echo e($task->status); ?></td>
                                        <td><?php echo e($task->created_at?->format('F j, Y, g:i A')); ?></td>
                                        <td>
                                            <?php
                                                $subtaskCount = $task->countCompletedSubtasks();
                                            ?>
                                            <?php echo e($subtaskCount['completed']); ?>/<?php echo e($subtaskCount['total']); ?> completed
                                            <?php if($subtaskCount['total'] != 0): ?>
                                                <button class="btn btn-primary btn-sm toggle-subtasks"
                                                    data-toggle="<?php echo e($task->id); ?>" title="Show Sub Tasks">Show</button>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($task->file_path): ?>
                                                <a href="#" class="btn btn-primary btn-sm"
                                                    onclick="window.open('<?php echo e(asset('storage/' . $task->file_path)); ?>','popUpWindow','height=500,width=400,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');"
                                                    title="View Image"><i class="fas fa-eye"></i></a>
                                            <?php endif; ?>
                                            <a href="<?php echo e(route('tasks.edit', $task->id)); ?>" class="btn btn-warning btn-sm"
                                                title="Edit Tasks"><i class="fas fa-edit"></i></a>
                                            <form action="<?php echo e(route('tasks.trash', $task->id)); ?>" method="POST"
                                                class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-danger btn-sm" title="Remove Tasks"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>

                                        </td>
                                    </tr>
                                    <tr class="subtasks-row subtasks-<?php echo e($task->id); ?> d-none">
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
                                                    <?php $__currentLoopData = $task->subtasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subtask): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr class="table-info">
                                                            <td colspan="2"><?php echo e($subtask->title); ?></td>
                                                            <td><?php echo e($subtask->content); ?></td>
                                                            <td>
                                                                <form
                                                                    action="<?php echo e(route('tasks.updateStatus', $subtask->id)); ?>"
                                                                    method="POST">
                                                                    <?php echo csrf_field(); ?>
                                                                    <select name="status" class="form-select"
                                                                        onchange="this.form.submit()">
                                                                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo e($status); ?>"
                                                                                <?php echo e($subtask->status === $status ? 'selected' : ''); ?>>
                                                                                <?php echo e($status); ?></option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                                    </select>
                                                                </form>
                                                            </td>
                                                            <td><?php echo e($subtask->created_at?->format('F j, Y, g:i A')); ?></td>
                                                            <td>
                                                                <?php if($subtask->file_path): ?>
                                                                    <a href="#" class="btn btn-primary btn-sm"
                                                                        onclick="window.open('<?php echo e(asset('storage/' . $subtask->file_path)); ?>','popUpWindow','height=500,width=400,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');"
                                                                        title="View Image"><i
                                                                            class="fas fa-eye fa-sm"></i></a>
                                                                <?php endif; ?>
                                                                <a href="<?php echo e(route('tasks.edit', $subtask->id)); ?>"
                                                                    class="btn btn-warning btn-sm" title="Edit Task"><i
                                                                        class="fas fa-edit fa-sm"></i></a>
                                                                <form action="<?php echo e(route('tasks.trash', $subtask->id)); ?>"
                                                                    method="POST" class="d-inline">
                                                                    <?php echo csrf_field(); ?>
                                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                                        title="Remove Task"><i
                                                                            class="fas fa-trash fa-sm"></i></button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php echo e($tasks->links()); ?>

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\task-manager-app\resources\views/tasks/index.blade.php ENDPATH**/ ?>