<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\DraftUpdateRequest;
use App\Models\Task;

class DraftController extends Controller
{
  /**
     * Display a list of draft tasks with optional filtering and sorting.
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
        $tasks = Task::whereIsDraft(true)
            ->where('user_id', auth()->id())
            ->whereNull('parent_id')
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), fn($query) => $query->where('title', 'like', '%' . $request->search . '%'))
            ->orderBy($orderBy, $orderDirection)
            ->paginate($request->get('limit', 10))
            ->appends($request->except('page')); // Keep all get parameters when redirect by paginator
            
         // Return the task index view with tasks and statuses
        return view('draft.index', compact('tasks','statuses'));
    }

    /**
     * Soft delete a task by ID.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        // Attempt to find the task for the authenticated user
        $task = Task::whereIsDraft(true)->where('user_id', auth()->id())->findOrFail($id);
        $task->delete(); // force delete
        return redirect()->back()->with('success', 'Task moved to trash successfully.');
    }

    /**
     * Edit a draft task by ID.
     *
     * @param Request $request
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        // Attempt to find the task for the authenticated user
        $task = Task::whereIsDraft(true)->where('user_id', auth()->id())->findOrFail($id);
        $statuses = Task::getStatuses(); // Get all possible statuses for tasks
        return view('draft.edit', compact('task', 'statuses'));
    }

    /**
     * Update an existing draf task.
     *
     * @param TaskUpdateRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(DraftUpdateRequest $request, int $id) : RedirectResponse
    {
        // Find the existing task
        $task = Task::findOrFail($id);

        // Update task attributes
        $taskData = $request->validated();
        $task->title = $taskData['title'];
        $task->content = $taskData['content'];
        $task->status = $taskData['status'];
        $task->is_draft = $taskData['is_draft'];

        // Optional file upload
        if ($request->hasFile('file')) {
            $task->file_path = $request->file('file')->store('uploads/tasks', 'public');
        }

        $task->save();

        // Check if subtasks are provided
        if ($request->has('subtasks')) {
            // Update existing subtasks
            foreach ($request->subtasks as $subtaskData) {
                if (isset($subtaskData['id'])) {
                    // Update existing subtask
                    $subtask = Task::findOrFail($subtaskData['id']);
                    $subtask->fill($subtaskData);
                    
                    // Optional file upload for subtasks
                    if (isset($subtaskData['file']) && $subtaskData['file']) {
                        $subtask->file_path = $subtaskData['file']->store('uploads/subtasks', 'public');
                    }

                    $subtask->save();
                } else {
                    // Create a new subtask if no ID is provided
                    $subtask = new Task($subtaskData);
                    $subtask->parent_id = $task->id; // Main task ID
                    $subtask->user_id = auth()->id();

                    // Optional file upload for new subtasks
                    if (isset($subtaskData['file']) && $subtaskData['file']) {
                        $subtask->file_path = $subtaskData['file']->store('uploads/subtasks', 'public');
                    }

                    $subtask->save();
                }
            }
        }

        return redirect()->route('draft.index')->with('success', 'Draft Task updated successfully.');
    }

    /**
     * publish a draft task by ID.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function publish(int $id): RedirectResponse
    {
        // Attempt to find the task for the authenticated user
        $task = Task::whereIsDraft(true)->where('user_id', auth()->id())->findOrFail($id);
        $task->update(['is_draft' => false]); // publish draft
        return redirect()->back()->with('success', 'Task publish successfully.');
    }
}
