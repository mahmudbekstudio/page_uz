<?php

namespace App\Criteria\Website;

use App\Models\Website;
use App\Repositories\WebsiteRepository;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AddMainWebsiteCriteriaCriteria.
 *
 * @package namespace App\Criteria\Website;
 */
class GetRootWebsiteCriteriaCriteria implements CriteriaInterface
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
        /** @var Website $currentWebsite */
        $currentWebsite = WebsiteRepository::getInstance()->getCurrent();
        $model = $model->where('websites.group_id', $currentWebsite->group_id);
        $model = $model->whereNull('websites.domain_id');
        return $model;
    }
}
