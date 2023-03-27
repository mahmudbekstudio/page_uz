<?php

namespace App\Rules;

use App\Repositories\TypeRepository;
use Illuminate\Contracts\Validation\Rule;

class TypeNameNotExist implements Rule
{
    public function passes($attribute, $value): bool
    {
        return !app(TypeRepository::class)->getByName($value);
    }

    public function message(): string
    {
        return trans('validation.typename_exist');
    }
}
