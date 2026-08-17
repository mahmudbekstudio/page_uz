<?php

namespace App\Services\Admin;

use App\Models\Template;
use App\Repositories\TemplateRepository;
use App\Repositories\WebsiteRepository;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use function Sodium\randombytes_random16;

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

        return $this->templateRepository->create($fields);
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

        /*$websiteMetas = WebsiteRepository::getInstance()->getMetas();

        foreach ($themeConfig['cdn'] as $component => $list) {
            $websiteMetas['theme_component_' . $component] = Arr::get(
                $websiteMetas, 'theme_component_' . $component,
                array_keys($themeConfig['components'][$component])[0]
            );
            foreach ($list as $type => $file) {
                $result[$type][] = str_replace('{VERSION}', $websiteMetas['theme_component_' . $component], $file);
            }
        }*/

        return $result;
    }

    public function getDefaultData(string $type): array
    {
        $templates = config('templates');
        $blocks = $templates['blocks'];
        $template = collect($blocks)->firstWhere('type', 'content');
        $templateItem = Arr::only($template, ['type', 'hide', 'fields', 'styles', 'title', 'styleFiles', 'scriptFiles', 'customStyles', 'children']);

        $templateItem['fields'] = [...Arr::get($templateItem, 'fields', []), ...$templates['fields']];
        $templateItem['styleFiles'] = [...Arr::get($templateItem, 'styleFiles', []), ...$templates['styleFiles']];
        $templateItem['scriptFiles'] = [...Arr::get($templateItem, 'scriptFiles', []), ...$templates['scriptFiles']];
        $templateItem['styles'] = [...Arr::get($templateItem, 'styles', []), ...$templates['styles']];
        $templateItem['values'] = $template['samples'][0]['values'];
        $templateItem['structure'] = $template['layout'][0]['structure'];
        $templateItem['id'] = $templateItem['type'] . '-1';
        $templateItem['title'] = Arr::get($templateItem, 'title', $templateItem['type']);
        $templateItem['customStyles'] = Arr::get($templateItem, 'customStyles', ['' => []]);
        $templateItem['isActive'] = false;
        $templateItem['key'] = time();
        $templateItem['children'] = Arr::get($templateItem, 'children', []);
        $templateItem['structure']['attributes']['id'] = $templateItem['id'];

        $params = [
            'styles' => '',
            'customStyles' => '',
            'contentHtml' => structureToHtml($templateItem['structure'], $templateItem['fields']),
        ];

        return [
            'content' => [$templateItem],
            'params' => $params,
        ];
    }
}
