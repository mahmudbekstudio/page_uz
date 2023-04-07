<?php

namespace App\Http\Requests\Admin\Category;

use App\Rules\CheckCategoryParentDeepLimit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CreateCategoryRequest extends FormRequest
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
        $categoryId = (int)request()->route()->parameter('category', 0);
        $rules = getFieldRules($typeId, $categoryId);

        if(Arr::has($rules, 'parent')) {
            $rules['parent'][] = new CheckCategoryParentDeepLimit($typeId, $categoryId);
        }

        return $rules;
    }
}
