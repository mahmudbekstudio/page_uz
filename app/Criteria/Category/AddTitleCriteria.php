<?php

namespace App\Criteria\Category;

use Illuminate\Database\Query\JoinClause;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class AddTitleCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->leftJoin('category_metas as category_title', function (JoinClause $join) {
            $join
                ->on('categories.id', '=', 'category_title.category_id')
                ->where('category_title.meta_key', '=', 'title');
        });

        return $model;
    }
}
