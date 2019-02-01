<?php

namespace App\DataFixtures;

use App\DTO\UserRegistrationDTO;
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
     *
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $userRegistrationDTO = new UserRegistrationDTO();

        // encode password
        $password = ($this->passwordEncoder->encodePassword($user, 'password'));

        $userRegistrationDTO->createUser('Romain', 'user@email.com', $password, ['ROLE_USER']);

        $user->createFromRegistration($userRegistrationDTO);

        $manager->persist($user);
        $manager->flush();
    }
}
