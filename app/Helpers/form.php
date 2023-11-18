<?php

use App\Repositories\TypeRepository;
use Illuminate\Support\Arr;
use App\Rules\MinIfNotEmpty;
use App\Rules\RouteNameExist;
use App\Rules\IsEmail;
use App\Rules\Required;
use App\Rules\RequiredIf;
use App\Rules\In;
use App\Rules\NotIn;
use App\Rules\Max;
use App\Rules\Min;

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
                $valueType = Arr::get($specific, $valueType, $valueType);

                if ($valueType) {
                    $typeRule = getValidationTypeRule($valueType);
                    $rules[$name] = $typeRule ? [$typeRule] : [$valueType];
                } else {
                    $rules[$name] = [];
                }

                $validation = Arr::get($field, 'params.validation', []);
                foreach ($validation as $rule => $value) {
                    $rule = getValidationRule($rule, $value, $typeId, $id);
                    if (gettype($rule) === 'array') {
                        foreach ($rule as $item) {
                            $rules[$name][] = $item;
                        }
                    } else {
                        $rules[$name][] = $rule;
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
                return new Required();
            case 'requiredIfNotEmpty':
                return new RequiredIf($value);
            //return 'required_if:' . $value;
            case 'max':
                return new Max($value);
            //return 'max:' . $value;
            case 'min':
                return new Min($value);
            //return 'min:' . $value;
            case 'integer':
                return new \App\Rules\Integer();
            case 'minIfNotEmpty':
                return new MinIfNotEmpty($value);
            case 'isEmail':
                return new IsEmail();
            case 'in':
                return new In(implode(',', explode("\n", $value)));
            //return 'in:' . implode(',', explode("\n", $value));
            case 'notIn':
                return new NotIn(implode(',', explode("\n", $value)));
            //return 'not_in:' . implode(',', explode("\n", $value));
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

if (! function_exists('getValidationTypeRule')) {
    function getValidationTypeRule($key) {
        switch ($key) {
            case 'integer':
                return new \App\Rules\Integer();
            case 'isEmail':
                return new IsEmail();
        }

        return false;
    }
}

if (! function_exists('valueIsEmpty')) {
    function valueIsEmpty($value): bool
    {
        if (is_string($value)) {
            return !$value;
        } elseif (is_array($value)) {
            foreach ($value as $item) {
                if (!$item) {
                    return true;
                }
            }

            return false;
        }

        return true;
    }
}

if (! function_exists('getFieldValue')) {
    function getFieldValue($value): mixed
    {
        if (is_string($value)) {
            return $value;
        } elseif (is_array($value)) {
            $lang = getLang();

            return isset($value[$lang]) ? $value[$lang] : array_values($value)[0];
        }

        return null;
    }
}
