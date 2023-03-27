<?php

namespace App\DataTable;

use App\Criteria\User\AddUserNameCriteriaCriteria;
use App\Criteria\User\AddUserRoleCriteriaCriteria;
use App\Repositories\UserRepository;

class UsersDataTable extends DataTable
{
    protected string $repositoryClass = UserRepository::class;
    protected string $sortBy = 'created_at';
    protected bool $sortDesc = true;
    protected array $columns = [
        'users.id' => 'id',
        'users.email' => 'email',
        'users.status' => 'status',
        'users.created_at' => 'created_at',
        'meta_last_name.meta_value' => 'last_name',
        'meta_first_name.meta_value' => 'first_name',
        'roles.name' => 'role'
    ];

    protected function handle()
    {
        return $this
            ->repository
            ->pushCriteria(AddUserNameCriteriaCriteria::class)
            ->pushCriteria(AddUserRoleCriteriaCriteria::class);
    }
}
