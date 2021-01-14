<?php

namespace App\DataFixtures;

use App\Entity\Duty;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DutyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $duty = new Duty('op', 'Оператор');
        $manager->persist($duty);

        $duty = new Duty('se', 'Секретарь');
        $manager->persist($duty);

        $duty = new Duty('mo', 'Монтажник');
        $manager->persist($duty);

        $duty = new Duty('me', 'Менеджер');
        $manager->persist($duty);

        $manager->flush();
    }
}
