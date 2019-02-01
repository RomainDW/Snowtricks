<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/1/19
 * Time: 12:27 PM.
 */

namespace App\DTO;

class UserDTO
{
    private $email;

    private $username;

    private $password;

    private $vkey;

    private $role;

    /**
     * UserDTO constructor.
     *
     * @param $email
     * @param $password
     * @param $username
     *
     * @throws \Exception
     */
    public function __construct($email, $password, $username)
    {
        $vkey = md5(random_bytes(10));

        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->vkey = $vkey;
        $this->role = ['ROLE_USER_NOT_VERIFIED'];
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getVkey()
    {
        return $this->vkey;
    }

    public function getRole()
    {
        return $this->role;
    }
}
