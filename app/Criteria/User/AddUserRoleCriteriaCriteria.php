<?php

namespace App\Criteria\User;

use Illuminate\Database\Query\JoinClause;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AddUserRoleCriteriaCriteria.
 *
 * @package namespace App\Criteria\User;
 */
class AddUserRoleCriteriaCriteria implements CriteriaInterface
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
        $model = $model->join('model_has_roles', function (JoinClause $join) use ($repository) {
            $join->on('users.id', '=', 'model_has_roles.model_id')->where('model_has_roles.model_type', '=', $repository->model());
        });
        $model = $model->join('roles', function (JoinClause $join) use ($repository) {
            $join->on('model_has_roles.role_id', '=', 'roles.id');
        });
        return $model;
    }
}
