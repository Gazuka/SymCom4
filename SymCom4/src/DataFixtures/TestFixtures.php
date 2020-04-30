<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Humain;
use App\Entity\Service;
use App\Entity\Fonction;
use App\Entity\Structure;
use App\Entity\Association;
use App\Entity\TypeFonction;
use App\Service\OutilsService;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TestFixtures extends Fixture
{
    private $faker;
    private $manager;
    private $outilsService;

    private $humains = Array();
    private $typeFonctions = Array();
    private $services = Array();
    private $associations = Array();
    private $structures = Array();

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create('fr_FR');
        $this->outilsService = new OutilsService($manager);

        //Création de 50 humains
        for($i=1 ; $i<=50 ; $i++)
        {
            $humain = $this->createHumain();
        }

        //Création de 50 type de fonction
        for($i=1 ; $i<=50 ; $i++)
        {
            $typeFonction = $this->createTypeFonction();
        }

        //Création de 10 services
        for($i=1 ; $i<=10 ; $i++)
        {
            $service = $this->createService();
        }

        //Création de 20 associations
        for($i=1 ; $i<=20 ; $i++)
        {
            $service = $this->createAssociation();
        }

        //Enregistrement de l'ensemble des données
        $this->manager->flush();

        //Fichier de config
        $this->configFile();
    }

    private function recupOne(Array $array):Object
    {
        $nbr = sizeOf($array)-1;
        return $array[rand(0, $nbr)];
    }

    private function configFile()
    {
        $fichier = fopen('configTest.php', 'w');
        fclose($fichier);

        $typeFonctions = $this->outilsService->findAll(TypeFonction::class);
        $humains = $this->outilsService->findAll(Humain::class);

        $texte = '<?php'.PHP_EOL;

        //TypeFonction
        $texte.= '$this->config[\'idTypeFonctionStart\']='.$typeFonctions[0]->getId().';'.PHP_EOL;
        $texte.= '$this->config[\'nbrIdTypeFonction\']='.sizeOf($typeFonctions).';'.PHP_EOL;

        //Humain
        $texte.= '$this->config[\'idHumainStart\']='.$humains[0]->getId().';'.PHP_EOL;
        $texte.= '$this->config[\'nbrIdHumain\']='.sizeOf($humains).';'.PHP_EOL;

        $texte.= '?>'.PHP_EOL;

        // 1 : on ouvre le fichier
        $monfichier = fopen('configTest.php', 'a+');
        // 2 : on fera ici nos opérations sur le fichier...
        fputs($monfichier, $texte);
        // 3 : quand on a fini de l'utiliser, on ferme le fichier
        fclose($monfichier);
    }

    private function createHumain():Humain
    {
        $humain = new Humain();
        $sexe[1] = 'h'; $sexe[2] = 'f'; $sexe = $sexe[random_int(1, 2)];
        $humain->setSexe($sexe);
        $humain->setNom($this->faker->lastName());
        if($sexe = 'h'){$humain->setPrenom($this->faker->firstName('male'));}else{$humain->setPrenom($this->faker->firstName('female'));}
        $this->manager->persist($humain);
        $this->humains[] = $humain;
        return $humain;
    }

    private function createTypeFonction():TypeFonction
    {
        $typeFonction = new TypeFonction();
        $typeFonction->setTitre($this->faker->jobTitle());
        $typeFonction->setTitreFeminin($typeFonction->getTitre());
        $this->manager->persist($typeFonction);
        $this->typeFonctions[] = $typeFonction;
        return $typeFonction;
    }

    private function createService():Service
    {
        $service = new Service();
        $service->setStructure($this->createStructure());
        $this->manager->persist($service);
        $this->services[] = $service;
        return $service;
    }

    private function createAssociation():Association
    {
        $association = new Association();
        $association->setStructure($this->createStructure());
        $association->setSigle('ABC');
        $this->manager->persist($association);
        $this->associations[] = $association;
        return $association;
    }
 
    private function createStructure():Structure
    {
        $structure = new Structure();
        $structure->setNom($this->faker->company());
        $structure->setPresentation($this->faker->realText());
        $structure->setLocal($this->faker->boolean());
        // // // // // // // // $structure->addContact();
        //Ajoute entre 0 et 10 fonctions
        for($i = 1 ; $i <= random_int(0,10) ; $i++)
        {
            $fonction = $this->createFonction();
            $fonction->setStructure($structure);
        }
        // $structure->setLien();
        // $structure->setImage();
        $this->manager->persist($structure);
        $this->structures[] = $structure;
        return $structure;
    }

    private function createFonction():Fonction
    {
        $fonction = new Fonction();
        //RECUP TYPE FONCTION
        $fonction->setTypeFonction($this->recupOne($this->typeFonctions));
        $this->manager->persist($fonction);
        $this->fonctions[] = $fonction;
        return $fonction;
    }
}
