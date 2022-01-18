<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Space;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SpaceFixtures extends Fixture implements DependentFixtureInterface
{
    private const TYPESPACE = ['Bureau privé', 'Co-Working', 'Salle de réunion', 'Open Space', 'Espace de stockage'];
    private const LOCATION = ['Reims', 'Charleville', 'Metz'];
    private const SPACE_COUNT = 30;
    private const SPACE_IMAGES = [
        'dane-deaner.jpg', 'coworking.jpeg', 'open_space.jpg',
        'openspace.jpg', 'salle_de_reunion.jpg', 'stockage.jpg'
    ];
    private const ADDRESS = ['6 rue de Saint Brice', '4 rue Emile Nivelet', '12 Rue Pasteur'];

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= self::SPACE_COUNT; $i++) {
            $space = new Space();
            $space->setName('Espace ' . $i);
            $randImage = array_rand(self::SPACE_IMAGES);
            $space->setPhoto(self::SPACE_IMAGES[$randImage]);
            $space->setPrice((rand(1, 10) / 10) * rand(100, 200));
            $space->setSurface(rand(5, 200));
            $typespace = array_rand(self::TYPESPACE);
            $space->setCategory(self::TYPESPACE[$typespace]);
            $space->setCapacity(rand(10, 100));
            $locationAndAdress = array_rand(self::LOCATION);
            $space->setLocation(self::LOCATION[$locationAndAdress]);
            $this->addReference($space->getName(), $space);
            $space->setOwner($this->getReference('user_j.v@gmail.com'));
            $space->setAddress(self::ADDRESS[$locationAndAdress]);
            $space->setAvailability("");
            $space->setUpdatedAt(new DateTime('now'));
            $manager->persist($space);
            $address = array_rand(self::ADDRESS);
            $space->setAddress(self::ADDRESS[$address]);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
