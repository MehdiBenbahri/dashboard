<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $faker->seed(1255468);

        for ($i = 0; $i < 1500; $i++){
            $user = new User();
            $createdAt = $faker->dateTimeBetween("-30 days","+30 days");
            
            $faker->seed(1000 + $i);
            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setName($faker->name())
                ->setEmail($faker->email())
                ->setBanned($faker->boolean(25))
                ->setDisabled($faker->boolean(15))
                ->setCreatedAt(new \DateTimeImmutable($createdAt->format("Y-m-d H:i:s")))
                ->setUpdatedAt(new \DateTimeImmutable($createdAt->modify("+ ". $faker->numberBetween(1,8) ." days")->format('Y-m-d H:i:s')));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
