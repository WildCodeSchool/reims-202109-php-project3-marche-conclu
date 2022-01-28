<?php

namespace App\DataFixtures;

use App\Entity\SpaceDisponibility;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SpaceDisponibilityFixtures extends Fixture implements DependentFixtureInterface
{
    private const SPACE_COUNT = 30;

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= self::SPACE_COUNT; $i++) {
            $spaceDisponibility = new SpaceDisponibility();
            $spaceDisponibility->setSpace($this->getReference('Espace ' . $i));
            $spaceDisponibility->setMonday("09h-12h");
            $spaceDisponibility->setTuesday("");
            $spaceDisponibility->setWednesday("");
            $spaceDisponibility->setThursday("");
            $spaceDisponibility->setFriday("");
            $spaceDisponibility->setSaturday("");
            $spaceDisponibility->setSunday("");

            $manager->persist($spaceDisponibility);
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            SpaceFixtures::class,
        ];
    }
}
