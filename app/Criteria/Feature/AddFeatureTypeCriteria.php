<?php

namespace App\Criteria\Feature;

use Illuminate\Database\Query\JoinClause;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class AddFeatureTypeCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->leftJoin('feature_types', function (JoinClause $join) {
            $join
                ->on('features.type_id', '=', 'feature_types.id');
        });

        return $model;
    }
}
