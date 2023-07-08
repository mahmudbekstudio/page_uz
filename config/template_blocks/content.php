<?php

return [
    'type' => 'content',
    'hide' => true,
    'name' => 'templates.content',
    'description' => 'templates.content_description',
    /*'fields' => [
        //
    ],*/
    'samples' => [
        [
            'layout' => 1,
            'values' => [
                'background' => ['style' => 'solid', 'color' => '#FFF'],
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
                        'text' => 'Content'
                    ]
                ]
            ]
        ]
    ]
];
