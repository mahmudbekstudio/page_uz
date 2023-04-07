<?php

namespace App\Criteria\Post;

use Illuminate\Database\Query\JoinClause;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class AddParentCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->leftJoin('post_metas as parent_post', function (JoinClause $join) {
            $join
                ->on('posts.parent_id', '=', 'parent_post.post_id')
                ->where('parent_post.meta_key', '=', 'title');
        });

        return $model;
    }
}
