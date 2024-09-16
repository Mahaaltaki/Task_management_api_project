<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the task is authorized to make this request.
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
        return [
            'title'=>'required|string|min:3',
            'description'=>'required|string|min:20|max:1000',
            'status'=>'required|in:completed,inProgress,new',
            'due_date'=>'required|date',
            'priority'=>'required|in:low,medium,high]',
            'project_id'=>'required|integer',
        
        ];
    }
}
