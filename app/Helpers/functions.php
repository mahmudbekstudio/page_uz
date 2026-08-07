<?php

use App\Helpers\DataFormat;
use App\Models\WebsiteMeta;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\UserRepository;
use App\Repositories\WebsiteRepository;
use App\Repositories\TypeRepository;
use App\Repositories\RouteRepository;
use Illuminate\Support\Arr;
use App\Repositories\PostRepository;
use App\Repositories\CategoryRepository;
use App\Helpers\GlobalVariable;
use App\Models\Template;
use App\Models\Type;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;

if (! function_exists('route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param  array|string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    function route($name, $parameters = [], $absolute = true)
    {
        dd(22222);
        if(!isset($parameters['domain'])) {
            $parameters['domain'] = $_SERVER['HTTP_HOST'];
        }

        return app('url')->route($name, $parameters, $absolute);
    }
}

if (! function_exists('update_dotenv')) {
    /**
     * Update DotEnv file
     *
     * @param string $filePath
     * @param array $values
     */
    function update_dotenv(string $filePath, array $values)
    {
        $values = array_change_key_case($values, CASE_UPPER);
        $updated = [];
        $lines = file($filePath);
        for($i = 0; $i < count($lines); $i++) {
            $lines[$i] = trim($lines[$i]);

            $pos = strpos($lines[$i], '=');
            $varName = substr($lines[$i], 0, $pos);

            if (isset($values[$varName])) {
                $lines[$i] = $varName . '=' . '"' . str_replace('"', '\"', $values[$varName]) . '"';
                $updated[] = $varName;
            }
        }

        foreach($values as $key => $val) {
            if(!in_array($key, $updated)) {
                if ($key) {
                    $lines[] = $key . '=' . '"' . str_replace('"', '\"', $val) . '"';
                }
            }
        }

        file_put_contents($filePath, implode("\n", array_filter($lines, function ($item) {
            return !!$item;
        })));
    }
}

if (! function_exists('generateRandomKey')) {
    /**
     * Random key generate
     *
     * @return string
     */
    function generateRandomKey()
    {
        return 'base64:' . base64_encode(Encrypter::generateKey(config('app.cipher')));
    }
}

if (! function_exists('randomString64')) {
    /**
     * Random string 64
     *
     * @return string
     */
    function randomString64()
    {
        return Str::random(64);
    }
}

if (! function_exists('responseJson')) {
    /**
     * Response json
     *
     * @param bool $result
     * @param array $message
     * @param array $data
     * @param array $setting
     * @return \Illuminate\Http\JsonResponse
     */
    function responseJson(bool $result, array $message = [], array $data = [], array $setting = []): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'result' => $result,
            'message' => $message,
            'data' => $data,
            'setting' => $setting
        ]);
    }
}

if (! function_exists('responseJsonMessage')) {
    /**
     * Response json message
     *
     * @param bool $result
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    function responseJsonMessage(bool $result, $message, array $setting = []): \Illuminate\Http\JsonResponse
    {
        return responseJson($result, (array)$message, [], $setting);
    }
}

if (! function_exists('responseJsonData')) {
    /**
     * Reponse json data
     *
     * @param bool $result
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    function responseJsonData(bool $result, array $data, array $setting = []): \Illuminate\Http\JsonResponse
    {
        return responseJson($result, [], $data, $setting);
    }
}

if (! function_exists('responseStatus')) {
    /**
     * Reponse json data
     *
     * @param bool $result
     * @return \Illuminate\Http\JsonResponse
     */
    function responseStatus(bool $result, array $setting = []): \Illuminate\Http\JsonResponse
    {
        return responseJson($result, [], [], $setting);
    }
}

if (! function_exists('responseSetting')) {
    /**
     * Response setting
     *
     * @param array $setting
     * @return array
     */
    /*function responseSetting(array $setting): array
    {
        return !empty($setting) ? $setting : ['user' => auth()->user()];
    }*/
}

