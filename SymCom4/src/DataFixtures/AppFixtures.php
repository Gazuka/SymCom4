<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Humain;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public static function getGroups(): array{
        return ['init', 'test'];
    }

    public function load(ObjectManager $manager)
    {
        //Initier les roles
        $adminRole = new Role();
        $adminRole->setTitre('ROLE_ADMIN');
        $manager->persist($adminRole);
        $adminMediathequeRole = new Role();
        $adminMediathequeRole->setTitre('ROLE_ADMIN_MEDIATHEQUE');
        $manager->persist($adminMediathequeRole);
        $membreMediathequeRole = new Role();
        $membreMediathequeRole->setTitre('ROLE_MEMBRE_MEDIATHEQUE');
        $manager->persist($membreMediathequeRole);

        //Ajouter l'utilisateur Admin
        $utilisateur = $this->initAdmin();
        $utilisateur->addRole($adminRole);
        $utilisateur->addRole($adminMediathequeRole);
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
