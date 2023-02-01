<?php

namespace App\DataFixtures;

use App\Entity\Reason;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ReasonFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $reason1 = new Reason();
        $reason1->setName('wrong quantity stowed');
        $this->addReference('reason_1', $reason1);
        $manager->persist($reason1);

        $reason2 = new Reason();
        $reason2->setName('item stowed in the adjacent bin');
        $this->addReference('reason_2', $reason2);
        $manager->persist($reason2);

        $reason3 = new Reason();
        $reason3->setName('wrong quantity picked');
        $this->addReference('reason_3', $reason3);
        $manager->persist($reason3);

        $reason4 = new Reason();
        $reason4->setName('item picked in the adjacent bin');
        $this->addReference('reason_4', $reason4);
        $manager->persist($reason4);

        $reason5 = new Reason();
        $reason5->setName('theft');
        $this->addReference('reason_5', $reason5);
        $manager->persist($reason5);

        $reason6 = new Reason();
        $reason6->setName('unknown');
        $this->addReference('reason_6', $reason6);
        $manager->persist($reason6);

        $manager->flush();
    }
}
