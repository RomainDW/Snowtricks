<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TrickFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; ++$i) {
            $trick = new Trick();
            $trick->setTitle('Trick n°'.$i);
            $trick->setSlug('trick-'.$i);
            $trick->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod 
			tempor incididunt ut labore et dolore magna aliqua. Sit amet cursus sit amet dictum sit amet justo.');
            $trick->setCreatedAt(new \DateTime());
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
