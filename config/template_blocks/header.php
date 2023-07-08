<?php

return [
    'type' => 'header',
    //'hide' => true,
    'name' => 'templates.header',
    'description' => 'templates.header_description',
    'fields' => [
        [
            'type' => 'image',
            'name' => 'logo',
            'label' => 'templates.site_logo',
        ],
        [
            'type' => 'account_button',
            'name' => 'account_button',
            'label' => 'templates.account_button',
        ],
        [
            'type' => 'cart_button',
            'name' => 'cart_button',
            'label' => 'templates.cart_button',
        ],
        [
            'type' => 'search',
            'name' => 'search',
            'label' => 'templates.search',
        ],
    ],
    'samples' => [
        [
            'layout' => 1,
            'values' => [
                'background' => ['style' => 'solid', 'color' => '#251608'],
                'logo' => ['value' => 'https://d1howb1wwyap5o.cloudfront.net/vuega/demo_store/en/header/logo-white.png', 'link' => '#'],
                'account_button' => ['color' => '#FFF'],
                'cart_button' => ['color' => '#FFF'],
                'menu' => ['link-color' => '#FFF', 'class' => 'mr-auto'],
                'action' => ['value' => 'Test', 'appearance' => 'outline', 'size' => 'small', 'shape' => 'pill', 'background-color' => '#191919', 'text-color' => '#FFF'],
                'search_button' => ['color' => '#FFF'],
                'search' => [],
            ],
        ],
        [
            'layout' => 2,
            'values' => [
                'background' => ['style' => 'solid', 'color' => '#FF86B4'],
                'logo' => ['value' => 'https://d1howb1wwyap5o.cloudfront.net/vuega/demo_store/en/header/logo-white.png', 'link' => '#'],
                'account_button' => ['color' => '#FFF'],
                'cart_button' => ['color' => '#FFF'],
                'menu' => ['link-color' => '#FFF', 'class' => 'mr-auto'],
                'phone' => ['value' => '+998 90 323-17-55', 'link' => 'tel:+998903231755', 'color' => '#FFF', 'size' => '14'],
                'email' => ['value' => 'mahmudbekstudio@mail.ru', 'link' => 'mail:mahmudbekstudio@mail.ru', 'color' => '#FFF', 'size' => '14'],
                'search' => [],
            ],
        ],
        [
            'layout' => 3,
            'values' => [
                'background' => ['style' => 'solid', 'color' => '#FF86B4'],
                'logo' => ['value' => 'https://d1howb1wwyap5o.cloudfront.net/vuega/demo_store/en/header/logo-white.png', 'link' => '#'],
                'account_button' => ['color' => '#FFF'],
                'cart_button' => ['color' => '#FFF'],
                'menu' => ['link-color' => '#FFF'],
                'action' => ['value' => 'Test', 'appearance' => 'outline', 'size' => 'small', 'shape' => 'pill', 'background-color' => '#191919', 'text-color' => '#FFF'],
                'search_button' => ['color' => '#FFF'],
                'search' => [],
            ],
        ],
        [
            'layout' => 4,
            'values' => [
                'background' => ['style' => 'solid', 'color' => '#FF86B4'],
                'logo' => ['value' => 'https://d1howb1wwyap5o.cloudfront.net/vuega/demo_store/en/header/logo-white.png', 'link' => '#'],
                'account_button' => ['color' => '#FFF'],
                'cart_button' => ['color' => '#FFF'],
                'menu' => ['link-color' => '#FFF'],
                'social_profile' => [
                    'color' => '#FFF',
                    'list' => [
                        [
                            'color' => '#FFF',
                            'icon' => 'instagram',
                            'link' => '#',
                        ],
                        [
                            'color' => '#FFF',
                            'icon' => 'twitter',
                            'link' => '#',
                        ],
                        [
                            'color' => '#FFF',
                            'icon' => 'facebook',
                            'link' => '#',
                        ]
                    ]
                ],
                'search_button' => ['color' => '#FFF'],
                'search' => [],
                'action' => ['value' => 'Test', 'appearance' => 'outline', 'size' => 'small', 'shape' => 'pill', 'background-color' => '#191919', 'text-color' => '#FFF'],
            ],
        ],
        /*[
            'layout' => 5,
            'values' => [
                'background' => ['style' => 'solid', 'color' => '#FF86B4'],
                'logo' => ['value' => 'https://d1howb1wwyap5o.cloudfront.net/vuega/demo_store/en/header/logo-white.png', 'link' => '#'],
                'account_button' => [],
                'cart_button' => [],
                'menu' => [],
                'search_button' => [],
            ],
        ],
        [
            'layout' => 6,
            'values' => [
                'background' => ['style' => 'solid', 'color' => '#FF86B4'],
                'logo' => ['value' => 'https://d1howb1wwyap5o.cloudfront.net/vuega/demo_store/en/header/logo-white.png', 'link' => '#'],
                'account_button' => [],
                'cart_button' => [],
                'menu' => [],
                'phone' => [],
                'email' => [],
                'social_profile' => [],
                'action' => [],
            ],
        ],
        [
            'layout' => 7,
            'values' => [
                'background' => ['style' => 'solid', 'color' => '#FF86B4'],
                'logo' => ['value' => 'https://d1howb1wwyap5o.cloudfront.net/vuega/demo_store/en/header/logo-white.png', 'link' => '#'],
                'account_button' => [],
                'cart_button' => [],
                'menu' => [],
                'phone' => [],
                'email' => [],
                'social_profile' => [],
                'action' => [],
            ],
        ],
        [
            'layout' => 8,
            'values' => [
                'background' => ['style' => 'solid', 'color' => '#FF86B4'],
                'logo' => ['value' => 'https://d1howb1wwyap5o.cloudfront.net/vuega/demo_store/en/header/logo-white.png', 'link' => '#'],
                'account_button' => [],
                'cart_button' => [],
                'menu' => [],
                'phone' => [],
                'email' => [],
                'action' => [],

            ],
        ],*/
    ],
    'styles' => [
        '#id.header-layout-1 .navbar' => [
            'field' => 'background',
            'min-height' => '70px',
        ],
        '#id.header-layout-1 .header-logo img' => [
            'max-height' => '40px',
        ],
        '#id.header-layout-1 .header-action' => [
            'margin' => '0 10px',
        ],
        '#id.header-layout-1 .header-search_button' => [
            'margin' => '0 10px',
        ],
        '#id.header-layout-1 .header-account_button' => [
            'margin' => '0 10px',
        ],
        '#id.header-layout-1 .header-cart_button' => [
            'margin' => '0 10px',
        ],
        '#id.header-layout-1 .header-search' => [
            'margin' => '0 10px',
        ],
        '#id.header-layout-1 .header-actions2, #id.header-layout-1 .header-actions' => [
            'display' => 'flex'
        ],
        '#id.header-layout-1 .navbar-toggler' => [
            'border' => '0',
            'padding' => '0',
            'font-size' => '1rem'
        ],


        ///
        '#id.header-layout-2 .navbar' => [
            'field' => 'background',
            'min-height' => '70px',
            'flex-flow' => 'column',
        ],
        '#id.header-layout-2 .header-logo img' => [
            'max-height' => '40px',
        ],
        '#id.header-layout-2 .header-actions2, #id.header-layout-2 .header-actions' => [
            'display' => 'flex',
        ],
        '#id.header-layout-2 .header-row' => [
            'display' => 'flex',
            'flex-flow' => 'row',
            'width' => '100%',
            'align-items' => 'center',
        ],
        '#id.header-layout-2 .header-row:last-child' => [
            'border-top' => '1px solid #FFF',
            'padding-top' => '5px',
        ],
        '#id.header-layout-2 .navbar-toggler' => [
            'border' => '0',
            'padding' => '0',
            'font-size' => '1rem'
        ],
        '#id.header-layout-2 .header-phone' => [
            'margin-right' => '15px'
        ],
        '#id.header-layout-2 .header-account_button' => [
            'margin' => '0 10px',
        ],
        '#id.header-layout-2 .header-cart_button' => [
            'margin' => '0 10px',
        ],

        '#id.header-layout-3 .navbar' => [
            'field' => 'background',
            'min-height' => '70px',
            'flex-flow' => 'row',
        ],
        '#id.header-layout-3 .header-logo img' => [
            'max-height' => '40px',
        ],
        /*'#id.header-layout-3 .header-actions2, #id.header-layout-3 .header-actions' => [
            'display' => 'flex',
        ],*/
        '#id.header-layout-3 .header-account_button' => [
            'margin' => '0 10px',
        ],
        '#id.header-layout-3 .header-cart_button' => [
            'margin' => '0 10px',
        ],
        '#id.header-layout-3 .header-search_button' => [
            'margin' => '0 10px',
        ],
        '#id.header-layout-3 .header-action' => [
            'margin' => '0 10px',
        ],
        '#id.header-layout-3 .navbar-toggler' => [
            'border' => '0',
            'padding' => '0',
            'font-size' => '1rem',
        ],
        '#id.header-layout-3 .header-menu, #id.header-layout-3 .navbar-toggler' => [
            'flex-basis' => '0',
            'flex-grow' => '3',
            'text-align' => 'left',
        ],
        '#id.header-layout-3 .header-logo' => [
            'flex-basis' => '0',
            'flex-grow' => '1',
            'display' => 'flex',
            'justify-content' => 'center',
        ],
        '#id.header-layout-3 .header-actions' => [
            'flex-basis' => '0',
            'flex-grow' => '3',
            'display' => 'flex',
            'justify-content' => 'end',
        ],

        '#id.header-layout-4 .navbar' => [
            'field' => 'background',
            'min-height' => '70px',
            'flex-flow' => 'column',
        ],
        '#id.header-layout-4 .header-logo img' => [
            'max-height' => '40px',
        ],
        '#id.header-layout-4 .header-account_button' => [
            'margin' => '0 10px',
        ],
        '#id.header-layout-4 .header-cart_button' => [
            'margin' => '0 10px',
        ],
        '#id.header-layout-4 .header-search_button' => [
            'margin' => '0 10px',
        ],
        '#id.header-layout-4 .header-action' => [
            'margin' => '0 10px',
        ],
        '#id.header-layout-4 .field-icon_list .field-icon_list-link' => [
            'margin' => '0 10px',
        ],
        '#id.header-layout-4 .navbar-toggler' => [
            'border' => '0',
            'padding' => '0',
            'font-size' => '1rem',
        ],
        '#id.header-layout-4 .header-icon_list, #id.header-layout-4 .navbar-toggler' => [
            'flex-basis' => '0',
            'flex-grow' => '3',
            'text-align' => 'left',
        ],
        '#id.header-layout-4 .header-logo' => [
            'flex-basis' => '0',
            'flex-grow' => '1',
            'display' => 'flex',
            'justify-content' => 'center',
        ],
        '#id.header-layout-4 .header-row' => [
            'width' => '100%',
            'display' => 'flex',
            'align-items' => 'center',
        ],
        '#id.header-layout-4 .header-actions' => [
            'flex-basis' => '0',
            'flex-grow' => '3',
            'display' => 'flex',
            'justify-content' => 'end',
        ],
        '#id.header-layout-4 .collapse' => [
            'justify-content' => 'center',
        ],
        '@media screen and (min-width: 768px)' => [
            '#id.header-layout-3 .header-menu-mobile' => [
                'display' => 'none !important',
            ],
        ],
        '@media screen and (max-width: 768px)' => [

            '#id.header-layout-1 .header-actions' => [
                'flex-flow' => 'column'
            ],
            '#id.header-layout-1 .header-actions .header-action' => [
                'margin-bottom' => '10px'
            ],
            '#id.header-layout-1 .header-actions .header-action .field-button' => [
                'display' => 'block'
            ],

            //
            '#id.header-layout-2 .header-search' => [
                'width' => '100%'
            ],
            '#id.header-layout-2 .header-row:last-child' => [
                'border-top' => '0',
                'padding-top' => '0',
            ],
            '#id.header-layout-2 .header-actions' => [
                'flex-flow' => 'column'
            ],
            '#id.header-layout-2 .header-phone, #id.header-layout-2 .header-email' => [
                'margin' => '5px 0',
                'text-align' => 'center',
            ],
            '#id.header-layout-3 .header-menu' => [
                'display' => 'none',
            ],
            '#id.header-layout-3 .navbar' => [
                'flex-flow' => 'wrap',
            ],
            '#id.header-layout-3 .header-search' => [
                'margin' => '10px 0',
            ],
            '#id.header-layout-3 .header-action' => [
                'margin' => '10px 0',
            ],
            '#id.header-layout-3 .header-action .field-button' => [
                'display' => 'block',
            ],

            '#id.header-layout-4 .header-search' => [
                'margin' => '10px 0',
            ],
            '#id.header-layout-4 .header-action' => [
                'margin' => '10px 0',
            ],
            '#id.header-layout-4 .header-action .field-button' => [
                'display' => 'block',
            ],
            '#id.header-layout-4 .header-icon_list' => [
                'display' => 'block',
            ]
        ],
    ],
    'layout' => [
        [
            'id' => 1,
            'structure' => [
                'tag' => 'header',
                'attributes' => ['class' => 'header header-layout-1'],
                'children' => [
                    [
                        'tag' => 'nav',
                        'attributes' => ['class' => 'navbar navbar-expand-md navbar-dark'],//fixed-top bg-dark
                        'children' => [
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'header-logo navbar-brand'],
                                'children' => [
                                    [
                                        'field' => 'logo'
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'header-actions2 ml-auto d-lg-none d-md-none'],
                                'children' => [
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'header-account_button'],
                                        'children' => [
                                            [
                                                'field' => 'account_button'
                                            ]
                                        ]
                                    ],
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'header-cart_button'],
                                        'children' => [
                                            [
                                                'field' => 'cart_button'
                                            ]
                                        ]
                                    ],
                                ]
                            ],
                            [
                                'tag' => 'button',
                                'attributes' => [
                                    'class' => 'navbar-toggler',
                                    'type' => 'button',
                                    'data-toggle' => 'collapse',
                                    'data-target' => '#navbarCollapse',
                                    'aria-controls' => 'navbarCollapse',
                                    'aria-expanded' => 'false',
                                    'aria-label' => 'Toggle navigation'
                                ],
                                'children' => [
                                    [
                                        'tag' => 'span',
                                        'attributes' => ['class' => 'navbar-toggler-icon']
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'collapse navbar-collapse', 'id' => 'navbarCollapse'],
                                'children' => [
                                    [
                                        'field' => 'menu'
                                    ],
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'header-actions'],
                                        'children' => [
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-action'],
                                                'children' => [
                                                    [
                                                        'field' => 'action'
                                                    ]
                                                ]
                                            ],
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-search_button d-none d-md-block'],
                                                'children' => [
                                                    [
                                                        'field' => 'search_button'
                                                    ]
                                                ]
                                            ],
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-search d-none d-sm-block d-md-none'],
                                                'children' => [
                                                    [
                                                        'field' => 'search'
                                                    ]
                                                ]
                                            ],
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-account_button d-sm-none d-md-block'],
                                                'children' => [
                                                    [
                                                        'field' => 'account_button'
                                                    ]
                                                ]
                                            ],
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-cart_button d-sm-none d-md-block'],
                                                'children' => [
                                                    [
                                                        'field' => 'cart_button'
                                                    ]
                                                ]
                                            ],
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'fields' => [
                [
                    'type' => 'menu',
                    'name' => 'menu',
                    'label' => 'templates.menu',
                ],
                [
                    'type' => 'button',
                    'name' => 'action',
                    'label' => 'templates.action_link',
                ],
                [
                    'type' => 'search_button',
                    'name' => 'search_button',
                    'label' => 'templates.account_button',
                ],
            ],
        ],
        [
            'id' => 2,
            'structure' => [
                'tag' => 'header',
                'attributes' => ['class' => 'header header-layout-2'],
                'children' => [
                    [
                        'tag' => 'nav',
                        'attributes' => ['class' => 'navbar navbar-expand-md navbar-dark'],
                        'children' => [
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'header-row'],
                                'children' => [
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'header-logo navbar-brand'],
                                        'children' => [
                                            [
                                                'field' => 'logo'
                                            ]
                                        ]
                                    ],

                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'header-actions2 d-md-flex ml-auto'],
                                        'children' => [
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-search d-none d-md-flex'],
                                                'children' => [
                                                    [
                                                        'field' => 'search'
                                                    ]
                                                ]
                                            ],
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-account_button'],
                                                'children' => [
                                                    [
                                                        'field' => 'account_button'
                                                    ]
                                                ]
                                            ],
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-cart_button'],
                                                'children' => [
                                                    [
                                                        'field' => 'cart_button'
                                                    ]
                                                ]
                                            ],
                                        ]
                                    ],
                                    [
                                        'tag' => 'button',
                                        'attributes' => [
                                            'class' => 'navbar-toggler',
                                            'type' => 'button',
                                            'data-toggle' => 'collapse',
                                            'data-target' => '#navbarCollapse',
                                            'aria-controls' => 'navbarCollapse',
                                            'aria-expanded' => 'false',
                                            'aria-label' => 'Toggle navigation'
                                        ],
                                        'children' => [
                                            [
                                                'tag' => 'span',
                                                'attributes' => ['class' => 'navbar-toggler-icon']
                                            ]
                                        ]
                                    ],
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'header-row'],
                                'children' => [

                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'collapse navbar-collapse', 'id' => 'navbarCollapse'],
                                        'children' => [
                                            [
                                                'field' => 'menu'
                                            ],
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-actions'],
                                                'children' => [
                                                    [
                                                        'tag' => 'div',
                                                        'attributes' => ['class' => 'header-action'],
                                                        'children' => [
                                                            [
                                                                'field' => 'action'
                                                            ]
                                                        ]
                                                    ],
                                                    [
                                                        'tag' => 'div',
                                                        'attributes' => ['class' => 'header-search_button d-none d-md-block'],
                                                        'children' => [
                                                            [
                                                                'field' => 'search_button'
                                                            ]
                                                        ]
                                                    ],
                                                    [
                                                        'tag' => 'div',
                                                        'attributes' => ['class' => 'header-search d-none d-sm-block d-md-none'],
                                                        'children' => [
                                                            [
                                                                'field' => 'search'
                                                            ]
                                                        ]
                                                    ],
                                                    [
                                                        'tag' => 'div',
                                                        'attributes' => ['class' => 'header-phone'],
                                                        'children' => [
                                                            [
                                                                'field' => 'phone'
                                                            ]
                                                        ]
                                                    ],
                                                    [
                                                        'tag' => 'div',
                                                        'attributes' => ['class' => 'header-email'],
                                                        'children' => [
                                                            [
                                                                'field' => 'email'
                                                            ]
                                                        ]
                                                    ],
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],


                        ]
                    ]
                ]
            ],
            'fields' => [
                [
                    'type' => 'menu',
                    'name' => 'menu',
                    'label' => 'templates.menu',
                ],
                [
                    'type' => 'text',
                    'name' => 'phone',
                    'label' => 'templates.phone',
                ],
                [
                    'type' => 'text',
                    'name' => 'email',
                    'label' => 'templates.email',
                ],
            ],
        ],
        [
            'id' => 3,
            'structure' => [
                'tag' => 'header',
                'attributes' => ['class' => 'header header-layout-3'],
                'children' => [
                    [
                        'tag' => 'nav',
                        'attributes' => ['class' => 'navbar navbar-expand-md navbar-dark'],
                        'children' => [
                            [
                                'tag' => 'button',
                                'attributes' => [
                                    'class' => 'navbar-toggler',
                                    'type' => 'button',
                                    'data-toggle' => 'collapse',
                                    'data-target' => '#navbarCollapse',
                                    'aria-controls' => 'navbarCollapse',
                                    'aria-expanded' => 'false',
                                    'aria-label' => 'Toggle navigation'
                                ],
                                'children' => [
                                    [
                                        'tag' => 'span',
                                        'attributes' => ['class' => 'navbar-toggler-icon']
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'header-menu'],
                                'children' => [
                                    [
                                        'field' => 'menu',
                                    ],
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'header-logo navbar-brand'],
                                'children' => [
                                    [
                                        'field' => 'logo'
                                    ]
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'header-actions'],
                                'children' => [
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'header-action d-none d-md-block'],
                                        'children' => [
                                            [
                                                'field' => 'action'
                                            ]
                                        ]
                                    ],
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'header-search_button d-none d-md-block'],
                                        'children' => [
                                            [
                                                'field' => 'search_button'
                                            ]
                                        ]
                                    ],
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'header-account_button'],
                                        'children' => [
                                            [
                                                'field' => 'account_button'
                                            ]
                                        ]
                                    ],
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'header-cart_button'],
                                        'children' => [
                                            [
                                                'field' => 'cart_button'
                                            ]
                                        ]
                                    ],
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'header-menu-mobile collapse navbar-collapse', 'id' => 'navbarCollapse'],
                                'children' => [
                                    [
                                        'field' => 'menu',
                                    ],
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'header-search d-none d-sm-block d-md-none'],
                                        'children' => [
                                            [
                                                'field' => 'search'
                                            ]
                                        ]
                                    ],
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'header-action d-none d-sm-block d-md-none'],
                                        'children' => [
                                            [
                                                'field' => 'action'
                                            ]
                                        ]
                                    ],
                                ]
                            ],
                        ]
                    ]
                ]
            ],
            'fields' => [
                [
                    'type' => 'menu',
                    'name' => 'menu',
                    'label' => 'templates.menu',
                ],
                [
                    'type' => 'button',
                    'name' => 'action',
                    'label' => 'templates.action_link',
                ],
                [
                    'type' => 'search_button',
                    'name' => 'search_button',
                    'label' => 'templates.account_button',
                ],
            ],
        ],
        [
            'id' => 4,
            'structure' => [
                'tag' => 'header',
                'attributes' => ['class' => 'header header-layout-4'],
                'children' => [
                    [
                        'tag' => 'nav',
                        'attributes' => ['class' => 'navbar navbar-expand-md navbar-dark'],
                        'children' => [
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'header-row'],
                                'children' => [
                                    [
                                        'tag' => 'button',
                                        'attributes' => [
                                            'class' => 'navbar-toggler',
                                            'type' => 'button',
                                            'data-toggle' => 'collapse',
                                            'data-target' => '#navbarCollapse',
                                            'aria-controls' => 'navbarCollapse',
                                            'aria-expanded' => 'false',
                                            'aria-label' => 'Toggle navigation'
                                        ],
                                        'children' => [
                                            [
                                                'tag' => 'span',
                                                'attributes' => ['class' => 'navbar-toggler-icon']
                                            ]
                                        ]
                                    ],
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'header-icon_list'],
                                        'children' => [
                                            [
                                                'field' => 'social_profile',
                                            ],
                                        ]
                                    ],
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'header-logo navbar-brand'],
                                        'children' => [
                                            [
                                                'field' => 'logo'
                                            ]
                                        ]
                                    ],
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'header-actions'],
                                        'children' => [
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-action d-none d-md-block'],
                                                'children' => [
                                                    [
                                                        'field' => 'action'
                                                    ]
                                                ]
                                            ],
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-search_button d-none d-md-block'],
                                                'children' => [
                                                    [
                                                        'field' => 'search_button'
                                                    ]
                                                ]
                                            ],
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-account_button'],
                                                'children' => [
                                                    [
                                                        'field' => 'account_button'
                                                    ]
                                                ]
                                            ],
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-cart_button'],
                                                'children' => [
                                                    [
                                                        'field' => 'cart_button'
                                                    ]
                                                ]
                                            ],
                                        ]
                                    ],
                                ]
                            ],
                            [
                                'tag' => 'div',
                                'attributes' => ['class' => 'header-row'],
                                'children' => [
                                    [
                                        'tag' => 'div',
                                        'attributes' => ['class' => 'collapse navbar-collapse', 'id' => 'navbarCollapse'],
                                        'children' => [
                                            [
                                                'field' => 'menu',
                                            ],
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-icon_list d-none d-sm-block d-md-none'],
                                                'children' => [
                                                    [
                                                        'field' => 'social_profile'
                                                    ]
                                                ]
                                            ],
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-search d-none d-sm-block d-md-none'],
                                                'children' => [
                                                    [
                                                        'field' => 'search'
                                                    ]
                                                ]
                                            ],
                                            [
                                                'tag' => 'div',
                                                'attributes' => ['class' => 'header-action d-none d-sm-block d-md-none'],
                                                'children' => [
                                                    [
                                                        'field' => 'action'
                                                    ]
                                                ]
                                            ],
                                        ]
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'fields' => [
                [
                    'type' => 'menu',
                    'name' => 'menu',
                    'label' => 'templates.menu',
                ],
                [
                    'type' => 'icon_list',
                    'name' => 'social_profile',
                    'label' => 'templates.social_profile',
                ],
                [
                    'type' => 'button',
                    'name' => 'action',
                    'label' => 'templates.action_link',
                ],
                [
                    'type' => 'search_button',
                    'name' => 'search_button',
                    'label' => 'templates.account_button',
                ],
            ],
        ],
        [
            'id' => 5,
            'structure' => [
                'tag' => 'header',
            ],
            'fields' => [
                [
                    'type' => 'menu',
                    'name' => 'menu',
                    'label' => 'templates.menu',
                ],
                [
                    'type' => 'search_button',
                    'name' => 'search_button',
                    'label' => 'templates.account_button',
                ],
            ],
        ],
        [
            'id' => 6,
            'structure' => [
                'tag' => 'header',
            ],
            'fields' => [
                [
                    'type' => 'text',
                    'name' => 'phone',
                    'label' => 'templates.phone',
                ],
                [
                    'type' => 'text',
                    'name' => 'email',
                    'label' => 'templates.email',
                ],
                [
                    'type' => 'icon_list',
                    'name' => 'social_profile',
                    'label' => 'templates.social_profile',
                ],
                [
                    'type' => 'button',
                    'name' => 'action',
                    'label' => 'templates.action_link',
                ],
            ],
        ],
        [
            'id' => 7,
            'structure' => [
                'tag' => 'header',
            ],
            'fields' => [
                [
                    'type' => 'text',
                    'name' => 'phone',
                    'label' => 'templates.phone',
                ],
                [
                    'type' => 'text',
                    'name' => 'email',
                    'label' => 'templates.email',
                ],
                [
                    'type' => 'icon_list',
                    'name' => 'social_profile',
                    'label' => 'templates.social_profile',
                ],
                [
                    'type' => 'button',
                    'name' => 'action',
                    'label' => 'templates.action_link',
                ],
            ],
        ],
        [
            'id' => 8,
            'structure' => [
                'tag' => 'header',
            ],
            'fields' => [
                [
                    'type' => 'menu',
                    'name' => 'menu',
                    'label' => 'templates.menu',
                ],
                [
                    'type' => 'text',
                    'name' => 'phone',
                    'label' => 'templates.phone',
                ],
                [
                    'type' => 'text',
                    'name' => 'email',
                    'label' => 'templates.email',
                ],
                [
                    'type' => 'button',
                    'name' => 'action',
                    'label' => 'templates.action_link',
                ],
            ],
        ],
    ]
];