if (! function_exists('getUserByToken')) {
    /**
     * Get user by token
     *
     * @param string $token
     * @return \App\Models\User|null
     */
    function getUserByToken(string $token)
    {
        if(!$token) {
            return null;
        }

        try {
            dd(JWTAuth::setToken($token));
            //dd(JWTAuth::refresh());
            return JWTAuth::toUser();
        } catch (Exception $e) {
            return null;
        }
    }
}

if (! function_exists('getUserData')) {
    /**
     * Get user data
     *
     * @param \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|int|null $user
     * @return array
     */
    function getUserData($user = null): array
    {
        $userData = [];

        if (is_int($user) && $user > 0) {
            try {
                $user = app(UserRepository::class)->getById($user);
            } catch (Exception $e) {
                $user = null;
            }
        } elseif(is_null($user)) {
            $user = auth()->user();
        }

        if($user) {
            $userData = [
                'id' => $user->id,
                'status' => $user->status,
                'email' => $user->email,
                'role' => $user->role,
                'meta' => [],
                'created_at' => $user->created_at,
            ];

            foreach($user->metas as $meta) {
                $userData['meta'][$meta->meta_key] = DataFormat::toFormat($meta->meta_value, $meta->meta_format);
            }
        }

        return $userData;
    }
}

if (! function_exists('websiteData')) {
    function websiteData($isJson = false)
    {
        $instance = WebsiteRepository::getInstance();
        $website = $instance->getCurrent();
        $storageUrl = Storage::url('');

        if ($storageUrl[strlen($storageUrl) - 1] == '/') {
            $storageUrl = substr($storageUrl, 0, strlen($storageUrl) - 1);
        }

        $data = [
            'id' => $website->id,
            'status' => $website->status,
            'domain' => app()->domain(),
            'created' => $website->created_at,
            'metas' => $instance->getMetas(),
            'fileBaseUrl' => $storageUrl,
            'lang' => getCurrentLang(),
        ];
        return $isJson ? json_encode($data) : $data;
    }
}

if (! function_exists('typeNavigation')) {
    function typeNavigation($isJson = false)
    {
        $posts = [];
        $categories = [];
        $blocks = [];
        $settings = [];
        $postRepository = app(PostRepository::class);
        app(TypeRepository::class)->getActiveList()->each(function ($item) use (&$posts, &$categories, &$blocks, &$settings, $postRepository) {
            if ($item->type === Type::TYPE_POST) {
                $posts[] = [
                    'text' => $item->title,
                    'icon' => 'article',
                    'child_of' => $item->child_of,
                    'children' => [
                        [
                            'text' => 'words.list',
                            'icon' => 'subject',
                            'route' => ['name' => 'post.list', 'params' => ['typeId' => $item->id]],
                            'active' => ['post.edit'],
                        ],
                        [
                            'text' => 'words.add_new',
                            'icon' => 'playlist_add',
                            'route' => ['name' => 'post.create', 'params' => ['typeId' => $item->id]],
                        ]
                    ]
                ];
            } elseif ($item->type === Type::TYPE_CATEGORY) {
                $categories[$item->id] = [
                    'text' => $item->title,
                    'icon' => 'view_list',
                    'route' => ['name' => 'category.list', 'params' => ['typeId' => $item->id]],
                    'active' => ['category.edit', 'category.create'],
                ];
            } elseif ($item->type === Type::TYPE_BLOCK) {
                $blocks[] = [
                    'text' => $item->title,
                    'icon' => 'mdi-view-quilt',
                    'route' => ['name' => 'block.list', 'params' => ['typeId' => $item->id]],
                    'active' => ['block.edit', 'block.create'],
                ];
            } elseif ($item->type === Type::TYPE_SETTING) {
                $params = ['typeId' => $item->id];
                $post = $postRepository->getByType($item->id)->first();

                if ($post) {
                    $params['id'] = $post->id;
                }

                $settings[$item->id] = [
                    'text' => $item->title,
                    'icon' => 'mdi-file-cog',
                    'route' => ['name' => 'setting.edit', 'params' => $params],
                    'childrenOf' => 'settings',
                ];
            }
        });

        $data = [];
        foreach ($posts as $post) {
            if ($post['child_of'] && isset($categories[$post['child_of']])) {
                $categories[$post['child_of']]['text'] = 'Category';
                $post['children'][] = $categories[$post['child_of']];
                unset($categories[$post['child_of']]);
            }
            $data[] = $post;

            unset($post['child_of']);
        }

        foreach ($categories as $category) {
            $data[] = $category;
        }

        if (!empty($blocks)) {
            $data[] = [
                'text' => 'words.blocks',
                'icon' => 'article',
                'children' => $blocks
            ];
        }

        foreach ($settings as $setting) {
            $data[] = $setting;
        }

        return $isJson ? json_encode($data) : $data;
    }
}

