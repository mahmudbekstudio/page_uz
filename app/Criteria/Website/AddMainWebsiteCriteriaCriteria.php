<?php

namespace App\Criteria\Website;

use App\Models\Website;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AddMainWebsiteCriteriaCriteria.
 *
 * @package namespace App\Criteria\Website;
 */
class AddMainWebsiteCriteriaCriteria implements CriteriaInterface
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
        $model = $model->leftJoin('websites as main_website', function (JoinClause $join) {
            $join
                ->on('websites.id', '=', 'main_website.domain_id')
                ->on('main_website.type', '=', DB::raw(Website::TYPE_MAIN));
        });
        return $model;
    }
}
