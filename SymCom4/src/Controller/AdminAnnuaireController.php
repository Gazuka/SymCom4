<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminAnnuaireController extends SymCom4Controller
{
    /**
     * @Route("/admin/annuaire", name="admin_annuaire")
     */
    public function index()
    {
        return $this->render('admin_annuaire/index.html.twig', [
            'controller_name' => 'AdminAnnuaireController',
        ]);
    }
}
