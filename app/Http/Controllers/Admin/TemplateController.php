<?php

namespace App\Http\Controllers\Admin;

use App\DataTable\TemplateDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Template\CreateTemplateRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Template;
use App\Models\WebsiteMeta;
use App\Repositories\TemplateRepository;
use App\Repositories\WebsiteRepository;
use App\Services\Admin\TemplateService;
use Illuminate\Support\Arr;

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

    public function create(CreateTemplateRequest $request)
    {
        $template = $this->templateService->create($request->only(['name', 'type', 'content', 'params', 'type_id', 'layout_id']));
        return responseJsonData(true, ['template' => $template]);
    }

    public function edit(int $template, CreateTemplateRequest $request)
    {
        return responseJsonData(true, ['template' => $this->templateService->update($template, $request->only('name', 'type', 'content', 'params'))]);
    }

    public function get(Template $template)
    {
        $template = $template->only(['name', 'type', 'content', 'params', 'type_id', 'layout_id']);
        $name = Arr::get($template, 'name', '');

        if (str_starts_with($name, '{') && str_ends_with($name, '}')) {
            Arr::set($template, 'name', json_decode($name, true));
        }

        return responseJsonData(true, ['template' => $template]);
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
            $postPostfix = WebsiteMeta::POST_TEMPLATE_POSTFIX;
            $categoryPostfix = WebsiteMeta::CATEGORY_TEMPLATE_POSTFIX;
            return str_ends_with($meta->meta_key, $postPostfix) || str_ends_with($meta->meta_key, $categoryPostfix);
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
        return responseJsonData(true, ['templates' => config('templates')]);
    }
}
