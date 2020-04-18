<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class OutilsService {
    
    private $request;               //Objet Request
    private $manager;               //Objet EntityManagerInterface

    /************************************************************************************/
    public function __construct(EntityManagerInterface $manager, RequestStack $requestStack)
    {
        $this->manager = $manager;
        $this->request = $requestStack->getCurrentRequest();
    }
    public function recupById($class, $id)
    {
        $repo = $this->manager->getRepository($class);
        return $repo->findOneById($id);
    }   
}