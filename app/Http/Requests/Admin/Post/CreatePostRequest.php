<?php

namespace App\Http\Requests\Admin\Post;

use App\Rules\CheckPostParentDeepLimit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CreatePostRequest extends FormRequest
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

        if(Arr::has($rules, 'parent')) {
            $rules['parent'][] = new CheckPostParentDeepLimit($typeId, $postId);
        }

        return $rules;

        /*$type = app(TypeRepository::class)->getById($typeId);

        foreach ($type->fields as $field) {
            $rule = getFieldRule($field);


            $name = Arr::get($field, 'name');
            if ($name) {
                $rules[$name] = [Arr::get($field, 'params')];
            }

        }*/

        //return $rules;
    }
}
