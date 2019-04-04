<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/1/19
 * Time: 12:27 PM.
 */

namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\UserRegistrationDTOInterface;
use App\Domain\Entity\Interfaces\ImageInterface;

class UserRegistrationDTO implements UserRegistrationDTOInterface
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
     * @param string         $username
     * @param string         $email
     * @param string         $password
     * @param string         $vkey
     * @param ImageInterface $picture
     * @param array          $roles
     */
    public function __construct($username, $email, $password, string $vkey = null, ImageInterface $picture = null, array $roles = ['ROLE_USER_NOT_VERIFIED'])
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->roles = $roles;
        $this->vkey = $vkey;
        $this->picture = $picture;
    }
}
