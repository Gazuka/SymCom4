<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LogController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     * 
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $utils):Response
    {
        $error = $utils->getLastAuthenticationError();
        $pseudo = $utils->getLastUsername();

        return $this->render('symcom4/public/login.html.twig', [
            'hasError' => $error !== null,
            'pseudo' => $pseudo
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @Route("/logout", name="logout")
     * @return void
     */
    public function logout():void
    {

    }
}
