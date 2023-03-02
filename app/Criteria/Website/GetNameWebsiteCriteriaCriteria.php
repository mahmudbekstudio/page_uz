<?php

namespace App\Criteria\Website;

use App\Models\Website;
use Illuminate\Database\Query\JoinClause;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AddMainWebsiteCriteriaCriteria.
 *
 * @package namespace App\Criteria\Website;
 */
class GetNameWebsiteCriteriaCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->join('website_metas as website_name', function (JoinClause $join) {
            $join->on('websites.id', '=', 'website_name.website_id')->where('website_name.meta_key', '=', 'name');
        });
        return $model;
    }
}
