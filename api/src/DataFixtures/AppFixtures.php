<?php

namespace App\DataFixtures;

use App\Entity\Character;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; ++$i) {
            $user = new User();
            $password = $this->passwordHasher->hashPassword(
                $user,
                '123456789'
            );
            $email = !$i ? 'thetribe@test.fr' : $faker->email;
            $user->setEmail($email)
                ->setPassword($password);
            $manager->persist($user);

            for ($c = 0; $c < mt_rand(0, 5); ++$c) {
                $character = new Character();
                $character->setName($faker->userName)
                    ->setHealth(mt_rand(10, 30))
                    ->setAttack(mt_rand(1, 10))
                    ->setDefense(mt_rand(0, 5))
                    ->setMagik(mt_rand(0, 5))
                    ->setRank(mt_rand(1, 5))
                    ->setSkillPoints(mt_rand(0, 10))
                    ->setOwnedBy($user);
                $manager->persist($character);
            }
        }

        $manager->flush();
    }
}
