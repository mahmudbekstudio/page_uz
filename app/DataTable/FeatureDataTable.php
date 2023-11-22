<?php

namespace App\DataTable;

use App\Criteria\Feature\AddFeatureTypeCriteria;
use App\Criteria\Feature\AddParentCriteria;
use App\Repositories\FeatureRepository;

class FeatureDataTable extends DataTable
{
    protected string $repositoryClass = FeatureRepository::class;
    protected string $sortBy = 'created_at';
    protected bool $sortDesc = true;
    protected array $columns = [
        'features.id' => 'id',
        'features.name' => 'name',
        'feature_types.name' => 'type',
        'types.title' => 'parent',
        'features.created_at' => 'created_at',
    ];

    protected function handle()
    {
        return $this->repository
            ->pushCriteria(AddFeatureTypeCriteria::class)
            ->pushCriteria(AddParentCriteria::class);
    }
}
