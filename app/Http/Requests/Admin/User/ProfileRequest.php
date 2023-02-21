<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\Traits\JsonResponseValidation;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'old_password' => ['required_unless:password,null'],
            'password' => ['sometimes', 'min:' . config('app.min_password_length'), 'confirmed']
        ];
    }
}
