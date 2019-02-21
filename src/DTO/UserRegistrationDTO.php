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
    public $picture;

    /**
     * UserRegistrationDTO constructor.
     *
     * @param string     $username
     * @param string     $email
     * @param string     $password
     * @param string     $vkey
     * @param PictureDTO $picture
     * @param array      $roles
     */
    public function __construct(string $username, string $email, string $password, string $vkey = null, PictureDTO $picture = null, array $roles = ['ROLE_USER_NOT_VERIFIED'])
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->roles = $roles;
        $this->vkey = $vkey;
        $this->picture = $picture;
    }
}
