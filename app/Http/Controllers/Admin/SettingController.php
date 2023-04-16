<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\WebsiteRepository;
use Illuminate\Support\Arr;

class SettingController extends Controller
{
    protected $list = [
        'name',
        'logo',
        'languages_list',
        'language',
        'timezone',
        'date_format',
        'time_format',
        'pageHome',
        'items_per_page',
        'phone',
        'address',
        'status',
        'page404',
        //'template',
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
        $languages = [];

        foreach (config('app.locale_list') as $val) {
            $languages[$val] = $val;
        }

        return responseJsonData(true, [
            'metas' => $metas,
            'languages' => $languages,
            'timezones' => [],
            'date_formats' => [],
            'time_formats' => [],
            'pages' => [],
            'images_sizes' => [],
        ]);
    }

    public function update()
    {
        //
    }
}
