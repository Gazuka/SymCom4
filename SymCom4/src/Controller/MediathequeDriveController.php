<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\Role;
use App\Service\PageService;
use App\Service\OutilsService;
use App\Entity\MediathequeMembre;
use App\Entity\MediathequeFamille;
use App\Service\FormulaireService;
use App\Controller\SymCom4Controller;
use App\Entity\MediathequeDriveCreneau;
use App\Entity\MediathequeDriveCommande;
use App\Service\MediathequeDriveService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\MediathequeDriveCommandeEtat;
use App\Form\MediathequeMembreInscriptionType;
use App\Form\MembreMediathequeInscriptionType;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MediathequeDriveCommandeNbrOuvragesType;
use App\Form\MediathequeMembreInscriptionFamilleType;
use App\Form\MembreMediathequeInscriptionFamilleType;
use App\Form\MediathequeDriveCommandeNbrOuvragesSurpriseType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MediathequeDriveController extends SymCom4Controller
{

    // GESTION DES CONNEXIONS ET DECONNEXIONS ----------------------------------------------------------------------------

    /**
     * Message d'accueil ou affichage du profil si connecté
     * 
     * @Route("/mediatheque/drive", name="mediatheque_drive")
     */
    public function index()
    {
        $membre = $this->LogMembre();
        if($membre != null)
        {
            //Si un membre est connect => on affiche son profil
            $this->defineRedirect('mediatheque_drive_profil');
        }
        else
        {
            //Sinon => on affichage du texte d'intro
            $this->defineTwig('mediatheque_drive/index.html.twig');
        }
        
        //On affiche la page ou la redirection      
        return $this->Afficher();
    }

    /**
     * Choix de connexion ou inscription
     * 
     * @Route("/mediatheque/drive/choix/log", name="mediatheque_drive_choix_log")
     *
     * @return void
     */
    public function choixLog()
    {
        $membre = $this->LogMembre();
        if($membre != null)
        {
            //Si un membre est connect => on affiche son profil
            $this->defineRedirect('mediatheque_drive_profil');
        }
        else
        {
           //Sinon => on lui propose de s'inscrire ou de se connecter
           $this->defineTwig('mediatheque_drive/connexionouinscription.html.twig');
        }
        //On affiche la page ou la redirection
        return $this->Afficher();
    }

    /**
     * Permet de se connecter
     * 
     * @Route("/mediatheque/drive/login", name="mediatheque_drive_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $pseudo = $utils->getLastUsername();

        return $this->render('mediatheque_drive/login.html.twig', [
            'hasError' => $error !== null,
            'pseudo' => $pseudo
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @Route("/mediatheque/drive/logout", name="mediatheque_drive_logout")
     * 
     * @return void
     */
    public function logout():void
    {
    }

    // GESTION DES ACTIONS POSSIBLES -------------------------------------------------------------------------------------

    /**
     * Page d'inscription
     * 
     * @Route("/mediatheque/drive/inscription", name="mediatheque_drive_inscription")
     * 
     * @return void
     */
    public function inscription(UserPasswordEncoderInterface $encoder)
    {
        $membre = $this->LogMembre();
        if($membre != null)
        {
            //Si un membre est connect => on affiche son profil
            $this->defineRedirect('mediatheque_drive_profil');
        }
        else
        {
            //Sinon on gère le formulaire d'inscription
            $this->formulaireService->setElement(new MediathequeMembre());
            $this->formulaireService->setClassType(MediathequeMembreInscriptionType::class);
            $this->formulaireService->setTwigFormulaire('mediatheque_drive/form_inscription.html.twig');
            $this->formulaireService->setPageResultat('mediatheque_drive_profil');
            $this->formulaireService->setActions($this, array(['name' => 'action_preinscriptionMembre', 'params' => array('encoder' => $encoder)]));
            $this->formulaireService->setTexteConfirmation("Votre inscription est bien enregistrée.");
            $this->createFormService();
        }

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Page d'inscription d'un membre de la famille
     * 
     * @Route("/mediatheque/drive/inscription/famille", name="mediatheque_drive_inscription_famille")
     * 
     * @return void
     */
    public function inscriptionFamille(UserPasswordEncoderInterface $encoder)
    {
        $membre = $this->getUser()->getMembreMediatheque();

        //Gérer le formulaire
        $this->formulaireService->setElement(new MediathequeMembre());
        $this->formulaireService->setClassType(MediathequeMembreInscriptionFamilleType::class);
        $this->formulaireService->setTwigFormulaire('mediatheque_drive/form_inscription_famille.html.twig');
        $this->formulaireService->setPageResultat('mediatheque_drive_profil');
        $this->formulaireService->setActions($this, array(['name' => 'action_preinscriptionMembreFamille', 'params' => array('encoder' => $encoder, 'membre' => $membre)]));
        $this->formulaireService->setTexteConfirmation("Votre inscription est bien enregistrée.");
        $this->createFormService();

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Page de profil de l'utilisateur
     * 
     * @Route("/mediatheque/drive/profil", name="mediatheque_drive_profil")
     * 
     * @IsGranted("ROLE_MEMBRE_MEDIATHEQUE")
     * 
     * @return void
     */
    public function Profil()
    {
        //On récupére membre
        $membre = $this->LogMembre();
        $emprunteur = $this->emprunteurActif($membre, 0);

        if(sizeOf($membre->getCommandes()) == 0)
        {
            //Si pas de commande alors on propose emprunt ou retour (1er connexion)
            $this->defineTwig('mediatheque_drive/account_pret_ou_retour.html.twig');
        }
        else
        {
            $derniereCommande = $membre->getCommandes()->last();
            if($derniereCommande->getEtat()->getEtat() != 'FINI')
            {
                if($derniereCommande->getEtat()->getEtat() == 'USER_RETOUR')
                {
                    if($derniereCommande->getCreneau() == null)
                    {
                        $membre->removeCommande($derniereCommande);
                        $this->defineRedirect('mediatheque_drive_profil');
                    }
                }
                $this->defineTwig('mediatheque_drive/account_global.html.twig');
                $this->defineParamTwig('derniereCommande', $derniereCommande);
            }
            else
            {
                $this->defineTwig('mediatheque_drive/account_pret_ou_retour.html.twig');  
            }
        }

        //On affiche la page
        return $this->Afficher();
    }

    /**
     * Choisir le type d'emprunt 
     * 
     * @Route("/mediatheque/drive/typeemprunt/{idemprunteur}", name="mediatheque_drive_typeemprunt")
     * 
     * @IsGranted("ROLE_MEMBRE_MEDIATHEQUE")
     * 
     * @return void
     */
    public function TypeEmprunt($idemprunteur = null)
    {
        //On recupere le membre connecté
        $membre = $this->LogMembre();

        //On récupere l'emprunteur actif
        $emprunteur = $this->emprunteurActif($membre, $idemprunteur);

        //On s'occupe du Twig
        $this->defineTwig('mediatheque_drive/typeemprunt.html.twig');

        //On affiche la page
        return $this->Afficher();
    }

    /**
     * Permet de choisir un creneau pour un retour uniquement
     * 
     * @Route("/mediatheque/drive/retour", name="mediatheque_drive_retour")
     * 
     * @IsGranted("ROLE_MEMBRE_MEDIATHEQUE")
     * 
     * @return void
     */
    public function RetourUnique(EntityManagerInterface $manager)
    {
        $membre = $this->getUser()->getMembreMediatheque();
        $retour = new MediathequeDriveCommande();
        $retour->setNbrLivreChoisi(0);
        $retour->setNbrCdChoisi(0);
        $retour->setNbrDvdChoisi(0);
        $retour->setNbrLivreSurprise(0);
        $retour->setNbrCdSurprise(0);
        $retour->setNbrDvdSurprise(0);
        $retour->setArchive(false);
        $retour->setMembre($membre);
        $retour->setCommentaire("Retour uniquement");
        $etat = new MediathequeDriveCommandeEtat('USER_RETOUR');
        $retour->addEtat($etat);

        $manager->persist($retour);
        $manager->flush();

        $this->defineRedirect('mediatheque_drive_creneaux');

        return $this->Afficher();
    }

    /**
     * Affiche la catalogue pour réservation
     * 
     * @Route("/mediatheque/drive/catalogue/{idemprunteur}", name="mediatheque_drive_catalogue")
     * 
     * @IsGranted("ROLE_MEMBRE_MEDIATHEQUE")
     * 
     * @return void
     */
    public function Catalogue($idemprunteur = null)
    {
        $membre = $this->getUser()->getMembreMediatheque();
        //On récupere l'emprunteur actif
        $emprunteur = $this->emprunteurActif($membre, $idemprunteur);

        $commande = $emprunteur->getOneCommandeByEtat('USER_ENCOURS');

        if($commande == null)
        {
            //Gérer le formulaire pour une nouvelle commande
            $this->formulaireService->setElement(new MediathequeDriveCommande());
            $this->formulaireService->setClassType(MediathequeDriveCommandeNbrOuvragesType::class);
            $this->formulaireService->setTwigFormulaire('mediatheque_drive/catalogue.html.twig');
            $this->formulaireService->setPageResultat('mediatheque_drive_profil');
            $this->formulaireService->setActions($this, array(['name' => 'action_enregistrerCommande', 'params' => array('emprunteur' => $emprunteur)]));
            $this->formulaireService->setTexteConfirmation("Votre commande est bien enregistrée.");
            $this->createFormService();
        }
        else
        {
            //Reprise d'une commande en cours...
            $this->formulaireService->setElement($commande);
            $this->formulaireService->setClassType(MediathequeDriveCommandeNbrOuvragesType::class);
            $this->formulaireService->setTwigFormulaire('mediatheque_drive/catalogue.html.twig');
            $this->formulaireService->setPageResultat('mediatheque_drive_profil');
            $this->formulaireService->setTexteConfirmation("Votre commande est bien modifiée.");
            $this->createFormService();
        }

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Permet de faire une réservation surprise
     * 
     * @Route("/mediatheque/drive/surprise/{idemprunteur}", name="mediatheque_drive_surprise")
     * 
     * @IsGranted("ROLE_MEMBRE_MEDIATHEQUE")
     * 
     * @return void
     */
    public function Surprise($idemprunteur = null)
    {
        $membre = $this->getUser()->getMembreMediatheque();
        //On récupere l'emprunteur actif
        $emprunteur = $this->emprunteurActif($membre, $idemprunteur);

        $commande = $emprunteur->getOneCommandeByEtat('USER_ENCOURS');

        if($commande == null)
        {
            //Gérer le formulaire pour une nouvelle commande
            $commande = new MediathequeDriveCommande();
            $commande->setNbrLivreChoisi(0);
            $commande->setNbrCDChoisi(0);
            $commande->setNbrDVDChoisi(0);
            $this->formulaireService->setElement($commande);
            $this->formulaireService->setClassType(MediathequeDriveCommandeNbrOuvragesSurpriseType::class);
            $this->formulaireService->setTwigFormulaire('mediatheque_drive/surprise.html.twig');
            $this->formulaireService->setPageResultat('mediatheque_drive_profil');
            $this->formulaireService->setActions($this, array(['name' => 'action_enregistrerCommande', 'params' => array('emprunteur' => $emprunteur)]));
            $this->formulaireService->setTexteConfirmation("Votre commande est bien enregistrée.");
            $this->createFormService();
        }
        else
        {
            //Reprise d'une commande en cours...
            $this->formulaireService->setElement($commande);
            $this->formulaireService->setClassType(MediathequeDriveCommandeNbrOuvragesSurpriseType::class);
            $this->formulaireService->setTwigFormulaire('mediatheque_drive/surprise.html.twig');
            $this->formulaireService->setPageResultat('mediatheque_drive_profil');
            $this->formulaireService->setTexteConfirmation("Votre commande est bien modifiée.");
            $this->createFormService();
        }

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Supprime une commande
     * 
     * @Route("/mediatheque/drive/supprimer/commande/{idemprunteur}", name="mediatheque_drive_supprimer_commande")
     * 
     * @IsGranted("ROLE_MEMBRE_MEDIATHEQUE")
     * 
     * @return void
     */
    public function SupprimerCommande($idemprunteur = null)
    {
        $membre = $this->getUser()->getMembreMediatheque();
        //On récupere l'emprunteur actif
        $emprunteur = $this->emprunteurActif($membre, $idemprunteur);

        dd($membre->getCommandes());

        $commande = $emprunteur->getOneCommandeByEtat('USER_ENCOURS');

        dd($commande);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Permet de voir tous les créneaux disponibles
     * 
     * @Route("/mediatheque/drive/creneaux", name="mediatheque_drive_creneaux")
     * 
     * @IsGranted("ROLE_MEMBRE_MEDIATHEQUE")
     */
    public function Creneaux(MediathequeDriveService $mediathequeDriveService)
    {
        //On récupére l'heure actuelle +2h pour correspondre avec le créneau horaire de la Médiathèque
        $now = new DateTime('now');
        $timestamp = $now->getTimestamp();
        $timestamp = strtotime("+ 0 minutes", $timestamp);
        $now->setTimestamp($timestamp);

        //On souhaite démarrer avec un creneau sur un horaire "rond"
        $horaireDebut = new DateTime($now->format('Y').'-'.$now->format('m').'-'.$now->format('d').' '.$now->format('H').':00:00');

        //Nombre de creneaux à afficher au max
        $limit = "200";

        //On récupère les creneaux à afficher
        $creneaux = $this->outilsService->returnRepo(MediathequeDriveCreneau::class)->findAllinFutur($horaireDebut, $limit);

        //On regarde si les creneaux sont disponibles
        $iterationCreneau = 0;
        foreach($creneaux as $creneau)
        {
            $creneau->verifEtat($iterationCreneau);
            $iterationCreneau = $iterationCreneau + 1;
        }

        $this->defineTwig('mediatheque_drive/creneaux.html.twig');
        $this->defineParamTwig('creneaux', $creneaux);
        // $this->defineParamTwig('now', $now);
        // $this->defineParamTwig('dansUneHeure', $mediathequeDriveService->dansUneHeure($now));
        return $this->Afficher();
    }

    /**
     * Réserver un creneau
     * 
     * @Route("/mediatheque/drive/reserver/creneau/{idcreneau}", name="mediatheque_drive_reserver_creneau")
     * 
     * @IsGranted("ROLE_MEMBRE_MEDIATHEQUE")
     */
    public function ReserverCreneau($idcreneau, EntityManagerInterface $manager)
    {
        $membre = $this->getUser()->getMembreMediatheque();
        $creneau = $this->outilsService->FindById(MediathequeDriveCreneau::class, $idcreneau);
        if($creneau != null)
        {
            //Si le creneau est disponible, on l'utilise sinon on affiche un message et on retourne sur la page précédante
            if($creneau->getEtat() == 'DISPONIBLE')
            {
                //On regarde si c'est un retour ou un pret / retour
                if($membre->getCommandes()->last()->getEtat()->getEtat() == 'USER_RETOUR')
                {
                    $commande = $membre->getCommandes()->last();
                    $creneau->addCommande($commande);
                    $creneau->setEtat('RESERVE');
                }
                else
                {
                    //On regarde si c'est une commande unique ou pour une famille
                    if($membre->getFamille() != null)
                    {
                        //Commande de famille
                        foreach($membre->getFamille()->getAdherents() as $adherent)
                        {
                            if($adherent->getCommandes() != null)
                            {
                                $commande = $adherent->getCommandes()->last();
                                if($commande->getEtat()->getEtat() == 'USER_ENCOURS');
                                {
                                    $creneau->addCommande($commande);
                                    $etat = new MediathequeDriveCommandeEtat('USER_VALIDE');
                                    $commande->addEtat($etat);
                                    $creneau->setEtat('RESERVE');
                                } 
                            }
                        }
                    }
                    else
                    {
                        //Commande unique
                        $commande = $membre->getOneCommandeByEtat('USER_ENCOURS');
                        if($commande != null)
                        {
                            $creneau->addCommande($commande);
                            $etat = new MediathequeDriveCommandeEtat('USER_VALIDE');
                            $commande->addEtat($etat);
                            $creneau->setEtat('RESERVE');
                        }
                    }
                }
            }
            else
            {
                    $this->addFlash('danger', "Désolé, mais ce créneau vient juste d'être réservé...");
                    $this->defineRedirect('mediatheque_drive_creneaux');
            }
            //Si on a reservé le creneau on affiche un message flush
            $this->addFlash('success', "Félicitation, votre créneau est reservé !");
            //On redirige vers le profil
            $this->defineRedirect('mediatheque_drive_profil');
            $manager->persist($creneau);
            $manager->flush();
        }
        else
        {
            //Si problème, on redirige vers le profil !
            $this->defineRedirect('mediatheque_drive_profil');
        }
       
        //On affiche la redirection
        return $this->Afficher();
    }

    // FONCTIONS PRIVEES -------------------------------------------------------------------------------------------------
    
    /**
     * Permet de récupérer le Membre connecté
     *
     * @return MediathequeMembre|null
     */
    private function LogMembre(): ?MediathequeMembre
    {
        if($this->getUser() != null)
        {
            return $this->getUser()->getMembreMediatheque();
        }
        else
        {
            return null;
        }
    }

    /**
     * Permet de vérifier que l'id de l'emprunteur est correcte puis retourne l'emprunteur
     *
     * @param MediathequeMembre $membre
     * @param integer $idEmprunteur
     * @return MediathequeMembre
     */
    private function emprunteurActif(MediathequeMembre $membre, int $idEmprunteur): MediathequeMembre
    {
        //Par defaut, l'emprunteur est le membre connecté !
        $emprunteur = $membre;

        //On verifie si il passe la commande pour un autre
        if($idEmprunteur != null && $idEmprunteur != $membre->getId())
        {
            $membreAVerifier = $this->outilsService->findById(MediathequeMembre::class, $idEmprunteur);
            if($membreAVerifier != null)
            {
                if($membreAVerifier->getFamille() == $membre->getFamille())
                {
                    $emprunteur = $membreAVerifier;
                }
            }
        }
        $this->defineParamTwig('emprunteur', $emprunteur);
        return $emprunteur;
    }

    // ACTIONS APPELEES PAR LES FORMULAIRES ------------------------------------------------------------------------------
    
    /**
     * Action - Ajoute le lien à la structure
     *
     * @param Object $lien
     * @param Array $params
     * @return Object
     */
    public function action_preinscriptionMembre(Object $membre, Array $params):Object
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
     * Action - ajouter un membre de la famille
     *
     * @param Object $lien
     * @param Array $params
     * @return Object
     */
    public function action_preinscriptionMembreFamille(Object $membreFamille, Array $params):Object
    {
        $chefMembre = $params['membre'];
        $membreFamille->getUtilisateur()->setPseudo($membreFamille->getNumCarte());
        $password = $params['encoder']->encodePassword($membreFamille->getUtilisateur(), $membreFamille->getUtilisateur()->getHumain()->getDateNaissance()->format('dm'));
        $membreFamille->getUtilisateur()->setPassword($password);
        
        //Si le membre qui inscrit à déjà une famille on la récupère sinon on en crée une nouvelle
        $famille = $chefMembre->getFamille();
        if($famille == null)
        {
            $famille = new MediathequeFamille();
            $chefMembre->setFamille($famille);
        }
        $membreFamille->setFamille($famille);
        $membreFamille->getUtilisateur()->setEmail($chefMembre->getUtilisateur()->getEmail());
        $roles = $this->outilsService->findAll(Role::class);
        foreach($roles as $role)
        {
            if($role->getTitre() == 'ROLE_MEMBRE_MEDIATHEQUE')
            {
                $membreFamille->getUtilisateur()->addRole($role);
            }
        }
        return $membreFamille;
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
        $emprunteur = $params['emprunteur'];
        
        //On prépare l'état de la commande
        $etat = new MediathequeDriveCommandeEtat('USER_ENCOURS');
        
        $commande->setArchive(false);
        $commande->addEtat($etat);
        $commande->setMembre($emprunteur);
        return $commande;
    }
      




    

    

    

    

    













    

    

    

    

    
    

    

    

    
    

    // /**
    //  * @Route("/mediatheque/drive/commandes", name="mediatheque_drive_commandes")
    //  * @IsGranted("ROLE_MEMBRE_MEDIATHEQUE")
    //  * @return void
    //  */
    // public function commandes()
    // {
    //     return $this->render('mediatheque_drive/commandes.html.twig');
    // }

    

    
}
