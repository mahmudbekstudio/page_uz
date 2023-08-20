<?php

namespace App\Http\Requests\Admin\Template;

use App\Models\Template;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateTemplateRequest extends FormRequest
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
        $rules = [
            'name' => ['required'],
            'type' => ['required', 'in:' . implode(',', Template::types())],
            'content' => ['required', 'array'],
            //'params' => ['required', 'array'],
        ];

        if (in_array($this->get('type'), [Template::TYPE_POST, Template::TYPE_CATEGORY])) {
            $rules['type_id'] = ['required', 'integer'];
            $rules['layout_id'] = ['required', 'integer'];
        }

        return $rules;
    }
}
