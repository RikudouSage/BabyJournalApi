<?php

namespace App\DataFixtures;

use App\Entity\Child;
use App\Repository\ParentalUnitRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class ChildFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $child1 = (new Child())
            ->setName($this->encrypt('Janice'))
            ->setParentalUnit($this->getParentalUnitByName('Main'))
        ;
        $child2 = (new Child())
            ->setName($this->encrypt('Noel'))
            ->setParentalUnit($this->getParentalUnitByName('Secondary'))
        ;

        $manager->persist($child1);
        $manager->persist($child2);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ParentalUnitFixtures::class,
        ];
    }
}
