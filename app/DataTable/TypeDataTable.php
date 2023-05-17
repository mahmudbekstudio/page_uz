<?php

namespace App\DataTable;

use App\Criteria\Type\AddChildOfCriteria;
use App\Repositories\TypeRepository;

class TypeDataTable extends DataTable
{
    protected string $repositoryClass = TypeRepository::class;
    protected string $sortBy = 'created_at';
    protected bool $sortDesc = true;
    protected array $columns = [
        'types.id' => 'id',
        'types.status' => 'status',
        'types.title' => 'title',
        'types.name' => 'name',
        'types.type' => 'type',
        'types.has_parent' => 'has_parent',
        'category_type.name' => 'child_of',
        'types.created_at' => 'created_at',
    ];

    protected function handle()
    {
        return $this
            ->repository
            ->pushCriteria(AddChildOfCriteria::class);
    }
}
