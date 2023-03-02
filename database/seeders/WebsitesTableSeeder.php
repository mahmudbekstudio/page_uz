<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Website;
use App\Models\WebsiteMeta;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class WebsitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = isProd() ? [] : $this->getDevList();

        foreach ($list as $item) {
            $website = Website::firstOrCreate($item['data']);
            if($item['data']['type'] == Website::TYPE_MAIN) {
                Artisan::call('domain:add ' . $website->id);
                update_dotenv(storage_path('domains/env/.env.' . $website->id), [
                    'APP_KEY' => generateRandomKey(),
                    'JWT_SECRET' => randomString64()
                ]);
            }

            if(isset($item['metas'])) {
                foreach($item['metas'] as $meta) {
                    $meta['website_id'] = $website->id;
                    WebsiteMeta::firstOrCreate($meta);
                }
            }
        }
    }

    private function getDevList() {
        $domainPostfix = config('app.main_website');
        $ids = [
            Website::STATUS_NOT_CONFIRMED => 1,
            Website::STATUS_ACTIVE => 2,
            Website::STATUS_BLOCKED => 3,
            Website::STATUS_TEMPORARILY_CLOSED => 4,
            Website::STATUS_FORBIDDEN => 5,
            Website::STATUS_CLOSED => 6,
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
                'id' => 7,
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test1.' . $domainPostfix,
                'type' => Website::TYPE_MAIN,
                'group_id' => 7,
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
                'id' => 8,
                'status' => Website::STATUS_ACTIVE,
                'domain' => 'test2.' . $domainPostfix,
                'type' => Website::TYPE_MAIN,
                'group_id' => 8,
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
