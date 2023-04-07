<?php

namespace App\Criteria\Post;

use Illuminate\Database\Query\JoinClause;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class AddTitleCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->leftJoin('post_metas as post_title', function (JoinClause $join) {
            $join
                ->on('posts.id', '=', 'post_title.post_id')
                ->where('post_title.meta_key', '=', 'title');
        });

        return $model;
    }
}
