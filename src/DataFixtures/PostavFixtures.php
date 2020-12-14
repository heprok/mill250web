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
        '{"butt":337,"elements":[{"position":-1275,"type":"LeftCutter"},{"nom_thickness":22,"nom_width":122,"thickness":230,"type":"Plank","width":1250},{"kerf":46,"position":-1022,"type":"Saw"},{"nom_thickness":28,"nom_width":200,"thickness":300,"type":"Plank","width":2030},{"kerf":46,"position":-676,"type":"Saw"},{"nom_thickness":60,"nom_width":250,"thickness":630,"type":"Plank","width":2550},{"kerf":46,"position":0,"type":"Saw"},{"nom_thickness":60,"nom_width":250,"thickness":630,"type":"Plank","width":2550},{"kerf":46,"position":676,"type":"Saw"},{"nom_thickness":28,"nom_width":200,"thickness":300,"type":"Plank","width":2030},{"kerf":46,"position":1022,"type":"Saw"},{"nom_thickness":22,"nom_width":122,"thickness":230,"type":"Plank","width":1250},{"position":1275,"type":"RightCutter"}],"name":"29_60⨯255-2_28⨯200-2_22⨯122-2","top":260,"type":"TurnLeft"}',
        '{"butt":633,"elements":[{"position":-1275,"type":"LeftCutter"},{"nom_thickness":22,"nom_width":122,"thickness":230,"type":"Plank","width":1250},{"kerf":46,"position":-1022,"type":"Saw"},{"nom_thickness":28,"nom_width":200,"thickness":300,"type":"Plank","width":2030},{"kerf":46,"position":-676,"type":"Saw"},{"nom_thickness":60,"nom_width":250,"thickness":630,"type":"Plank","width":2550},{"kerf":46,"position":0,"type":"Saw"},{"nom_thickness":60,"nom_width":250,"thickness":630,"type":"Plank","width":2550},{"kerf":46,"position":676,"type":"Saw"},{"nom_thickness":28,"nom_width":200,"thickness":300,"type":"Plank","width":2030},{"kerf":46,"position":1022,"type":"Saw"},{"nom_thickness":22,"nom_width":122,"thickness":230,"type":"Plank","width":1250},{"position":1275,"type":"RightCutter"}],"name":"29_60⨯260-2_28⨯200-2_22⨯122-2","top":256,"type":"TurnLeft"}',
        '{"butt":334,"elements":[{"position":-1275,"type":"LeftCutter"},{"nom_thickness":22,"nom_width":122,"thickness":230,"type":"Plank","width":1250},{"kerf":46,"position":-1022,"type":"Saw"},{"nom_thickness":28,"nom_width":200,"thickness":300,"type":"Plank","width":2030},{"kerf":46,"position":-676,"type":"Saw"},{"nom_thickness":60,"nom_width":250,"thickness":630,"type":"Plank","width":2550},{"kerf":46,"position":0,"type":"Saw"},{"nom_thickness":60,"nom_width":250,"thickness":630,"type":"Plank","width":2550},{"kerf":46,"position":676,"type":"Saw"},{"nom_thickness":28,"nom_width":200,"thickness":300,"type":"Plank","width":2030},{"kerf":46,"position":1022,"type":"Saw"},{"nom_thickness":22,"nom_width":122,"thickness":230,"type":"Plank","width":1250},{"position":1275,"type":"RightCutter"}],"name":"29_60⨯290-2_28⨯200-2_22⨯122-2","top":233,"type":"TurnLeft"}'
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
