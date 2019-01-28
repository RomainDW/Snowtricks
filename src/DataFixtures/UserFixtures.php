<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $vkey = md5(random_bytes(10));

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user, 'password'))
            ->setEmail('user@email.com')
            ->setRoles(['ROLE_USER'])
            ->setVkey($vkey);

        $manager->persist($user);
        $manager->flush();
    }
}
