<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/24/19
 * Time: 3:40 PM.
 */

namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\UpdateUserDTOInterface;
use App\Domain\Entity\User;

class UpdateUserDTO implements UpdateUserDTOInterface
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

    public static function createFromUser(User $user): UpdateUserDTOInterface
    {
        $userRequest = new self(
            $user->getUsername(),
            $user->getPicture()
        );

        return $userRequest;
    }
}
