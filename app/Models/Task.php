<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Task extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title', 'content', 'status', 'parent_id', 'user_id', 'file_path',
    ];

    // Define the ENUM values as constants
    const STATUS_TODO = 'to-do';
    const STATUS_IN_PROGRESS = 'in-progress';
    const STATUS_DONE = 'done';

    protected static $statuses = [
        self::STATUS_TODO,
        self::STATUS_IN_PROGRESS,
        self::STATUS_DONE,
    ];

    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function countCompletedSubtasks()
    {
        $completedCount = $this->subtasks()->where('status', 'done')->count();
        $totalCount = $this->subtasks()->count();
        
        return [
            'completed' => $completedCount,
            'total' => $totalCount,
        ];
    }

    public static function getStatuses()
    {
        return self::$statuses;
    }

}
