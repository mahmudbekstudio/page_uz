<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsEmail implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (gettype($value) === 'string') {
            return filter_var($value, FILTER_VALIDATE_EMAIL);
        } elseif(gettype($value) === 'array') {
            foreach ($value as $email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function message(): string
    {
        return trans('validation.email');
    }
}
