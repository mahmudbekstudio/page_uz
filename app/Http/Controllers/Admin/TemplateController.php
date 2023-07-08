<?php

namespace App\Http\Controllers\Admin;

use App\DataTable\TemplateDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Template\CreateTemplateRequest;
use App\Models\Template;
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

    public function create(CreateTemplateRequest $request)
    {
        $template = $this->templateService->create($request->only(['name', 'type', 'content', 'params']));
        return responseJsonData(true, ['template' => $template]);
    }

    public function edit(int $template, CreateTemplateRequest $request)
    {
        return responseJsonData(true, ['template' => $this->templateService->update($template, $request->only('name', 'type', 'content', 'params'))]);
    }

    public function get(Template $template)
    {
        return responseJsonData(true, ['template' => $template->only(['name', 'type', 'content', 'params'])]);
    }

    public function delete(Template $template)
    {
        if (getCurrentWebsiteId() == $template->website_id) {
            $template->delete();
        }

        return responseJsonData(true, ['template' => $template]);
    }

    public function blocks()
    {
        return responseJsonData(true, ['templates' => config('templates')]);
    }
}
