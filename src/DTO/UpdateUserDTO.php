<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/24/19
 * Time: 3:40 PM.
 */

namespace App\DTO;

use App\Entity\User;

class UpdateUserDTO
{
    public $username;
    public $picture;

    /**
     * UpdateUserDTO constructor.
     * @param $username
     * @param $picture
     */
    public function __construct($username, $picture)
    {
        $this->username = $username;
        $this->picture = $picture;
    }

    public static function createFromUser(User $user)
    {
        $userRequest = new self(
            $user->getUsername(),
            $user->getPicture()
        );

        return $userRequest;
    }
}
