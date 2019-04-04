<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 6:11 PM.
 */

namespace App\Domain\DTO\Interfaces;

use App\Domain\Entity\User;

interface UpdateUserDTOInterface
{
    public function __construct($username, $picture);

    public static function createFromUser(User $user): self;
}
