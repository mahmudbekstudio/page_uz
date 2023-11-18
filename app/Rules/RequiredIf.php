<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiredIf implements Rule
{
    private string $fieldName;
    private $value;

    public function __construct(string $fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function passes($attribute, $value): bool
    {
        $this->value = $value;
        if ($this->fieldName && !valueIsEmpty(request($this->fieldName))) {
            return !valueIsEmpty($value);
        }

        return true;
    }

    public function message(): string
    {
        return trans('validation.required_if', ['other' => $this->fieldName, 'value' => getFieldValue($this->value)]);
    }
}
