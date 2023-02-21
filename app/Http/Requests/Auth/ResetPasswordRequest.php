<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Traits\JsonResponseValidation;
use App\Rules\CheckUserStatusActive;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'token' => 'required',
            'email' => ['required', 'email', new CheckUserStatusActive()],
            'password' => ['required', 'min:' . config('app.min_password_length'), 'confirmed'],
        ];
    }
}
