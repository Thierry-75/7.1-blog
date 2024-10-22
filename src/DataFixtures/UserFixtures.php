<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;
    private SluggerInterface $slugger;


    public function __construct(SluggerInterface $slugger)
    {
        $this->faker = Factory::create('fr_FR');
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [];
        for ($i = 0; $i < 20; $i++) {
            $user  = new User();
            $user->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setCreateAt(new \DateTimeImmutable())
                ->setPlainpassword('ArethiA75!')
                ->setZip(str_replace(' ', '', $this->faker->postcode()))
                ->setCity($this->faker->city())
                ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstNameFemale() . '-' . mt_rand(1, 99) : $this->faker->firstNameMale() . '-' . mt_rand(0, 99))
                ->setIsVerified(mt_rand(0, 1) === 1 ? true : false);
                if($user->getIsVerified() === true){
            $users[] = $user;
                }
            $manager->persist($user);
        }
        for($j =0; $j < 24; $j++){
            $article = new Article();
            $article->setCreateAt(new \DateTimeImmutable())
                    ->setTitre($this->faker->sentence(3))
                    ->setFirstParagraphe($this->faker->paragraph())
                    ->setSecondParagraph($this->faker->paragraph())
                    ->setThirdParagraph($this->faker->paragraph())
                    ->setUser($users[mt_rand(0, count($users) -1)])
                    ->setSlug(strtolower($this->slugger->slug($article->getTitre())));
            $manager->persist($article);
        }
        $manager->flush();
     
    }
}
