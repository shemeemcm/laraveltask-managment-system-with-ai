<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['sometimes', 'string', 'in:low,medium,high'],
            'status' => ['sometimes', 'string', 'in:pending,in_progress,completed'],
            'due_date' => ['nullable', 'date'],
            'assigned_to' => ['sometimes', 'exists:users,id'],
        ];
    }
}
