<?php

namespace App\Criteria\Feature;

use Illuminate\Database\Query\JoinClause;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class AddTypeCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->leftJoin('types', function (JoinClause $join) {
            $join
                ->on('features.type_id', '=', 'types.id');
        });

        return $model;
    }
}
