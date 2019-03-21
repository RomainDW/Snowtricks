<?php

namespace App\DataFixtures;

use App\DTO\CreateTrickDTO;
use App\DTO\UserRegistrationDTO;
use App\Domain\Entity\Category;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use App\Utils\Slugger;
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
     *
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();

        // encode password
        $password = ($this->passwordEncoder->encodePassword($user, 'password'));

        $userRegistrationDTO = new UserRegistrationDTO('Romain', 'user@email.com', $password, null, null, ['ROLE_USER']);

        $user->createFromRegistration($userRegistrationDTO);

        $category1 = new Category();
        $category1 = $category1->setName('Catégorie 1');
        $category2 = new Category();
        $category2 = $category2->setName('Catégorie 2');
        $category3 = new Category();
        $category3 = $category3->setName('Catégorie 3');

        $categories = [
            1 => $category1,
            2 => $category2,
            3 => $category3,
        ];

        for ($i = 0; $i < 20; ++$i) {
            $category = $categories[rand(1, 3)];
            $title = 'Trick n°'.$i;
            $slug = Slugger::slugify($title);

            $trickDTO = new CreateTrickDTO(
                $title,
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut 
                labore et dolore magna aliqua. Sit amet cursus sit amet dictum sit amet justo.',
                $category,
                [],
                []
            );

            $trickDTO->createdAt = new \DateTime();
            $trickDTO->user = $user;
            $trickDTO->slug = $slug;

            $trick = new Trick($trickDTO);

            $manager->persist($user);
            $manager->persist($category1);
            $manager->persist($category2);
            $manager->persist($category3);
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
