<?php

return [
    'main' => include config_path('website-list/main.php'),
    //...(in_array(config('app.env'), ['prod', 'production']) ? [] : include config_path('website-list/test.php')),
    //...(include config_path('website-list/theme.php'))
];
