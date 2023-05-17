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

    'accepted' => ':attribute qabul qilinishi kerak.',
    'accepted_if' => ':attribute :other :value bo‘lganda qabul qilinishi kerak.',
    'active_url' => ':attribute yaroqli URL manzil emas.',
    'after' => ':attribute :date dan keyingi sana bo\'lishi kerak.',
    'after_or_equal' => ':attribute :date dan keyingi sana yoki unga teng bo\'lishi kerak.',
    'alpha' => ':attribute faqat harflardan iborat bo\'lishi kerak.',
    'alpha_dash' => ':attribute faqat harflar, raqamlar, tire va pastki chiziqdan iborat bo\'lishi kerak.',
    'alpha_num' => ':attribute faqat harflar va raqamlardan iborat bo\'lishi kerak.',
    'array' => ':attribute massiv bo\'lishi kerak.',
    'before' => ':attribute :date dan oldingi sana bo\'lishi kerak.',
    'before_or_equal' => ':attribute :date dan oldingi sana yoki unga teng bo\'lishi kerak.',
    'between' => [
        'numeric' => ':attribute :min va :max orasida bo\'lishi kerak.',
        'file' => ':attribute :min va :max kilobayt oralig\'ida bo\'lishi kerak.',
        'string' => ':attribute :min va :max belgilar orasida bo\'lishi kerak.',
        'array' => ':attribute :min va :max orasida bo\'lishi kerak.',
    ],
    'boolean' => ':attribute maydoni rost yoki yolgʻon boʻlishi kerak.',
    'confirmed' => ':attribute tasdiqlash mos kelmaydi.',
    'current_password' => 'Parol noto\'g\'ri.',
    'date' => ':attribute haqiqiy sana emas.',
    'date_equals' => ':attribute :date ga teng sana bo\'lishi kerak.',
    'date_format' => ':attribute :format formatiga mos kelmaydi.',
    'different' => ':attribute va :other boshqacha bo\'lishi kerak.',
    'digits' => ':attribute :digits raqamlari bo\'lishi kerak.',
    'digits_between' => ':attribute :min va :max raqamlari orasida bo\'lishi kerak.',
    'dimensions' => ':attribute da rasm oʻlchamlari yaroqsiz.',
    'distinct' => ':attribute maydonida takroriy qiymat mavjud.',
    'email' => ':attribute yaroqli elektron pochta manzili boʻlishi kerak.',
    'ends_with' => ':attribute quyidagilardan biri bilan tugashi kerak: :values.',
    'exists' => 'Tanlangan :attribute yaroqsiz.',
    'file' => ':attribute fayl bo\'lishi kerak.',
    'filled' => ':attribute maydonida qiymat bo\'lishi kerak.',
    'gt' => [
        'numeric' => ':attribute :value dan katta bo\'lishi kerak.',
        'file' => ':attribute :value kilobaytdan katta bo\'lishi kerak.',
        'string' => ':attribute :value belgilaridan katta bo\'lishi kerak.',
        'array' => ':attribute da :value dan ortiq element bo‘lishi kerak.',
    ],
    'gte' => [
        'numeric' => ':attribute :value dan katta yoki teng bo\'lishi kerak.',
        'file' => ':attribute :value kilobaytdan katta yoki teng bo\'lishi kerak.',
        'string' => ':attribute :value belgilaridan katta yoki teng bo\'lishi kerak.',
        'array' => ':attribute da :value yoki undan ko\'p element bo\'lishi kerak.',
    ],
    'image' => ':attribute rasm bo\'lishi kerak.',
    'in' => 'Tanlangan :attribute yaroqsiz.',
    'in_array' => ':attribute maydoni :other ichida mavjud emas.',
    'integer' => ':attribute butun son bo\'lishi kerak.',
    'ip' => ':attribute toʻgʻri IP manzil boʻlishi kerak.',
    'ipv4' => ':attribute toʻgʻri IPv4 manzil boʻlishi kerak.',
    'ipv6' => ':attribute toʻgʻri IPv6 manzil boʻlishi kerak.',
    'json' => ':attribute toʻgʻri JSON manzil boʻlishi kerak.',
    'lt' => [
        'numeric' => ':attribute :value dan kichik bo\'lishi kerak.',
        'file' => ':attribute :value kilobaytdan kichik bo\'lishi kerak.',
        'string' => ':attribute :value belgilaridan kichik bo\'lishi kerak.',
        'array' => ':attribute :value dan kam bo\'lishi kerak.',
    ],
    'lte' => [
        'numeric' => ':attribute :value dan kichik yoki teng bo\'lishi kerak.',
        'file' => ':attribute :value kilobaytdan kichik yoki teng bo\'lishi kerak.',
        'string' => ':attribute :value belgilaridan kichik yoki teng bo\'lishi kerak.',
        'array' => ':attribute :value dan ortiq bo‘lmasligi kerak.',
    ],
    'max' => [
        'numeric' => ':attribute :max dan katta bo\'lmasligi kerak.',
        'file' => ':attribute :max kilobaytdan oshmasligi kerak.',
        'string' => ':attribute :max belgilardan oshmasligi kerak.',
        'array' => ':attribute da :max dan ortiq elementlar bo‘lmasligi kerak.',
    ],
    'mimes' => ':attribute :values turidagi fayl bo\'lishi kerak.',
    'mimetypes' => ':attribute :values turidagi fayl bo\'lishi kerak.',
    'min' => [
        'numeric' => ':attribute kamida :min bo\'lishi kerak.',
        'file' => ':attribute kamida :min kilobayt bo\'lishi kerak.',
        'string' => ':attribute kamida :min belgilar boʻlishi kerak.',
        'array' => ':attribute da kamida :min elementlar bo‘lishi kerak.',
    ],
    'multiple_of' => ':attribute :value ning ko\'paytmasi bo\'lishi kerak.',
    'not_in' => 'Tanlangan :attribute yaroqsiz.',
    'not_regex' => ':attribute formati yaroqsiz.',
    'numeric' => ':attribute raqam bo\'lishi kerak.',
    'password' => 'Parol noto\'g\'ri.',
    'present' => ':attribute maydoni mavjud bo\'lishi kerak.',
    'regex' => ':attribute formati yaroqsiz.',
    'required' => ':attribute maydoni talab qilinadi.',
    'required_if' => ':attribute maydoni :other :value bo‘lganda talab qilinadi.',
    'required_unless' => ':values ichida :other bo\'lmasa, :attribute maydoni talab qilinadi.',
    'required_with' => ':values mavjud bo\'lganda :attribute maydoni talab qilinadi.',
    'required_with_all' => ':attribute maydoni :values mavjud bo\'lganda talab qilinadi.',
    'required_without' => ':attribute maydoni :values mavjud bo\'lmaganda talab qilinadi.',
    'required_without_all' => ':attribute maydoni :values dan hech biri mavjud bo\'lmaganda talab qilinadi.',
    'prohibited' => ':attribute maydoni taqiqlangan.',
    'prohibited_if' => ':other :value bo\'lganda :attribute maydoni taqiqlanadi.',
    'prohibited_unless' => ':attribute maydoni, agar :values ichida :other bo\'lmasa, taqiqlanadi.',
    'prohibits' => ':attribute maydoni :other ning mavjud bo\'lishini taqiqlaydi.',
    'same' => ':attribute va :other mos kelishi kerak.',
    'size' => [
        'numeric' => ':attribute :size bo\'lishi kerak.',
        'file' => ':attribute :size kilobayt bo\'lishi kerak.',
        'string' => ':attribute :size belgilar bo\'lishi kerak.',
        'array' => ':attribute da :size elementlari bo‘lishi kerak.',
    ],
    'starts_with' => ':attribute quyidagilardan biri bilan boshlanishi kerak: :values.',
    'string' => ':attribute satr bo\'lishi kerak.',
    'timezone' => ':attribute yaroqli vaqt mintaqasi boʻlishi kerak.',
    'unique' => ':attribute allaqachon olingan.',
    'uploaded' => ':attribute yuklanmadi.',
    'url' => ':attribute toʻgʻri URL boʻlishi kerak.',
    'uuid' => ':attribute haqiqiy UUID bo\'lishi kerak.',

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

    "this_field_is_required" => "Ushbu qator to'ldirilishi shart",
    "field_is_required" => "{field} boʻlishi shart",
    "field_must_be_less_than_maxlength_character" => "{field} {maxLength} ta belgidan kam boʻlishi kerak",
    "field_must_be_more_than_minlength_character" => "{field} {minLength} ta belgidan oshmasligi kerak",
    "field_must_be_valid" => "{field} toʻgʻri boʻlishi kerak",
    "field_must_be_in_the_list" => "{field} roʻyxatda boʻlishi kerak ({list})",
    "field_must_not_be_in_the_list" => "{field} roʻyxatda boʻlmasligi kerak ({list})",
    "user_not_exist" => "Foydalanuvchi mavjud emas",
    "user_not_active" => "Foydalanuvchi faol emas",
    "user_confirmed" => "Foydalanuvchi tasdiqlandi",
    "user_not_exist_with_email" => "Bu e-mail foydalanuvchi mavjud emas",
    'confirmation' => '{field} tasdiqlash mos kelmaydi.',
    'old_password_incorrect' => 'Eski parol noto\'g\'ri',
    'typename_exist' => 'Tur nomi mavjud',
    'routename_exist' => 'Marshrut nomi mavjud',
    'parent_deep_limit_exceed' => 'Ota-ona chegarasidan oshib ketdi',
    'parent_loop' => 'Ota-ona tsikli',
];
