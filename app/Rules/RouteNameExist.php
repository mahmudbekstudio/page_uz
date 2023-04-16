<?php

namespace App\Rules;

use App\Repositories\RouteRepository;
use Illuminate\Contracts\Validation\Rule;

class RouteNameExist implements Rule
{
    private $typeId;
    private $id;

    public function __construct($typeId, $id = 0)
    {
        $this->typeId = $typeId;
        $this->id = $id;
    }

    public function passes($attribute, $value): bool
    {
        $route = app(RouteRepository::class)->getByName($value);

        if($route && $route->parent_id == $this->id) {
            return true;
        }

        return !$route;
    }

    public function message(): string
    {
        return trans('validation.routename_exist');
    }
}
