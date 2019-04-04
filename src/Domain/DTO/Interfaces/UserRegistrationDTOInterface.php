<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 6:11 PM.
 */

namespace App\Domain\DTO\Interfaces;

use App\Domain\Entity\Interfaces\ImageInterface;

interface UserRegistrationDTOInterface
{
    public function __construct($username, $email, $password, string $vkey = null, ImageInterface $picture = null, array $roles = ['ROLE_USER_NOT_VERIFIED']);
}
