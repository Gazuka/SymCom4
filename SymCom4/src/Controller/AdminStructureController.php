<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\EditLienType;
use App\Entity\Association;
use App\Form\NewServiceType;
use App\Service\PageService;
use App\Entity\TypeAssociation;
use App\Service\ContactService;
use App\Form\NewAssociationType;
use App\Repository\LienRepository;
use App\Form\NewTypeAssociationType;
use App\Controller\SymCom4Controller;
use App\Repository\DossierRepository;
use App\Repository\ServiceRepository;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssociationRepository;
use App\Repository\TypeAssociationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminStructureController extends SymCom4Controller
{
    /******************************************************************************************/
    /****************************************Les services *************************************/
       
    /**
     * @Route("/admin/structures/services", name="admin_structures_services")
     */
    public function services(ServiceRepository $repo): Response
    {
        //Récupére tous les services
        $services = $repo->findAll();
        //Prépare le Twig
        $this->initTwig('services');
        $this->defineTwig('symcom4/admin/structures/services/services.html.twig');        
        $this->defineParamTwig('services', $services);
        //Affiche la page
        return $this->Afficher();
    }
    /**
     * @Route("/admin/structures/service/new", name="admin_structures_service_new")
     */
    public function newService(Request $request, EntityManagerInterface $manager):Response
    {
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Service();
        $variables['classType'] = NewServiceType::class;
        $variables['pagedebase'] = 'symcom4/admin/structures/services/new_service.html.twig';
        $variables['pagederesultat'] = 'admin_structures_services';
        $variables['texteConfirmation'] = "Le service ### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getStructure()->getNom();'];
        $options['actions'] = array(['name' => 'action_newStructure', 'params' => []]);
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('services');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * @Route("/admin/structures/service/{id}", name="admin_structures_service")
     */
    public function service($id, ServiceRepository $repo, ContactService $contactService, PageService $pageService, Request $request): Response
    {
        //On récupère l'id de la page en cours
        $this->pageService = $pageService;
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('id', $id); 
        $idPage = $this->pageService->getPageId();      

        //On récupére le service
        $service = $repo->findOneById($id);

        //On passe le service à notre ContactService
        $contactService->setParent($service->getStructure());      
        $contactService->setIdPage($idPage);  

        //Prépare le Twig
        $this->initTwig('services');
        $this->defineTwig('symcom4/admin/structures/services/service.html.twig');        
        $this->defineParamTwig('service', $service);
        $this->defineParamTwig('contactService', $contactService);

        //Affiche la page
        return $this->Afficher();
    }

    /**
     * @Route("/admin/structures/service/edit/{id}", name="admin_structures_service_edit")
     */
    public function editService(ServiceRepository $repo, Request $request, EntityManagerInterface $manager, $id):Response
    {
        //On recupére le Service
        $service = $repo->findOneById($id);
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $service;
        $variables['classType'] = NewServiceType::class;
        $variables['pagedebase'] = 'symcom4/admin/structures/services/new_service.html.twig';
        $variables['pagederesultat'] = 'admin_structures_services';
        $variables['texteConfirmation'] = "Le service ### a bien été modifié !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getStructure()->getNom();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('services');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/structures/service/delete/{id}", name="admin_structures_service_delete")
     */
    public function deleteService(ServiceRepository $repo, EntityManagerInterface $manager, $id):Response
    {
        //On supprimer le Service
        $this->delete($repo, $manager, $id);
        //Défini la page de redirection
        $this->defineRedirect('admin_structures_services');
        //Prépare le Twig
        $this->initTwig('services');
        //Affiche la redirection
        return $this->Afficher();
    }
    /******************************************************************************************/
    /****************************************Les associations *********************************/
    /**
     * @Route("/admin/structures/associations", name="admin_structures_associations")
     */
    public function associations(AssociationRepository $repo): Response
    {
        //Récupére tous les services
        $associations = $repo->findAll();
        //Prépare le Twig
        $this->initTwig('associations');
        $this->defineTwig('symcom4/admin/structures/associations/associations.html.twig');
        $this->defineParamTwig('associations', $associations);
        //Affiche la page
        return $this->Afficher();
    }
    /**
     * @Route("/admin/structures/association/new", name="admin_structures_association_new")
     */
    public function newAssociation(Request $request, EntityManagerInterface $manager, DossierRepository $repoDossier):Response
    {
        $dossierAssociations = $repoDossier->findOneBy(['titre' => 'associations']);

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Association();
        $variables['classType'] = NewAssociationType::class;
        $variables['pagedebase'] = 'symcom4/admin/structures/associations/new_association.html.twig';
        $variables['pagederesultat'] = 'admin_structures_associations';
        $variables['texteConfirmation'] = "L'association ### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getStructure()->getNom();'];
        $options['actions'] = array(['name' => 'action_newStructure', 'params' => []], ['name' => 'action_newAssociation', 'params' => ['dossierAssociations' => $dossierAssociations]]);
        

        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/structures/association/edit/{id}", name="admin_structures_association_edit")
     */
    public function editAssociation(AssociationRepository $repo, Request $request, EntityManagerInterface $manager, $id):Response
    {
        //On recupére le Service
        $association = $repo->findOneById($id);
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $association;
        $variables['classType'] = NewAssociationType::class;
        $variables['pagedebase'] = 'symcom4/admin/structures/associations/new_association.html.twig';
        $variables['pagederesultat'] = 'admin_structures_associations';
        $variables['texteConfirmation'] = "L'association ### a bien été modifié !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getStructure()->getNom();'];
        $options['actions'] = array(['name' => 'action_newStructure', 'params' => []]);
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/structures/association/delete/{id}", name="admin_structures_association_delete")
     */
    public function deleteAssociation(AssociationRepository $repo, EntityManagerInterface $manager, $id):Response
    {
        //On supprimer le Service
        $this->delete($repo, $manager, $id);
        //Défini la page de redirection
        $this->defineRedirect('admin_structures_associations');
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche la redirection
        return $this->Afficher();
    }
    /******************************************************************************************/
    /****************************************Les types d'associations *************************/
    /**
     * @Route("/admin/structures/associations/types", name="admin_structures_associations_types")
     */
    public function typeAssociations(TypeAssociationRepository $repo): Response
    {
        //Récupére tous les types d'associations
        $typeassociations = $repo->findAll();
        //Prépare le Twig
        $this->initTwig('associations');
        $this->defineTwig('symcom4/admin/structures/associations/types/typeassociations.html.twig');
        $this->defineParamTwig('typeassociations', $typeassociations);
        //Affiche la page
        return $this->Afficher();
    }
    /**
     * @Route("/admin/structures/associations/type/new", name="admin_structures_associations_type_new")
     */
    public function newTypeAssociation(Request $request, EntityManagerInterface $manager):Response
    {
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new TypeAssociation();
        $variables['classType'] = NewTypeAssociationType::class;
        $variables['pagedebase'] = 'symcom4/admin/structures/associations/types/new_typeassociation.html.twig';
        $variables['pagederesultat'] = 'admin_structures_associations';
        $variables['texteConfirmation'] = "Le type d'association ### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getNom();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/structures/associations/type/edit/{id}", name="admin_structures_associations_type_edit")
     */
    public function editTypeAssociation(TypeAssociationRepository $repo, Request $request, EntityManagerInterface $manager, $id):Response
    {
        //On recupére le type d'association
        $typeassociation = $repo->findOneById($id);
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $typeassociation;
        $variables['classType'] = NewTypeAssociationType::class;
        $variables['pagedebase'] = 'symcom4/admin/structures/associations/types/new_typeassociation.html.twig';
        $variables['pagederesultat'] = 'admin_structures_associations';
        $variables['texteConfirmation'] = "Le type d'association ### a bien été modifié !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getNom();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/structures/associations/type/delete/{id}", name="admin_structures_associations_type_delete")
     */
    public function deleteTypeAssociation(TypeAssociationRepository $repo, EntityManagerInterface $manager, $id):Response
    {
        //On supprimer le Service
        $this->delete($repo, $manager, $id);
        //Défini la page de redirection
        $this->defineRedirect('admin_structures_associations');
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche la redirection
        return $this->Afficher();
    }

    protected function action_newStructure($structureChild, $params, $request)
    {
        $structure = $structureChild->getStructure();
        if($structure->getLien() != null)
        {
            if($structure->getLien()->getLabel() == null)
            {
                $structure->setLien(null);
            }
        }
        return $structureChild;
    }

    protected function action_newAssociation($association, $params, $request)
    {
        $dossier = $params['dossierAssociations'];
        //Si le média existe on le récupère
        if($association->getStructure()->getImage() != null)
        {
            //On recupere le media
            $media = $association->getStructure()->getImage()->getMedia();
            //On change son dossier
            $media->deplacer($dossier, $request);
        }
        return $association;
    }

    /**
     * @Route("/admin/structures/{idstructure}/lien/{idlien}/edit", name="admin_structure_editlien")
     */
    public function editLienStructure(StructureRepository $repoStructure, LienRepository $repoLien, Request $request, EntityManagerInterface $manager, $idstructure, $idlien):Response
    {
        //On recupére le lien et la structure
        $lien = $repoLien->findOneById($idlien);
        $structure = $repoStructure->findOneById($idstructure);
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $lien;
        $variables['classType'] = EditLienType::class;
        $variables['pagedebase'] = 'symcom4/admin/general/edit_lien.html.twig';
        switch($structure->getType())
        {
            case 'service':
                $variables['pagederesultat'] = 'admin_structures_service';
                $variables['pagederesultatConfig'] = array('id' => $structure->getService()->getId());
                //Prépare le Twig
                $this->initTwig('services');
            break;
        }
        $variables['texteConfirmation'] = "Le lien ### a bien été modifié !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getLabel();'];
        $this->afficherFormulaire($variables, $options);
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/structures/{idstructure}/lien/{idlien}/delete", name="admin_structure_deletelien")
     */
    public function deleteLienStructure(StructureRepository $repoStructure, LienRepository $repoLien, EntityManagerInterface $manager, $idstructure, $idlien):Response
    {
        $structure = $repoStructure->findOneById($idstructure);
        $structure->setLien(null);
        //On supprimer le Service
        $this->delete($repoLien, $manager, $idlien);
        //Défini la page de redirection
        switch($structure->getType())
        {
            case 'service':
                $this->defineRedirect('admin_structures_service');
                $this->defineParamRedirect(['id' => $structure->getService()->getId()]);
                $this->defineParamTwig('service', $structure->getService());
                //Prépare le Twig
                $this->initTwig('services');
            break;
        }
        //Affiche la redirection
        return $this->Afficher();
    }
}
