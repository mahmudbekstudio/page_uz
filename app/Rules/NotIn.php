<?php

namespace App\Rules;

class NotIn extends In
{
    public function message(): string
    {
        return trans('validation.not_in');
    }

    protected function checkValue($value)
    {
        return !in_array($value, $this->list);
    }
}
