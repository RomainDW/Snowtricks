<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 1/27/19
 * Time: 8:45 PM.
 */

namespace App\Service;

use App\Entity\User;
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
        $hasAccess = in_array('ROLE_USER_NOT_VERIFIED', $user->getRoles());

        if (!$hasAccess) {
            return 'Votre compte est déjà vérifié';
        } else {
            $user->setRoles(['ROLE_USER']);
            $this->manager->persist($user);
            $this->manager->flush();
            return "Merci d'avoir vérifié votre compte, vous êtes maintenant connecté !";
        }
    }
}
