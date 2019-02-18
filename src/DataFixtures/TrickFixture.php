<?php

namespace App\DataFixtures;

use App\DTO\UserRegistrationDTO;
use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TrickFixture extends Fixture
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

        // encode password
        $password = ($this->passwordEncoder->encodePassword($user, 'password'));

        $userRegistrationDTO = new UserRegistrationDTO('Romain', 'user@email.com', $password, null, ['ROLE_USER']);

        $user->createFromRegistration($userRegistrationDTO);

        $category1 = new Category();
        $category1 = $category1->setName('Catégorie 1');
        $category2 = new Category();
        $category2 = $category2->setName('Catégorie 2');

        for ($i = 0; $i < 20; ++$i) {
            $trick = new Trick();
            $trick->setTitle('Trick n°'.$i);
            $trick->setSlug('trick-'.$i);
            $trick->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod 
			tempor incididunt ut labore et dolore magna aliqua. Sit amet cursus sit amet dictum sit amet justo.');
            $trick->setCreatedAt(new \DateTime());

            rand(0, 1) ? $category = $category1 : $category = $category2;

            $trick->setCategory($category);
            $trick->setUser($user);

            $manager->persist($user);
            $manager->persist($category1);
            $manager->persist($category2);
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
