<?php

namespace App\DataTable;

use App\Repositories\TemplateRepository;

class TemplateDataTable extends DataTable
{
    protected string $repositoryClass = TemplateRepository::class;
    protected string $sortBy = 'created_at';
    protected bool $sortDesc = true;
    protected array $columns = [
        'templates.id' => 'id',
        'templates.name' => 'name',
        'templates.type' => 'type',
        'templates.created_at' => 'created_at',
    ];

    protected function handle()
    {
        return $this->repository;
    }
}
