<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SymCom4Controller extends AbstractController
{
    /**
     * @Route("/", name="symcom4")
     */
    public function index()
    {
        return $this->render('symcom4/public/index.html.twig', [
            'controller_name' => 'SymCom4Controller',
        ]);
    }
}
