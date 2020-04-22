<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Humain;
use App\Entity\Service;
use App\Entity\Fonction;
use App\Entity\Structure;
use App\Entity\TypeFonction;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class StructuresFixtures extends Fixture
{
    private $faker;
    private $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->faker = Factory::create('fr_FR');

        //Création d'humains
        for($i=1 ; $i<=50 ; $i++)
        {
            $humain = new Humain();
            $sexe[1] = 'h'; $sexe[2] = 'f'; $sexe = $sexe[random_int(1, 2)];
            $humain->setSexe($sexe);
            $humain->setNom($this->faker->lastName());
            if($sexe = 'h'){$humain->setPrenom($this->faker->firstName('male'));}else{$humain->setPrenom($this->faker->firstName('female'));}
            $this->manager->persist($humain);
        }

        //Création de type de fonction
        for($i=1 ; $i<=50 ; $i++)
        {
            $typeFonction = $this->createTypeFonction();
            $this->manager->persist($typeFonction);
        }

        //Création de services
        for($i=1 ; $i<=10 ; $i++)
        {
            $service = new Service();
            $service->setStructure($this->createStructure());
            $this->manager->persist($service);
        }
        $this->manager->flush();
    }

    private function createStructure():Structure
    {
        $structure = new Structure();
        $structure->setNom($this->faker->company());
        $structure->setPresentation($this->faker->realText());
        $structure->setLocal($this->faker->boolean());
        // $structure->addContact();
        for($i = 1 ; $i <= random_int(0,7) ; $i++)
        {
            $fonction = $this->createFonction();
            $fonction->setStructure($structure);
            $this->manager->persist($fonction);
        }
        // $structure->setLien();
        // $structure->setImage();
        $this->manager->persist($structure);
        return $structure;
    }

    private function createTypeFonction():TypeFonction
    {
        $typeFonction = new TypeFonction();
        $typeFonction->setTitre($this->faker->jobTitle());
        $typeFonction->setTitreFeminin($typeFonction->getTitre());
        $this->manager->persist($typeFonction);
        return $typeFonction;
    }

    private function createFonction():Fonction
    {
        $fonction = new Fonction();
        $fonction->setTypeFonction($this->createTypeFonction());
        return $fonction;
    }
}
