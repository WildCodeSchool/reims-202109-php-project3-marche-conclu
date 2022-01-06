<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public const USERS = [
        [
            "email" => "David@email.com",
            "password" => "David",
            "role" => ["ROLE_ADMIN"],
            "firstname" => "David",
            "lastname" => "ADMIN",
        ],
        [
            "email" => "Robert@email.com",
            "password" => "Robert",
            "role" => ["ROLE_CONTRIBUTOR"],
            "firstname" => "Robert",
            "lastname" => "CONTRIB",
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $userData) {
            $contributor = new User();
            $contributor->setEmail($userData['email']);
            $contributor->setRoles($userData['role']);
            $contributor->setFirstname($userData['firstname']);
            $contributor->setLastname($userData['lastname']);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $contributor,
                $userData['password']
            );
            $this->addReference('user_' . $userData['email'], $contributor);
            $contributor->setPassword($hashedPassword);
            $manager->persist($contributor);
        }

        $manager->flush();
    }
}
