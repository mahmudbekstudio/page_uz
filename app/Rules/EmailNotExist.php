<?php

namespace App\Rules;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Validation\Rule;

class EmailNotExist implements Rule
{
    public function passes($attribute, $value): bool
    {
        return !UserRepository::getInstance()->getByEmail($value);
    }

    public function message(): string
    {
        return trans('passwords.user_exist');
    }
}
