<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/1/19
 * Time: 12:27 PM.
 */

namespace App\DTO;

class UserRegistrationDTO
{
    public $email;
    public $username;
    public $password;
    public $vkey;
    public $roles;

    /**
     * UserRegistrationDTO constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $vkey = md5(random_bytes(10));
        $this->vkey = $vkey;
    }

    public function createUser($username, $email, $password, $roles = ['ROLE_USER_NOT_VERIFIED'])
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->roles = $roles;
    }
}
