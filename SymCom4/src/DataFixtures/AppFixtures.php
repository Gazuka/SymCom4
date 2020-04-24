<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Humain;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        //Initier les roles
        $adminRole = new Role();
        $adminRole->setTitre('ROLE_ADMIN');
        $manager->persist($adminRole);

        //Ajouter l'utilisateur Admin
        $utilisateur = $this->initAdmin();
        $utilisateur->addRole($adminRole);
        $manager->persist($utilisateur);

        //Sauvegarder l'ensemble
        $manager->flush();
    }

    private function initAdmin()
    {
        $humain = new Humain();
        $humain->setNom("CARION");
        $humain->setPrenom("Jérôme");
        $humain->setSexe("h");

        $utilisateur = new Utilisateur();
        $utilisateur->setPseudo("j.carion");
        $utilisateur->setPassword($this->encoder->encodePassword($utilisateur, "password"));
        $utilisateur->setEmail("jerome.carion@gmail.com");
        $utilisateur->setHumain($humain);

        return $utilisateur;
    }
}
