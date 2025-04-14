<?php

namespace App\Http\Requests\Api;

use Illuminate\{Foundation\Http\FormRequest, Validation\Rule};

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'completed' => 'required|bool',
            'priority' => 'required|string|in:low,medium,high',
            //'attachment' => 'nullable|file|mimes:pdf|max:2048',
        ];
    }
}
