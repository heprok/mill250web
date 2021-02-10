<?php

namespace App\DataFixtures;

use App\Entity\Downtime;
use App\Entity\DowntimeCause;
use App\Entity\DowntimeGroup;
use App\Entity\DowntimeLocation;
use App\Entity\DowntimePlace;
use DateInterval;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DowntimeFixtures extends Fixture
{
    const COUNT_CAUSE = 10;
    const COUNT_PLACE = 10;
    const COUNT_LOCATIONS = 3;
    const COUNT_GROUP = 3;
    const COUNT_DOWNTIME = 1000;

    public function load(ObjectManager $manager)
    {
        $arrPlace = [];
        $arrCause = [];

        $arrLocation = $this->loadDowntimeLocation($manager);
        $arrGroup = $this->loadDowntimeGroup($manager);

        for ($i = 0; $i < self::COUNT_CAUSE; $i++) {
            $cause = new DowntimeCause($i, 'Причина ' . ($i + 1));
            $cause->setGroups($arrGroup[array_rand($arrGroup)]);
            $arrCause[] = $cause;

            $manager->persist($cause);
        }

        for ($i = 0; $i < self::COUNT_PLACE; $i++) {
            $place = new DowntimePlace($i, "Место  " . ($i + 1));
            $arrPlace[] = $place;
            $place->setLocation($arrLocation[array_rand($arrLocation)]);
            $manager->persist($place);
        }

        $randomDatesTimestamp = AppFixtures::getRandomDatetime(self::COUNT_DOWNTIME);

        for ($i = 0; $i < self::COUNT_DOWNTIME; $i++) {
            $downtime = new Downtime();

            $startTime = new DateTime();
            $startTime->setTimestamp($randomDatesTimestamp[$i]);
            $downtime->setDrec($startTime);

            $stopTime = new DateTime();
            $stopTime->setTimestamp($randomDatesTimestamp[$i] + 3 * 60);
            $downtime->setFinish($stopTime);

            $downtime->setCause($arrCause[rand(0, self::COUNT_CAUSE - 1)]);
            $downtime->setPlace($arrPlace[rand(0, self::COUNT_PLACE - 1)]);
            $manager->persist($downtime);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @return DowntimeLocation[]
     */
    private function loadDowntimeLocation(ObjectManager $manager): array
    {
        $locations = [];

        for ($i = 0; $i < self::COUNT_LOCATIONS; $i++) {
            $location = new DowntimeLocation($i, 'Локация №' . ($i + 1));
            $locations[] = $location;
            $manager->persist($location);
        }

        $manager->flush();
        return $locations;
    }

    /**
     * @param ObjectManager $manager
     * @return DowntimeGroup[]
     */
    private function loadDowntimeGroup(ObjectManager $manager): array
    {
        $groups = [];

        for ($i = 0; $i < self::COUNT_GROUP; $i++) {
            $group = new DowntimeGroup($i, 'Группа причин №' . ($i + 1));
            $groups[] = $group;
            $manager->persist($group);
        }

        $manager->flush();
        return $groups;
    }
}
