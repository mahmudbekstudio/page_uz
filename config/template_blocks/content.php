<?php

return [
    'type' => 'content',
    'hide' => true,
    'name' => 'templates.content',
    'description' => 'templates.content_description',
    'fields' => [
        [
            'type' => 'content',
            'name' => 'content',
            'label' => 'templates.content',
            'hide' => true,
        ],
    ],
    'samples' => [
        [
            'layout' => 1,
            'values' => [
                'background' => ['style' => 'solid', 'color' => '#FFF'],
                'content' => [],
            ],
        ]
    ],
    'styles' => [
        '.content' => [
            'field' => 'background',
        ],
    ],
    'layout' => [
        [
            'id' => 1,
            'structure' => [
                'tag' => 'div',
                'attributes' => ['class' => 'container-fluid content'],
                'children' => [
                    [
                        'field' => 'content'
                    ]
                ]
            ]
        ]
    ]
];
