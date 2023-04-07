<?php

namespace App\Criteria\Category;

use Illuminate\Database\Query\JoinClause;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class AddParentCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->leftJoin('category_metas as parent_category', function (JoinClause $join) {
            $join
                ->on('categories.parent_id', '=', 'parent_category.category_id')
                ->where('parent_category.meta_key', '=', 'title');
        });

        return $model;
    }
}
