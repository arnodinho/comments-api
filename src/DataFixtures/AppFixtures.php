<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // use the factory to create a Faker\Generator instance

        // use the factory to create a Faker\Generator instance
        $faker = Factory::create();

        // Création des auteurs.
        $listAuthor = [];
        // Création d'un user "normal"
        $user = new User();
        $user->setEmail($faker->email());
        $user->setFirstname($faker->firstName());
        $user->setLastname($faker->lastName());
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
        $manager->persist($user);
        $listAuthor[] = $user;

        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail($faker->email());
        $userAdmin->setFirstname($faker->firstName());
        $userAdmin->setLastname($faker->lastName());
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));
        $manager->persist($userAdmin);
        $listAuthor[] = $userAdmin;

        $manager->flush();

        // Création des commentaire
        for ($i = 0; $i < 10; $i++) {
            $comment = new Comment();
            $comment->setText($faker->text());
            $comment->addNote(random_int(1, 20));
            $comment->addNote(random_int(1, 20));

            $comment->setUser($listAuthor[array_rand($listAuthor)]);

            if (0 === $i) {
                $parent = $comment;
            }
            if (1 === $i) {
                $parent1 = $comment;
            }

            if (in_array($i, [3,4,5])) {
                $comment->setParent($parent);
            }

            if (in_array($i, [6,7,8])) {
                $comment->setParent($parent1);
            }

            $manager->persist($comment);
        }

        $manager->flush();
    }
}
