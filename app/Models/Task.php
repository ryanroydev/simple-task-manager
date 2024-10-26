<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Task extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title', 'content', 'status', 'parent_id', 'user_id', 'file_path',
    ];

    // Define the ENUM values as constants
    const STATUS_TODO = 'to-do';
    const STATUS_IN_PROGRESS = 'in-progress';
    const STATUS_DONE = 'done';

    protected static array $statuses = [
        self::STATUS_TODO,
        self::STATUS_IN_PROGRESS,
        self::STATUS_DONE,
    ];

    /**
     * Get the subtasks associated with the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subtasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

   /**
     * Get the parent task associated with the subtask.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function parent(): ?\Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    /**
     * Count completed subtasks.
     *
     * @return array<string, int>
     */
    public function countCompletedSubtasks() : array
    {
        $completedCount = $this->subtasks()->where('status', 'done')->count();
        $totalCount = $this->subtasks()->count();
        
        return [
            'completed' => $completedCount,
            'total' => $totalCount,
        ];
    }
    
    /**
     * Get the statuses available for tasks.
     *
     * @return array<string>
     */
    public static function getStatuses(): array
    {
        return self::$statuses;
    }

}
