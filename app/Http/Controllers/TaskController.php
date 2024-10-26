<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Models\Task;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateStatusRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class TaskController extends Controller
{
    protected TaskService $taskService;

    // Constructor to initialize the TaskService
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a list of tasks with optional filtering and sorting.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request) : View
    {

        $allowedOrderByColumns = ['title', 'created_at']; // add here collumn to sort
        // Determine the column to order by
        $orderBy = in_array($request->get('order_by'), $allowedOrderByColumns) ? $request->get('order_by') : 'created_at';
        // Determine the order direction, defaulting to descending
        $orderDirection = in_array($request->get('order_direction'), ['asc', 'desc']) ? $request->get('order_direction') : 'desc';
    
        // Get all possible task statuses
        $statuses = Task::getStatuses();

        // Build the query to retrieve tasks
        $tasks = Task::where('user_id', auth()->id())
            ->whereNull('parent_id')
            ->whereIsDraft(false) //show only publish
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), fn($query) => $query->where('title', 'like', '%' . $request->search . '%'))
            ->orderBy($orderBy, $orderDirection)
            ->paginate($request->get('limit', 10))
            ->appends($request->except('page')); // Keep all get parameters when redirect by paginator
            
         // Return the task index view with tasks and statuses
        return view('tasks.index', compact('tasks','statuses'));
    }

    /**
     * Show the form to create a new task.
     *
     * @return View
     */
    public function create() : View
    {

        $statuses = Task::getStatuses(); // Get all possible statuses for tasks
        return view('tasks.create',compact('statuses')); // Return create view with statuses
    }
    
    /**
     * Store a new task.
     *
     * @param TaskStoreRequest $request
     * @return RedirectResponse
     */
    public function store(TaskStoreRequest $request) : RedirectResponse
    {
        // Create task
        $taskData = $request->validated();
        $task = new Task();
        $task->title = $taskData['title'];
        $task->content = $taskData['content'];
        $task->status = $taskData['status'];
        $task->user_id = auth()->id(); 
        $task->is_draft = $taskData['is_draft']; // Save draft status

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

    /**
     * Update the status of a specific subtask.
     *
     * @param TaskUpdateStatusRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function updateStatus(TaskUpdateStatusRequest $request, $id) : RedirectResponse
    {
        try {
            //call task service for update status logic
            $this->taskService->updateStatus($id, $request->status);
            return redirect()->back()->with('success', 'Subtask status updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Task ID Not Found or error encountered");
        }  
    }

    /**
     * Soft delete a task by ID.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function trash(int $id): RedirectResponse
    {
        // Attempt to find the task for the authenticated user
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $task->delete(); // Soft delete
        return redirect()->back()->with('success', 'Task moved to trash successfully.');
    }

    /**
     * Move to Draft.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function draft(int $id): RedirectResponse
    {
        // Attempt to find the task for the authenticated user
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $task->update(['is_draft' => true]);
        return redirect()->back()->with('success', 'Task moved to draft successfully.');
    }
}
