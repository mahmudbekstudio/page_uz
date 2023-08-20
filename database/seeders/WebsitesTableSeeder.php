<?php

namespace Database\Seeders;

use App\Helpers\DataFormat;
use App\Models\Post;
use App\Models\PostMeta;
use App\Models\Route;
use App\Models\Type;
use App\Models\TypeRouteStructure;
use Illuminate\Database\Seeder;
use App\Models\Website;
use App\Models\WebsiteMeta;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class WebsitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mainWebsite = config('app.main_website');
        $mainWebsiteData = [ // database\seeders\WebsitesTableSeeder.php:28
            "data" => [
                "id" => 1,
                "status" => Website::STATUS_ACTIVE,
                "domain" => $mainWebsite,
                "type" => Website::TYPE_MAIN,
                "group_id" => 1,
                "domain_id" => null,
            ],
            "metas" => [
                0 => [
                    "meta_key" => "name",
                    "meta_value" => $mainWebsite,
                    "user_id" => 0,
                ]
            ]
        ];
        $this->createWebsite($mainWebsiteData);

        $list = isProd() ? [] : $this->getDevList();

        foreach ($list as $item) {
            $this->createWebsite($item);
        }
    }

    private function createWebsite($item) {
        $website = Website::firstOrCreate($item['data']);
        if($item['data']['type'] == Website::TYPE_MAIN) {
            Artisan::call('domain:add ' . $website->id);
            update_dotenv(storage_path('domains/env/.env.' . $website->id), [
                'APP_KEY' => generateRandomKey(),
                'JWT_SECRET' => randomString64()
            ]);

            createStorageTemplateDir('', $website->id);
            //createStorageTemplateDir('layout', $website->id);
            createStorageTemplateDir('post', $website->id);
            createStorageTemplateDir('category', $website->id);
            createStorageTemplateFile('post', '0', $website->id);
            createStorageTemplateFile('category', '0', $website->id);
        }

        if(isset($item['metas'])) {
            foreach($item['metas'] as $meta) {
                $meta['website_id'] = $website->id;
                WebsiteMeta::firstOrCreate($meta);
            }
        }
        $lang = [
            [
                'meta_key' => 'languages_list',
                'meta_value' => DataFormat::toString(config('app.locale_list'), DataFormat::FORMAT_ARRAY),
                'meta_format' => DataFormat::FORMAT_ARRAY,
                'user_id' => 0
            ],
            [
                'meta_key' => 'language',
                'meta_value' => config('app.default_locale'),
                'user_id' => 0
            ],
        ];

        foreach($lang as $meta) {
            $meta['website_id'] = $website->id;
            WebsiteMeta::firstOrCreate($meta);
        }

        WebsiteMeta::firstOrCreate([
            'meta_key' => 'root-folder-path',
            'meta_value' => generateRootFolderName($website->id),
            'user_id' => 0,
            'website_id' => $website->id,
        ]);

        $type = Type::firstOrCreate([
            'user_id' => 0,
            'website_id' => $website->id,
            'status' => 1,
            'type' => Type::TYPE_POST,
            'has_parent' => 1,
            'child_of' => 0,
        ], [
            'title' => json_encode(['en' => 'Page', 'ru' =>  'Страница', 'uz' => 'Sahifa']),
            'name' => config('app.main_page.name'),
            'structure' => json_decode(config('app.main_page.structure'), true),
            'fields' => json_decode(config('app.main_page.fields'), true),
        ]);
        $pageHome = Post::firstOrCreate([
            'user_id' => 0,
            'website_id' => $website->id,
            'category_id' => 0,
            'template_id' => 0,
            'status' => 1,
            'parent_id' => 0,
            'type_id' => $type->id,
            'url' => 'page/home',
        ]);
        Route::firstOrCreate([
            'website_id' => $website->id,
            'name' => 'home',
            'parent_id' => $pageHome->id,
            'type_id' => $type->id,
        ]);
        TypeRouteStructure::firstOrCreate([
            'website_id' => $website->id,
            'type_id' => $type->id,
            'parent_id' => $pageHome->id,
        ], [
            'params' => [],
            'structure' => []
        ]);
        PostMeta::firstOrCreate([
            'user_id' => 0,
            'website_id' => $website->id,
            'post_id' => $pageHome->id,
            'meta_format' => DataFormat::FORMAT_STRING,
            'meta_key' => 'title',
        ], [
            'meta_value' => json_encode(['en' => 'Home', 'ru' => 'Главная', 'uz' => 'Bosh sahifa']),
        ]);
        WebsiteMeta::firstOrCreate([
            'meta_key' => 'pageHome',
            'meta_value' => $pageHome->id,
            'meta_format' => DataFormat::FORMAT_INT,
            'user_id' => 0,
            'website_id' => $website->id,
        ]);
        $page404 = Post::firstOrCreate([
            'user_id' => 0,
            'website_id' => $website->id,
            'category_id' => 0,
            'template_id' => 0,
            'status' => 1,
            'parent_id' => 0,
            'type_id' => $type->id,
            'url' => 'page/404',
        ]);
        Route::firstOrCreate([
            'website_id' => $website->id,
            'name' => '404',
            'parent_id' => $page404->id,
            'type_id' => $type->id,
        ]);
        TypeRouteStructure::firstOrCreate([
            'website_id' => $website->id,
            'type_id' => $type->id,
            'parent_id' => $page404->id,
        ], [
            'params' => [],
            'structure' => []
        ]);
        PostMeta::firstOrCreate([
            'user_id' => 0,
            'website_id' => $website->id,
            'post_id' => $page404->id,
            'meta_format' => DataFormat::FORMAT_STRING,
            'meta_key' => 'title',
            'meta_value' => '404'
        ]);
        WebsiteMeta::firstOrCreate([
            'meta_key' => 'page404',
            'meta_value' => $page404->id,
            'meta_format' => DataFormat::FORMAT_INT,
            'user_id' => 0,
            'website_id' => $website->id,
        ]);
    }

    private function getDevList() {
        $domainPostfix = config('app.main_website');
        $ids = [
            Website::STATUS_NOT_CONFIRMED => 2,
            Website::STATUS_ACTIVE => 3,
            Website::STATUS_BLOCKED => 4,
            Website::STATUS_TEMPORARILY_CLOSED => 5,
            Website::STATUS_FORBIDDEN => 6,
            Website::STATUS_CLOSED => 7,
        ];
        $list = [];

        $list[] = [
            'data' => [
                'id' => $ids[Website::STATUS_NOT_CONFIRMED],
                'status' => Website::STATUS_NOT_CONFIRMED,
                'domain' => 'test-main-' . Website::STATUS_NOT_CONFIRMED . '.' . $domainPostfix,
                'type' => Website::TYPE_MAIN,
                'group_id' => $ids[Website::STATUS_NOT_CONFIRMED],
                'domain_id' => null
            ],
            'metas' => [
                [
                    'meta_key' => 'name',
                    'meta_value' => 'Test main ' . Website::STATUS_NOT_CONFIRMED,
                    'user_id' => 0
                ]
            ]
        ];

        $list[] = [
            'data' => [
                'id' => $ids[Website::STATUS_ACTIVE],
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test-main-' . Website::STATUS_ACTIVE . '.' . $domainPostfix,
                'type' => Website::TYPE_MAIN,
                'group_id' => $ids[Website::STATUS_ACTIVE],
                'domain_id' => null
            ],
            'metas' => [
                [
                    'meta_key' => 'name',
                    'meta_value' => 'Test main ' . Website::STATUS_ACTIVE,
                    'user_id' => 0
                ]
            ]
        ];

        $list[] = [
            'data' => [
                'id' => $ids[Website::STATUS_BLOCKED],
                'status' => Website::STATUS_BLOCKED,
                'domain' => 'test-main-' . Website::STATUS_BLOCKED . '.' . $domainPostfix,
                'type' => Website::TYPE_MAIN,
                'group_id' => $ids[Website::STATUS_BLOCKED],
                'domain_id' => null
            ],
            'metas' => [
                [
                    'meta_key' => 'name',
                    'meta_value' => 'Test main ' . Website::STATUS_BLOCKED,
                    'user_id' => 0
                ]
            ]
        ];

        $list[] = [
            'data' => [
                'id' => $ids[Website::STATUS_TEMPORARILY_CLOSED],
                'status' => Website::STATUS_TEMPORARILY_CLOSED,
                'domain' => 'test-main-' . Website::STATUS_TEMPORARILY_CLOSED . '.' . $domainPostfix,
                'type' => Website::TYPE_MAIN,
                'group_id' => $ids[Website::STATUS_TEMPORARILY_CLOSED],
                'domain_id' => null
            ],
            'metas' => [
                [
                    'meta_key' => 'name',
                    'meta_value' => 'Test main ' . Website::STATUS_TEMPORARILY_CLOSED,
                    'user_id' => 0
                ]
            ]
        ];

        $list[] = [
            'data' => [
                'id' => $ids[Website::STATUS_FORBIDDEN],
                'status' => Website::STATUS_FORBIDDEN,
                'domain' => 'test-main-' . Website::STATUS_FORBIDDEN . '.' . $domainPostfix,
                'type' => Website::TYPE_MAIN,
                'group_id' => $ids[Website::STATUS_FORBIDDEN],
                'domain_id' => null
            ],
            'metas' => [
                [
                    'meta_key' => 'name',
                    'meta_value' => 'Test main ' . Website::STATUS_FORBIDDEN,
                    'user_id' => 0
                ]
            ]
        ];

        $list[] = [
            'data' => [
                'id' => $ids[Website::STATUS_CLOSED],
                'status' => Website::STATUS_CLOSED,
                'domain' => 'test-main-' . Website::STATUS_CLOSED . '.' . $domainPostfix,
                'type' => Website::TYPE_MAIN,
                'group_id' => $ids[Website::STATUS_CLOSED],
                'domain_id' => null
            ],
            'metas' => [
                [
                    'meta_key' => 'name',
                    'meta_value' => 'Test main ' . Website::STATUS_CLOSED,
                    'user_id' => 0
                ]
            ]
        ];

        $list[] = [
            'data' => [
                'id' => 8,
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test1.' . $domainPostfix,
                'type' => Website::TYPE_MAIN,
                'group_id' => 8,
                'domain_id' => null
            ],
            'metas' => [
                [
                    'meta_key' => 'name',
                    'meta_value' => 'Test 1 ' . Website::STATUS_ACTIVE,
                    'user_id' => 0
                ]
            ]
        ];

        $list[] = [
            'data' => [
                'id' => 9,
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test2.' . $domainPostfix,
                'type' => Website::TYPE_MAIN,
                'group_id' => 9,
                'domain_id' => null
            ],
            'metas' => [
                [
                    'meta_key' => 'name',
                    'meta_value' => 'Test 2 ' . Website::STATUS_ACTIVE,
                    'user_id' => 0
                ]
            ]
        ];

        $list[] = [
            'data' => [
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test-alias-' . Website::STATUS_NOT_CONFIRMED . '.' . $domainPostfix,
                'type' => Website::TYPE_ALIAS,
                'group_id' => null,
                'domain_id' => $ids[Website::STATUS_NOT_CONFIRMED]
            ]
        ];
        $list[] = [
            'data' => [
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test-redirect-' . Website::STATUS_NOT_CONFIRMED . '.' . $domainPostfix,
                'type' => Website::TYPE_REDIRECT,
                'group_id' => null,
                'domain_id' => $ids[Website::STATUS_NOT_CONFIRMED]
            ]
        ];
        $list[] = [
            'data' => [
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test-alias-' . Website::STATUS_ACTIVE . '.' . $domainPostfix,
                'type' => Website::TYPE_ALIAS,
                'group_id' => null,
                'domain_id' => $ids[Website::STATUS_ACTIVE]
            ]
        ];
        $list[] = [
            'data' => [
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test-redirect-' . Website::STATUS_ACTIVE . '.' . $domainPostfix,
                'type' => Website::TYPE_REDIRECT,
                'group_id' => null,
                'domain_id' => $ids[Website::STATUS_ACTIVE]
            ]
        ];
        $list[] = [
            'data' => [
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test-alias-' . Website::STATUS_BLOCKED . '.' . $domainPostfix,
                'type' => Website::TYPE_ALIAS,
                'group_id' => null,
                'domain_id' => $ids[Website::STATUS_BLOCKED]
            ]
        ];
        $list[] = [
            'data' => [
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test-redirect-' . Website::STATUS_BLOCKED . '.' . $domainPostfix,
                'type' => Website::TYPE_REDIRECT,
                'group_id' => null,
                'domain_id' => $ids[Website::STATUS_BLOCKED]
            ]
        ];
        $list[] = [
            'data' => [
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test-alias-' . Website::STATUS_TEMPORARILY_CLOSED . '.' . $domainPostfix,
                'type' => Website::TYPE_ALIAS,
                'group_id' => null,
                'domain_id' => $ids[Website::STATUS_TEMPORARILY_CLOSED]
            ]
        ];
        $list[] = [
            'data' => [
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test-redirect-' . Website::STATUS_TEMPORARILY_CLOSED . '.' . $domainPostfix,
                'type' => Website::TYPE_REDIRECT,
                'group_id' => null,
                'domain_id' => $ids[Website::STATUS_TEMPORARILY_CLOSED]
            ]
        ];
        $list[] = [
            'data' => [
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test-alias-' . Website::STATUS_FORBIDDEN . '.' . $domainPostfix,
                'type' => Website::TYPE_ALIAS,
                'group_id' => null,
                'domain_id' => $ids[Website::STATUS_FORBIDDEN]
            ]
        ];
        $list[] = [
            'data' => [
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test-redirect-' . Website::STATUS_FORBIDDEN . '.' . $domainPostfix,
                'type' => Website::TYPE_REDIRECT,
                'group_id' => null,
                'domain_id' => $ids[Website::STATUS_FORBIDDEN]
            ]
        ];
        $list[] = [
            'data' => [
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test-alias-' . Website::STATUS_CLOSED . '.' . $domainPostfix,
                'type' => Website::TYPE_ALIAS,
                'group_id' => null,
                'domain_id' => $ids[Website::STATUS_CLOSED]
            ]
        ];
        $list[] = [
            'data' => [
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test-redirect-' . Website::STATUS_CLOSED . '.' . $domainPostfix,
                'type' => Website::TYPE_REDIRECT,
                'group_id' => null,
                'domain_id' => $ids[Website::STATUS_CLOSED]
            ]
        ];

        return $list;
    }
}
