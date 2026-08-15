<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DataFormat;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\CreateSettingRequest;
use App\Http\Requests\Admin\Setting\UpdateSettingRequest;
use App\Models\SettingMeta;
use App\Models\Template;
use App\Models\Type;
use App\Models\Website;
use App\Models\WebsiteMeta;
use App\Repositories\PostRepository;
use App\Repositories\SettingMetaRepository;
use App\Repositories\SettingRepository;
use App\Repositories\TypeRepository;
use App\Repositories\WebsiteRepository;
use App\Services\Admin\TemplateService;
use Illuminate\Support\Arr;

class SettingController extends Controller
{
    private $activeTypesList = null;
    protected $metaList = [
        'name' => DataFormat::FORMAT_STRING,
        'logo' => DataFormat::FORMAT_ARRAY,
        'pageHome' => DataFormat::FORMAT_INT,
        'page404' => DataFormat::FORMAT_INT,
        'status' => DataFormat::FORMAT_BOOL,
        'languages_list' => DataFormat::FORMAT_ARRAY,
        'language' => DataFormat::FORMAT_STRING,
        //'phone' => DataFormat::FORMAT_STRING,//
        //'address' => DataFormat::FORMAT_STRING,//
        'favicon' => DataFormat::FORMAT_ARRAY,
        //'copyright' => DataFormat::FORMAT_STRING,

        /*'timezone' => DataFormat::FORMAT_STRING,
        'date_format' => DataFormat::FORMAT_STRING,
        'time_format' => DataFormat::FORMAT_STRING,
        'items_per_page' => DataFormat::FORMAT_INT,*/

        'seoDescription' => DataFormat::FORMAT_STRING,
        'indexing' => DataFormat::FORMAT_BOOL,
        'seoKeyword' => DataFormat::FORMAT_STRING,
        //'meta_tags' => DataFormat::FORMAT_STRING,

        /*'image_sizes' => DataFormat::FORMAT_ARRAY,

        'social_networks' => DataFormat::FORMAT_ARRAY,*/
        //'category---category-template' => DataFormat::FORMAT_INT,
        //'page---post-template' => DataFormat::FORMAT_INT,
        //'post---post-template' => DataFormat::FORMAT_INT,
        //'theme_component_bootstrap' => DataFormat::FORMAT_STRING,
        //'theme_component_swiper_js' => DataFormat::FORMAT_STRING,
    ];

    private $settingRepository = null;
    private $settingMetaRepository = null;

    public function __construct()
    {
        $this->settingRepository = app(SettingRepository::class);
        $this->settingMetaRepository = app(SettingMetaRepository::class);
    }

    public function all()
    {
        return responseJsonData(true, $this->getData());
    }

    private function getData()
    {
        $metas = Arr::only(WebsiteRepository::getInstance()->getMetas(), array_keys($this->list));
        $data = $this->getSettings(
            $this->getMainFields($metas),
            $this->getThemeFields($metas),
            $this->getSeoFields($metas),
            $this->getAdditionalFields($metas),
            $this->getImageFields($metas),
            $this->getSocialFields($metas)
        );

        return $data;
    }

    private function getActiveTypesList()
    {
        if (!$this->activeTypesList) {
            $this->activeTypesList = app(TypeRepository::class)->getActiveList()->toArray();
        }

        return $this->activeTypesList;
    }

    private function getActiveType($name) {
        $list = $this->getActiveTypesList();

        foreach ($list as $item) {
            if ($item['name'] === $name) {
                return $item;
            }
        }

        return [];
    }

