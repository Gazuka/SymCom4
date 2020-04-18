<?php

namespace App\Controller;

use App\Entity\Fonction;
use App\Entity\TypeFonction;
use App\Form\FonctionBaseType;
use App\Form\TypeFonctionType;
use App\Service\ContactService;
use App\Service\GestionService;
use App\Controller\AdminController;
use App\Repository\ContactRepository;
use App\Repository\FonctionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeFonctionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminFonctionController extends AdminController
{
    /*========================================================================================*/
    /** Les fonctions *************************************************************************/
    
    /** @Route("/admin/fonctions", name="admin_fonctions")
     * Affichage du tableau avec l'ensemble des fonctions de la BDD
     *
     * @param Request $request
     * @param EntrepriseRepository $repo
     * @return Response
     */
    public function fonctions(Request $request, FonctionRepository $repoFonction): Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //On récupére toutes les fonctions
        $fonctions = $repoFonction->findAll();

        //On prépare le Twig
        $this->initTwig('fonction');
        $this->defineTwig('symcom4/admin/fonctions/fonctions.html.twig');        
        $this->defineParamTwig('fonctions', $fonctions);

        //On affiche la page
        return $this->Afficher();
    }    
    /*========================================================================================*/
    /** Les types de fonctions ****************************************************************/

    /** @Route("/admin/fonctions/types", name="admin_fonctions_types")
     * Affichage du tableau avec l'ensemble des types de fonctions de la BDD
     *
     * @param Request $request
     * @param TypeFonctionRepository $repoTypeFonction
     * @return Response
     */
    public function typefonctions(Request $request, TypeFonctionRepository $repoTypeFonction): Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //On récupére toutes les fonctions
        $typeFonctions = $repoTypeFonction->findAll();

        //On prépare le Twig
        $this->initTwig('fonction');
        $this->defineTwig('symcom4/admin/fonctions/typefonctions.html.twig');        
        $this->defineParamTwig('typeFonctions', $typeFonctions);
        //On affiche la page
        return $this->Afficher();
    }

    /** @Route("/admin/fonctions/type/new", name="admin_fonctions_type_new")
     * Affiche le formulaire de création d'un type de fonctions
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function newTypeFonction(Request $request, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //On donne au formulaire la page de resultat et sa config
        $variables['pagederesultat'] = 'admin_fonctions_types';

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new TypeFonction();
        $variables['classType'] = TypeFonctionType::class;
        $variables['pagedebase'] = 'symcom4/admin/general/form_typefonction.html.twig';
        $variables['texteConfirmation'] = "Le type de fonction ### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getTitre();'];
        $this->afficherFormulaire($variables, $options);
        
        //Prépare le Twig
        $this->initTwig('fonction');
        
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/fonctions/type/edit/{idtypefonction}", name="admin_fonctions_type_edit")
     */
    public function editTypeFonction(Request $request, $idtypefonction, TypeFonctionRepository $repoTypeFonction, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idtypefonction', $idtypefonction);
        
        //On récupère le type de fonction
        $typeFonction = $repoTypeFonction->findOneById($idtypefonction);
        
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['classType'] = TypeFonctionType::class;
        $variables['element'] = $typeFonction;
        $variables['pagedebase'] = 'symcom4/admin/general/form_typefonction.html.twig';
        $variables['pagederesultat'] = 'admin_fonctions_types';
        $variables['texteConfirmation'] = "Le type de fonction ### a bien été modifiée !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getTitre();'];
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('fonction');

        //Affiche la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/fonctions/type/delete/{idtypefonction}", name="admin_fonctions_type_delete")
     * Page qui supprime un type d'entreprise
     *
     * @param Request $request
     * @param [type] $idtypeentreprise
     * @param typeEntrepriseRepository $repoTypeEntreprise
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function deleteTypeFonction(Request $request, $idtypefonction, TypeFonctionRepository $repoTypeFonction, EntityManagerInterface $manager):Response
    {
        //On récupère l'id de la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idtypefonction', $idtypefonction);

        //On supprimer l'fonction
        $this->delete($repoTypeFonction, $manager, $idtypefonction);

        //Défini la page de redirection
        $this->defineRedirect('admin_fonctions_types');

        //Prépare le Twig
        $this->initTwig('fonction');

        //Affiche la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /** Les fonctions - Ajoute, édite et supprime un contact *********************************/

    /** @Route("/admin/structure/fonction/addcontact/{idfonction}/{idpage}/{type}", name="admin_structure_fonction_addcontact")
     * Permet d'ajouter un contact à une fonction (adresse, telephone ou mail)
     */
    public function addcontactStructureFonction(Request $request, $idfonction, $idpage, $type, ContactService $contactService, FonctionRepository $repoFonction, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idfonction', $idfonction);
        $this->pageService->addParam('idpage', $idpage); 
        $this->pageService->addParam('type', $type); 

        //On récupère la fonction et la page mère
        $fonction = $repoFonction->findOneById($idfonction);
        $pageMere = $this->pageService->recupPageMere($idpage);
        $structure = $fonction->getStructure();

        //Selon le type, on crée le type de contact voulu
        $variables = $contactService->addContactConfigForm($type);

        //Lorsque le contact aura un Id, le formulaire devra l'associer avec la fonction
        $options['actions'] = array(['name' => 'action_addcontactStructureFonction', 'params' => ['fonction' => $fonction]]);

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['pagedebase'] = 'symcom4/admin/general/form_contact.html.twig';
        $variables['pagederesultat'] = $pageMere->getNomChemin();
        $variables['pagederesultatConfig'] = $pageMere->getParams();
        $variables['texteConfirmation'] = "Le contact a bien été modifié !";
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('type', $type);
        $this->defineParamTwig('structure', $structure);
        $this->defineParamTwig('fonction', $fonction);

        //Affiche la redirection
        return $this->Afficher();
    }

    /** Action - relative à l'ajout d'un contact sur une fonction
     */
    protected function action_addcontactStructureFonction(Object $contactChild, $params, $request)
    {
        $contact = $contactChild->getContact();
        $contact->addFonction($params['fonction']);
        return $contactChild;
    }

    /** @Route("/admin/structure/fonction/editcontact/{idfonction}/{idcontact}/{idpage}", name="admin_structure_fonction_editcontact")
     * Permet l'édition d'un contact d'une fonction
     */
    public function editcontactStructure(Request $request, $idfonction, $idcontact, $idpage, FonctionRepository $repoFonction, ContactRepository $repoContact, ContactService $contactService, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idfonction', $idfonction);
        $this->pageService->addParam('idcontact', $idcontact);
        $this->pageService->addParam('idpage', $idpage);

        //On récupère la structure, le contact et la page mère
        $fonction = $repoFonction->findOneById($idfonction);
        $structure = $fonction->getStructure();
        $contact = $repoContact->findOneById($idcontact);
        $pageMere = $this->pageService->recupPageMere($idpage);

        //Selon le type, on crée le type de contact voulu
        $variables = $contactService->editContactConfigForm($contact);

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['pagedebase'] = 'symcom4/admin/general/form_contact.html.twig';
        $variables['pagederesultat'] = $pageMere->getNomChemin();
        $variables['pagederesultatConfig'] = $pageMere->getParams();
        $variables['texteConfirmation'] = "Le contact a bien été modifié !"; 
        $options = array();
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);
        $this->defineParamTwig('fonction', $fonction);
        $this->defineParamTwig('type', $contact->getType());

        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/structure/fonction/deletecontact/{idfonction}/{idcontact}/{idpage}", name="admin_structure_fonction_deletecontact")
     * Permet de supprimer un contact associé à une fonction
     */
    public function deleteContactStructureFonction(Request $request, $idfonction, $idcontact, $idpage, FonctionRepository $repoFonction, ContactRepository $repoContact, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idfonction', $idfonction);
        $this->pageService->addParam('idcontact', $idcontact);
        $this->pageService->addParam('idpage', $idpage);

        //On récupère la structure et la page mère
        $fonction = $repoFonction->findOneById($idfonction);
        $structure = $fonction->getStructure();
        $pageMere = $this->pageService->recupPageMere($idpage);

        //On supprimer le contact
        $this->delete($repoContact, $manager, $idcontact);

        //On affiche un message de validation
        $this->addFlash('success', 'Le contact a bien été supprimé.');

        //Défini la page de redirection
        $this->defineRedirect($pageMere->getNomChemin());
        $this->defineParamRedirect($pageMere->getParams());

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);
        $this->defineParamTwig('fonction', $fonction);

        //Affiche la redirection
        return $this->Afficher();
    }


}