if (! function_exists('settingData')) {
    function settingData()
    {
        $user = auth()->user();

        if(!$user) {
            return [];
        }

        $data = [
            'user' => getUserData($user),
        ];
        return $data;
    }
}

if (! function_exists('generateRootFolderName')) {
    function generateRootFolderName($websiteId)
    {
        return $websiteId . 'p' . md5($websiteId);
    }
}

if (! function_exists('generateRouteUrl')) {
    function generateRouteUrl($typeId, $ids): array
    {
        $result = [];
        $routeRepository = app(RouteRepository::class);

        foreach ($ids as $id) {
            $route = $routeRepository->getByTypeParentId($typeId, $id);
            $result[] = $route->name;
        }

        return $result;
    }
}

if (! function_exists('redirectToPage')) {
    function redirectToPage($id, $lang = null, $isPost = true)
    {
        $websiteRepository = WebsiteRepository::getInstance();
        $websiteMetas = $websiteRepository->getMetas();

        if (!in_array($lang, Arr::get($websiteMetas, 'languages_list'))) {
            $lang = Arr::get($websiteMetas, 'language');
        }

        if ($isPost) {
            $repo = app(PostRepository::class);
        } else {
            $repo = app(CategoryRepository::class);
        }

        $item = $repo->getById($id);
        return redirect(implode('/', [$lang, $item->url]));
    }
}

if (! function_exists('goHome')) {
    function goHome($lang = null)
    {
        $websiteRepository = WebsiteRepository::getInstance();
        $websiteMetas = $websiteRepository->getMetas();
        return redirectToPage(Arr::get($websiteMetas, 'pageHome'), $lang);
    }
}

if (! function_exists('go404')) {
    function go404($lang = null)
    {
        $websiteRepository = WebsiteRepository::getInstance();
        $websiteMetas = $websiteRepository->getMetas();
        return redirectToPage(Arr::get($websiteMetas, 'page404'), $lang);
    }
}

if (! function_exists('viewTemplatePath')) {
    function viewTemplatePath($templateId, $isPost) {
        $typePath = $isPost ? 'post/' : 'category/';
        return storage_path('app/template/' . $typePath) . $templateId . '.php';
    }
}

if (! function_exists('viewLayoutTemplatePath')) {
    function viewLayoutTemplatePath($templateId) {
        return storage_path('app/template/layout/') . $templateId . '.php';
    }
}

