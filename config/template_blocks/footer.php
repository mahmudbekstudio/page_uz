<?php

return [
    'type' => 'footer',
    //'hide' => true,
    'name' => 'templates.footer',
    'description' => 'templates.footer_description',
    'fields' => [
        [
            'type' => 'textarea',
            'name' => 'text',
            'label' => 'templates.copyright',
        ],
    ],
    'samples' => [
        [
            'layout' => 1,
            'values' => [
                'text' => ['value' => '© 2023 Your company name', 'color' => '#D4D4D4'],
                'background' => ['style' => 'solid', 'color' => '#2E3338'],
            ],
        ],
    ],
    'styles' => [
        '.footer' => [
            'field' => 'background',
        ],
        '.footer-wrap' => [
            'padding' => '30px 0',
        ],
        '.footer-copyright' => [
            'margin' => '0 0 10px 0',
            'text-align' => 'center',
        ],
    ],
    'layout' => [
        [
            'id' => 1,
            'structure' => [
                'tag' => 'footer',
                'attributes' => ['class' => 'footer'],
                'children' => [
                    [
                        'tag' => 'div',
                        'attributes' => ['class' => 'footer-wrap'],
                        'children' => [
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'footer-copyright'],
                                'children' => [
                                    [
                                        'field' => 'text'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
        ]
    ],
];
