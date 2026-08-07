<?php

namespace App\Services\Admin;

use App\Helpers\DataFormat;
use App\Models\Post;
use App\Models\PostMeta;
use App\Models\Route;
use App\Models\Template;
use App\Models\Type;
use App\Models\TypeRouteStructure;
use App\Models\Website;
use App\Models\WebsiteMeta;
use App\Repositories\WebsiteRepository;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

class WebsiteService extends BaseService
{
    //private WebsiteRepository $websiteRepository;

    public function __construct(WebsiteRepository $websiteRepository)
    {
        //$this->websiteRepository = $websiteRepository;
    }

    public function create(array $data, array $metas, array $settings = [])
    {
        $website = WebsiteRepository::getInstance()->getByDomain(Arr::get($data, 'domain', ''));

        if (!$website) {
            $website = Website::firstOrCreate($data);

            if($data['type'] == Website::TYPE_MAIN) {
                foreach ($metas as $meta) {
                    if ($meta['meta_key'] === 'name') {
                        $settings['env']['app_name'] = $meta['meta_value'];
                        break;
                    }
                }

                $this->createWebsiteResoucres($website->id, $settings);
                //$this->createTemplates($website->id);

                $this->createMetas($website->id, $metas, $settings);
                /*$type = $this->createTypePage($website->id);
                $this->createHomePage($website->id, $type->id);
                $this->create404Page($website->id, $type->id);

                $typeCategory = $this->createTypeCategory($website->id);
                $typePost = $this->createTypePost($website->id, $typeCategory);*/
            }
        }

        return $website;
    }

    private function createWebsiteResoucres(int $websiteId, array $settings = [])
    {
        Artisan::call('domain:add ' . $websiteId);
        $env = [
            ...Arr::get($settings, 'env', []),
            ...[
                'app_key' => generateRandomKey(),
                'jwt_secret' => randomString64(),
            ]
        ];
        update_dotenv(storage_path('domains/env/.env.' . $websiteId), $env);

        $websiteFolders = [
            'domains/' . $websiteId . '/framework/views',
            'domains/' . $websiteId . '/framework/cache/data',
            'domains/' . $websiteId . '/framework/sessions',
            'domains/' . $websiteId . '/app/template',
        ];
        foreach ($websiteFolders as $folder) {
            $path = storage_path($folder);
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }

        $templates = [
            ['path' => ''],
            ['path' => 'layout', 'file_name' => '0'],
            ['path' => 'block'],
            ['path' => 'post', 'file_name' => '0'],
            ['path' => 'category', 'file_name' => '0'],
        ];

        foreach ($templates as $template) {
            $this->createStorageTemplate($websiteId, $template['path'], Arr::get($template, 'file_name', ''));
        }
    }

    private function createStorageTemplate(int $websiteId, string $path, string $fileName = '')
    {
        createStorageTemplateDir($path, $websiteId);

        if ($fileName !== '') {
            createStorageTemplateFile($path, $fileName, $websiteId);
        }
    }

    public function createMetas(int $websiteId, array $metas, array $settings = [])
    {
        $localeList = Arr::get($settings, 'website_meta.locale_list', config('app.locale_list'));
        $locale = Arr::get($settings, 'website_meta.default_locale', config('app.default_locale'));
        $langMetas = [
            [
                'meta_key' => 'languages_list',
                'meta_value' => DataFormat::toString($localeList, DataFormat::FORMAT_ARRAY),
                'meta_format' => DataFormat::FORMAT_ARRAY,
            ],
            [
                'meta_key' => 'language',
                'meta_value' => $locale,
            ],
        ];
        $extMetas = [
            [
                'meta_key' => 'root-folder-path',
                'meta_value' => generateRootFolderName($websiteId),
            ]
        ];
        $metas = array_merge($metas, $langMetas, $extMetas);

        foreach($metas as $meta) {
            $meta['website_id'] = $websiteId;
            WebsiteMeta::firstOrCreate($meta);
        }
    }

    /*private function createTypeCategory(int $websiteId): Type
    {
        return Type::firstOrCreate([
            'website_id' => $websiteId,
            'status' => 1,
            'type' => Type::TYPE_CATEGORY,
            'has_parent' => 1,
            'child_of' => 0,
        ], [
            'title' => getAllTranslation('words.category'),
            'name' => config('app.main_page_type'),
            'structure' => config('type-structure.page'),
        ]);
    }*/

