<?php

namespace App\DataTable;

use App\Criteria\Post\AddCategoryCriteria;
use App\Criteria\Post\AddParentCriteria;
use App\Criteria\Post\AddTitleCriteria;
use App\Criteria\Post\SetTypeIdCriteria;
use App\Repositories\PostRepository;

class PostDataTable extends DataTable
{
    protected string $repositoryClass = PostRepository::class;
    protected string $sortBy = 'created_at';
    protected bool $sortDesc = true;
    protected array $columns = [
        'posts.id' => 'id',
        'posts.status' => 'status',
        'posts.created_at' => 'created_at',
        'post_title.meta_value' => 'title',
        'parent_post.meta_value' => 'parent',
        'category_metas.meta_value' => 'category'
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
            ->pushCriteria(AddParentCriteria::class)
            ->pushCriteria(AddCategoryCriteria::class);
    }
}
