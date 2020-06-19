<?php

namespace App\Service;

use App\Entity\Dossier;

class MediaService {
    
    private $outilsBox;

    public function __construct()    {        
        
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS GET ET SET ******************************************************************/

    public function setOutilsBox($outilsBox)
    {
        $this->outilsBox = $outilsBox;
    }
    

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS PUBLIQUES *******************************************************************/

    public function ouvrirDossier($dossierParent, $titreDossier)
    {
        if($dossierParent == null)
        {
            $dossier = $this->outilsBox->findEntityBy(Dossier::class, ['cote' => $titreDossier]);
            if(sizeOf($dossier) == 0)
            {
                $dossier = new Dossier();
                $dossier->setTitre($titreDossier);
                $dossier->creerDossierPhysique();
                $this->outilsBox->persist($dossier);
            }
            else
            {
                $dossier = $dossier[0];
            }            
        }
        else
        {
            $dossier = null;
            foreach($dossierParent->getEnfants() as $enfant)
            {
                if($enfant->getTitre() == $titreDossier)
                {
                    $dossier = $enfant;
                }
            }
            if($dossier == null)
            {
                //Le dossier n'existe pas donc on va le créer
                $dossier = new Dossier();
                $dossier->setParent($dossierParent);
                $dossier->setTitre($titreDossier);
                $dossier->creerDossierPhysique();
                $this->outilsBox->persist($dossier);
            }
        }
                
        return $dossier;
    }
    
}