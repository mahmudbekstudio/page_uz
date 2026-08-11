<?php

namespace App\Criteria\Block;

use Illuminate\Database\Query\JoinClause;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class AddTitleCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->leftJoin('block_metas as block_title', function (JoinClause $join) {
            $join
                ->on('blocks.id', '=', 'block_title.block_id')
                ->where('block_title.meta_key', '=', 'title');
        });

        return $model;
    }
}
