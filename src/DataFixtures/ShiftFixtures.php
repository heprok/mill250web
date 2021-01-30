<?php

namespace App\DataFixtures;

use App\Entity\People;
use App\Entity\Shift;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ShiftFixtures extends Fixture
{
    const COUNT_SHIFT = 30;
    
    public function load(ObjectManager $manager)
    {
        $repositoryPeople = $manager->getRepository(People::class);
        $peoples = $repositoryPeople->findAll();
        $randomDatesTimestamp = AppFixtures::getRandomDatetime(self::COUNT_SHIFT);
        
        for ($i=0; $i < self::COUNT_SHIFT; $i++) { 
            $shift = new Shift();

            $startTime = new DateTime();
            $startTime->setTimestamp($randomDatesTimestamp[$i]);
            $shift->setStart($startTime);

            $stopTime = new DateTime();
            $stopTime->setTimestamp($randomDatesTimestamp[$i] + 8 * 60 * 60);
            $shift->setStop($stopTime);

            $shift->setNumber(rand(1, 2));
            $shift->setPeople($peoples[rand(0, PeopleFixtures::COUNT_PEOPLE - 1)]);

            $manager->persist($shift);
        }

        $manager->flush();
    }
}
