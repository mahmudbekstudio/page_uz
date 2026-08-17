<?php

namespace Database\Seeders;

use App\Dto\Admin\User\CreateData;
use App\Helpers\DataFormat;
use App\Repositories\CategoryRepository;
use App\Repositories\FileRepository;
use App\Repositories\FolderRepository;
use App\Repositories\MenuRepository;
use App\Repositories\PostRepository;
use App\Repositories\TemplateRepository;
use App\Repositories\TypeRepository;
use App\Repositories\UserRepository;
use App\Repositories\WebsiteRepository;
use App\Services\Admin\CategoryService;
use App\Services\Admin\FolderFileService;
use App\Services\Admin\MenuService;
use App\Services\Admin\PostService;
use App\Services\Admin\TemplateService;
use App\Services\Admin\TypeService;
use App\Services\Admin\UserService;
use App\Services\Admin\WebsiteService;
use Illuminate\Database\Seeder;
use App\Models\Website;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use App\Services\AuthService;
use App\Models\User;

class WebsitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $domainPath = config_path() . '/domain.php';
        if (!file_exists($domainPath)) {
            $file = fopen($domainPath, 'w+');
            fwrite($file, "<?php\nreturn [];");
            fclose($file);
        }

        $websites = config('website');
        $websiteService = app(WebsiteService::class);
        $userService = app(UserService::class);
        $typeService = app(TypeService::class);
        $templateService = app(TemplateService::class);
        $postService = app(PostService::class);
        $categoryService = app(CategoryService::class);
        $currentUser = null;

        $userRepository = UserRepository::getInstance();
        $typeRepository = app(TypeRepository::class);
        $templateRepository = app(TemplateRepository::class);
        $postRepository = app(PostRepository::class);
        $categoryRepository = app(CategoryRepository::class);

        foreach ($websites as $websiteKey => $website) {
            $websiteInstance = $websiteService->create($website['website']['data'], Arr::get($website['website'], 'metas', []));
            WebsiteRepository::getInstance()->setCurrent($websiteInstance->id);
            $itemsById = [];

            // create users
            UserService::createDefaultRoles();
            $users = Arr::get($website, 'users', []);
            foreach ($users as $user) {
                $metas = [];

                foreach ($user['metas'] as $meta) {
                    $metas[$meta['meta_key']] = $meta;
                }

                $userInstance = $userRepository->getByEmail($user['data']['email']);
                if (!$userInstance) {
                    $userInstance = $userService->create(new CreateData([
                        'email' => $user['data']['email'],
                        'first_name' => Arr::get($metas, 'first_name.meta_value', ''),
                        'last_name' => Arr::get($metas, 'last_name.meta_value', ''),
                        'password' => $user['data']['password'],
                        'role' => $user['role'],
                        'status' => $user['data']['status'],
                    ]));
                }

                if (isset($user['id'])) {
                    $itemsById['user#' . $user['id']] = $userInstance->toArray();
                }

                if (is_null($currentUser) && $user['role'] === User::ROLE_SUPER_ADMIN) {
                    app(AuthService::class)->loginById($userInstance->id);
                }
            }

            // create types
            $types = Arr::get($website, 'types', []);
            foreach ($types as $type) {
                $data = [
                    'title' => $type['title'],
                    'name' => $type['name'],
                    'status' => $type['status'],
                    'type' => $type['type'],
                    'has_parent' => $type['has_parent'],
                ];

                if (isset($type['child_of']) && Arr::has($itemsById, 'type#' . $type['child_of'])) {
                    $childOfId = Arr::get($itemsById, 'type#' . $type['child_of']);
                    $childOfLabel = Arr::get($itemsById, 'type#' . str_replace('.id', '.title', $type['child_of']));
                    $data['child_of'] = $childOfId;
                    $type['structure'] = initChildOfField($type['structure'], $childOfId, $childOfLabel);
                }

                $data['structure'] = $type['structure'];
                $data['fields'] = getStructureFields($type['structure']);

                $typeInstance = $typeRepository->getByName($data['name']);
                if (!$typeInstance) {
                    $typeInstance = $typeService->create($data);
                }

                if (isset($type['id'])) {
                    $itemsById['type#' . $type['id']] = $typeInstance->toArray();
                }
            }

            // create templates
            $templates = Arr::get($website, 'templates', []);
            foreach ($templates as $template) {
                if (empty($template['content'])) {
                    $defaultData = $templateService->getDefaultData($template['type']);
                    $template['content'] = $defaultData['content'];
                    $template['params'] = $defaultData['params'];
                }
                $data = [
                    'name' => $template['name'],
                    'type' => $template['type'],
                    'content' => $template['content'],
                    'params' => $template['params'],
                ];

                if (isset($template['layout_id']) && Arr::has($itemsById, 'template#' . $template['layout_id'])) {
                    $data['layout_id'] = Arr::get($itemsById, 'template#' . $template['layout_id']);
                }

                if (isset($template['type_id']) && Arr::has($itemsById, 'type#' . $template['type_id'])) {
                    $data['type_id'] = Arr::get($itemsById, 'type#' . $template['type_id']);
                }

                $templateInstance = $templateRepository
                    ->where('name', json_encode($data['name']))
                    ->where('type', $data['type'])
                    ->first();
                if (!$templateInstance) {
                    $templateInstance = $templateService->create($data);
                }

                if (isset($template['id'])) {
                    $itemsById['template#' . $template['id']] = $templateInstance;
                }
            }

            // create pages
            $pages = Arr::get($website, 'pages', []);
            foreach ($pages as $page) {
                //$typeId = explode('.', $page['type_id'])[0];
                //$type = $itemsById['type#' . $typeId];
                $data = [
                    'status' => $page['status'],
                    'routeName' => $page['routeName'],
                ];

                $isPost = $page['type'] === 'post';
                $itemKey = $isPost ? 'post#' : 'category#';

                if (isset($page['childOf']) && Arr::has($itemsById, 'type#' . $page['childOf'])) {
                    $data['childOf'] = Arr::get($itemsById, 'type#' . $page['childOf']);
                }

                if (isset($page['template']) && Arr::has($itemsById, 'template#' . $page['template'])) {
                    $data['template'] = Arr::get($itemsById, 'template#' . $page['template']);
                }

                if (isset($page['parent']) && Arr::has($itemsById, $itemKey . $page['parent'])) {
                    $data['parent'] = Arr::get($itemsById, $itemKey . $page['parent']);
                }

                $typeName = $itemsById['type#' . explode('.', $page['type_id'])[0]]['name'];
                $typeId = Arr::get($itemsById, 'type#' . $page['type_id']);
                $routeName = $typeName . '/' . $data['routeName'];
                $pageRepository = $isPost ? $postRepository : $categoryRepository;
                $pageInstance = $pageRepository
                    ->where('type_id', $typeId)
                    ->where('url', $routeName)
                    ->first();
                if (!$pageInstance) {
                    $service = $isPost ? $postService : $categoryService;
                    $pageInstance = $service->create(Arr::get($itemsById, 'type#' . $page['type_id']), $data);
                }

                if (isset($page['metas']) && !empty($page['metas'])) {
                    foreach ($page['metas'] as $meta) {
                        $metaInstance = $pageInstance->metas()->where('meta_key', $meta['meta_key'])->first();
                        if (!$metaInstance) {
                            $metaInstance = $pageInstance->metas()->create(
                                [
                                    'meta_key' => $meta['meta_key'],
                                    'meta_format' => $meta['meta_format'],
                                    'meta_value' => $meta['meta_value'],
                                ]
                            );
                        }
                        $itemsById[$itemKey . '_meta_' . $page['id'] . '_' . $meta['meta_key']] = $metaInstance->toArray();
                    }
                }

                if (isset($page['id'])) {
                    $itemsById[$itemKey . $page['id']] = $pageInstance->toArray();
                }

                if (isset($page['website_metas']) && !empty($page['website_metas'])) {
                    $websiteMetas = [];
                    foreach ($page['website_metas'] as $websiteMeta) {
                        $value = Arr::get($itemsById, $itemKey . $websiteMeta['meta_value'], $websiteMeta['meta_value']);
                        $websiteMetas[] = [...$websiteMeta, 'meta_value' => DataFormat::toString($value, $websiteMeta['meta_format'])];
                    }
                    $websiteService->createMetas($websiteInstance->id, $websiteMetas);
                }
            }

            // folder and files
            $this->createFolderFiles(
                $websiteKey,
                //Arr::get($website, 'folders', []),
                $itemsById,
                app(FolderFileService::class)
            );

            $menuRepository = app(MenuRepository::class);
            $menusCount = $menuRepository->all(['id'])->count();
            if (!$menusCount) {
                $this->createMenu(Arr::get($website, 'menus', []), $itemsById);
            }

            $currentUser = null;
        }
        /*$mainWebsite = config('app.main_website');
        $websiteService = app(WebsiteService::class);
        $mainWebsiteData = [
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
        $websiteService->create($mainWebsiteData['data'], $mainWebsiteData['metas']);

        $list = isProd() ? [] : $this->getDevList();

        foreach ($list as $item) {
            $websiteService->create($item['data'], Arr::get($item, 'metas', []));
        }*/
    }

    private function createFolderFiles(
        $websiteKey,
        //$folders,
        &$itemsById,
        FolderFileService $folderFileService,
    ) {
        $rootPath = 'files/' . $websiteKey;
        $list = Storage::disk('resources')->allDirectories($rootPath);
        $folderFileService = app(FolderFileService::class);
        $folderRepository = app(FolderRepository::class);
        $fileRepository = app(FileRepository::class);


        $addedFolders = [];
        foreach ($list as $folder) {
            $folderPath = str_replace($rootPath . '/', '', $folder);
            $folderPathList = explode('/', $folderPath);
            $folderName = array_pop($folderPathList);
            $parentPath = implode('/', $folderPathList);
            $parentId = 0;

            if (!empty($parentPath)) {
                $parentId = $addedFolders[$parentPath]['id'];
            }

            $addedFolders[$folderPath] = $folderFileService->createFolder($parentId, $folderName);

            if ($addedFolders[$folderPath] === false) {
                $addedFolders[$folderPath] = $folderRepository
                    ->findWhere(['parent_id' => $parentId, 'name' => $folderName, 'is_local' => false])
                    ->first()
                    ->toArray();
            }

            $itemsById['folder#' . $addedFolders[$folderPath]['id']] = $addedFolders[$folderPath];
        }

        $list = Storage::disk('resources')->allFiles($rootPath);
        foreach ($list as $file) {
            $fileInfo = pathinfo($file);
            $filePath = str_replace($rootPath . '/', '', $file);
            $fileFolderPathList = explode('/', $filePath);
            array_pop($fileFolderPathList);
            $fileFolderPath = implode('/', $fileFolderPathList);
            $fileInstance = $fileRepository
                ->where('folder_id', $addedFolders[$fileFolderPath]['id'])
                ->where('name', $fileInfo['filename'])
                ->where('extension', $fileInfo['extension'])
                ->first();
            if (!$fileInstance) {
                $uploadedFile = pathToUploadedFile(resource_path($file));
                $fileInstance = $folderFileService->uploadFile($addedFolders[$fileFolderPath]['id'], $uploadedFile);
            }

            $itemsById['file#' . $fileInstance['id']] = $fileInstance->toArray();
        }

        $folderFileService->resetRootPath();
    }

    private function createMenu(array $menus, $itemsById)
    {
        foreach ($menus as $menu) {
            app(MenuService::class)->create([
                'name' => $menu['name'],
                'items' => $this->getMenuItems($menu['items'], $itemsById)
            ]);
        }
    }

    private function getMenuItems(array $items, $itemsById)
    {
        $list = [];
        foreach ($items as $item) {
            $children = isset($item['children']) && !empty($item['children']) ? $this->getMenuItems($item['children'], $itemsById) : [];
            if ($item['type'] === 'custom') {
                $menuItem = [
                    'id' => 'custom',
                    'title' => $item['title'],
                    'url' => $item['url'],
                    'type_id' => 0,
                    'canHasChild' => true,
                    'children' => $children,
                ];
            } else {
                $menuItem = [
                    'id' => $itemsById[$item['type'] . '#' . $item['key']]['id'],
                    'title' => json_decode($itemsById[$item['type'] . '#_meta_' . $item['key'] . '_title']['meta_value']),
                    'url' => $itemsById[$item['type'] . '#' . $item['key']]['url'],
                    'type_id' => $itemsById[$item['type'] . '#' . $item['key']]['type_id'],
                    'canHasChild' => true,
                    'children' => $children,
                ];
            }

            $list[] = $menuItem;
        }

        return $list;
    }

    /*private function getDevList() {
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
    }*/
}
