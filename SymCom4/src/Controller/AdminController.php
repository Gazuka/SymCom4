<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Association;
use App\Form\NewServiceType;
use App\Entity\TypeAssociation;
use App\Form\NewAssociationType;
use App\Form\NewTypeAssociationType;
use App\Controller\SymCom4Controller;
use App\Repository\DossierRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssociationRepository;
use App\Repository\TypeAssociationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends SymCom4Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        //Prépare le Twig
        $this->defineTwig('symcom4/admin/index.html.twig');
        $this->initTwig();
        //Affiche la page
        return $this->Afficher();
    }
}
