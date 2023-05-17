<?php

namespace App\Http\Requests\Admin\Post;

use App\Repositories\RouteRepository;
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

        if (!$this->request->has('routeName') || empty($this->request->get('routeName', ''))) {
            $title = Arr::get($this->request->all(), 'title');
            $isArray = gettype($title) === 'array';
            $val = $isArray ? reset($title) : $title;
            $routeName = convertStringToRouteName($val);
            $routePrefix = [];
            while (true) {
                $route = app(RouteRepository::class)->getByName($routeName . implode('', $routePrefix));

                if(!$route || $route->parent_id == $postId) {
                    $routeName = $routeName . implode('', $routePrefix);
                    break;
                }

                if (empty($routePrefix)) {
                    $routePrefix = ['-', 1];
                } else {
                    $routePrefix[1]++;
                }
            }

            $this->merge(['routeName' => $routeName]);
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
