<?php

namespace App\Services\Admin;

use App\Models\Template;
use App\Repositories\TemplateRepository;
use App\Repositories\WebsiteRepository;
use App\Services\BaseService;
use Illuminate\Support\Arr;

class TemplateService extends BaseService
{
    private TemplateRepository $templateRepository;

    public function __construct()
    {
        $this->templateRepository = app(TemplateRepository::class);
    }

    public function update($id, array $fields)
    {
        return $this->templateRepository->update($fields, $id)->only(['name', 'type', 'content', 'params']);
    }

    public function create(array $fields)
    {
        Arr::set($fields, 'params', Arr::get($fields, 'params', []));

        return $this->templateRepository->create($fields)->only(['name', 'type', 'content', 'params', 'type_id', 'layout_id']);
    }

    public function getByType(string $type)
    {
        if (!in_array($type, Template::types())) {
            return [];
        }

        return $this->templateRepository->findWhere(['type' => $type], ['id', 'name']);
    }

    public function delete(Template $template): bool
    {
        if (
            $template->type === Template::TYPE_LAYOUT &&
            $this->templateRepository->findWhere(['layout_id' => $template->id])->isNotEmpty()
        ) {
            return false;
        }

        $template->delete();

        return true;
    }

    public function getThemeConfig()
    {
        $result = ['js' => [], 'css' => []];
        $themeConfig = config('app.theme');

        foreach ($themeConfig['default']['js'] as $js) {
            $result['js'][] = $js;
        }

        foreach ($themeConfig['default']['css'] as $css) {
            $result['css'][] = $css;
        }

        $websiteMetas = WebsiteRepository::getInstance()->getMetas();

        foreach ($themeConfig['cdn'] as $component => $list) {
            $websiteMetas['theme_component_' . $component] = Arr::get(
                $websiteMetas, 'theme_component_' . $component,
                array_keys($themeConfig['components'][$component])[0]
            );
            foreach ($list as $type => $file) {
                $result[$type][] = str_replace('{VERSION}', $websiteMetas['theme_component_' . $component], $file);
            }
        }

        return $result;
    }
}
