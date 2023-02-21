<?php
namespace App\Dto\Admin\User;

use Spatie\DataTransferObject\DataTransferObject;

class ProfileData extends DataTransferObject
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
     * @var string|null
     */
    public $old_password;
}