if (! function_exists('viewTemplate')) {
    function viewTemplate($item, $typeItem, $routeItem, $isPost)
    {
        /**
         * @var GlobalVariable
         */
        $variables = app(GlobalVariable::class);
        $templateId = $item->template_id;
        $layoutId = 0;
        $templatePath = viewTemplatePath($templateId, $isPost);

        if (!file_exists($templatePath)) {
            $templateId = 0;
            $templatePath = viewTemplatePath($templateId, $isPost);
        }

        $websiteRepository = WebsiteRepository::getInstance();
        $websiteMetas = $websiteRepository->getMetas();

        if (!$templateId) {
            $templateKeyPostFix = $isPost ? WebsiteMeta::POST_TEMPLATE_POSTFIX : WebsiteMeta::CATEGORY_TEMPLATE_POSTFIX;
            $templateKey = $typeItem->name . $templateKeyPostFix;
            $defaultTemplateId = Arr::get($websiteMetas, $templateKey);

            if ($defaultTemplateId) {
                $templateId = $defaultTemplateId;
                $templatePath = viewTemplatePath($templateId, $isPost);
            }
        }

        if ($templateId) {
            $pageTemplate = Template::find($templateId);

            $layoutId = $pageTemplate->layout_id;
            $layoutTemplatePath = viewLayoutTemplatePath($layoutId);

            if (!file_exists($templatePath)) {
                $templateId = 0;
                $layoutTemplatePath = viewLayoutTemplatePath($templateId);
            }
        } else {
            $layoutTemplatePath = viewLayoutTemplatePath($templateId);
        }


        $fields = [];
        $typeFields = [];

        foreach ($item->type->fields as $field) {
            $typeFields[Arr::get($field, 'name', '')] = $field;
        }

        $item->metas->each(function ($item) use (&$fields, $typeFields) {
            $fileType = Arr::get($typeFields, $item->meta_key);
            if ($fileType) {
                $fields[$item->meta_key] = getFormattedField($item, $fileType);
            }
        });

        $variables->set('item', $item);
        $variables->set('type-item', $typeItem);
        $variables->set('route-item', $routeItem);
        $variables->set('is-post', $isPost);
        $variables->set('website-metas', $websiteMetas);
        $variables->set('fields', $fields);
        $variables->set('pageTemplatePath', $templatePath);
//dd($layoutTemplatePath);
        \Debugbar::disable();
        include $layoutTemplatePath;
    }
}

if (! function_exists('createStorageTemplateDir')) {
    function createStorageTemplateDir($path, $domainId = null, $permission = 0777)
    {
        $path = getStorageTemplatePath($path, $domainId);

        if (!is_dir($path)) {
            mkdir($path, $permission);
        }
    }
}

if (! function_exists('createStorageTemplateFile')) {
    function createStorageTemplateFile($path, $fileName, $domainId = null, $content = '')
    {
        $filePath = getStorageTemplatePath($path, $domainId) . '/' . $fileName . '.php';

        if (!file_exists($filePath)) {
            $mode = 'w+';
            $file = fopen($filePath, $mode);
            fwrite($file, $content);
            fclose($file);
        }
    }
}

if (! function_exists('updateStorageTemplateFile')) {
    function updateStorageTemplateFile($path, $fileName, $domainId = null, $content = '')
    {
        $storageTemplatePath = getStorageTemplatePath($path, $domainId);
        $filePath = $storageTemplatePath . '/' . $fileName . '.php';
        $resFileName = $fileName . '-backup';

        if (file_exists($filePath)) {
            renameStorageTemplateFile($path, $fileName, $resFileName, $domainId);
        }

        createStorageTemplateFile($path, $fileName, $domainId, $content);

        if (file_exists($storageTemplatePath . '/' . $resFileName . '.php')) {
            deleteStorageTemplateFile($path, $resFileName, $domainId);
        }
    }
}

