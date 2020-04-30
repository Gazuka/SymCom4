<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class OutilsService {
    
    private $manager;               //Objet EntityManagerInterface

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS MAGIQUES ********************************************************************/

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
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

    public function findBy(string $class, array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $repo = $this->manager->getRepository($class);
        return $repo->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function returnRepo(string $class)
    {
        $repo = $this->manager->getRepository($class);
        return $repo;
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
     * Récupérer une entités au hasard d'une classe
     *
     * @param string $class
     * @return Array
     */
    public function findOneofAll(string $class):Array
    {
        $entitys = $this->findAll($class);
        $value = rand(0, sizeof($entitys));
        return $entitys[$value];
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