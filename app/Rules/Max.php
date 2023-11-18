<?php

namespace App\Rules;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Validation\Rule;

class Max implements Rule
{
    private $max;
    public function __construct($max = null)
    {
        $this->max = (int)$max;
    }

    public function passes($attribute, $value): bool
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                if (!$this->checkValue($item)) {
                    return false;
                }
            }

            return true;
        } else {
            return $this->checkValue($value);
        }
    }

    public function message(): string
    {
        return trans('validation.max.numeric');
    }

    private function checkValue($value)
    {
        switch (gettype($value)) {
            case 'integer':
                return $this->max >= $value;
            case 'string':
                return $this->max >= strlen($value);
            case 'array':
                return $this->max >= count($value);
        }

        return true;
    }
}
