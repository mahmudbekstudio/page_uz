<?php
namespace App\Dto\Auth;

use Spatie\DataTransferObject\DataTransferObject;

class LoginData extends DataTransferObject
{
    /**
     * @var string
     */
    public string $email;

    /**
     * @var string
     */
    public string $password;
}