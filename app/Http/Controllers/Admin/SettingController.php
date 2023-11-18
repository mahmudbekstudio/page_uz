<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DataFormat;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\UpdateSettingRequest;
use App\Models\Template;
use App\Models\Website;
use App\Repositories\PostRepository;
use App\Repositories\TypeRepository;
use App\Repositories\WebsiteRepository;
use App\Services\Admin\TemplateService;
use Illuminate\Support\Arr;

class SettingController extends Controller
{
    protected $list = [
        'name' => DataFormat::FORMAT_STRING,
        'logo' => DataFormat::FORMAT_ARRAY,
        'pageHome' => DataFormat::FORMAT_INT,
        'page404' => DataFormat::FORMAT_INT,
        'status' => DataFormat::FORMAT_BOOL,
        'languages_list' => DataFormat::FORMAT_ARRAY,
        'language' => DataFormat::FORMAT_STRING,
        'phone' => DataFormat::FORMAT_STRING,
        'address' => DataFormat::FORMAT_STRING,
        'favicon' => DataFormat::FORMAT_ARRAY,
        //'copyright' => DataFormat::FORMAT_STRING,

        'timezone' => DataFormat::FORMAT_STRING,
        'date_format' => DataFormat::FORMAT_STRING,
        'time_format' => DataFormat::FORMAT_STRING,
        'items_per_page' => DataFormat::FORMAT_INT,
        'post_template' => DataFormat::FORMAT_INT,
        'category_template' => DataFormat::FORMAT_INT,

        'seoDescription' => DataFormat::FORMAT_STRING,
        'indexing' => DataFormat::FORMAT_BOOL,
        'seoKeyword' => DataFormat::FORMAT_STRING,
        'meta_tags' => DataFormat::FORMAT_STRING,

        'image_sizes' => DataFormat::FORMAT_ARRAY,

        'social_networks' => DataFormat::FORMAT_ARRAY,
    ];

    public function get()
    {
        return responseJsonData(true, $this->getData());
    }

    private function getData()
    {
        $metas = Arr::only(WebsiteRepository::getInstance()->getMetas(), array_keys($this->list));
        $data = $this->getSettings(
            $this->getMainFields($metas),
            $this->getSeoFields($metas),
            $this->getAdditionalFields($metas),
            $this->getImageFields($metas),
            $this->getSocialFields($metas)
        );

        return $data;
    }

    public function update(UpdateSettingRequest $request)
    {
        $metas = [];

        foreach ($this->list as $key => $format) {
            $metas[$key] = [
                'value'  => $request->get($key),
                'format' => $format
            ];
        }

        $metas = $this->checkValues($metas);
        WebsiteRepository::getInstance()->storeMetas($metas);

        if (Arr::has($metas, 'status')) {
            WebsiteRepository::changeStatusOfCurrentWebsite(
                $metas['status']['value'] ? Website::STATUS_ACTIVE : Website::STATUS_TEMPORARILY_CLOSED
            );
        }

        return responseJsonData(true, $this->getData(), ['website' => websiteData()]);
    }

    private function getSettings($main, $seo, $additional, $image, $social): array
    {
        return [
            [
                'title' => 'words.main',
                'children' => $main,
            ],
            [
                'title' => 'words.seo',
                'children' => $seo,
            ],
            [
                'title' => 'words.additional',
                'children' => $additional,
            ],
            /*[
                'title' => 'words.image',
                'children' => $image,
            ],
            [
                'title' => 'words.social',
                'children' => $social,
            ],*/
        ];
    }

