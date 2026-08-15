<?php

namespace App\Http\Controllers\Admin;

use App\DataTable\TemplateCategoryDataTable;
use App\DataTable\TemplateDataTable;
use App\DataTable\TemplateLayoutDataTable;
use App\DataTable\TemplatePostDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Template\CreateTemplateRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Template;
use App\Models\WebsiteMeta;
use App\Repositories\WebsiteRepository;
use App\Services\Admin\TemplateService;

class TemplateController extends Controller
{
    private TemplateService $templateService;

    public function __construct(
        TemplateService $templateService
    ) {
        $this->templateService = $templateService;
    }

    public function list(TemplateDataTable $dataTable)
    {
        return responseJsonData(true, $dataTable->toArray());
    }

    public function listLayout(TemplateLayoutDataTable $dataTable)
    {
        return responseJsonData(true, $dataTable->toArray());
    }

    public function listPost(TemplatePostDataTable $dataTable)
    {
        return responseJsonData(true, $dataTable->toArray());
    }

    public function listCategory(TemplateCategoryDataTable $dataTable)
    {
        return responseJsonData(true, $dataTable->toArray());
    }

    public function create(CreateTemplateRequest $request)
    {
        $data = $request->only(['name', 'type', 'content', 'params', 'type_id', 'layout_id']);

        return responseJsonData(true, [
            'template' => $this->templateService
                ->create($data)
                ->only(['name', 'type', 'content', 'params', 'type_id', 'layout_id'])
        ]);
    }

    public function edit(int $template, CreateTemplateRequest $request)
    {
        $data = $request->only('name', 'type', 'content', 'params');

        return responseJsonData(true, [
            'template' => $this->templateService->update($template, $data)
        ]);
    }

    public function get(Template $template)
    {
        $template = $template->only(['name', 'type', 'content', 'params', 'type_id', 'layout_id']);
        /*$name = Arr::get($template, 'name', '');

        if (str_starts_with($name, '{') && str_ends_with($name, '}')) {
            Arr::set($template, 'name', json_decode($name, true));
        }*/

        return responseJsonData(true, [
            'template' => $template,
            'theme_config' => $this->templateService->getThemeConfig()
        ]);
    }

    public function getByType(string $type)
    {
        return responseJsonData(true, ['list' => $this->templateService->getByType($type)]);
    }

    private function canDeleteTemplate(Template $template)
    {
        if ($template->type === Template::TYPE_LAYOUT) {
            return !Template::firstWhere('layout_id', $template->id);
        }

        $post = Post::firstWhere('template_id', $template->id);

        if ($post) {
            return false;
        }

        $category = Category::firstWhere('template_id', $template->id);

        if ($category) {
            return false;
        }

        $metasIds = WebsiteRepository::getInstance()->getCurrent()->metas->filter(function ($meta) {
            return
                str_ends_with($meta->meta_key, WebsiteMeta::POST_TEMPLATE_POSTFIX) ||
                str_ends_with($meta->meta_key, WebsiteMeta::CATEGORY_TEMPLATE_POSTFIX);
        })->pluck('meta_value')->map(function ($id) {
            return (int)$id;
        })->toArray();

        if (in_array($template->id, $metasIds)) {
            return false;
        }

        return true;
    }

    public function delete(Template $template)
    {
        $result = true;

        if (getCurrentWebsiteId() == $template->website_id) {
            if ($this->canDeleteTemplate($template)) {
                $result = $this->templateService->delete($template);
            } else {
                return responseJsonMessage(false, trans('error.template_used'));
            }
        }

        return responseJsonData($result, ['template' => $template]);
    }

    public function blocks()
    {
        return responseJsonData(true, [
            'blocks'       => config('templates'),
            'theme_config' => $this->templateService->getThemeConfig(),
        ]);
    }

    public function settings()
    {
        return responseJsonData(true, [
            'templates'    => [
                'category' => $this->templateService->getByType(Template::TYPE_CATEGORY),
                'post'     => $this->templateService->getByType(Template::TYPE_POST),
            ],
            //'theme_config' => $this->templateService->getThemeConfig($theme),
        ]);
    }

    public function themeConfig()
    {
        return responseJsonData(true, [
            //'theme_config' => $this->templateService->getThemeConfig($theme),
        ]);
    }
}
