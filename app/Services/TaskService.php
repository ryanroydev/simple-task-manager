<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function updateStatus(int $subtaskId, String $status) : bool
    {
        $subtask = Task::where('id', $subtaskId)
                            ->where('user_id', Auth::id()) // Ensure  user own task
                            ->firstOrFail();

        // Update the subtask status
        $subtask->status = $status;
        $subtask->save(); //update regardless task or sub tasks

        if($subtask->parent_id){ //check if subtask
              // Check if all subtasks are done and update parent task if needed
            $allDone = Task::where('parent_id', $subtask->parent_id)
                ->where('status', '!=', 'done') // Only check for subtasks not done
                ->doesntExist();

            if ($allDone) {
                Task::where('id', $subtask->parent_id)
                     ->update(['status' => 'done']);
            }

        } else {
            Task::where('parent_id', $subtask->id)
                ->where('user_id', Auth::id()) 
                ->update(['status'=>$subtask->status]);
        }
        
        return true;
    }
}