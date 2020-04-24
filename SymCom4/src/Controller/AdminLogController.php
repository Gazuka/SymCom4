<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminLogController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $pseudo = $utils->getLastUsername();

        return $this->render('symcom4/admin/log/login.html.twig', [
            'hasError' => $error !== null,
            'pseudo' => $pseudo
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @Route("/admin/logout", name="admin_logout")
     * @return void
     */
    public function logout():void
    {

    }
}
