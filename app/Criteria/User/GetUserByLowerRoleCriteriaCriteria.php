<?php

namespace App\Criteria\User;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class GetUserByLowerRoleCriteriaCriteria.
 *
 * @package namespace App\Criteria\User;
 */
class GetUserByLowerRoleCriteriaCriteria implements CriteriaInterface
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
        return $model->whereHas('roles', function ($query) {
            $query->whereIn('roles.name', config('app.manage.user.' . auth()->user()->role . '.read'));
        });
    }
}
