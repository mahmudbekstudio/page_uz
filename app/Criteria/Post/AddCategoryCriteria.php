<?php

namespace App\Criteria\Post;

use Illuminate\Database\Query\JoinClause;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class AddCategoryCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->leftJoin('category_metas', function (JoinClause $join) {
            $join
                ->on('posts.category_id', '=', 'category_metas.category_id')
                ->where('category_metas.meta_key', '=', 'title');
        });

        return $model;
    }
}
