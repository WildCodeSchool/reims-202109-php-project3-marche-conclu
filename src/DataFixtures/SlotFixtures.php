<?php

namespace App\DataFixtures;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Slot;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SlotFixtures extends Fixture implements DependentFixtureInterface
{

    private const SPACE_COUNT = 30;
    private const TIMESTAMP_START = 1638316800;
    private const TIMESTAMP_END = 1638921600;

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= self::SPACE_COUNT; $i++) {
            $slot = new Slot();
            $slot->setPrice((rand(1, 10) / 10) * rand(100, 200));
            $timestamp = rand(self::TIMESTAMP_START, self::TIMESTAMP_END);
            $slot->setSlotTime(
                new DateTime(
                    date("Y-m-d", $timestamp)
                )
            );
            $slot->setSpace($this->getReference('Espace ' . rand(1, 30)));
            $slot->setOwner($this->getReference('user_David@email.com'));
            $manager->persist($slot);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SpaceFixtures::class,
        ];
    }
}
