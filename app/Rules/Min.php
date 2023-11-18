<?php

namespace App\Rules;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Validation\Rule;

class Min implements Rule
{
    private $min;
    public function __construct($min = null)
    {
        $this->min = (int)$min;
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
        return trans('validation.min.numeric');
    }

    private function checkValue($value)
    {
        switch (gettype($value)) {
            case 'integer':
                return $this->min <= $value;
            case 'string':
                return $this->min <= strlen($value);
            case 'array':
                return $this->min <= count($value);
        }

        return true;
    }
}
