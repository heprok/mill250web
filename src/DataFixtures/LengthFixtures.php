<?php

namespace App\DataFixtures;

use App\Entity\Length;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LengthFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $length = new Length(3000);
        $length->setMinimum(0);
        $length->setMaximum(3990);
        $manager->persist($length);

        $length = new Length(4000);
        $length->setMinimum(3990);
        $length->setMaximum(4990);
        $manager->persist($length);

        $length = new Length(5000);
        $length->setMinimum(4990);
        $length->setMaximum(5990);
        $manager->persist($length);

        $length = new Length(6000);
        $length->setMinimum(5990);
        $length->setMaximum(9000);
        $manager->persist($length);

        $manager->flush();
    }
}
