<?php

namespace App\Http\Requests\Api\Authentication;

use App\Enums\StatusEnum;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\{Foundation\Http\FormRequest};

class RegisterRequest extends FormRequest
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
            'name' => ['required','string','max:255'],
            'email' => ['required','email', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                //'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]+$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.regex' => __('validation.password_complex'),
        ];
    }
}
