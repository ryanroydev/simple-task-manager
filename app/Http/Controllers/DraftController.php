<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
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
     * publish a task by ID.
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
