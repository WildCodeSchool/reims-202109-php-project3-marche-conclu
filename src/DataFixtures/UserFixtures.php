<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public const USERS = [
        [
            "email" => "m.p@gmail.com",
            "password" => "marion",
            "role" => ["ROLE_CONTRIBUTOR"],
            "firstname" => "Marion",
            "lastname" => "PATINET",
            "phoneNumber" => "0688225932",
            "job" => "Chef de projet",
            "company" => "Champagne Ruinart"
        ],
        [
            "email" => "j.v@gmail.com",
            "password" => "jordan",
            "role" => ["ROLE_ADMIN"],
            "firstname" => "Jordan",
            "lastname" => "VERREAUX",
            "phoneNumber" => "0618225933",
            "job" => "Chef d'entreprise",
            "company" => "ConnecT"
        ],
        [
            "email" => "David@email.com",
            "password" => "David",
            "role" => ["ROLE_ADMIN"],
            "firstname" => "David",
            "lastname" => "ADMIN",
            "phoneNumber" => "0688775932",
            "job" => "Comptable",
            "company" => "Champagne Krug"
        ],
        [
            "email" => "Robert@email.com",
            "password" => "Robert",
            "role" => ["ROLE_CONTRIBUTOR"],
            "firstname" => "Robert",
            "lastname" => "CONTRIB",
            "phoneNumber" => "0688224932",
            "job" => "Salarié",
            "company" => "Forbo"
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
            $contributor->setphoneNumber($userData['phoneNumber']);
            $contributor->setJob($userData['job']);
            $contributor->setCompany($userData['company']);
            $contributor->setPhoto('symbole-github-bleu-61e6d56a9a578776627036.png');
            $contributor->setUpdatedAt(new DateTime('now'));

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
