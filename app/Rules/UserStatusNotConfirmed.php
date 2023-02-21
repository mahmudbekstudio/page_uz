<?php

namespace App\Rules;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Validation\Rule;

class UserStatusNotConfirmed implements Rule
{
    private $user;

    public function passes($attribute, $value): bool
    {
        $this->user = UserRepository::getInstance()->getByEmail($value);

        if(!$this->user) {
            return false;
        }

        return $this->user->status == User::STATUS_NOT_CONFIRMED;
    }

    public function message(): string
    {
        return $this->user ? trans('validation.user_confirmed') : trans('validation.user_not_exist');
    }
}
