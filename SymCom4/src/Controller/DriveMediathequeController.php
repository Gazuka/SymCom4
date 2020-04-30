<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\Role;
use App\Service\OutilsService;
use App\Entity\MembreMediatheque;
use App\Service\FormulaireService;
use App\Controller\SymCom4Controller;
use App\Entity\CreneauDriveMediatheque;
use App\Entity\CommandeDriveMediatheque;
use App\Service\DriveMediathequeService;
use App\Form\MembreMediathequeInscriptionType;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\NbrOuvragesCommandeDriveMediathequeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DriveMediathequeController extends SymCom4Controller
{
    /**
     * Page d'accueil du drive
     * 
     * @Route("/mediatheque/drive", name="drive_mediatheque")
     */
    public function index()
    {
        return $this->render('drive_mediatheque/index.html.twig');
    }

    /**
     * Choix de connexion ou inscription
     * 
     * @Route("/mediatheque/drive/1", name="drive_mediatheque_etape1")
     *
     * @return void
     */
    public function drive1()
    {
        return $this->render('drive_mediatheque/connexionouinscription.html.twig');
    }

    /**
     * Permet de se connecter
     * 
     * @Route("/mediatheque/drive/login", name="drive_mediatheque_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $pseudo = $utils->getLastUsername();

        return $this->render('drive_mediatheque/login.html.twig', [
            'hasError' => $error !== null,
            'pseudo' => $pseudo
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @Route("/mediatheque/drive/logout", name="drive_mediatheque_logout")
     * @return void
     */
    public function logout():void
    {

    }

    /**
     * Page d'inscription
     * @Route("/mediatheque/drive/inscription", name="drive_mediatheque_inscription")
     * @return void
     */
    public function inscription(UserPasswordEncoderInterface $encoder)
    {
        //Gérer le formulaire
        $this->formulaireService->setElement(new MembreMediatheque());
        $this->formulaireService->setClassType(MembreMediathequeInscriptionType::class);
        $this->formulaireService->setTwigFormulaire('drive_mediatheque/form_inscription.html.twig');
        $this->formulaireService->setPageResultat('drive_mediatheque_account');
        $this->formulaireService->setActions($this, array(['name' => 'action_preinscriptionMembreMediatheque', 'params' => array('encoder' => $encoder)]));
        $this->formulaireService->setTexteConfirmation("Votre inscription est bien enregistrée.");
        $this->createFormService();

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Action - Ajoute le lien à la structure
     *
     * @param Object $lien
     * @param Array $params
     * @return Object
     */
    public function action_preinscriptionMembreMediatheque(Object $membre, Array $params):Object
    {
        $membre->getUtilisateur()->setPseudo($membre->getNumCarte());
        $password = $params['encoder']->encodePassword($membre->getUtilisateur(), $membre->getUtilisateur()->getHumain()->getDateNaissance()->format('dm'));
        $membre->getUtilisateur()->setPassword($password);
        $roles = $this->outilsService->findAll(Role::class);
        foreach($roles as $role)
        {
            if($role->getTitre() == 'ROLE_MEMBRE_MEDIATHEQUE')
            {
                $membre->getUtilisateur()->addRole($role);
            }
        }
        return $membre;
    }

    /**
     * Page de l'utilisateur
     * @Route("/mediatheque/drive/account", name="drive_mediatheque_account")
     * @IsGranted("ROLE_MEMBRE_MEDIATHEQUE")
     * @return void
     */
    public function Account()
    {
        return $this->render('drive_mediatheque/account.html.twig');
    }

    /**
     * Choisir le type d'emprunt
     * @Route("/mediatheque/drive/typeemprunt", name="drive_mediatheque_typeemprunt")
     * @IsGranted("ROLE_MEMBRE_MEDIATHEQUE")
     * @return void
     */
    public function TypeEmprunt()
    {
        return $this->render('drive_mediatheque/typeemprunt.html.twig');
    }

    /**
     * Catalogie
     * @Route("/mediatheque/drive/catalogue", name="drive_mediatheque_catalogue")
     * @IsGranted("ROLE_MEMBRE_MEDIATHEQUE")
     * @return void
     */
    public function Catalogue()
    {
        $membre = $this->getUser()->getMembreMediatheque();
        //Gérer le formulaire
        $this->formulaireService->setElement(new CommandeDriveMediatheque());
        $this->formulaireService->setClassType(NbrOuvragesCommandeDriveMediathequeType::class);
        $this->formulaireService->setTwigFormulaire('drive_mediatheque/catalogue.html.twig');
        $this->formulaireService->setPageResultat('drive_mediatheque_commandes');
        $this->formulaireService->setActions($this, array(['name' => 'action_enregistrerCommande', 'params' => array('membre' => $membre)]));
        $this->formulaireService->setTexteConfirmation("Votre commande est bien enregistrée.");
        $this->createFormService();

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * Action - Passer une commande
     *
     * @param Object $lien
     * @param Array $params
     * @return Object
     */
    public function action_enregistrerCommande(Object $commande, Array $params):Object
    {
        $membre = $params['membre'];
        $commande->setMembreMediatheque($membre);
        return $commande;
    }

    /**
     * @Route("/mediatheque/drive/commandes", name="drive_mediatheque_commandes")
     * @IsGranted("ROLE_MEMBRE_MEDIATHEQUE")
     * @return void
     */
    public function commandes()
    {
        return $this->render('drive_mediatheque/commandes.html.twig');
    }

    /**
     * @Route("/mediatheque/drive/creneaux", name="drive_mediatheque_creneaux")
     * @IsGranted("ROLE_MEMBRE_MEDIATHEQUE")
     */
    public function Creneaux(DriveMediathequeService $driveMediathequeService)
    {
        $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
        if($now->format('H') < 12)
        {
            $horaireDebut = new DateTime($now->format('Y').'-'.$now->format('m').'-'.$now->format('d').' 08:00:00');
        }
        else
        {
            if($now->format('D') == "Tuesday")
            {
                $horaireDebut = new DateTime($now->format('Y').'-'.$now->format('m').'-'.$now->format('d').' 13:00:00');
            }
            else
            {
                $horaireDebut = new DateTime($now->format('Y').'-'.$now->format('m').'-'.$now->format('d').' 14:00:00');
            }
        }
        $limit = "200";
        $creneaux = $this->outilsService->returnRepo(CreneauDriveMediatheque::class)->findAllinFutur($horaireDebut, $limit);
        // dd($creneaux);
        $this->defineTwig('drive_mediatheque/creneaux.html.twig');
        $this->defineParamTwig('creneaux', $creneaux);
        $this->defineParamTwig('now', $now);
        $this->defineParamTwig('dansUneHeure', $driveMediathequeService->dansUneHeure($now));
        return $this->Afficher();
    }
}
