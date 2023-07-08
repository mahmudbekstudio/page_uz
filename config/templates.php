<?php

$header = include config_path('template_blocks/header.php');
$announcement = include config_path('template_blocks/announcement.php');
$cover = include config_path('template_blocks/cover.php');
$footer = include config_path('template_blocks/footer.php');
$grid = include config_path('template_blocks/grid.php');
$content = include config_path('template_blocks/content.php');

return [
    'fields' => [
        [
            'type' => 'background',
            'name' => 'background',
            'label' => 'templates.background'
        ]
    ],
    'styleFiles' => [
        '/css/app/styles.css'
    ],
    'scriptFiles' => [
        '/js/app/main.js'
    ],
    'styles' => [
        //
    ],
    'blocks' => [
        $header,
        $announcement,
        $cover,
        $footer,
        $grid,
        $content,
    ]
];
