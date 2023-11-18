<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Integer implements Rule
{
    public function passes($attribute, $value): bool
    {
        if(gettype($value) === 'array') {
            foreach ($value as $item) {
                if (!is_numeric($item)) {
                    return false;
                }
            }

            return true;
        }

        return is_numeric($value);
    }

    public function message(): string
    {
        return trans('validation.required');
    }
}
