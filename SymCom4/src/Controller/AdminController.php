<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Association;
use App\Form\NewServiceType;
use App\Entity\TypeAssociation;
use App\Form\NewAssociationType;
use App\Form\NewTypeAssociationType;
use App\Controller\SymCom4Controller;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssociationRepository;
use App\Repository\TypeAssociationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends SymCom4Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        //Prépare le Twig
        $this->defineTwig('symcom4/admin/index.html.twig');
        $this->initTwig();
        //Affiche la page
        return $this->Afficher();
    }

    /******************************************************************************************/
    /****************************************Les services *************************************/
    /**
     * @Route("/admin/services", name="admin_services")
     */
    public function services(ServiceRepository $repo): Response
    {
        //Récupére tous les services
        $services = $repo->findAll();
        //Prépare le Twig
        $this->initTwig('services');
        $this->defineTwig('symcom4/admin/services/services.html.twig');        
        $this->defineParamTwig('services', $services);
        //Affiche la page
        return $this->Afficher();
    }
    /**
     * @Route("/admin/service/new", name="admin_service_new")
     */
    public function newService(Request $request, EntityManagerInterface $manager):Response
    {
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Service();
        $variables['classType'] = NewServiceType::class;
        $variables['pagedebase'] = 'symcom4/admin/services/new_service.html.twig';
        $variables['pagederesultat'] = 'admin_services';
        $variables['texteConfirmation'] = "Le service ### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getStructure()->getNom();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('services');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/service/edit/{id}", name="admin_service_edit")
     */
    public function editService(ServiceRepository $repo, Request $request, EntityManagerInterface $manager, $id):Response
    {
        //On recupére le Service
        $service = $repo->findOneById($id);
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $service;
        $variables['classType'] = newServiceType::class;
        $variables['pagedebase'] = 'symcom4/admin/services/new_service.html.twig';
        $variables['pagederesultat'] = 'admin_services';
        $variables['texteConfirmation'] = "Le service ### a bien été modifié !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getStructure()->getNom();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('services');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/service/delete/{id}", name="admin_service_delete")
     */
    public function deleteService(ServiceRepository $repo, EntityManagerInterface $manager, $id):Response
    {
        //On supprimer le Service
        $this->delete($repo, $manager, $id);
        //Défini la page de redirection
        $this->defineRedirect('admin_services');
        //Prépare le Twig
        $this->initTwig('services');
        //Affiche la redirection
        return $this->Afficher();
    }
    /******************************************************************************************/
    /****************************************Les associations *********************************/
    /**
     * @Route("/admin/associations", name="admin_associations")
     */
    public function associations(AssociationRepository $repo): Response
    {
        //Récupére tous les services
        $associations = $repo->findAll();
        //Prépare le Twig
        $this->initTwig('associations');
        $this->defineTwig('symcom4/admin/associations/associations.html.twig');
        $this->defineParamTwig('associations', $associations);
        //Affiche la page
        return $this->Afficher();
    }
    /**
     * @Route("/admin/association/new", name="admin_association_new")
     */
    public function newAssociation(Request $request, EntityManagerInterface $manager):Response
    {
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Association();
        $variables['classType'] = NewAssociationType::class;
        $variables['pagedebase'] = 'symcom4/admin/associations/new_association.html.twig';
        $variables['pagederesultat'] = 'admin_associations';
        $variables['texteConfirmation'] = "L'association ### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getStructure()->getNom();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/association/edit/{id}", name="admin_association_edit")
     */
    public function editAssociation(AssociationRepository $repo, Request $request, EntityManagerInterface $manager, $id):Response
    {
        //On recupére le Service
        $association = $repo->findOneById($id);
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $association;
        $variables['classType'] = newAssociationType::class;
        $variables['pagedebase'] = 'symcom4/admin/associations/new_association.html.twig';
        $variables['pagederesultat'] = 'admin_associations';
        $variables['texteConfirmation'] = "L'association ### a bien été modifié !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getStructure()->getNom();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/association/delete/{id}", name="admin_association_delete")
     */
    public function deleteAssociation(AssociationRepository $repo, EntityManagerInterface $manager, $id):Response
    {
        //On supprimer le Service
        $this->delete($repo, $manager, $id);
        //Défini la page de redirection
        $this->defineRedirect('admin_associations');
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche la redirection
        return $this->Afficher();
    }
    /******************************************************************************************/
    /****************************************Les types d'associations *************************/
    /**
     * @Route("/admin/associations/types", name="admin_associations_types")
     */
    public function typeAssociations(TypeAssociationRepository $repo): Response
    {
        //Récupére tous les types d'associations
        $typeassociations = $repo->findAll();
        //Prépare le Twig
        $this->initTwig('associations');
        $this->defineTwig('symcom4/admin/associations/types/typeassociations.html.twig');
        $this->defineParamTwig('typeassociations', $typeassociations);
        //Affiche la page
        return $this->Afficher();
    }
    /**
     * @Route("/admin/associations/type/new", name="admin_associations_type_new")
     */
    public function newTypeAssociation(Request $request, EntityManagerInterface $manager):Response
    {
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new TypeAssociation();
        $variables['classType'] = NewTypeAssociationType::class;
        $variables['pagedebase'] = 'symcom4/admin/associations/types/new_typeassociation.html.twig';
        $variables['pagederesultat'] = 'admin_associations';
        $variables['texteConfirmation'] = "Le type d'association ### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getNom();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/associations/type/edit/{id}", name="admin_associations_type_edit")
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
        $variables['pagedebase'] = 'symcom4/admin/associations/types/new_typeassociation.html.twig';
        $variables['pagederesultat'] = 'admin_associations';
        $variables['texteConfirmation'] = "Le type d'association ### a bien été modifié !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getNom();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/associations/type/delete/{id}", name="admin_associations_type_delete")
     */
    public function deleteTypeAssociation(TypeAssociationRepository $repo, EntityManagerInterface $manager, $id):Response
    {
        //On supprimer le Service
        $this->delete($repo, $manager, $id);
        //Défini la page de redirection
        $this->defineRedirect('admin_associations');
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche la redirection
        return $this->Afficher();
    }
}
