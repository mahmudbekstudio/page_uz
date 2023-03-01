<?php
namespace App\Dto\Admin\User;

use Spatie\DataTransferObject\DataTransferObject;

class CreateData extends DataTransferObject
{
    /**
     * @var string
     */
    public string $email;

    /**
     * @var string
     */
    public string $first_name;

    /**
     * @var string
     */
    public string $last_name;

    /**
     * @var string
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
