<?php

namespace App\Criteria\Post;

use Illuminate\Database\Query\JoinClause;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class SetTypeIdCriteria implements CriteriaInterface
{
    private $typeId;

    public function __construct($typeId)
    {
        $this->typeId = $typeId;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('posts.type_id', '=', $this->typeId);
    }
}
