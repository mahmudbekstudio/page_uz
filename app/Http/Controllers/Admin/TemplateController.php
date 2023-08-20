<?php

namespace App\Http\Controllers\Admin;

use App\DataTable\TemplateDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Template\CreateTemplateRequest;
use App\Models\Template;
use App\Repositories\TemplateRepository;
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

    public function delete(Template $template)
    {
        $result = true;

        if (getCurrentWebsiteId() == $template->website_id) {
            $result = $this->templateService->detele($template);
        }

        return responseJsonData($result, ['template' => $template]);
    }

    public function blocks()
    {
        return responseJsonData(true, ['templates' => config('templates')]);
    }
}
