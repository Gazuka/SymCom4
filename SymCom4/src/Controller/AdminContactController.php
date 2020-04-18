<?php

namespace App\Controller;

use App\Entity\Mail;
use App\Entity\Humain;
use App\Entity\Adresse;
use App\Entity\Contact;
use App\Entity\Telephone;
use App\Form\NewMailType;
use App\Entity\Utilisateur;
use App\Form\NewHumainType;
use App\Form\NewAdresseType;
use App\Service\PageService;
use App\Form\UtilisateurType;
use App\Form\NewTelephoneType;
use App\Controller\AdminController;
use App\Form\HumainUtilisateurType;
use App\Repository\HumainRepository;
use App\Repository\ContactRepository;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminContactController extends AdminController
{
    /******************************************************************************************/
    /****************************************Les humain ***************************************/
    /**
     * @Route("/admin/humains", name="admin_humains")
     */
    public function humains(Request $request, HumainRepository $repoHumain): Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //Récupére tous les services
        $humains = $repoHumain->findAll();
        //Prépare le Twig
        $this->initTwig('humains');
        $this->defineTwig('symcom4/admin/humains/humains.html.twig');        
        $this->defineParamTwig('humains', $humains);
        //Affiche la page
        return $this->Afficher();
    }
    /**
     * @Route("/admin/humain/new", name="admin_humain_new")
     */
    public function newHumain(Request $request, EntityManagerInterface $manager):Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Humain();
        $variables['classType'] = NewHumainType::class;
        $variables['pagedebase'] = 'symcom4/admin/humains/new_humain.html.twig';
        $variables['pagederesultat'] = 'admin_humains';
        $variables['texteConfirmation'] = "### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getNom()." ".$element->getPrenom();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('humains');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/humain/edit/{idhumain}", name="admin_humain_edit")
     */
    public function editHumain(Request $request, $idhumain, HumainRepository $repoHumain, EntityManagerInterface $manager):Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idhumain', $idhumain); 

        //On recupére le Service
        $humain = $repoHumain->findOneById($idhumain);
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $humain;
        $variables['classType'] = NewHumainType::class;
        $variables['pagedebase'] = 'symcom4/admin/humains/new_humain.html.twig';
        $variables['pagederesultat'] = 'admin_humains';
        $variables['texteConfirmation'] = "### a bien été modifié !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getNom()." ".$element->getPrenom();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('humains');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/humain/delete/{idhumain}", name="admin_humain_delete")
     */
    public function deleteHumain(HumainRepository $repo, EntityManagerInterface $manager, $idhumain):Response
    {
        //On supprimer le Service
        $this->delete($repo, $manager, $id);
        //Défini la page de redirection
        $this->defineRedirect('admin_humains');
        //Prépare le Twig
        $this->initTwig('humains');
        //Affiche la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/humain/addcontact/{id}/{type}", name="admin_humain_addcontact")
     */
    public function addcontactHumain(HumainRepository $repo, Request $request, EntityManagerInterface $manager, $id, $type):Response
    {
        //On récupère l'humain concerné
        $humain = $repo->findOneById($id);

        //Selon le type, on crée le type de contact voulu
        $variables = $this->factor_addcontact($type);
             
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['pagedebase'] = 'symcom4/admin/humains/add_contact.html.twig';
        $variables['pagederesultat'] = 'admin_humains';
        $variables['texteConfirmation'] = "Le contact a bien été modifié !";
        $options['actions'] = array(['name' => 'action_addcontactHumain', 'params' => ['humain' => $humain]]);
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('humains');
        $this->defineParamTwig('type', $type);
        $this->defineParamTwig('humain', $humain);
        //Affiche la redirection
        return $this->Afficher();
    }

    protected function action_addcontactHumain(Object $contactChild, $params, $request)
    {
        $contact = $contactChild->getContact();
        $contact->addHumain($params['humain']);
        return $contactChild;
    }

    /******************************************************************************************/
    /****************************************Les utilisateurs *********************************/
    /**
     * @Route("/admin/utilisateurs", name="admin_utilisateurs")
     */
    public function utilisateurs(Request $request, UtilisateurRepository $repoUtilisateur): Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //Récupére tous les utilisateurs
        $utilisateurs = $repoUtilisateur->findAll();
        //Prépare le Twig
        $this->initTwig('humains');
        $this->defineTwig('symcom4/admin/humains/utilisateurs.html.twig');        
        $this->defineParamTwig('utilisateurs', $utilisateurs);
        //Affiche la page
        return $this->Afficher();
    }

    /**
     * @Route("/admin/humain/utilisateur/new/idhumain/idpage", name="admin_humain_utilisateur_new")
     */
    public function addutilisateurHumain(Request $request, $idhumain, $idpage, HumainRepository $repoHumain, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idhumain', $idhumain);
        $this->pageService->addParam('idpage', $idpage);

        //On récupère l'humain et la page mère
        $humain = $repoHumain->findOneById($idhumain);
        $pageMere = $this->pageService->recupPageMere($idpage);

        //Lorsque le contact aura un Id, le formulaire devra l'associer avec la structure
        $options['actions'] = array(['name' => 'action_addutilisateurHumain', 'params' => ['humain' => $humain]]);

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Utilisateur();
        $variables['classType'] = HumainUtilisateurType::class;
        $variables['pagedebase'] = 'symcom4/admin/general/form_utilisateur.html.twig';
        $variables['pagederesultat'] = $pageMere->getNomChemin();
        $variables['pagederesultatConfig'] = $pageMere->getParams();
        $variables['texteConfirmation'] = "### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getPseudo();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('humains');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }

    /** Action - relative à l'ajout d'un utilisateur sur un humain
     */
    protected function action_addutilisateurHumain(Object $utilisateur, $params, $request)
    {
        $utilisateur->setHumain($params['humain']);
        return $utilisateur;
    }

    








    // /**
    //  * @Route("/admin/utilisateur/new", name="admin_utilisateur_new")
    //  */
    // public function newUtilisateur(Request $request, EntityManagerInterface $manager):Response
    // {
    //     //On génére la page en cours
    //     $this->pageService->setRoute($request->get('_route'));

    //     //Préparation et traitement du formulaire
    //     $variables['request'] = $request;
    //     $variables['manager'] = $manager;
    //     $variables['element'] = new Utilisateur();
    //     $variables['classType'] = UtilisateurType::class;
    //     $variables['pagedebase'] = 'symcom4/admin/general/form_utilisateur.html.twig';
    //     $variables['pagederesultat'] = 'admin_utilisateurs';
    //     $variables['texteConfirmation'] = "### a bien été créé !"; 
    //     $options['texteConfirmationEval'] = ["###" => '$element->getPseudo();'];
    //     $this->afficherFormulaire($variables, $options);
    //     //Prépare le Twig
    //     $this->initTwig('humains');
    //     //Affiche le formulaire ou la redirection
    //     return $this->Afficher();
    // }

    

    

    
}
