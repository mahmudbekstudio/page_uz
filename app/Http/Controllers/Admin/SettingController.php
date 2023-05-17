<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DataFormat;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\UpdateSettingRequest;
use App\Repositories\PostRepository;
use App\Repositories\TypeRepository;
use App\Repositories\WebsiteRepository;
use Illuminate\Support\Arr;

class SettingController extends Controller
{
    protected $list = [
        'name',
        'logo',
        'pageHome',
        'page404',
        'status',
        'languages_list',
        'language',
        'phone',
        'address',
        'timezone',
        'date_format',
        'time_format',
        'items_per_page',
        //'post_template',
        //'category_template',
        'favicon',
        'copyright',

        'description',
        'indexing',
        'keywords',
        'meta_tags',

        'image_sizes',

        'social_networks'
    ];

    public function get()
    {
        $metas = Arr::only(WebsiteRepository::getInstance()->getMetas(), $this->list);
        $settings = $this->getSettings(
            $this->getMainFields($metas),
            $this->getSeoFields($metas),
            $this->getImageFields($metas),
            $this->getSocialFields($metas)
        );
        return responseJsonData(true, $settings);


        /*return responseJsonData(true, [
            'metas' => $metas,
            'languages' => $languages,
            'timezones' => [],
            'date_formats' => [],
            'time_formats' => [],
            'pages' => [],
            'images_sizes' => [],
        ]);*/
    }

    public function update(UpdateSettingRequest $request)
    {
        //
    }

    private function getSettings($main, $seo, $image, $social): array
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
            $languages[$val] = $val;
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
        //'post_template',
        //'category_template',
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
                "type" => "select",
                "name" => "pageHome",
                "value" => Arr::get($metas, 'pageHome', 0),
                "params" => [
                    "options" => $pages,
                    "valueType" => DataFormat::FORMAT_STRING,
                    "label" => "words.pageHome"
                ],
            ],
            [
                "type" => "select",
                "name" => "page404",
                "value" => Arr::get($metas, 'page404', 0),
                "params" => [
                    "options" => $pages,
                    "valueType" => DataFormat::FORMAT_STRING,
                    "label" => "words.page404"
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
                "name" => "description",
                "value" => "",
                "params" => [
                    "valueType" => DataFormat::FORMAT_STRING,
                    "label" => "words.description"
                ],
            ],
            [
                "type" => "text",
                "name" => "keywords",
                "value" => "",
                "params" => [
                    "valueType" => DataFormat::FORMAT_STRING,
                    "label" => "words.keywords"
                ],
            ],
            [
                "type" => "switch",
                "name" => "indexing",
                "value" => true,
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
}
