<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Required implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (gettype($value) === 'string') {
            return !!$value;
        } elseif(gettype($value) === 'array') {
            foreach ($value as $item) {
                if (!$item) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function message(): string
    {
        return trans('validation.required');
    }
}
