<?php

namespace App\Service;

use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;

class PageService {

    private $repo;
    private $manager;
    //Page actuelle :
    private $route;
    private $params = array();
    private $page;
    //Page mère :
    private $pageMere;

    //===================================================================================//
    //** Fonctions magiques **************************************************************/

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->repo = $manager->getRepository(Page::class);
    }

    //===================================================================================//
    //** GETs et SETs ********************************************************************/

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

    //===================================================================================//
    //** Page acutellle ******************************************************************/

    /** Retourne la page actuelle
     *
     * @return void
     */
    public function getPage()
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
        return $this->page;
    }

    /** Retourne l'Id de la page actuelle
     *
     * @return void
     */
    public function getPageId()
    {
        return $this->getPage()->getId();
    }

    /** Permet l'enregistrement de la page en BDD
     *
     * @return void
     */
    public function Enregistrer()
    {
        if($this->page != null)
        {
            $this->manager->persist($this->page);
            $this->manager->flush();
        }
    }

    //===================================================================================//
    //** Page mère ***********************************************************************/
    
    /** Récupère la page mère à partir de son Id
     *
     * @param [type] $id
     * @return void
     */
    public function recupPageMere($id)
    {
        $this->pageMere = $this->repo->findOneById($id);
        return $this->pageMere;
    }
}