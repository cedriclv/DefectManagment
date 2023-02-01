<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Count;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Validator\Constraints\Date;

class CountFixtures extends Fixture
{

    public const NB_COUNT = 5;
    public function load(ObjectManager $manager): void
    {

        for ($i=0; $i < self::NB_COUNT; $i++) { 
            $count = new Count();
            $date = new DateTime('now -'.($i*7).' day');//
            $count->setDate($date);
            $this->addReference('count_' . $i, $count);
            $manager->persist($count);
        }


        $manager->flush();
    }
}
