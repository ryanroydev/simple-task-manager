<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Models\Task;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateStatusRequest;
class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request) {

        $statuses = Task::getStatuses();
        $tasks = Task::where('user_id', auth()->id())->whereNull('parent_id')
                ->when($request->filled('status'), function ($query) use ($request) {
                    // Filtering status optional
                    $query->where('status', $request->status);
                })->when($request->filled('search'), function ($query) use ($request) {
                    // Search title optional
                    $query->where('title', 'like', '%' . $request->search . '%');
                })->orderBy('created_at', 'desc')->paginate($request->get('limit', 10));
        
        //keep all get parameters when redirect by paginator
        $tasks->appends($request->except('page')); 

        return view('tasks.index', compact('tasks','statuses'));
    }

    public function create() {

        $statuses = Task::getStatuses();
        return view('tasks.create',compact('statuses'));
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

    public function updateStatus(TaskUpdateStatusRequest $request, $id)
    {
        try {
            $this->taskService->updateStatus($id, $request->status);
            return redirect()->back()->with('success', 'Subtask status updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Task ID Not Found or error encountered");
        }  
    }

    public function trash($id)
    {
        $task = Task::findOrFail($id);
        $task->delete(); // Soft delete
        return redirect()->back();
    }
}
