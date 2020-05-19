<?php

namespace App\Controller;

use DateTime;
use App\Controller\SymCom4Controller;
use App\Form\MediathequeDriveScanType;
use App\Entity\MediathequeDriveCreneau;
use App\Entity\MediathequeDriveCommande;
use App\Service\MediathequeDriveService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\MediathequeDriveScanRetour;
use App\Entity\MediathequeDriveCommandeEtat;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MediathequeDriveCommandeRetourType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MediathequeDriveAdminController extends SymCom4Controller
{
    private $jours = array();
    private $nbrCommandesATraiter = 0;

    /**
     * @Route("/admin/mediatheque/drive", name="admin_mediatheque_drive")
     * @IsGranted("ROLE_ADMIN_MEDIATHEQUE")
     */
    public function index()
    {
        //Prépare les x prochains jours pour valider l'ouverture
        $this->prepaJours();
        
        //Recupere les prochains creneaux et les jours valide
        $creneaux = $this->recupCreneauxAujourdhui();
        
        //Recupere les anciens creneaux non fini
        $anciensCreneaux = $this->recupAncienCreneauNonFini();
        foreach($anciensCreneaux as $ancienCreneau)
        {
            //On verifie si le creneau et est fin automatisé
            $ancienCreneau->verifCreneauPasse();
        }
        $anciensCreneaux = $this->recupAncienCreneauNonFini();
        
        

        $this->defineTwig('mediatheque_drive_admin/index.html.twig');
        $this->defineParamTwig('creneaux', $creneaux);
        $this->defineParamTwig('jours', $this->jours);
        $this->defineParamTwig('nbrCommandesATraiter', $this->nbrCommandesATraiter);
        $this->defineParamTwig('anciensCreneaux', $anciensCreneaux);

        return $this->Afficher();
    }

    /**
     * @Route("/admin/mediatheque/drive/borne", name="admin_mediatheque_drive_borne")
     * @IsGranted("ROLE_ADMIN_MEDIATHEQUE")
     */
    public function borne(Request $request, EntityManagerInterface $manager)
    {
        //Recupere les prochains creneaux et les jours valide
        $creneaux = $this->recupCreneauxActuels();

        //Création d'un formulaire pour l'utilisation du scanner
        $form = $this->createForm(MediathequeDriveScanType::class);        
        $form->handleRequest($request);

        //Action a effectuer selon le scan
        $toto = $this->gestionScan($form, $manager);

        //Heure actuelle
        $now = new DateTime('now');
        $timestamp = $now->getTimestamp();
        $timestamp = strtotime("+ 0 minutes", $timestamp); //////////////////////////////////////////////////////+120 changé pour les tests (triche)
        $now->setTimestamp($timestamp);
        
        $this->defineTwig('mediatheque_drive_admin/borne.html.twig');
        $this->defineParamTwig('creneaux', $creneaux);
        $this->defineParamTwig('now', $now);
        $this->defineParamTwig('form', $form->createView());

        $repoScanRetour = $this->outilsService->returnRepo(MediathequeDriveScanRetour::class);
        $scansRetour = $repoScanRetour->findLastNonTraite();
        $this->defineParamTwig('scansRetour', $scansRetour);
        
        return $this->Afficher();
    }

    /**
     * @Route("/admin/mediatheque/drive/scansretour", name="admin_mediatheque_drive_scansretour")
     * @IsGranted("ROLE_ADMIN_MEDIATHEQUE")
     */
    public function scanRetour(Request $request, EntityManagerInterface $manager)
    {
        $repoScanRetour = $this->outilsService->returnRepo(MediathequeDriveScanRetour::class);
        $scansRetour = $repoScanRetour->findNonTraite();
        
        $this->defineTwig('mediatheque_drive_admin/scanretour.html.twig');
        $this->defineParamTwig('scansRetour', $scansRetour);
        
        return $this->Afficher();
    }

    /**
     * @Route("/admin/mediatheque/drive/scanretour/supprimer/{idscan}", name="admin_mediatheque_drive_scanretour_supprimer")
     * @IsGranted("ROLE_ADMIN_MEDIATHEQUE")
     */
    public function scanRetourSupprimer($idscan, EntityManagerInterface $manager)
    {
        $scan = $this->outilsService->findById(MediathequeDriveScanRetour::class, $idscan);
        $scan->setTraite(true);
        
        $this->defineRedirect('admin_mediatheque_drive_scansretour');
        $manager->persist($scan);
        $manager->flush();
        return $this->Afficher();
    }

    private function gestionScan($form, $manager)
    {
        $code = $this->Scan($form);
        switch($code)
        {
            case '019432':
                $creneaux = $this->recupCreneauActuel();
                $this->defineRedirect('admin_mediatheque_drive_creneau_finir_borne');
                $this->defineParamRedirect(['idcreneau' => $creneaux[0]->getId()]);
            break;
            case null:
                //rien
            break;
            default :
                $scanRetour = new MediathequeDriveScanRetour();
                $scanRetour->setCodeBarre($code);
                $now = new DateTime('now');
                $scanRetour->setDateScan($now);
                $scanRetour->setTraite(false);
                $manager->persist($scanRetour);
                //$manager->flush();                
            break;
        }
        return null;
    }

    protected function Scan($form)
    {
        $scan = null;
        if($form->isSubmitted() && $form->isValid()) 
        {
            $scan = $form['scan']->getData();       
        }
        return $scan;
    }

    /**
     * @Route("/admin/mediatheque/drive/commande/{idcommande}", name="admin_mediatheque_drive_commande")
     * @IsGranted("ROLE_ADMIN_MEDIATHEQUE")
     */
    public function commande($idcommande)
    {
        $commande = $this->outilsService->findById(MediathequeDriveCommande::class, $idcommande);
        //$this->defineTwig('mediatheque_drive_admin/commande.html.twig');
        $this->defineParamTwig('commande', $commande);

        //Formulaire pour le nombre de retour et les notes
        //Gérer le formulaire
        $this->formulaireService->setElement($commande);
        $this->formulaireService->setClassType(MediathequeDriveCommandeRetourType::class);
        $this->formulaireService->setTwigFormulaire('mediatheque_drive_admin/commande.html.twig');
        $this->formulaireService->setPageResultat('admin_mediatheque_drive_commande');
        $this->formulaireService->setPageResultatConfig(['idcommande' => $idcommande]);
        //$this->formulaireService->setActions($this, array(['name' => 'action_preinscriptionMembreFamille', 'params' => array('encoder' => $encoder, 'membre' => $membre)]));
        $this->formulaireService->setTexteConfirmation("Les modification sont bien enregistrées.");
        $this->createFormService();
        
        return $this->Afficher();
    }

    /**
     * @Route("/admin/mediatheque/drive/creneau/finir/{idcreneau}", name="admin_mediatheque_drive_creneau_finir")
     * @IsGranted("ROLE_ADMIN_MEDIATHEQUE")
     */
    public function finirCreneau($idcreneau, EntityManagerInterface $manager)
    {
        $creneau = $this->outilsService->findById(MediathequeDriveCreneau::class, $idcreneau);

        //On passe le creneau et ses commandes en fini
        $commandes = $creneau->getCommandes();
        foreach($commandes as $commande)
        {
            $etat = new MediathequeDriveCommandeEtat('FINI');
            $commande->addEtat($etat);
            $manager->persist($commande);
        }

        $creneau->setEtat('FINI');
        $manager->persist($creneau);
        $manager->flush();

        $this->defineRedirect('admin_mediatheque_drive');
        
        return $this->Afficher();
    }
    /**
     * @Route("/admin/mediatheque/drive/creneau/finir_borne/{idcreneau}", name="admin_mediatheque_drive_creneau_finir_borne")
     * @IsGranted("ROLE_ADMIN_MEDIATHEQUE")
     */
    public function finirCreneau2($idcreneau, EntityManagerInterface $manager)
    {
        $creneau = $this->outilsService->findById(MediathequeDriveCreneau::class, $idcreneau);
        
        //On passe le creneau et ses commandes en fini
        $commandes = $creneau->getCommandes();
        foreach($commandes as $commande)
        {
            $etat = new MediathequeDriveCommandeEtat('FINI');
            $commande->addEtat($etat);
            $manager->persist($commande);
        }

        $creneau->setEtat('FINI');
        $manager->persist($creneau);
        $manager->flush();

        $this->defineRedirect('admin_mediatheque_drive_borne');
        
        return $this->Afficher();
    }

    /**
     * @Route("/admin/mediatheque/drive/commande/prepare/{idcommande}", name="admin_mediatheque_drive_commande_prepare")
     * @IsGranted("ROLE_ADMIN_MEDIATHEQUE")
     */
    public function commandePrepare($idcommande)
    {
        $commande = $this->outilsService->findById(MediathequeDriveCommande::class, $idcommande);
        
        
        $etat = new MediathequeDriveCommandeEtat('PRET');
        $commande->addEtat($etat);

        $this->defineRedirect('admin_mediatheque_drive');
        
        return $this->Afficher();
    }


    /**
     * @Route("/admin/mediatheque/drive/addcreneaux/{jour}/{mois}/{annee}/{heure}/{minute}", name="admin_mediatheque_drive_add_creneaux")
     * @IsGranted("ROLE_ADMIN_MEDIATHEQUE")
     */
    public function newCreneaux($jour, $mois, $annee, $heure, $minute, MediathequeDriveService $mediathequeDriveService)
    {
        $mediathequeDriveService->creerCreneauxJournee($jour, $mois, $annee, $heure, $minute);

        $this->defineRedirect('admin_mediatheque_drive');
        return $this->Afficher();
    }

    private function recupCreneauxAujourdhui()
    {
        //On récupére l'heure actuelle +2h pour correspondre avec le créneau horaire de la Médiathèque
        $now = new DateTime('now');
        $timestamp = $now->getTimestamp();
        $timestamp = strtotime("+ 0 minutes", $timestamp);
        $now->setTimestamp($timestamp);

        //On affiche tous les créneaux à partir du matin
        $horaireDebut = new DateTime($now->format('Y').'-'.$now->format('m').'-'.$now->format('d').' 08:00:00');

        //Nombre de creneaux à afficher au max
        $limit = "1000";

        //On récupère les creneaux à afficher
        $creneaux = $this->outilsService->returnRepo(MediathequeDriveCreneau::class)->findAllinFutur($horaireDebut, $limit);

        //On passe automatiquement les creneaux fermé / vide et passé en fini
        foreach($creneaux as $creneau)
        {
            
            //On verifie le jour du creneau pour voir si le drive est ouvert
            $this->recupJoursDrive($creneau);
            //Calcul le nombre de commandes à préparer
            foreach($creneau->getCommandes() as $commande)
            {
                if($commande->getEtat()->getEtat() != 'FINI' && $commande->getEtat()->getEtat() != 'PRET')
                {
                    $this->nbrCommandesATraiter = $this->nbrCommandesATraiter + 1;
                }
            }
        }

        return $creneaux;
    }

    private function recupCreneauxActuels()
    {
        //On récupére l'heure actuelle +2h pour correspondre avec le créneau horaire de la Médiathèque
        $now = new DateTime('now');
        $now1havant = new DateTime('now');
        $now1hapres = new DateTime('now');

        $timestamp = $now->getTimestamp();
        $timestamp = strtotime("+ 0 minutes", $timestamp); //////////////////////////////////////////////////////+120 changé pour les tests (triche)
        $timestamp1havant = strtotime("- 15 minutes", $timestamp);
        $timestamp1hapres = strtotime("+ 15 minutes", $timestamp);
        
        $now->setTimestamp($timestamp);
        $now1havant->setTimestamp($timestamp1havant);
        $now1hapres->setTimestamp($timestamp1hapres);

        //On affiche les créneaux de H-15 minutes à h+15 minutes soit 3 creneaux
        $horaireDebut = $now1havant;

        //Nombre de creneaux à afficher au max
        $limit = "3";

        //On récupère les creneaux à afficher
        $creneaux = $this->outilsService->returnRepo(MediathequeDriveCreneau::class)->findAllinFutur($horaireDebut, $limit);

        return $creneaux;
    }

    private function recupCreneauActuel()
    {
        //On récupére l'heure actuelle +2h pour correspondre avec le créneau horaire de la Médiathèque
        $now = new DateTime('now');
        $timestamp = $now->getTimestamp();
        $timestamp = strtotime("+ 0 minutes", $timestamp); //////////////////////////////////////////////////////+120 changé pour les tests (triche)
        $now->setTimestamp($timestamp);
        
        //On affiche les créneaux de H-15 minutes à h+15 minutes soit 3 creneaux
        $horaireDebut = $now;

        //Nombre de creneaux à afficher au max
        $limit = "1";

        //On récupère les creneaux à afficher
        $creneaux = $this->outilsService->returnRepo(MediathequeDriveCreneau::class)->findAllinFutur($horaireDebut, $limit);

        return $creneaux;
    }

    //On récupère les ancien creneaux non fini
    private function recupAncienCreneauNonFini()
    {
        //On récupére l'heure actuelle +2h pour correspondre avec le créneau horaire de la Médiathèque
        $now = new DateTime('now');
        $timestamp = $now->getTimestamp();
        $timestamp = strtotime("+ 0 minutes", $timestamp);
        $now->setTimestamp($timestamp);

        $creneaux = $this->outilsService->returnRepo(MediathequeDriveCreneau::class)->findNonFiniInPasse($now);
        return $creneaux;
    }

    private function recupJoursDrive($creneau)
    {
        $date = $creneau->getDebut();
        $this->jours[$date->format('d/m/Y')]['actif'] = true;
        $this->jours[$date->format('d/m/Y')]['date'] = $date;
    }

    private function prepaJours()
    {
        $today = new DateTime('now');
        $timestamp = $today->getTimestamp();
        $timestamp = strtotime("+ 0 minutes", $timestamp);
        $today->setTimestamp($timestamp);

        $newDate = new DateTime('now');
        $newDate->setTimestamp($timestamp);

        $this->jours[$today->format('d/m/Y')]['actif'] = false;
        $this->jours[$today->format('d/m/Y')]['date'] = $newDate;

        for($i = 0; $i <= 10; $i++)
        {
            $timestamp = $today->getTimestamp();
            $timestamp = strtotime("+ 1440 minutes", $timestamp);
            $today->setTimestamp($timestamp);

            $newDate = new DateTime('now');
            $newDate->setTimestamp($timestamp);

            $this->jours[$today->format('d/m/Y')]['actif'] = false;
            $this->jours[$today->format('d/m/Y')]['date'] = $newDate;
        }
    }




    
}
