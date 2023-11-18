<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class In implements Rule
{
    protected $list;

    public function __construct($list = [])
    {
        $this->list = $list;
    }

    public function passes($attribute, $value): bool
    {
        if (valueIsEmpty($value)) {
            return true;
        }

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
        return trans('validation.in');
    }

    protected function checkValue($value)
    {
        return in_array($value, $this->list);
    }
}
