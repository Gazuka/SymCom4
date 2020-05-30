<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Structure;
use App\Entity\Association;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SiteController extends SymCom4Controller
{
    /**
     * @Route("/site", name="site")
     */
    public function index()
    {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * Afficher un service
     *
     * @Route("/structure/{slugstructure}", name="structure")
     * 
     * @return Response
     */
    public function structure($slugstructure):Response
    {
        //Récupérer le service
        $structure = $this->outilsService->findBySlug(Structure::class, $slugstructure);

        switch($structure->getType())
        {
            case 'association':
                $this->defineTwig('symcom4/public/association.html.twig'); 
            break;
            case 'service':
                $this->defineTwig('symcom4/public/service.html.twig'); 
            break;
            case 'entreprise':
            break;
            default:
                //Faire une redirection car page n'existe pas...
            break;
        }
        
        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);

        //Afficher la page
        return $this->Afficher();
    }

    /**
     * Afficher tous les services
     *
     * @Route("/services", name="services")
     * 
     * @return Response
     */
    public function services():Response
    {
        //Récupérer le service
        $services = $this->outilsService->findAll(Service::class);
        
        $this->defineTwig('symcom4/public/services.html.twig');

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('services', $services);

        //Afficher la page
        return $this->Afficher();
    }

    /**
     * Afficher tous les associations
     *
     * @Route("/associations", name="associations")
     * 
     * @return Response
     */
    public function associations():Response
    {
        //Récupérer le service
        $services = $this->outilsService->findAll(Association::class);
        
        $this->defineTwig('symcom4/public/associations.html.twig');

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('associations', $associations);

        //Afficher la page
        return $this->Afficher();
    }


}
