<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute должен быть принят.',
    'accepted_if' => ':attribute должен быть принят, когда :other равно :value.',
    'active_url' => ':attribute не является допустимым URL.',
    'after' => ':attribute должен быть датой после :date.',
    'after_or_equal' => ':attribute должен быть датой после :date или равным ей.',
    'alpha' => ':attribute должен содержать только буквы.',
    'alpha_dash' => ':attribute должен содержать только буквы, цифры, дефисы и символы подчеркивания.',
    'alpha_num' => ':attribute должен содержать только буквы и цифры.',
    'array' => ':attribute должен быть массивом.',
    'before' => ':attribute должен быть датой до :date.',
    'before_or_equal' => ':attribute должен быть датой, предшествующей :date или равной ей.',
    'between' => [
        'numeric' => ':attribute должен быть между :min и :max.',
        'file' => ':attribute должен быть между :min и :max килобайтами.',
        'string' => ':attribute должен находиться между символами :min и :max.',
        'array' => ':attribute должен иметь элементы от :min до :max.',
    ],
    'boolean' => 'Поле :attribute должно быть истинным или ложным.',
    'confirmed' => 'Подтверждение :attribute не совпадает.',
    'current_password' => 'Неправильный пароль.',
    'date' => ':attribute не является действительной датой.',
    'date_equals' => ':attribute должен быть датой, равной :date.',
    'date_format' => ':attribute не соответствует формату :format.',
    'different' => ':attribute и :other должны быть разными.',
    'digits' => ':attribute должен быть :digits цифры.',
    'digits_between' => ':attribute должен быть между цифрами :min и :max.',
    'dimensions' => ':attribute имеет недопустимые размеры изображения.',
    'distinct' => 'Поле :attribute имеет повторяющееся значение.',
    'email' => ':attribute должен быть действительным адресом электронной почты.',
    'ends_with' => ':attribute должен заканчиваться одним из следующих: :values.',
    'exists' => 'Выбранный :attribute недействителен.',
    'file' => ':attribute должен быть файлом.',
    'filled' => 'Поле :attribute должно иметь значение.',
    'gt' => [
        'numeric' => ':attribute должен быть больше :value.',
        'file' => ':attribute должен быть больше :value килобайт.',
        'string' => ':attribute должен быть больше символов :value.',
        'array' => ':attribute должен иметь больше, чем :value элементов.',
    ],
    'gte' => [
        'numeric' => ':attribute должен быть больше или равен :value.',
        'file' => ':attribute должен быть больше или равен :value килобайтам.',
        'string' => ':attribute должен быть больше или равен :value символов.',
        'array' => ':attribute должен иметь элементы :value или более.',
    ],
    'image' => ':attribute должен быть изображением.',
    'in' => 'Выбранный :attribute недействителен.',
    'in_array' => 'Поле :attribute не существует в :other.',
    'integer' => ':attribute должен быть целым числом.',
    'ip' => ':attribute должен быть действительным IP-адресом.',
    'ipv4' => ':attribute должен быть действительным адресом IPv4.',
    'ipv6' => ':attribute должен быть действительным адресом IPv6.',
    'json' => ':attribute должен быть допустимой строкой JSON.',
    'lt' => [
        'numeric' => ':attribute должен быть меньше :value.',
        'file' => 'Размер :attribute должен быть меньше :value килобайт.',
        'string' => ':attribute должен быть меньше символов :value.',
        'array' => 'Элемент :attribute должен содержать меньше элементов :value.',
    ],
    'lte' => [
        'numeric' => ':attribute должен быть меньше или равен :value.',
        'file' => ':attribute должен быть меньше или равен :value килобайтам.',
        'string' => ':attribute должен быть меньше или равен :value символов.',
        'array' => ':attribute не должен содержать более :value элементов.',
    ],
    'max' => [
        'numeric' => ':attribute не должен быть больше :max.',
        'file' => ':attribute не должен превышать :max килобайт.',
        'string' => ':attribute не должен превышать :max символов.',
        'array' => ':attribute не должен содержать более :max элементов.',
    ],
    'mimes' => ':attribute должен быть файлом типа: :values.',
    'mimetypes' => ':attribute должен быть файлом типа: :values.',
    'min' => [
        'numeric' => ':attribute должен быть как минимум :min.',
        'file' => 'Размер :attribute должен быть не менее :min килобайт.',
        'string' => ':attribute должен содержать не менее :min символов.',
        'array' => ':attribute должен иметь как минимум :min элементов.',
    ],
    'multiple_of' => ':attribute должен быть кратен :value.',
    'not_in' => 'Выбранный :attribute недействителен.',
    'not_regex' => 'Недопустимый формат :attribute.',
    'numeric' => ':attribute должен быть числом.',
    'password' => 'Неправильный пароль.',
    'present' => 'Поле :attribute должно присутствовать.',
    'regex' => 'Недопустимый формат :attribute.',
    'required' => 'Поле :attribute является обязательным.',
    'required_if' => 'Поле :attribute является обязательным, если :other равно :value.',
    'required_unless' => 'Поле :attribute является обязательным, если только :other не находится в :values.',
    'required_with' => 'Поле :attribute обязательно, когда присутствует :values.',
    'required_with_all' => 'Поле :attribute обязательно, когда присутствуют :values.',
    'required_without' => 'Поле :attribute является обязательным, если :values отсутствует.',
    'required_without_all' => 'Поле :attribute является обязательным, если ни одно из значений :value не присутствует.',
    'prohibited' => 'Поле :attribute запрещено.',
    'prohibited_if' => 'Поле :attribute запрещено, когда :other равно :value.',
    'prohibited_unless' => 'Поле :attribute запрещено, если только :other не находится в :values.',
    'prohibits' => 'Поле :attribute запрещает присутствие :other.',
    'same' => ':attribute и :other должны совпадать.',
    'size' => [
        'numeric' => ':attribute должен быть :size.',
        'file' => ':attribute должен быть :size килобайт.',
        'string' => ':attribute должен состоять из символов :size.',
        'array' => ':attribute должен содержать элементы :size.',
    ],
    'starts_with' => ':attribute должен начинаться с одного из следующих: :values.',
    'string' => ':attribute должен быть строкой.',
    'timezone' => ':attribute должен быть действительным часовым поясом.',
    'unique' => ':attribute уже занят.',
    'uploaded' => 'Не удалось загрузить :attribute.',
    'url' => ':attribute должен быть допустимым URL.',
    'uuid' => ':attribute должен быть допустимым UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

    "this_field_is_required" => "Это поле обязательно к заполнению",
    "field_is_required" => "{field}, обязательное для заполнения",
    "field_must_be_less_than_maxlength_character" => "{field} должно быть меньше {maxLength} символов",
    "field_must_be_more_than_minlength_character" => "{field} должно содержать более {minLength} символов.",
    "field_must_be_valid" => "{field} должно быть действительным",
    "field_must_be_in_the_list" => "{field} должно быть в списке ({list})",
    "field_must_not_be_in_the_list" => "{field} не должно быть в списке ({list})",
    "user_not_exist" => "Пользователь не существует",
    "user_not_active" => "Пользователь не активен",
    "user_confirmed" => "Пользователь подтвержден",
    "user_not_exist_with_email" => "Пользователь не существует с этим адресом электронной почты",
    'confirmation' => 'Подтверждение {field} не совпадает.',
    'old_password_incorrect' => 'Старый пароль неверный',
    'typename_exist' => 'Имя типа существует',
    'routename_exist' => 'Название маршрута существует',
    'parent_deep_limit_exceed' => 'Превышение родительского глубокого предела',
    'parent_loop' => 'Родительский цикл',
];
