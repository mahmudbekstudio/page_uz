<?php

use App\Models\Website;
use App\Models\User;
use App\Helpers\DataFormat;
use App\Models\Template;

$main_website = config('app.main_website');

return [
    'website' => [
        'data' => [
            'status' => Website::STATUS_ACTIVE,
            'domain' => $main_website,
            'type' => Website::TYPE_MAIN,
        ],
        'metas' => [
            [
                'meta_key' => 'name',
                'meta_value' => config('app.name'),
            ]
        ],
    ],
    'users' => [
        [
            'id' => 'user_main_super_admin',
            'data' => [
                'status' => User::STATUS_ACTIVE,
                'email' => 'main_' . User::ROLE_SUPER_ADMIN . '@' . $main_website,
                'password' => 'Password123!',
            ],
            'role' => User::ROLE_SUPER_ADMIN,
            'metas' => [
                [
                    'meta_key' => 'first_name',
                    'meta_value' => 'Admin',
                    'meta_format' => DataFormat::FORMAT_STRING,
                ],
            ],
        ]
    ],
    'types' => [
        [
            'id' => 'type_page',
            'type' => 'post',
            'status' => true,
            'title' => ['en' => 'Page', 'ru' => 'Страница', 'uz' => 'Sahifa'],
            'name' => config('app.main_page_type'),
            'has_parent' => true,
            'structure' => config('type-structure.page'),
        ],
        [
            'id' => 'type_category',
            'type' => 'category',
            'status' => true,
            'title' => ['en' => 'Category', 'ru' => 'Категория', 'uz' => 'Kategoriya'],
            'name' => config('app.main_category_type'),
            'has_parent' => false,
            'structure' => config('type-structure.category'),
        ],
        [
            'id' => 'type_post',
            'type' => 'post',
            'child_of' => 'type_category.id',
            'status' => true,
            'title' => ['en' => 'Post', 'ru' => 'Пост', 'uz' => 'Post'],
            'name' => config('app.main_post_type'),
            'has_parent' => false,
            'structure' => config('type-structure.post'),
        ],
    ],
    'folders' => [
        // get all folder and files from resources/files/{key of website}
        /*[
            'name' => 'Folder1',
            'folders' => [
                [
                    'name' => 'Folder1_1'
                ],
                [
                    'name' => 'Folder1_2',
                    'files' => []
                ]
            ],
        ],
        [
            'name' => 'Folder2',
            //'folders' => [],
            'files' => [
//                'pathtofile1',
//                'pathtofile2'
            ]
        ]*/
    ],
    'menus' => [
        [
            'name' => ['en' => 'Menu', 'ru' => 'Меню', 'uz' => 'Menyu'],
            'items' => [
                [
                    'type' => 'post',
                    'key' => 'page_home'
                ],
                [
                    'type' => 'category',
                    'key' => 'page_category_1',
                    'children' => [
                        [
                            'type' => 'post',
                            'key' => 'page_category_post_1'
                        ],
                    ]
                ],
                [
                    'type' => 'category',
                    'key' => 'page_category_2',
                ],
                [
                    'type' => 'custom',
                    'title' => ['en' => 'Custom', 'ru' => 'Кастом', 'uz' => 'Kastom'],
                    'url' => ['en' => '#1', 'ru' => '#2', 'uz' => '#3'],
                ],
            ],
        ],
    ],
    'templates' => [
        [
            'id' => 'template_layout_1',
            'name' => ['en' => 'Layout'],
            'type' => Template::TYPE_LAYOUT,
            'content' => [
                //TODO: theme content
            ],
            'params' => [
                //TODO: theme params
            ],
        ],
        [
            'id' => 'template_page_1',
            'name' => ['en' => 'Page'],
            'type' => Template::TYPE_POST,
            'type_id' => 'type_page.id',
            'layout_id' => 'template_layout_1.id',
            'content' => [
                //TODO: theme content
            ],
            'params' => [
                //TODO: theme params
            ],
        ],
        [
            'id' => 'template_category_1',
            'name' => ['en' => 'Category'],
            'type' => Template::TYPE_CATEGORY,
            'type_id' => 'type_category.id',
            'layout_id' => 'template_layout_1.id',
            'content' => [
                //TODO: theme content
            ],
            'params' => [
                //TODO: theme params
            ],
        ],
        [
            'id' => 'template_post_1',
            'name' => ['en' => 'Post'],
            'type' => Template::TYPE_POST,
            'type_id' => 'type_post.id',
            'layout_id' => 'template_layout_1.id',
            'content' => [
                //TODO: theme content
            ],
            'params' => [
                //TODO: theme params
            ],
        ],
    ],
    'pages' => [
        [
            'id' => 'page_home',
            'type' => 'post',
            'childOf' => '',
            'template' => 'template_page_1.id',
            'status' => 1,
            'parent' => '',
            'type_id' => 'type_page.id',
            'routeName' => 'home',
            'metas' => [
                [
                    'meta_key' => 'title',
                    'meta_value' => DataFormat::toString(['en' => 'Home page'], DataFormat::FORMAT_STRING),
                    'meta_format' => DataFormat::FORMAT_STRING,
                ]
            ],
            'website_metas' => [
                [
                    'meta_key' => 'pageHome',
                    'meta_value' => 'page_home.id',
                    'meta_format' => DataFormat::FORMAT_INT
                ]
            ],
        ],
        [
            'id' => 'page_404',
            'type' => 'post',
            'childOf' => '',
            'template' => 'template_page_1.id',
            'status' => 1,
            'parent' => '',
            'type_id' => 'type_page.id',
            'routeName' => '404',
            'metas' => [
                [
                    'meta_key' => 'title',
                    'meta_value' => DataFormat::toString(['en' => '404 page'], DataFormat::FORMAT_STRING),
                    'meta_format' => DataFormat::FORMAT_STRING,
                ]
            ],
            'website_metas' => [
                [
                    'meta_key' => 'page404',
                    'meta_value' => 'page_404.id',
                    'meta_format' => DataFormat::FORMAT_INT
                ]
            ],
        ],
        [
            'id' => 'page_category_1',
            'type' => 'category',
            'template' => 'template_category_1.id',
            'status' => 1,
            'parent' => '',
            'type_id' => 'type_category.id',
            'routeName' => 'category1',
            'metas' => [
                [
                    'meta_key' => 'title',
                    'meta_value' => DataFormat::toString(['en' => 'Category 1'], DataFormat::FORMAT_STRING),
                    'meta_format' => DataFormat::FORMAT_STRING,
                ]
            ],
        ],
        [
            'id' => 'page_category_2',
            'type' => 'category',
            'template' => 'template_category_1.id',
            'status' => 1,
            'parent' => 'page_category_1.id',
            'type_id' => 'type_category.id',
            'routeName' => 'category2',
            'metas' => [
                [
                    'meta_key' => 'title',
                    'meta_value' => DataFormat::toString(['en' => 'Category 2'], DataFormat::FORMAT_STRING),
                    'meta_format' => DataFormat::FORMAT_STRING,
                ]
            ],
        ],
       [
           'id' => 'page_category_post_1',
           'type' => 'post',
           'childOf' => 'page_category_1.id',
           'template' => 'template_post_1.id',
           'status' => 1,
           'parent' => '',
           'type_id' => 'type_post.id',
           'routeName' => 'category_post',
           'metas' => [
               [
                   'meta_key' => 'title',
                   'meta_value' => DataFormat::toString(['en' => 'category post'], DataFormat::FORMAT_STRING),
                   'meta_format' => DataFormat::FORMAT_STRING,
               ]
           ],
       ]
    ],
    /*'data' => [
        'status' => Website::STATUS_ACTIVE,
        'domain' => config('app.main_website'),
        'type' => Website::TYPE_MAIN,
        'domain_id' => null
    ],
    'metas' => [
        [
            'meta_key' => 'name',
            'meta_value' => config('app.name'),
            'user_id' => 0
        ]
    ],
    'theme' => [
        'name' => ['en' => 'My theme', 'ru' => 'Моя тема', 'uz' => 'Mening mavzuim'],
        'content' => [
            'bootstrap' => '5.3',
            'swiper_js' => '11',
        ],
        'template' => [
            [
                'post' => [
                    'status' => true,
                    'title' => ['en' => 'Page', 'ru' => 'Страница', 'uz' => 'Sahifa'],
                    'name' => config('app.main_page_type'),
                    'has_parent' => true,
                    'structure' => config('type-structure.page'),
                ]
            ],
            [
                'post' => [
                    'status' => true,
                    'title' => ['en' => 'Post', 'ru' => 'Пост', 'uz' => 'Post'],
                    'name' => 'post',
                    'has_parent' => false,
                ],
                'category' => [
                    'status' => true,
                    'title' => ['en' => 'Category', 'ru' => 'Категория', 'uz' => 'Kategoriya'],
                    'name' => 'category',
                    'has_parent' => false,
                ]
            ]
        ],
    ],*/
];
