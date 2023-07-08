<?php

namespace App\Services\Admin;

use App\Repositories\TemplateRepository;
use App\Services\BaseService;

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
        return $this->templateRepository->create($fields)->only(['name', 'type', 'content', 'params']);
    }
}
