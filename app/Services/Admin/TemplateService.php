<?php

namespace App\Services\Admin;

use App\Models\Template;
use App\Repositories\TemplateRepository;
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
        $name = Arr::get($fields, 'name');

        if (is_array($name)) {
            Arr::set($fields, 'name', json_encode($name));
        }

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

    public function detele(Template $template): bool
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
}