    /*private function createTypePage(int $websiteId): Type
    {
        return Type::firstOrCreate([
            'website_id' => $websiteId,
            'status' => 1,
            'type' => Type::TYPE_POST,
            'has_parent' => 1,
            'child_of' => 0,
        ], [
            'title' => getAllTranslation('words.page'),//['en' => 'Page', 'ru' =>  'Страница', 'uz' => 'Sahifa'],
            'name' => config('app.main_page_type'),
            'structure' => config('type-structure.page'),
        ]);
    }

    private function createHomePage(int $websiteId, int $typeId)
    {
        $pageHome = Post::firstOrCreate([
            'website_id' => $websiteId,
            'category_id' => 0,
            'template_id' => 0,
            'status' => 1,
            'parent_id' => 0,
            'type_id' => $typeId,
            'url' => 'page/home',
        ]);
        Route::firstOrCreate([
            'website_id' => $websiteId,
            'name' => 'home',
            'parent_id' => $pageHome->id,
            'type_id' => $typeId,
        ]);
        TypeRouteStructure::firstOrCreate([
            'website_id' => $websiteId,
            'type_id' => $typeId,
            'parent_id' => $pageHome->id,
        ], [
            'params' => [],
            'structure' => []
        ]);
        PostMeta::firstOrCreate([
            'website_id' => $websiteId,
            'post_id' => $pageHome->id,
            'meta_format' => DataFormat::FORMAT_STRING,
            'meta_key' => 'title',
        ], [
            'meta_value' => DataFormat::toString(getAllTranslation('words.home_page'), DataFormat::FORMAT_STRING),//json_encode(['en' => 'Home', 'ru' => 'Главная', 'uz' => 'Bosh sahifa']),
        ]);
        WebsiteMeta::firstOrCreate([
            'meta_key' => 'pageHome',
            'meta_value' => $pageHome->id,
            'meta_format' => DataFormat::FORMAT_INT,
            'website_id' => $websiteId,
        ]);
    }

    private function create404Page(int $websiteId, int $typeId)
    {
        $page404 = Post::firstOrCreate([
            'website_id' => $websiteId,
            'category_id' => 0,
            'template_id' => 0,
            'status' => 1,
            'parent_id' => 0,
            'type_id' => $typeId,
            'url' => 'page/404',
        ]);
        Route::firstOrCreate([
            'website_id' => $websiteId,
            'name' => '404',
            'parent_id' => $page404->id,
            'type_id' => $typeId,
        ]);
        TypeRouteStructure::firstOrCreate([
            'website_id' => $websiteId,
            'type_id' => $typeId,
            'parent_id' => $page404->id,
        ], [
            'params' => [],
            'structure' => []
        ]);
        PostMeta::firstOrCreate([
            'website_id' => $websiteId,
            'post_id' => $page404->id,
            'meta_format' => DataFormat::FORMAT_STRING,
            'meta_key' => 'title',
        ], [
            'meta_value' => DataFormat::toString(getAllTranslation('words.404_page'), DataFormat::FORMAT_STRING)
        ]);
        WebsiteMeta::firstOrCreate([
            'meta_key' => 'page404',
            'meta_value' => $page404->id,
            'meta_format' => DataFormat::FORMAT_INT,
            'website_id' => $websiteId,
        ]);
    }*/

    /*private function createTemplates(int $websiteId): Template
    {
        $content = [];
        $templateService = app(TemplateService::class);
        $templateBlocks = $templateService->getTemplateBlocks();

        foreach ($templateBlocks['blocks'] as $block) {
            if ($block['type'] === 'content') {
                $content = [$block['samples'][0]];
                break;
            }
        }

        $params = [
            'contentHtml' => $templateService->getBlockHtml($content),
            'styles' => $templateService->getBlockStyle($content),
        ];

        $layout = Template::firstOrCreate([
            'website_id' => $websiteId,
            'theme_id' => $themeId,
            'type' => Template::TYPE_LAYOUT,
        ],[
            'name' => getAllTranslation('words.layout'),
            'content' => $content,
            'params' => $params,
        ]);

        return $layout;
    }*/
}