    private function getMainFields($metas)
    {
        $languages = [];

        foreach (config('app.locale_list') as $val) {
            $languages[$val] = 'words.languages_list.' . $val;
        }

        $pageType = app(TypeRepository::class)->getByName(config('app.main_page.name'));
        $pages = [];
        app(PostRepository::class)->getActiveList($pageType->id)->each(function ($item) use (&$pages) {
            $pages[$item['id']] = $item['name'];
        });
        /*
        'timezone',
        'date_format',
        'time_format',
        'items_per_page',
        'favicon',
        'copyright',*/
        return [
            [
                "type" => "text",
                "name" => "name",
                "value" => Arr::get($metas, 'name', ''),
                "params" => [
                    "valueType" => DataFormat::FORMAT_STRING,
                    "label" => "words.name"
                ],
            ],
            [
                "type" => "file",
                "name" => "logo",
                "value" => Arr::get($metas, 'logo', []),
                "params" => [
                    "valueType" => DataFormat::FORMAT_ARRAY,
                    "label" => "words.logo",
                    "fileType" => "image"
                ],
            ],
            [
                "type" => "file",
                "name" => "favicon",
                "value" => Arr::get($metas, 'favicon', []),
                "params" => [
                    "valueType" => DataFormat::FORMAT_ARRAY,
                    "label" => "words.favicon",
                    "fileType" => "image"
                ],
            ],
            [
                "type" => "select",
                "name" => "pageHome",
                "value" => Arr::get($metas, 'pageHome', 0),
                "params" => [
                    "options" => $pages,
                    "valueType" => DataFormat::FORMAT_INT,
                    "label" => "words.home_page"
                ],
            ],
            [
                "type" => "select",
                "name" => "page404",
                "value" => Arr::get($metas, 'page404', 0),
                "params" => [
                    "options" => $pages,
                    "valueType" => DataFormat::FORMAT_INT,
                    "label" => "words.404_page"
                ],
            ],
            [
                "type" => "switch",
                "name" => "status",
                "value" => Arr::get($metas, 'status', true),
                "params" => [
                    "valueType" => DataFormat::FORMAT_BOOL,
                    "label" => "words.status"
                ],
            ],
            [
                "type" => "select",
                "name" => "languages_list",
                "value" => Arr::get($metas, 'languages_list', []),
                "params" => [
                    "options" => $languages,
                    "multiple" => true,
                    "valueType" => DataFormat::FORMAT_ARRAY,
                    "label" => "words.languages"
                ],
            ],
            [
                "type" => "select",
                "name" => "language",
                "value" => Arr::get($metas, 'language', ''),
                "params" => [
                    "options" => $languages,
                    "valueType" => DataFormat::FORMAT_STRING,
                    "label" => "words.language"
                ],
            ],
            [
                "type" => "text",
                "name" => "phone",
                "value" => Arr::get($metas, 'phone', ''),
                'hasLang' => false,
                "params" => [
                    "valueType" => DataFormat::FORMAT_STRING,
                    "label" => "words.phone"
                ],
            ],
            [
                "type" => "textarea",
                "name" => "address",
                "value" => Arr::get($metas, 'address', ''),
                "params" => [
                    "valueType" => DataFormat::FORMAT_STRING,
                    "label" => "words.address",
                ],
            ],
            /*[
                "type" => "text",
                "name" => "copyright",
                "value" => Arr::get($metas, 'copyright', ''),
                "params" => [
                    "valueType" => DataFormat::FORMAT_STRING,
                    "label" => "words.copyright"
                ],
            ],*/
        ];
    }

    private function getAdditionalFields($metas): array
    {
        /**
         * @var TemplateService
         */
        $templateService = app(TemplateService::class);
        //'post_template',
        //'category_template',
        $postTemplates = [];
        $categoryTemplates = [];

        foreach ($templateService->getByType(Template::TYPE_POST) as $item) {
            $postTemplates[$item->id] = $item->name;
        }

        foreach ($templateService->getByType(Template::TYPE_CATEGORY) as $item) {
            $categoryTemplates[$item->id] = $item->name;
        }

        return [
            [
                "type" => "select",
                "name" => "post_template",
                "value" => Arr::get($metas, 'post_template', 0),
                "params" => [
                    "options" => $postTemplates,
                    "multiple" => false,
                    "valueType" => DataFormat::FORMAT_ARRAY,
                    "label" => "words.post_template"
                ],
            ],
            [
                "type" => "select",
                "name" => "category_template",
                "value" => Arr::get($metas, 'category_template', 0),
                "params" => [
                    "options" => $categoryTemplates,
                    "multiple" => false,
                    "valueType" => DataFormat::FORMAT_ARRAY,
                    "label" => "words.category_template"
                ],
            ],
        ];
    }

    private function getSeoFields($metas): array
    {
        /*
        'meta_tags',
        */
        return [
            [
                "type" => "text",
                "name" => "seoDescription",
                "value" => Arr::get($metas, 'seoDescription', ''),
                "params" => [
                    "valueType" => DataFormat::FORMAT_STRING,
                    "label" => "words.description"
                ],
            ],
            [
                "type" => "text",
                "name" => "seoKeyword",
                "value" => Arr::get($metas, 'seoKeyword', ''),
                "params" => [
                    "valueType" => DataFormat::FORMAT_STRING,
                    "label" => "words.keywords"
                ],
            ],
            [
                "type" => "switch",
                "name" => "indexing",
                "value" => Arr::get($metas, 'indexing', true),
                "params" => [
                    "valueType" => DataFormat::FORMAT_BOOL,
                    "label" => "words.indexing"
                ],
            ],
        ];
    }

    private function getImageFields($metas): array
    {
        return [];
    }

    private function getSocialFields($metas): array
    {
        return [];
    }

    private function checkValues(array $metas): array
    {
        if (empty($metas['languages_list']['value'])) {
            $metas['languages_list']['value'] = [config('app.default_locale')];
        }

        if (!in_array($metas['language']['value'], $metas['languages_list']['value'])) {
            $metas['language']['value'] = $metas['languages_list']['value'][0];
        }

        return $metas;
    }
}
