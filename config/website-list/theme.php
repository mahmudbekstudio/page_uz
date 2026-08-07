<?php

use App\Models\Website;

$themes = include config_path('theme.php');

$domainPostfix = config('app.main_website');

return array_combine(array_keys($themes), array_map(function($name, $config) use ($domainPostfix, &$themeStartId) {
    return [
        'data' => [
            'status' => Website::STATUS_ACTIVE,
            'domain' => $name . '.' . $domainPostfix,
            'type' => Website::TYPE_MAIN,
            'domain_id' => null
        ],
        'metas' => [
            [
                'meta_key' => 'name',
                'meta_value' => 'Theme ' . $name,
                'user_id' => 0
            ]
        ],
        'theme' => $config,
    ];
}, array_keys($themes), array_values($themes)));
