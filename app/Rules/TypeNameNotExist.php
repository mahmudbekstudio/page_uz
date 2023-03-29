<?php

namespace App\Rules;

use App\Repositories\TypeRepository;
use Illuminate\Contracts\Validation\Rule;

class TypeNameNotExist implements Rule
{
    private $id;
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function passes($attribute, $value): bool
    {
        $type = app(TypeRepository::class)->getByName($value);

        if ($type && $type->id == $this->id) {
            return true;
        }

        return !$type;
    }

    public function message(): string
    {
        return trans('validation.typename_exist');
    }
}
