<?php

namespace App\DataFixtures;

use App\Entity\Defect;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class DefectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 1500; $i++) {

            $defect = new Defect();
            $defect->setBinNumber('P-A-228-03');
            $defect->setItem('ELECTRO-1328154');
            $defect->setExpectedQuantity(random_int(0, 18));
            $defect->setActualQuantity(random_int(0, 18));
            $defect->setComment('it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "L');
            $defect->setAttachmentLink('testImageDansAssetsImages.png');
            $randomCount = 'count_' . random_int(0, 51);
            if ($randomCount === 'count_0') {
                $randomInvesigationStatus = random_int(0, 1);
                if ($randomInvesigationStatus === 0) {
                    $defect->setIsInvestigated(false);
                } else {
                    $defect->setIsInvestigated(true);
                }
            } else {
                $defect->setIsInvestigated(true);
            }
            $defect->setCount($this->getReference($randomCount));
            $manager->persist($defect);

        }

        $manager->flush();
    }
}