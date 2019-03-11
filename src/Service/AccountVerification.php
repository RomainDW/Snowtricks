<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 1/27/19
 * Time: 8:45 PM.
 */

namespace App\Service;

use App\Domain\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class AccountVerification
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function verification(User $user)
    {
        $hasAccess = $user->hasRole('ROLE_USER_NOT_VERIFIED');

        if (!$hasAccess) {
            return false;
        } else {
            $user->updateRole(['ROLE_USER']);
            $user->eraseCredentials();
            $this->manager->persist($user);
            $this->manager->flush();
            return true;
        }
    }
}
