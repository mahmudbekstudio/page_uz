<?php

namespace App\Criteria\Type;

use Illuminate\Database\Query\JoinClause;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class AddChildOfCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->leftJoin('types as category_type', function (JoinClause $join) {
            $join->on('types.child_of', '=', 'category_type.id');
        });

        return $model;
    }
}
