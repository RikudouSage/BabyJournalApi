<?php

namespace App\DataFixtures;

use App\Entity\ParentalUnit;
use Doctrine\Persistence\ObjectManager;

class ParentalUnitFixtures extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $parentalUnit1 = (new ParentalUnit())
            ->setName($this->encrypt('Main'));
        $parentalUnit2 = (new ParentalUnit())
            ->setName($this->encrypt('Secondary'));

        $manager->persist($parentalUnit1);
        $manager->persist($parentalUnit2);

        $manager->flush();
    }
}
