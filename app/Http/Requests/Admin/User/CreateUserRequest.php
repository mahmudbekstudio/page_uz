<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\Traits\JsonResponseValidation;
use App\Models\User;
use App\Rules\EmailNotExist;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
    use JsonResponseValidation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email', new EmailNotExist()],
            'status' => ['required', Rule::in([User::STATUS_NOT_CONFIRMED, User::STATUS_ACTIVE, User::STATUS_BLOCKED])],
            'role' => ['required', Rule::in(config('app.userRoles'))],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'password' => ['required', 'min:' . config('app.min_password_length'), 'confirmed']
        ];
    }
}
