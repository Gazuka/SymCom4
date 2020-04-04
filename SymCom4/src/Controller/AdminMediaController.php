<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Form\NewDossierType;
use App\Repository\DossierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminMediaController extends SymCom4Controller
{
    /**
     * @Route("/admin/medias", name="admin_medias")
     */
    public function index()
    {
        //Prépare le Twig
        $this->defineTwig('symcom4/admin/medias/medias.html.twig');
        $this->initTwig();
        //Affiche la page
        return $this->Afficher();
    }

    /******************************************************************************************/
    /****************************************Les dossiers *************************************/
    /**
     * @Route("/admin/medias/dossiers", name="admin_medias_dossiers")
     */
    public function dossiers(DossierRepository $repo): Response
    {
        //Récupére tous les dossiers
        $dossiers = $repo->findAll();
        //Prépare le Twig
        $this->initTwig('medias');
        $this->defineTwig('symcom4/admin/medias/dossiers/dossiers.html.twig');        
        $this->defineParamTwig('dossiers', $dossiers);
        //Affiche la page
        return $this->Afficher();
    }
    /**
     * @Route("/admin/medias/dossier/new", name="admin_medias_dossier_new")
     */
    public function newDossier(Request $request, EntityManagerInterface $manager):Response
    {
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Dossier();
        $variables['classType'] = NewDossierType::class;
        $variables['pagedebase'] = 'symcom4/admin/medias/dossiers/new_dossier.html.twig';
        $variables['pagederesultat'] = 'admin_medias';
        $variables['texteConfirmation'] = "Le dossier ### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getTitre();'];
        $options['actions'] = array(['name' => 'action_newDossier', 'params' => []]);
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('medias');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }

    protected function action_newDossier(Object $dossier, $params, $request)
    {
        //On crée le dossier correspondant
        //mkdir('medias/'.$dossier->getChemin());
        $filesystem = new Filesystem();
        $filesystem->mkdir('/tmp/photos');
        $toto = $filesystem->exists('/tmp/photos');
        dump($toto);
        die();
    }
}
