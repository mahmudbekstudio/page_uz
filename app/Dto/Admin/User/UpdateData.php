<?php
namespace App\Dto\Admin\User;

use Spatie\DataTransferObject\DataTransferObject;

class UpdateData extends DataTransferObject
{
    /**
     * @var string
     */
    public string $first_name;

    /**
     * @var string
     */
    public string $last_name;

    /**
     * @var string|null
     */
    public $password;

    /**
     * @var string
     */
    public $role;

    /**
     * @var int
     */
    public $status;
}
