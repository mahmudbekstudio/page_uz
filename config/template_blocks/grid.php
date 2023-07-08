<?php

return [
    'type' => 'grid',
    //'hide' => true,
    'name' => 'templates.grid',
    'description' => 'templates.grid_description',
    'canHasChild' => true,
    'fields' => [
        [
            'type' => 'container',
            'name' => 'container1',
        ],
        [
            'type' => 'container',
            'name' => 'container2',
        ],
        [
            'type' => 'container',
            'name' => 'container3',
        ],
        [
            'type' => 'container',
            'name' => 'container4',
        ],
    ],
    'samples' => [
        [
            'layout' => 1,
            'values' => [
                'container1' => [],
                'container2' => [],
            ],
        ],
        [
            'layout' => 2,
            'values' => [
                'container1' => [],
                'container2' => [],
            ],
        ],
        [
            'layout' => 3,
            'values' => [
                'container1' => [],
                'container2' => [],
            ],
        ],
        [
            'layout' => 4,
            'values' => [
                'container1' => [],
                'container2' => [],
                'container3' => [],
            ],
        ],
        [
            'layout' => 5,
            'values' => [
                'container1' => [],
                'container2' => [],
                'container3' => [],
            ],
        ],
        [
            'layout' => 6,
            'values' => [
                'container1' => [],
                'container2' => [],
                'container3' => [],
            ],
        ],
        [
            'layout' => 7,
            'values' => [
                'container1' => [],
                'container2' => [],
                'container3' => [],
            ],
        ],
        [
            'layout' => 8,
            'values' => [
                'container1' => [],
                'container2' => [],
                'container3' => [],
                'container4' => [],
            ],
        ],
    ],
    'styles' => [
        //
    ],
    'layout' => [
        [
            'id' => 1,
            'children' => [
                [
                    'key' => 1,
                    'title' => 'container1',
                    'name' => 'container1',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
                [
                    'key' => 2,
                    'title' => 'container2',
                    'name' => 'container2',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
            ],
            'structure' => [
                'tag' => 'div',
                'attributes' => ['class' => 'container-fluid'],
                'children' => [
                    [
                        'tag' => 'div',
                        'attributes' => ['class' => 'row'],
                        'children' => [
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container1',
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container2',
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 2,
            'children' => [
                [
                    'key' => 1,
                    'title' => 'container1',
                    'name' => 'container1',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
                [
                    'key' => 2,
                    'title' => 'container2',
                    'name' => 'container2',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
            ],
            'structure' => [
                'tag' => 'div',
                'attributes' => ['class' => 'container-fluid'],
                'children' => [
                    [
                        'tag' => 'div',
                        'attributes' => ['class' => 'row'],
                        'children' => [
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-3 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container1',
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-9 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container2',
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 3,
            'children' => [
                [
                    'key' => 1,
                    'title' => 'container1',
                    'name' => 'container1',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
                [
                    'key' => 2,
                    'title' => 'container2',
                    'name' => 'container2',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
            ],
            'structure' => [
                'tag' => 'div',
                'attributes' => ['class' => 'container-fluid'],
                'children' => [
                    [
                        'tag' => 'div',
                        'attributes' => ['class' => 'row'],
                        'children' => [
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-9 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container1',
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-3 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container2',
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 4,
            'children' => [
                [
                    'key' => 1,
                    'title' => 'container1',
                    'name' => 'container1',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
                [
                    'key' => 2,
                    'title' => 'container2',
                    'name' => 'container2',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
                [
                    'key' => 3,
                    'title' => 'container3',
                    'name' => 'container3',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
            ],
            'structure' => [
                'tag' => 'div',
                'attributes' => ['class' => 'container-fluid'],
                'children' => [
                    [
                        'tag' => 'div',
                        'attributes' => ['class' => 'row'],
                        'children' => [
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-6 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container1',
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-3 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container2',
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-3 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container3',
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 5,
            'children' => [
                [
                    'key' => 1,
                    'title' => 'container1',
                    'name' => 'container1',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
                [
                    'key' => 2,
                    'title' => 'container2',
                    'name' => 'container2',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
                [
                    'key' => 3,
                    'title' => 'container3',
                    'name' => 'container3',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
            ],
            'structure' => [
                'tag' => 'div',
                'attributes' => ['class' => 'container-fluid'],
                'children' => [
                    [
                        'tag' => 'div',
                        'attributes' => ['class' => 'row'],
                        'children' => [
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-3 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container1',
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-6 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container2',
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-3 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container3',
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 6,
            'children' => [
                [
                    'key' => 1,
                    'title' => 'container1',
                    'name' => 'container1',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
                [
                    'key' => 2,
                    'title' => 'container2',
                    'name' => 'container2',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
                [
                    'key' => 3,
                    'title' => 'container3',
                    'name' => 'container3',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
            ],
            'structure' => [
                'tag' => 'div',
                'attributes' => ['class' => 'container-fluid'],
                'children' => [
                    [
                        'tag' => 'div',
                        'attributes' => ['class' => 'row'],
                        'children' => [
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-3 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container1',
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-3 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container2',
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-6 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container3',
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 7,
            'children' => [
                [
                    'key' => 1,
                    'title' => 'container1',
                    'name' => 'container1',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
                [
                    'key' => 2,
                    'title' => 'container2',
                    'name' => 'container2',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
                [
                    'key' => 3,
                    'title' => 'container3',
                    'name' => 'container3',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
            ],
            'structure' => [
                'tag' => 'div',
                'attributes' => ['class' => 'container-fluid'],
                'children' => [
                    [
                        'tag' => 'div',
                        'attributes' => ['class' => 'row'],
                        'children' => [
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-4 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container1',
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-4 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container2',
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-4 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container3',
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 8,
            'children' => [
                [
                    'key' => 1,
                    'title' => 'container1',
                    'name' => 'container1',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
                [
                    'key' => 2,
                    'title' => 'container2',
                    'name' => 'container2',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
                [
                    'key' => 3,
                    'title' => 'container3',
                    'name' => 'container3',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
                [
                    'key' => 4,
                    'title' => 'container4',
                    'name' => 'container4',
                    'notSort' => true,
                    'canHasChild' => true,
                ],
            ],
            'structure' => [
                'tag' => 'div',
                'attributes' => ['class' => 'container-fluid'],
                'children' => [
                    [
                        'tag' => 'div',
                        'attributes' => ['class' => 'row'],
                        'children' => [
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-3 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container1',
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-3 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container2',
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-3 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container3',
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'col-3 pl-0 pr-0'],
                                'children' => [
                                    [
                                        'field' => 'container4',
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
    ],
];
