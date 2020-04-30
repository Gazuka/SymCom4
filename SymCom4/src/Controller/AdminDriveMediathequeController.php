<?php

namespace App\Controller;

use DateTime;
use App\Controller\SymCom4Controller;
use App\Entity\CreneauDriveMediatheque;
use App\Service\DriveMediathequeService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDriveMediathequeController extends SymCom4Controller
{
    /**
     * @Route("/admin/mediatheque/drive", name="admin_drive_mediatheque")
     * @IsGranted("ROLE_ADMIN_MEDIATHEQUE")
     */
    public function index()
    {
        return $this->render('admin_drive_mediatheque/index.html.twig', [
            'controller_name' => 'AdminDriveMediathequeController',
        ]);
    }

    /**
     * @Route("/admin/mediatheque/drive/addcreneaux/{jour}/{mois}/{annee}/{heure}/{minute}", name="admin_drive_add_creneaux")
     * @IsGranted("ROLE_ADMIN_MEDIATHEQUE")
     */
    public function newCreneaux($jour, $mois, $annee, $heure, $minute, DriveMediathequeService $driveMediathequeService)
    {
        $driveMediathequeService->creerCreneauxJournee($jour, $mois, $annee, $heure, $minute);

        $this->defineRedirect('admin_drive_mediatheque');
        return $this->Afficher();
    }

    
}
