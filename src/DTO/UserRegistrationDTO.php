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
     * @param $username
     * @param $email
     * @param $password
     * @param null  $vkey
     * @param array $roles
     */
    public function __construct($username, $email, $password, $vkey = null, $roles = ['ROLE_USER_NOT_VERIFIED'])
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->roles = $roles;
        $this->vkey = $vkey;
    }
}
