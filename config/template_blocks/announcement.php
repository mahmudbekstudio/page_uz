<?php

return [
    'type' => 'announcement',
    'name' => 'templates.announcement',
    'description' => 'templates.announcement_description',
    'fields' => [
        [
            'type' => 'text',
            'name' => 'text',
            'label' => 'templates.announcement_text',
        ],
        [
            'type' => 'button',
            'name' => 'action',
            'label' => 'templates.action_link',
        ],
    ],
    'samples' => [
        [
            'layout' => 1,
            'values' => [
                'text' => ['value' => 'Use code “HELLO-WEEN” to get a 25% discount', 'color' => '#000000'],
                'background' => ['style' => 'solid', 'color' => '#FF86B4'],
            ],
        ],
        [
            'layout' => 1,
            'values' => [
                'text' => ['value' => 'Free shipping for orders over $100', 'color' => '#FFFFFF'],
                'background' => ['style' => 'solid', 'color' => '#0E89CB'],
            ],
        ],
        [
            'layout' => 1,
            'values' => [
                'text' => ['value' => 'We are on vacation until 5 September', 'color' => '#FFFFFF'],
                'background' => ['style' => 'solid', 'color' => '#461DBA'],
            ],
        ],
        [
            'layout' => 1,
            'values' => [
                'text' => ['value' => 'Check out our spring collection', 'color' => '#000000'],
                'background' => ['style' => 'solid', 'color' => '#FFFFFF'],
                'action' => ['value' => 'GET INSPIRED', 'appearance' => 'solid', 'shape' => 'pill', 'background-color' => '#000000', 'text-color' => '#FFF'],
            ],
        ],
        [
            'layout' => 1,
            'values' => [
                'text' => ['value' => 'Our exclusive shirts', 'color' => '#161616'],
                'background' => ['style' => 'solid', 'color' => '#FDBE2E'],
                'action' => ['value' => 'CHECK RIGHT NOW', 'appearance' => 'text', 'text-color' => '#161616', 'background-color' => '#FFF'],
            ],
        ],
        [
            'layout' => 1,
            'values' => [
                'text' => ['value' => 'Sales up to −30% on ALL', 'color' => '#FFFFFF'],
                'background' => ['style' => 'gradient', 'color' => ['#2EB67D', '#FDBE2E']],
                'action' => ['value' => 'ORDER NOW', 'appearance' => 'outline', 'shape' => 'round-corner', 'text-color' => '#FFF', 'background-color' => '#000'],
            ],
        ],
    ],
    'styles' => [
        '.announcement' => [
            'field' => 'background',
        ],
        '.announcement-wrap' => [
            'padding' => '6px 0',
            'text-align' => 'center',
        ],
        '.announcement-information' => [
            'display' => 'inline'
        ],
        '.announcement-button-wrap' => [
            'display' => 'inline'
        ],
        '.announcement-button-wrap .button' => [
            'margin-left' => '10px'
        ],
    ],
    'layout' => [
        [
            'id' => 1,
            'structure' => [
                'tag' => 'main',
                'attributes' => ['class' => 'announcement'],
                'children' => [
                    [
                        'tag' => 'div',
                        'attributes' => ['class' => 'announcement-wrap'],
                        'children' => [
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'announcement-information'],
                                'children' => [
                                    [
                                        'field' => 'text'
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'announcement-button-wrap'],
                                'children' => [
                                    [
                                        'field' => 'action'
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
