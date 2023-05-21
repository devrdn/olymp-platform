<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("john.doe@gmail.com");
        $user->setName("John Doe");
        $user->setUsername("john_doe");
        $user->setPassword("password");
        $user->setCreatedAt(new \DateTimeImmutable());

        $manager->persist($user);
        $manager->flush();
    }
}