    public function __get(string $name)
    {
        if ($name === 'list') {
            $list = $this->getActiveTypesList();
            foreach ($list as $type) {
                $typePostfix =
                    $type['type'] === Type::TYPE_POST ?
                        WebsiteMeta::POST_TEMPLATE_POSTFIX :
                        ($type['type'] === Type::TYPE_CATEGORY ?
                            WebsiteMeta::CATEGORY_TEMPLATE_POSTFIX :
                            ($type['type'] === Type::TYPE_SETTING ?
                                WebsiteMeta::SETTING_TEMPLATE_POSTFIX :
                                WebsiteMeta::BLOCK_TEMPLATE_POSTFIX));
                $typeKey = $type['name'] . $typePostfix;

                if (!isset($this->metaList[$typeKey])) {
                    $this->metaList[$typeKey] = DataFormat::FORMAT_INT;
                }
            }
            return $this->metaList;
        }
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

    private function getSettings($main, $theme, $seo, $additional, $image, $social): array
    {
        return [
            [
                'title' => 'words.main',
                'children' => $main,
            ],
            [
                'title' => 'words.theme',
                'children' => $theme,
            ],
            [
                'title' => 'words.seo',
                'children' => $seo,
            ],
            /*[
                'title' => 'words.additional',
                'children' => $additional,
            ],*/
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

    private function getThemeFields($metas)
    {
        /*$bootstrap = config('app.theme.components.bootstrap');
        $bootstrapVersions = [];
        foreach ($bootstrap as $key => $value) {
            $bootstrapVersions[$key] = $key . '.' . $value;
        }

        $swiperJs = config('app.theme.components.swiper_js');
        $swiperJsVersions = [];
        foreach ($swiperJs as $key => $value) {
            $swiperJsVersions[$key] = $key . '.' . $value;
        }

        $result = [
            [
                "type" => "select",
                "name" => "theme_component_bootstrap",
                "value" => Arr::get($metas, 'theme_component_bootstrap', array_keys($bootstrap)[0]),
                "params" => [
                    "options" => $bootstrapVersions,
                    "valueType" => DataFormat::FORMAT_STRING,
                    "label" => "words.bootstrap"
                ],
            ],
            [
                "type" => "select",
                "name" => "theme_component_swiper_js",
                "value" => Arr::get($metas, 'theme_component_swiper_js', array_keys($swiperJs)[0]),
                "params" => [
                    "options" => $swiperJsVersions,
                    "valueType" => DataFormat::FORMAT_STRING,
                    "label" => "words.swiper_js"
                ],
            ],
        ];*/

        /**
         * @var TemplateService
         */
        //$templateService = app(TemplateService::class);
        //'post_template',
        //'category_template',
        /*$postTemplates = [];
        $categoryTemplates = [];

        foreach ($templateService->getByType(Template::TYPE_POST) as $item) {
            $postTemplates[$item->id] = $item->name;
        }

        foreach ($templateService->getByType(Template::TYPE_CATEGORY) as $item) {
            $categoryTemplates[$item->id] = $item->name;
        }*/

        /*foreach ($this->list as $key => $item) {
            if (str_ends_with($key, WebsiteMeta::POST_TEMPLATE_POSTFIX)) {
                $name = substr($key, 0, strlen($key) - strlen(WebsiteMeta::POST_TEMPLATE_POSTFIX));
                $activeType = $this->getActiveType($name);

                if (gettype($activeType['title']) === 'string') {
                    $activeType['title'] .= ' ' . trans('words.template');
                } else {
                    foreach ($activeType['title'] as $lang => $value) {
                        $activeType['title'][$lang] = $value . ' ' . trans('words.template', [], $lang);
                    }
                }

                $result[] = [
                    "type" => "select",
                    "name" => $key,
                    "value" => Arr::get($metas, $key, 0),
                    "params" => [
                        "options" => $postTemplates,
                        "multiple" => false,
                        "valueType" => DataFormat::FORMAT_STRING,
                        "label" => $activeType['title']
                    ],
                ];
            }

            if (str_ends_with($key, WebsiteMeta::CATEGORY_TEMPLATE_POSTFIX)) {
                $name = substr($key, 0, strlen($key) - strlen(WebsiteMeta::CATEGORY_TEMPLATE_POSTFIX));
                $activeType = $this->getActiveType($name);

                if (gettype($activeType['title']) === 'string') {
                    $activeType['title'] .= ' ' . trans('words.template');
                } else {
                    foreach ($activeType['title'] as $lang => $value) {
                        $activeType['title'][$lang] = $value . ' ' . trans('words.template', [], $lang);
                    }
                }

                $result[] = [
                    "type" => "select",
                    "name" => $key,
                    "value" => Arr::get($metas, $key, 0),
                    "params" => [
                        "options" => $categoryTemplates,
                        "multiple" => false,
                        "valueType" => DataFormat::FORMAT_STRING,
                        "label" => $activeType['title']
                    ],
                ];
            }
        }*/

        return [];
    }

    private function getMainFields($metas)
    {
        $languages = [];

        foreach (config('app.locale_list') as $val) {
            $languages[$val] = 'words.languages_list.' . $val;
        }

        $pageType = app(TypeRepository::class)->getByName(config('app.main_page_type'));
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
            /*[
                "type" => "text",
                "name" => "phone",
                "value" => Arr::get($metas, 'phone', ''),
                'hasLang' => false,
                "params" => [
                    "valueType" => DataFormat::FORMAT_STRING,
                    "label" => "words.phone"
                ],
            ],*/
            /*[
                "type" => "textarea",
                "name" => "address",
                "value" => Arr::get($metas, 'address', ''),
                "params" => [
                    "valueType" => DataFormat::FORMAT_STRING,
                    "label" => "words.address",
                ],
            ],*/
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
        return [];
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

    public function get(int $type, int $setting)
    {
        $settingItem = $this->settingRepository->getById($setting);
        $result = getFieldValues(getTypeById($type)->fields);

        $settingItem->metas->each(function (SettingMeta $meta) use (&$result) {
            $result[$meta->meta_key] = DataFormat::toFormat($meta->meta_value, $meta->meta_format);
        });

        return responseJsonData(true, ['setting' => $result]);
    }

    public function edit(int $type, int $setting, CreateSettingRequest $request)
    {
        $fieldNames = $request->only(getFieldNames(getTypeById($type)->fields));
        $typeFields = getFields(getTypeById($type)->fields);
        $settingItem = $this->settingRepository->getById($setting);
        $settingItem->metas()->delete();
        foreach ($fieldNames as $name => $value) {
            $format = Arr::get($typeFields[$name], 'params.valueType', DataFormat::getDefault());
            $settingMetaAttributes = [
                'setting_id' => $settingItem->id,
                'meta_format' => $format,
                'meta_key' => $name,
                'meta_value' => DataFormat::toString($value, $format),
            ];
            $this->settingMetaRepository->create($settingMetaAttributes);
        }

        return responseJsonData(true, ['setting' => $settingItem]);
    }

    public function create(int $type, CreateSettingRequest $request)
    {
        $fieldNames = $request->only(getFieldNames(getTypeById($type)->fields));
        $typeFields = getFields(getTypeById($type)->fields);
        $setting = $this->settingRepository->create(['type_id' => $type]);

        foreach ($fieldNames as $name => $value) {
            $format = Arr::get($typeFields[$name], 'params.valueType', DataFormat::getDefault());
            $settingMetaAttributes = [
                'setting_id' => $setting->id,
                'meta_format' => $format,
                'meta_key' => $name,
                'meta_value' => DataFormat::toString($value, $format),
            ];
            $this->settingMetaRepository->create($settingMetaAttributes);
        }

        return responseJsonData(true, ['setting' => $setting]);
    }
}
