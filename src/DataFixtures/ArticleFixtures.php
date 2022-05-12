<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker=Factory::create('fr_FR');
        for ($i=0;$i<40;$i++) {
            $a = new \App\Entity\Article();
            $a->setNom($faker->name);
            $r=(float)rand(100000,500000);
            $a->setPrix($r);
            $manager->persist($a);
        }
        $manager->flush();

        $manager->flush();
    }
}
