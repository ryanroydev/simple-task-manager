<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
class TaskController extends Controller
{
    public function index(Request $request) {
        
        $query = Task::where('user_id', auth()->id())->whereNull('parent_id');
    
        // Filtering status optional
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        // Search title optional
        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }
    
        // pagination and user can choose limit 10 or 20 etc per page
        $tasks = $query->orderBy('created_at', 'desc')->paginate($request->get('limit', 10));
    
        return view('tasks.index', compact('tasks'));
    }

    public function store(TaskStoreRequest $request)
    {
        // Create task
        $taskData = $request->validated();
        $task = new Task();
        $task->title = $taskData['title'];
        $task->content = $taskData['content'];
        $task->status = $taskData['status'];
        $task->user_id = auth()->id(); 

        // optional upload 
        if ($request->hasFile('file')) {
            $task->file_path = $request->file('file')->store('uploads/tasks', 'public');
        }

        $task->save();

        // Check if subtasks are provided
        if ($request->has('subtasks')) {
            foreach ($request->subtasks as $subtaskData) {
                $subtask = new Task($subtaskData);
                $subtask->parent_id = $task->id; //  main task id
                $subtask->user_id = auth()->id(); 

                // upload file optional
                if (isset($subtaskData['file']) && $subtaskData['file']) {
                    $subtask->file_path = $subtaskData['file']->store('uploads/subtasks', 'public');
                }

                $subtask->save();
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }
}
