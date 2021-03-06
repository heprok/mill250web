<?php

namespace App\DataFixtures;

use App\Entity\Duty;
use App\Entity\People;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PeopleFixtures extends Fixture
{
    const COUNT_PEOPLE = 10;
    
    public function load(ObjectManager $manager)
    {
        $arr_pat = [ 'Филимонович','Демьянович','Мечиславович','Климентович', 'Олегович', 'Левович', 'Филиппович', 'Чеславович', 'Ростиславович', 'Макарович'];
        $arr_fam = ['Яхаев','Радыгин','Погребной','Цыганков','Брагин','Рекунов','Толстобров','Носачёв','Шкловский','Васенин'];
        $arr_nam = [ 'Афанасий', 'Арсений', 'Еремей', 'Клавдий', 'Евстигней', 'Рубен', 'Варфоломей', 'Саввелий', 'Евгений', 'Агап'];

        $duties = $manager->getRepository(Duty::class)->findAll();
        for ($i=0; $i < self::COUNT_PEOPLE; $i++) { 
            $people = new People($arr_fam[rand(0, count($arr_fam) - 1)]);
            $people->setPat($arr_pat[rand(0, count($arr_pat) - 1)]);
            $people->setNam($arr_nam[rand(0, count($arr_nam) - 1)]);
            foreach ((array)array_rand($duties, rand(1, count($duties) - 1)) as $keyDuty) {
                $people->addDuty($duties[$keyDuty]);
            }

            $manager->persist($people);
        }
        
        $manager->flush();
    }


    public function getDependencies()
    {
        return [
            DutyFixtures::class,
        ];
    }
}
