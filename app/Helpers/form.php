<?php

use App\Repositories\TypeRepository;
use Illuminate\Support\Arr;
use App\Rules\MinIfNotEmpty;
use App\Rules\RouteNameExist;

if (! function_exists('getTypeById')) {
    function getTypeById($typeId) {
        return app(TypeRepository::class)->getById($typeId);
    }
}

if (! function_exists('getFieldNames')) {
    function getFieldNames($fields) {
        $names = [];

        foreach ($fields as $field) {
            $name = Arr::get($field, 'name');

            if ($name) {
                $names[] = $name;
            }
        }

        return $names;
    }
}

if (! function_exists('getFieldValues')) {
    function getFieldValues($fields) {
        $names = [];

        foreach ($fields as $field) {
            $name = Arr::get($field, 'name');

            if ($name) {
                $names[$name] = Arr::get($field, 'value');
            }
        }

        return $names;
    }
}

if (! function_exists('getFields')) {
    function getFields($fields) {
        $result = [];

        foreach ($fields as $field) {
            $name = Arr::get($field, 'name');

            if ($name) {
                $result[$name] = $field;
            }
        }

        return $result;
    }
}

if (! function_exists('getFieldRules')) {
    function getFieldRules($typeId, $id = 0)
    {
        $rules = [];
        $type = getTypeById($typeId);
        $specific = ['bool' => 'boolean', 'int' => 'integer', 'double' => 'numeric', 'string' => 'present'];

        foreach ($type->fields as $field) {
            $name = Arr::get($field, 'name');

            if ($name) {
                $valueType = Arr::get($field, 'params.valueType');
                $rules[$name] = [Arr::get($specific, $valueType, $valueType)];

                $validation = Arr::get($field, 'params.validation', []);
                foreach ($validation as $rule => $value) {
                    $rule = getValidationRule($rule, $value, $typeId, $id);
                    if (gettype($rule) === 'string') {
                        $rules[$name][] = $rule;
                    } else {
                        foreach ($rule as $item) {
                            $rules[$name][] = $item;
                        }
                    }
                }
            }
        }

        return $rules;
    }
}

if (! function_exists('getValidationRule')) {
    function getValidationRule($key, $value, $typeId, $id = 0) {
        switch ($key) {
            case 'required':
                return 'required';
            case 'requiredIfNotEmpty':
                return 'required_if:' . $value;
            case 'max':
                return 'max:' . $value;
            case 'min':
                return 'min:' . $value;
            case 'minIfNotEmpty':
                return new MinIfNotEmpty($value);
            case 'isEmail':
                return 'email';
            case 'in':
                return 'in:' . implode(',', explode("\n", $value));
            case 'notIn':
                return 'not_in:' . implode(',', explode("\n", $value));
            case 'confirmation':
                return 'same:' . $value;
            case 'routeName':
                return [
                    'regex:/^' . config('app.route_rules.name') . '$/i',
                    new RouteNameExist($typeId, $id)
                ];
        }

        return false;
    }
}
