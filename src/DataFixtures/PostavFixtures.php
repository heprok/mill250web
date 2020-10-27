<?php

namespace App\DataFixtures;

use App\Entity\Postav;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostavFixtures extends Fixture 
{
    const COUNT_POSTAV = 3;
    const POSTAV_JSON = [ 
        '{\'top\':160,\'butt\':185,\'name\':\'\',\'type\':\'TurnLeft\',\'elements\':[{\'type\':\'LeftCutter\',\'position\':-601},{\'type\':\'Plank\',\'width\':970,\'nom_width\':90,\'thickness\':240,\'nom_thickness\':20},{\'kerf\':34,\'type\':\'Saw\',\'position\':-344},{\'type\':\'Plank\',\'width\':1270,\'nom_width\':120,\'thickness\':310,\'nom_thickness\':27},{\'kerf\':34,\'type\':\'Saw\',\'position\':0},{\'type\':\'Plank\',\'width\':1270,\'nom_width\':120,\'thickness\':310,\'nom_thickness\':27},{\'kerf\':34,\'type\':\'Saw\',\'position\':344},{\'type\':\'Plank\',\'width\':970,\'nom_width\':90,\'thickness\':240,\'nom_thickness\':20},{\'type\':\'RightCutter\',\'position\':601}]}',
        '{\'top\':120,\'butt\':139,\'name\':\'\',\'type\':\'TurnLeft\',\'elements\':[{\'type\':\'LeftCutter\',\'position\':-534},{\'type\':\'Plank\',\'width\':790,\'nom_width\':72,\'thickness\':220,\'nom_thickness\':18},{\'kerf\':34,\'type\':\'Saw\',\'position\':-297},{\'type\':\'Plank\',\'width\':880,\'nom_width\':81,\'thickness\':560,\'nom_thickness\':51},{\'kerf\':34,\'type\':\'Saw\',\'position\':297},{\'type\':\'Plank\',\'width\':790,\'nom_width\':72,\'thickness\':220,\'nom_thickness\':18},{\'type\':\'RightCutter\',\'position\':534}]}',
        '{\'top\':120,\'butt\':152,\'name\':\'БЕЗ_ИМЕНИ\',\'type\':\'TurnLeft\',\'elements\':[{\'type\':\'LeftCutter\',\'position\':-379},{\'type\':\'Plank\',\'width\':1000,\'nom_width\':96,\'thickness\':170,\'nom_thickness\':15},{\'kerf\':34,\'type\':\'Saw\',\'position\':-192},{\'type\':\'Plank\',\'width\':1000,\'nom_width\':96,\'thickness\':350,\'nom_thickness\':32},{\'kerf\':34,\'type\':\'Saw\',\'position\':192},{\'type\':\'Plank\',\'width\':1000,\'nom_width\':96,\'thickness\':170,\'nom_thickness\':15},{\'type\':\'RightCutter\',\'position\':379}]}'
    ];
    public function load(ObjectManager $manager)
    {
        $randomDatesTimestamp = AppFixtures::getRandomDatetime(self::COUNT_POSTAV);
        
        for ($i=0; $i < self::COUNT_POSTAV; $i++) { 

            $postav = new Postav();
            $drec = new DateTime();
            $drec->setTimestamp($randomDatesTimestamp[$i]);
            $postav->setDrec($drec);
            $postav->setComm('Постав № ' . $i);
            $postav->setPostav([self::POSTAV_JSON[$i]]);
            $postav->setEnabled(true);
            $manager->persist($postav);
            
        }

        $manager->flush();
    }

}
