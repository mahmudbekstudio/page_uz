<?php

namespace App\DataTable;

use App\Criteria\Category\AddParentCriteria;
use App\Criteria\Category\AddTitleCriteria;
use App\Criteria\Category\SetTypeIdCriteria;
use App\Repositories\CategoryRepository;

class CategoryDataTable extends DataTable
{
    protected string $repositoryClass = CategoryRepository::class;
    protected string $sortBy = 'created_at';
    protected bool $sortDesc = true;
    protected array $columns = [
        'categories.id' => 'id',
        'categories.status' => 'status',
        'categories.created_at' => 'created_at',
        'category_title.meta_value' => 'title',
        'parent_category.meta_value' => 'parent'
    ];

    protected $typeId;

    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
        return $this;
    }

    protected function handle()
    {
        return $this
            ->repository
            ->pushCriteria(new SetTypeIdCriteria($this->typeId))
            ->pushCriteria(AddTitleCriteria::class)
            ->pushCriteria(AddParentCriteria::class);
    }
}
