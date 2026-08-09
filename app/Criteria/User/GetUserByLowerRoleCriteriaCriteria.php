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
    private $roles = [];
    public function __construct()
    {
        $userRoles = config('app.userRoles');
        $currentUserRole = auth()->user()->role;
        $isFound = false;
        foreach ($userRoles as $role) {
            if ($currentUserRole == $role) {
                $isFound = true;
            }

            if ($isFound) {
                $this->roles[] = $role;
            }
        }
    }

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
            $query->whereIn('roles.name', $this->roles);
        });
    }
}
