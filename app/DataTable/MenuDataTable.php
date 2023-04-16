<?php

namespace App\DataTable;

use App\Repositories\MenuRepository;

class MenuDataTable extends DataTable
{
    protected string $repositoryClass = MenuRepository::class;
    protected string $sortBy = 'created_at';
    protected bool $sortDesc = true;
    protected array $columns = [
        'menus.id' => 'id',
        'menus.name' => 'name',
        'menus.created_at' => 'created_at',
    ];

    protected function handle()
    {
        return $this->repository;
    }
}
