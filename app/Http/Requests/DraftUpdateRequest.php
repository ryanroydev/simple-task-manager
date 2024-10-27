<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class DraftUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->draft;
        return [
            'title' => [
                'required',
                'string',
                'max:100',
                'unique:tasks,title,' . $id, // Exclude current task
            ],
            'content' => 'required|string',
            'status' => 'required|in:to-do,in-progress,done',
            'file' => 'nullable|image|max:4096',
            'is_draft' => 'required|boolean',
            'subtasks' => 'array|nullable', // Ensure subtasks is an array
            'subtasks.*.title' => [
                'required',
                'string',
                'max:100',
                // Custom rule for unique titles including the main task
                function ($attribute, $value, $fail) {
                    
                    // Collect titles from subtasks and main task
                    $titles = collect($this->input('subtasks'))->pluck('title');
                    $ids = collect($this->input('subtasks'))->pluck('id');
                    $titles->push($this->input('title'));
        
                    // Check for duplicates within the request
                    if ($titles->duplicates()->isNotEmpty()) {
                        $fail('Each title must be unique within the request.');
                    }
        
                    // Check against existing tasks excluding current task
                    $existingCount = \App\Models\Task::where('title', $value)
                        ->when($this->task, function ($query) {
                            return $query->where('id', '!=', $this->task->id);
                        })
                        ->when( $ids, function ($query) use ($ids) {
                            return $query->whereNotIn('id', $ids);
                        })
                        ->count();
                       
                    if ($existingCount > 0) {
                        $fail('The title has already been taken.');
                    }
                },
            ],
            'subtasks.*.content' => 'required|string',
            'subtasks.*.file' => 'nullable|image|max:4096',
        ];
    }

    
    public function messages(): array
    {
        return [
            'subtasks.*.title.required' => 'The subtask title is required.',
            'subtasks.*.content.required' => 'The subtask content is required.',
        ];
    }
}
