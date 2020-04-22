<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class OutilsService {
    
    private $request;               //Objet Request
    private $manager;               //Objet EntityManagerInterface

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS MAGIQUES ********************************************************************/

    public function __construct(EntityManagerInterface $manager, RequestStack $requestStack)
    {
        $this->manager = $manager;
        $this->request = $requestStack->getCurrentRequest();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS PUBLIQUES *******************************************************************/

    /**
     * Récupérer une entité à partir de son id
     *
     * @param string $class
     * @param integer $id
     * @return Object
     */
    public function findById(string $class, int $id):Object
    {
        $repo = $this->manager->getRepository($class);
        return $repo->findOneById($id);
    }

    /**
     * Récupérer toutes les entités d'une classe
     *
     * @param string $class
     * @return Array
     */
    public function findAll(string $class):Array
    {
        $repo = $this->manager->getRepository($class);
        return $repo->findAll();
    }

    /**
     * Supprimer un objet
     *
     * @param string $class
     * @param integer $id
     * @return void
     */
    public function delete(string $class, int $id):void
    {
        //Récupérer l'objet
        $objet = $this->findById($class, $id);
        //Supprimer l'objet
        $this->manager->remove($objet);
        //Enregistrer
        $this->manager->flush();
    }

}