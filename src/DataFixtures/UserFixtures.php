<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class UserFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $mainParentalUnit = $this->getParentalUnitByName('Main');
        $secondaryParentalUnit = $this->getParentalUnitByName('Secondary');

        $parent1 = (new User())
            ->setParentalUnit($mainParentalUnit)
            ->setName($this->encrypt('John'))
        ;
        $parent2 = (new User())
            ->setParentalUnit($mainParentalUnit)
            ->setName($this->encrypt('Jane'))
        ;
        $parent3 = (new User())
            ->setParentalUnit($secondaryParentalUnit)
            ->setName($this->encrypt('Nick'))
        ;

        $manager->persist($parent1);
        $manager->persist($parent2);
        $manager->persist($parent3);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ParentalUnitFixtures::class,
        ];
    }
}
