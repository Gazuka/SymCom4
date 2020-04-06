<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Dossier;
use App\Form\NewMediaType;
use App\Entity\Illustration;
use App\Form\NewDossierType;
use App\Form\NewIllustrationType;
use App\Repository\DossierRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        $this->initTwig('medias');
        //Affiche la page
        return $this->Afficher();
    }

    /**
     * @Route("/admin/medias/initDossier", name="admin_medias_initDossier")
     */
    public function initDossier()
    {
        //A n'utiliser que lors d'une nouvelle installation
        /*
            CREER UNE FONCTION QUI GERE LENSEMBLE DES DOSSIERS REQUIS POUR LE FONCTIONNEMENT DU SITE
            > images
            > associations
            $dossier = new Dossier();
        */

        //Prépare le Twig
        $this->defineTwig('symcom4/admin/medias/medias.html.twig');
        $this->initTwig('medias');
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
        $variables['pagederesultat'] = 'admin_medias_dossiers';
        $variables['texteConfirmation'] = "Le dossier ### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getTitre();'];
        $options['actions'] = array(['name' => 'action_newDossier', 'params' => []]);
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('medias');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * @Route("/admin/medias/new", name="admin_medias_new")
     */
    public function newMedia(Request $request, EntityManagerInterface $manager, DossierRepository $repoDossier):Response
    {
        $dossierImages = $repoDossier->findOneBy(['titre' => 'images']);
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Media();
        $variables['classType'] = NewMediaType::class;
        $variables['pagedebase'] = 'symcom4/admin/medias/new_media.html.twig';
        $variables['pagederesultat'] = 'admin_medias';
        $variables['texteConfirmation'] = "Le média ### a bien été importé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getNom();'];
        $options['actions'] = array(['name' => 'action_newMedia', 'params' => ['dossierImages' => $dossierImages]]);
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('medias');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }

    /******************************************************************************************/
    /****************************************GESTIONS DES ACTIONS *****************************/
    protected function action_newDossier(Object $dossier, $params, $request)
    {
        //On crée le dossier correspondant
        $dossier->creerDossierPhysique();
        return $dossier;
    }

    protected function action_newMedia(Object $media, $params, $request)
    {
        $media->upload($params);
        return $media;
    }
}
