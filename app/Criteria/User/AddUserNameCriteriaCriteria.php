<?php

namespace App\Criteria\User;

use App\Models\User;
use Illuminate\Database\Query\JoinClause;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AddUserFirstNameCriteriaCriteria.
 *
 * @package namespace App\Criteria\User;
 */
class AddUserNameCriteriaCriteria implements CriteriaInterface
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
        $model = $model->join('user_metas as meta_last_name', function (JoinClause $join) {
            $join->on('users.id', '=', 'meta_last_name.user_id')->where('meta_last_name.meta_key', '=', 'last_name');
        });
        $model = $model->join('user_metas as meta_first_name', function (JoinClause $join) {
            $join->on('users.id', '=', 'meta_first_name.user_id')->where('meta_first_name.meta_key', '=', 'first_name');
        });
        return $model;
    }
}
