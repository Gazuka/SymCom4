<?php

namespace App\Controller;

use App\Entity\Lien;
use App\Entity\Mail;
use App\Entity\Page;
use App\Entity\Adresse;
use App\Entity\Telephone;
use App\Form\NewMailType;
use App\Form\NewAdresseType;
use App\Service\PageService;
use App\Form\NewTelephoneType;
use App\Service\OutilsService;
use App\Service\GestionService;
use App\Service\FormulaireService;
use App\Controller\OutilsController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SymCom4Controller extends OutilsController
{
    protected $formulaireService;
    protected $gestionService;
    protected $outilsService;

    protected $liensRapides = array();
    protected $request;    

    public function __construct(PageService $pageService, FormulaireService $formulaireService, GestionService $gestionService, OutilsService $outilsService)
    {
        $this->pageService = $pageService;
        $this->formulaireService = $formulaireService;
        $this->gestionService = $gestionService;
        $this->outilsService = $outilsService;
    }

    /**
     * @Route("/", name="accueil")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index()
    {
        return $this->render('symcom4/public/index.html.twig', [
            'controller_name' => 'SymCom4Controller',
        ]);
    }

    
}
