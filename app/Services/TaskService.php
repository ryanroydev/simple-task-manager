<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function updateStatus(int $subtaskId, String $status) : void 
    {
        $subtask = Task::where('id', $subtaskId)
                            ->where('user_id', Auth::id()) // Ensure  user own task
                            ->firstOrFail();

        // Update the subtask status
        $subtask->status = $status;
        $subtask->save();

        // Check if all subtasks are done and update parent task if needed
        $allDone = Task::where('parent_id', $subtask->parent_id)
            ->where('status', '!=', 'done') // Only check for subtasks not done
            ->doesntExist();

        if ($allDone) {
            $task = Task::findOrFail($subtask->parent_id);
            $task->status = 'done';
            $task->save();
        }
    }
}