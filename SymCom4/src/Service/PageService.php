<?php

namespace App\Service;

use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;

class PageService {

    private $route;
    private $params = array();
    private $repo;
    private $manager;
    private $page;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->repo = $manager->getRepository(Page::class);
    }
    public function getRoute()
    {
        return $this->route;
    }
    public function setRoute($route)
    {
        $this->route = $route;
    }
    public function getParams()
    {
        return $this->params;
    }
    public function addParam($cle, $valeur)
    {
        $this->params[$cle] = $valeur;
    }

    public function getPageId()
    {
        //Si l'objet page n'est pas encore créé
        if($this->page == null)
        {
            //On vérifi si il existe dans la BDD et on le récupère
            $this->page = $this->repo->findOneByCheminParam($this->route, serialize($this->params));
            //Sinon, on le crée
            if($this->page == null)
            {
                $this->page = new Page();
                $this->page->setNomChemin($this->route);
                $this->page->setParams($this->params);
                $this->manager->persist($this->page);
                $this->manager->flush();
            }
        }
        return $this->page->getId();
    }

    public function setId($id)
    {
        $this->page = $this->repo->findOneById($id);
        return $this->page;
    }

    public function Enregistrer()
    {
        if($this->page != null)
        {
            $this->manager->persist($this->page);
            $this->manager->flush();
        }
    }
}