if (! function_exists('deleteStorageTemplateFile')) {
    function deleteStorageTemplateFile($path, $fileName, $domainId = null)
    {
        $filePath = getStorageTemplatePath($path, $domainId) . '/' . $fileName . '.php';

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}

if (! function_exists('renameStorageTemplateFile')) {
    function renameStorageTemplateFile($path, $fileName, $newFileName, $domainId = null)
    {
        $storageTemplatePath = getStorageTemplatePath($path, $domainId);
        $filePath = $storageTemplatePath . '/' . $fileName . '.php';

        if (file_exists($filePath)) {
            $newFileName = $storageTemplatePath . '/' . $newFileName . '.php';
            rename($filePath, $newFileName);
        }
    }
}

if (! function_exists('getStorageTemplatePath')) {
    function getStorageTemplatePath($folder = '', $domainId = null)
    {
        if (!$domainId) {
            $domainId = WebsiteRepository::getInstance()->getCurrent()->id;
        }

        $domainPath = ['domains', $domainId];
        $path = ['app', 'template', $folder];

        if (!str_ends_with(correctPath(storage_path()), implode('/', $domainPath))) {
            $path = [...$domainPath, ...$path];
        }

        return storage_path(implode('/', $path));
    }
}

if (! function_exists('correctPath')) {
    function correctPath($path)
    {
        return str_replace('\\', '/', $path);
    }
}

if (! function_exists('getCurrentTemplate')) {
    function getCurrentTemplate($filePath)
    {
        $filePath = correctPath($filePath);
        $path = explode('/', substr($filePath, 0, -4));
        $templateRepository = app(\App\Repositories\TemplateRepository::class);

        return $templateRepository->getById(end($path));
    }
}

if (! function_exists('translateText')) {
    function translateText($text, $lang = null)
    {
        if (gettype($text) == 'array') {
            if (isset($text[app()->getLocale()])) {
                return stripslashes($text[app()->getLocale()]);
            }

            return stripslashes(array_values($text)[0]);
        }

        return trans(stripslashes($text));
    }
}

if (! function_exists('convertStringToRouteName')) {
    function convertStringToRouteName($str)
    {
        $allowedChars = [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i',
            'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r',
            's', 't', 'u', 'v', 'w', 'x', 'y', 'z',

            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
            'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
            'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',

            '1', '2', '3', '4', '5', '6', '7', '8', '9', '0',

            '-', '_',
        ];
        $converter = array(
            'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
            'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
            'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
            'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
            'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
            'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
            'э' => 'e',    'ю' => 'yu',   'я' => 'ya',

            'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
            'Е' => 'E',    'Ё' => 'E',    'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
            'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
            'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
            'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
            'Ш' => 'Sh',   'Щ' => 'Sch',  'Ь' => '',     'Ы' => 'Y',    'Ъ' => '',
            'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',

            ' ' => '-',

            'ў' => 'u', 'Ў' => 'U', 'Қ' => 'Q', 'қ' => 'q', 'Ғ' => 'G', 'ғ' => 'g', 'Ҳ' => 'H', 'ҳ' => 'h',

            'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
            'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
            'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
            'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
            'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
            'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
            'ă'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Ș'=>'S', 'Ț'=>'T',

            'ا' => 'a','ب' => 'b', 'ت' => 't', 'و'=>'o','ـ'=>'','ث' => 'th', 'ج' => 'j', 'ح' => 'h', 'خ' => 'kh',
            'د' => 'd', 'ذ' => 'z', 'ص' => 'sa',  'ض' => 'da', 'ع' => 'e', 'غ' => 'g', 'ف' => 'f', 'ق' => 'k',
            'ط' => 'ta','ظ' => 'za', 'م' => 'm', 'ك' => 'k', 'س' => 's', 'ش' => 'sh', 'ﻻ' => 'la', 'ي'=>'e',
            'ى' => 'y', 'ل' => 'l', 'ة' => 't', 'ه' => 'h', 'ؤ' => 'oa', 'ئ' => 'eo', 'ن' => 'n', 'ز' => 'z', 'ر' => 'r',

            'پ' => 'p', 'چ' => 'ch', 'ژ' => 'z', 'ک' => 'k', 'گ' => 'g', 'ی' => 'y',

            'ა' => 'a', 'ბ' => 'b', 'გ' => 'g', 'დ' => 'd', 'ე' => 'e', 'ვ' => 'v', 'ზ' => 'z', 'თ' => 't', 'ი' => 'i',
            'კ' => 'k', 'ლ' => 'l', 'მ' => 'm', 'ნ' => 'n', 'ო' => 'o', 'პ' => 'p', 'ჟ' => 'zh', 'რ' => 'r', 'ს' => 's',
            'ტ' => 't', 'უ' => 'u', 'ფ' => 'p', 'ქ' => 'k', 'ღ' => 'gh', 'ყ' => 'q', 'შ' => 'sh', 'ჩ' => 'ch', 'ც' => 'ts',
            'ძ' => 'dz', 'წ' => 'ts', 'ჭ' => 'ch', 'ხ' => 'kh', 'ჯ' => 'j', 'ჰ' => 'h',

            'Ә' => 'A', 'ә' => 'a', 'A̋' => 'A', 'a̋' => 'a', 'Ġ' => 'G', 'ġ' => 'g', 'Ī' => 'Y', 'ī' => 'y', 'Ĭ' => 'I',
            'ĭ' => 'i', 'K̦' => 'Q', 'k̦' => 'q', 'Ң' => 'Ng', 'ң' => 'ng', 'N͡g' => 'Ng', 'n͡g' => 'ng', 'N̦' => 'Ng', 'n̦' => 'ng',
            'Ө' => 'O', 'ө' => 'o', 'Ȯ' => 'O', 'ȯ' => 'o', 'Ū' => 'U', 'ū' => 'u', 'Ұ' => 'U', 'ұ' => 'u', 'U̇' => 'U', 'u̇' => 'u',
            'Ү' => 'U', 'ү' => 'u', 'Ḣ' => 'H', 'ḣ' => 'h', 'Ḥ' => 'H', 'ḥ' => 'h', 'T͡s' => 'Ts', 't͡s' => 'ts',
            'Č' => 'Ch', 'č' => 'ch', 'Ŝ' => 'Shch', 'ŝ' => 'shch', 'Ė' => 'E', 'ė' => 'e', 'I͡u' => 'Yu', 'i͡u' => 'yu',
            'I͡a' => 'Ya', 'i͡a' => 'ya',

            'Ҷ' => 'Ch', 'ҷ' => 'ch',

            'Ƣ' => 'G', 'ƣ' => 'g', 'Ğ' => 'G', 'ğ' => 'g', 'Ƶ' => 'Z', 'ƶ' => 'z', 'Ň' => 'N', 'ň' => 'n', 'Ꞑ' => 'N', 'ꞑ' => 'n',
            'Ş' => 'Sh', 'ş' => 'sh', '$' => 'Sh', '¢' => 'sh', '¥' => 'Y',
        );

        $result = '';
        $strLen = mb_strlen($str);
        for ($i = 0; $i < $strLen; $i++) {
            $chr = mb_substr($str, $i, 1, 'utf-8');
            if (in_array($chr, $allowedChars)) {
                $result .= $chr;
            } elseif (isset($converter[$chr])) {
                $result .= $converter[$chr];
            }
        }

        return strtolower($result);
    }
}

if (! function_exists('getAllTranslation')) {
    function getAllTranslation(string $key)
    {
        $list = [];
        $localList = config('app.locale_list');

        foreach ($localList as $langCode) {
            $list[$langCode] = trans($key, [], $langCode);
        }

        return $list;
    }
}

if (! function_exists('pathToUploadedFile')) {
    function pathToUploadedFile( $path, $test = true ) {
        $filesystem = new Filesystem;

        $name = $filesystem->name( $path );
        $extension = $filesystem->extension( $path );
        $originalName = $name . '.' . $extension;
        $mimeType = $filesystem->mimeType( $path );
        $error = null;

        return new UploadedFile( $path, $originalName, $mimeType, $error, $test );
    }
}
