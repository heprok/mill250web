<?php

namespace App\DataFixtures;

use App\Entity\Bnom;
use App\Entity\Postav;
use App\Entity\Species;
use App\Entity\Timber;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TimberFixtures extends Fixture implements DependentFixtureInterface
{
    const COUNT_TIMBER = 10000;
    
    public function load(ObjectManager $manager)
    {
        $randomDatesTimestamp = AppFixtures::getRandomDatetime(self::COUNT_TIMBER);
        $postavs = $manager->getRepository(Postav::class)->findAll();
        $speices = $manager->getRepository(Species::class)->findAll();
        $boards = [];
        $boards[] = new Bnom(27, 90);
        $boards[] = new Bnom(27, 150);
        $boards[] = new Bnom(27, 150);
        $boards[] = new Bnom(27, 90);
        for ($i=0; $i < self::COUNT_TIMBER; $i++) { 
            $timber = new Timber();
            $timber->setScid(1);

            $drec = new DateTime();
            $drec->setTimestamp($randomDatesTimestamp[$i]);
            $timber->setDrec($drec);
            $timber->setDiam(rand(5, 8));
            $timber->setTop(rand(170, 190));
            $timber->setButt(rand(198, 200));            
            
            $timber->setTopTaper(rand(-2, 2));
            $timber->setButtTaper(rand(-2, 2));
            $timber->setLength(rand(4000, 4300));
            $timber->setSweep(rand(0, 1));
            $timber->setPostav($postavs[rand(0, count($postavs) - 1)]);

            $timber->setBoards($boards);
            $timber->setSpecies($speices[rand(0, count($speices)- 1 )]);

            $manager->persist($timber);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SpeciesFixtures::class,
            PostavFixtures::class,
            AppFixtures::class
        ];
    }
}
