<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Dossier;
use App\Form\NewMediaType;
use App\Entity\Illustration;
use App\Form\NewDossierType;
use App\Form\NewIllustrationType;
use App\Controller\AdminController;
use App\Repository\DossierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminMediaController extends AdminController
{
    /**
     * @Route("/admin/medias", name="admin_medias")
     */
    public function index()
    {
        $medias = $this->outilsBox->findAllEntity(Media::class);
        //Prépare le Twig
        $this->outilsBox->defineTwig('symcom4/admin/medias/medias.html.twig');
        $this->initTwig('medias');
        $this->outilsBox->addParamTwig('medias', $medias);
        //Affiche la page
        return $this->jobController();
    }

    private function initDossiers()
    {
        //On crée les dossiers de fonctionnement du site
        $dossier_upload = $this->mediaService->ouvrirDossier(null, 'upload');
        $dossier_upload->setCote('upload');
        $dossier_upload->setDescriptif('Contient touts les fichiers uploadés sur le site et pas encore utilisés.');
        $this->outilsBox->persist($dossier_upload);

            $dossier_upload_images = $this->mediaService->ouvrirDossier($dossier_upload, 'images');
            $dossier_upload_images->setCote('upload_images');
            $dossier_upload_images->setDescriptif('Contient toutes les images uploadées sur le site et pas encore utilisés.');
            $this->outilsBox->persist($dossier_upload_images);
        
        $dossier_images = $this->mediaService->ouvrirDossier(null, 'images');
        $dossier_images->setCote('images');
        $dossier_images->setDescriptif('Contient toutes les images utilisées sur le site.');
        $this->outilsBox->persist($dossier_images);
    }

    /******************************************************************************************/
    /****************************************Les dossiers *************************************/
    /**
     * @Route("/admin/medias/dossiers", name="admin_medias_dossiers")
     */
    public function dossiers(): Response
    {
        $this->initDossiers();
        //Récupére tous les dossiers        
        $dossiers = $this->outilsBox->findAllEntity(Dossier::class);
        //Prépare le Twig
        $this->initTwig('medias');
        $this->outilsBox->defineTwig('symcom4/admin/medias/dossiers/dossiers.html.twig');        
        $this->outilsBox->addParamTwig('dossiers', $dossiers);
        //Affiche la page
        return $this->jobController();
    }
    /**
     * @Route("/admin/medias/dossier/new", name="admin_medias_dossier_new")
     */
    public function newDossier():Response
    {
        //Gérer le formulaire
        $this->outilsBox->setFormElement(new Dossier());
        $this->outilsBox->setFormClassType(NewDossierType::class);
        $this->outilsBox->setFormPageResultat('admin_medias_dossiers');
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/medias/dossiers/new_dossier.html.twig');
        $this->outilsBox->setFormTexteConfirmation("Le dossier ### a bien été créé !");
        $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getTitre();']);
        $this->outilsBox->setFormActions(array(['name' => 'action_newDossier', 'params' => []]));
        $this->addPageMereFormService();

        //Prépare le Twig
        $this->initTwig('medias');

        //Affiche le formulaire ou la redirection
        return $this->jobController();
    }

    /**
     * @Route("/admin/medias/new/{cote}", name="admin_medias_new", defaults={"cote": "upload"})
     */
    public function newMedia($cote):Response
    {
        $dossier = $this->outilsBox->findEntityBy(Dossier::class, ['cote' => $cote]);
        $dossier = $dossier[0];
        
        //Gérer le formulaire
        $this->outilsBox->setFormElement(new Media());
        $this->outilsBox->setFormClassType(NewMediaType::class);
        $this->outilsBox->setFormPageResultat('admin_medias');
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/medias/new_media.html.twig');
        $this->outilsBox->setFormTexteConfirmation("Le média ### a bien été importé !");
        $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getNom();']);
        $this->outilsBox->setFormActions(array(['name' => 'action_newMedia', 'params' => ['dossier' => $dossier]]));
        $this->addPageMereFormService();

        //Prépare le Twig
        $this->initTwig('medias');

        //Affiche le formulaire ou la redirection
        return $this->jobController();
    }

    /******************************************************************************************/
    /****************************************GESTIONS DES ACTIONS *****************************/
    public function action_newDossier(Object $dossier, $params)
    {
        //On crée le dossier correspondant
        $dossier->creerDossierPhysique();
        return $dossier;
    }

    public function action_newMedia(Object $media, $params)
    {
        $media->upload($params['dossier']);
        return $media;
    }
}
