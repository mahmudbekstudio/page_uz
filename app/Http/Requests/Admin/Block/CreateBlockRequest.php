<?php

namespace App\Http\Requests\Admin\Block;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateBlockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $typeId = (int)request()->route()->parameter('type', 0);
        $postId = (int)request()->route()->parameter('post', 0);
        $rules = getFieldRules($typeId, $postId);

        return $rules;
    }
}
