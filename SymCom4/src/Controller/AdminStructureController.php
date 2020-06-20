<?php

namespace App\Controller;

use App\Entity\Lien;
use App\Entity\Page;

use App\Entity\Image;
use App\Entity\Media;
use App\Entity\Contact;
use App\Entity\Dossier;
use App\Entity\Service;
use App\Entity\Fonction;
use App\Entity\Structure;
use App\Entity\Entreprise;
use App\Form\FonctionType;
use App\Form\LienBaseType;

use App\Entity\Association;
use App\Entity\TypeFonction;
use App\Service\PageService;
use App\Form\ServiceBaseType;
use App\Service\MediaService;
use App\Entity\TypeEntreprise;
use App\Form\FonctionBaseType;
use App\Form\TypeFonctionType;
use App\Entity\TypeAssociation;
use App\Service\ContactService;
use App\Service\GestionService;

use App\Form\EntrepriseBaseType;
use App\Form\EntrepriseTypeType;
use App\Form\FonctionHumainType;

use App\Form\TypeEntrepriseType;
use App\Form\AssociationBaseType;
use App\Form\AssociationTypeType;
use App\Form\TypeAssociationType;
use App\Controller\AdminController;
use App\Controller\SymCom4Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeFonctionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminStructureController extends AdminController
{
    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** STRUCTURES - AJOUTER, MODIFIER et SUPPRIMER un LIEN ***********************************/

    /**
     * Ajouter un lien à une structure
     *
     * @Route("/admin/structure/addlien/{idstructure}/{idpagemere}", name="admin_structure_addlien")
     * 
     * (Test n°1 - Assert 6)
     * 
     * @param integer $idstructure
     * @param integer $idpagemere
     * @return Response
     */
     public function addlienStructure(int $idstructure, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idpagemere'));

        //Récupérer l'objet Structure
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);

        //Gérer le formulaire
        $this->outilsBox->setFormElement(new Lien());
        $this->outilsBox->setFormClassType(LienBaseType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_lien.html.twig');
        $this->outilsBox->setFormTexteConfirmation("Le lien a bien été modifié !");
        $this->outilsBox->setFormActions(array(['name' => 'action_addlienStructure', 'params' => ['structure' => $structure]]));
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Modifier le lien d'une structure
     *
     * @Route("/admin/structures/lien/edit/{idstructure}/{idlien}/{idpagemere}", name="admin_structure_editlien")
     * 
     * (Test n°1 - Assert 7)
     * 
     * @param integer $idstructure
     * @param integer $idlien
     * @param integer $idpagemere
     * @return Response
     */
    public function editLienStructure(int $idstructure, int $idlien, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idlien', 'idpagemere'));

        //Récupérer l'objet Structure et l'objet lien
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        $lien = $this->outilsBox->findEntityById(Lien::class, $idlien);

        //Gérer le formulaire
        $this->outilsBox->setFormElement($lien);
        $this->outilsBox->setFormClassType(LienBaseType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_lien.html.twig');
        $this->outilsBox->setFormTexteConfirmation("Le lien ### a bien été modifié !");
        $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getLabel();']);
        $this->outilsBox->setFormActions(array(['name' => 'action_addlienStructure', 'params' => ['structure' => $structure]]));
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Supprimer le lien d'une structure
     *
     * @Route("/admin/structures/lien/delete/{idstructure}/{idlien}/{idpagemere}", name="admin_structure_deletelien")
     * 
     * (Test n°1 - Assert 8)
     * 
     * @param integer $idstructure
     * @param integer $idlien
     * @param integer $idpagemere
     * @return Response
     */
    public function deleteLienStructure(int $idstructure, int $idlien, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idlien', 'idpagemere'));

        //Récupérer l'objet Structure et l'objet page
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        $pageMere = $this->outilsBox->findEntityById(Page::class, $idpagemere);

        //Supprimer le lien de la structure
        $structure->setLien(null);

        //Supprimer le lien de la BDD
        $this->outilsBox->deleteEntityById(Lien::class, $idlien);
        
        //Afficher un message de validation
        $this->addFlash('success', 'Le lien a bien été supprimé.');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);

        //Définir la page de redirection
        $this->outilsBox->defineRedirection($pageMere->getNomChemin());
        $this->outilsBox->addParamsRedirect($pageMere->getParams());

        //Afficher la redirection
        return $this->jobController();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** STRUCTURES - CHOISIR, AJOUTER, MODIFIER et SUPPRIMER une IMAGE ************************/

    /**
     * Ajouter un lien à une structure
     *
     * @Route("/admin/structure/choisirimage/{idstructure}/{idpagemere}", name="admin_structure_choisirimage")
     * 
     */
    public function choisirImageStructure(int $idstructure, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idpagemere'));

        //Récupérer l'objet Structure
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Définir le twig a utiliser
        $this->outilsBox->defineTwig('symcom4/admin/general/form_structure_choix_image.html.twig');

        //Choisir les images disponibles
        $dossier = $this->outilsBox->findEntityBy(Dossier::class, ['titre' => 'upload_image']);
        $dossier = $dossier[0];
        $medias = $this->outilsBox->findEntityBy(Media::class, ['dossier' => $dossier]);
        
        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('dossier', 'upload_image');
        $this->outilsBox->addParamTwig('structure', $structure);
        $this->outilsBox->addParamTwig('medias', $medias);
        $this->outilsBox->addParamTwig('idpagemere', $idpagemere);
        
        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Ajouter un lien à une structure
     *
     * @Route("/admin/structure/addimage/{idstructure}/{idpagemere}/{idimage}", name="admin_structure_addimage")
     * 
     */
    public function addImageStructure(int $idstructure, int $idpagemere, int $idimage):Response
    {
        //Récupérer l'objet Structure et l'objet media
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        $image = $this->outilsBox->findEntityById(Image::class, $idimage);

        //On récupère le dossier du type de structure s'il existe sinon on le crée
        $dossier = $this->mediaService->ouvrirDossier(null, 'images');
        $dossier = $this->mediaService->ouvrirDossier($dossier, 'structures');
        $dossier = $this->mediaService->ouvrirDossier($dossier, $structure->getType().'s');
        $dossier = $this->mediaService->ouvrirDossier($dossier, $structure->getSlug());
                
        // On associe l'image à la structure
        $structure->setImage($image);
        $this->outilsBox->persist($structure);

        //On déplace l'image
        $image->getMedia()->deplacer($dossier);
        $this->outilsBox->persist($image);

        //On redirige vers la structure
        switch($structure->getType())
        {
            case 'service':
                $this->outilsBox->defineRedirection('admin_structures_service');        
                $this->outilsBox->addParamRedirect('idservice', $structure->getService()->getId());
            break;
            case 'association':
                $this->outilsBox->defineRedirection('admin_structures_association');        
                $this->outilsBox->addParamRedirect('idassociation', $structure->getAssociation()->getId());
            break;
            case 'entreprise':
                $this->outilsBox->defineRedirection('admin_structures_entreprise');        
                $this->outilsBox->addParamRedirect('identreprise', $structure->getEntreprise()->getId());
            break;

        }
        
        

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Modifier le lien d'une structure
     *
     * @Route("/admin/structures/lien/edit/{idstructure}/{idimage}/{idpagemere}", name="admin_structure_editimage")
     * 
     */
    public function editImageStructure(int $idstructure, int $idlien, int $idpagemere):Response
    {
        // //Donner les arguments de la page en cours au PageService
        // // $this->outilsBox->setPageParams(compact('idstructure', 'idlien', 'idpagemere'));

        // //Récupérer l'objet Structure et l'objet lien
        // $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        // $lien = $this->outilsBox->findEntityById(Lien::class, $idlien);

        // //Gérer le formulaire
        // $this->outilsBox->setFormElement($lien);
        // $this->outilsBox->setFormClassType(LienBaseType::class);
        // $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_lien.html.twig');
        // $this->outilsBox->setFormTexteConfirmation("Le lien ### a bien été modifié !");
        // $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getLabel();']);
        // $this->outilsBox->setFormActions(array(['name' => 'action_addlienStructure', 'params' => ['structure' => $structure]]));
        
        
        // //Obtenir le titre et le menu rapide en fonction du type
        // $this->initTwig($structure->getType());

        // //Fournir les paramètres requis au Twig
        // $this->outilsBox->addParamTwig('structure', $structure);

        // //Laisser le controller faire son Job avec tout ça...
        // return $this->jobController();
    }

    /**
     * Supprimer le lien d'une structure
     *
     * @Route("/admin/structures/lien/delete/{idstructure}/{idimage}/{idpagemere}", name="admin_structure_deleteimage")
     * 
     */
    public function deleteImageStructure(int $idstructure, int $idlien, int $idpagemere):Response
    {
        // //Donner les arguments de la page en cours au PageService
        // // $this->outilsBox->setPageParams(compact('idstructure', 'idlien', 'idpagemere'));

        // //Récupérer l'objet Structure et l'objet page
        // $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        // $pageMere = $this->outilsBox->findEntityById(Page::class, $idpagemere);

        // //Supprimer le lien de la structure
        // $structure->setLien(null);

        // //Supprimer le lien de la BDD
        // $this->outilsBox->deleteEntityById(Lien::class, $idlien);
        
        // //Afficher un message de validation
        // $this->addFlash('success', 'Le lien a bien été supprimé.');

        // //Obtenir le titre et le menu rapide en fonction du type
        // $this->initTwig($structure->getType());

        // //Fournir les paramètres requis au Twig
        // $this->outilsBox->addParamTwig('structure', $structure);

        // //Définir la page de redirection
        // $this->outilsBox->defineRedirection($pageMere->getNomChemin());
        // $this->outilsBox->addParamsRedirect($pageMere->getParams());

        // //Afficher la redirection
        // return $this->jobController();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** STRUCTURES - AJOUTER, MODIFIER et SUPPRIMER un CONTACT ********************************/

    /**
     * Ajouter un contact à la structure
     *
     * @Route("/admin/structure/addcontact/{idstructure}/{idpagemere}/{type}", name="admin_structure_addcontact")
     * 
     * (Test n°1 - Assert 9 - Adresse)
     * (Test n°1 - Assert 12 - Téléphone)
     * (Test n°1 - Assert 15 - Mail)
     * 
     * @param integer $idstructure
     * @param integer $idpagemere
     * @param string $type
     * @param ContactService $contactService
     * @return Response
     */
    public function addcontactStructure(int $idstructure, int $idpagemere, string $type, ContactService $contactService):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idpagemere', 'type'));
        
        //Récupérer l'objet Structure et l'objet page
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        
        //Gérer le formulaire
        $this->outilsBox->setFormElement($contactService->getElementFormulaire($type));
        $this->outilsBox->setFormClassType($contactService->getClassTypeFormulaire($type));
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_contact.html.twig');
        $this->outilsBox->setFormTexteConfirmation("Le contact a bien été modifié !");
        $this->outilsBox->setFormActions(array(['name' => 'action_addcontactStructure', 'params' => ['structure' => $structure]]));
        
        

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);
        $this->outilsBox->addParamTwig('type', $type);

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Modifier le contact de la structure
     *
     * @Route("/admin/structure/editcontact/{idstructure}/{idcontact}/{idpagemere}", name="admin_structure_editcontact")
     * 
     * (Test n°1 - Assert 10 - Adresse)
     * (Test n°1 - Assert 13 - Téléphone)
     * (Test n°1 - Assert 16 - Mail)
     * 
     * @param integer $idstructure
     * @param integer $idcontact
     * @param integer $idpagemere
     * @param ContactService $contactService
     * @return Response
     */
    public function editcontactStructure(int $idstructure, int $idcontact, int $idpagemere, ContactService $contactService):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idcontact', 'idpagemere'));

        //Récupérer l'objet Structure, l'objet Contact et le type de contact
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        $contact = $this->outilsBox->findEntityById(Contact::class, $idcontact);
        $type = $contact->getType();

        //Gérer le formulaire
        $this->outilsBox->setFormElement($contactService->getElementFormulaire($type, $contact));
        $this->outilsBox->setFormClassType($contactService->getClassTypeFormulaire($type));
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_contact.html.twig');
        $this->outilsBox->setFormTexteConfirmation("Le contact a bien été modifié !");
        
        

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);
        $this->outilsBox->addParamTwig('type', $type);

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Supprimer un contact de la structure
     *
     * @Route("/admin/structure/deletecontact/{idstructure}/{idcontact}/{idpagemere}", name="admin_structure_deletecontact")
     * 
     * (Test n°1 - Assert 11 - Adresse)
     * (Test n°1 - Assert 14 - Téléphone)
     * (Test n°1 - Assert 17 - Mail)
     * 
     * @param integer $idstructure
     * @param integer $idcontact
     * @param integer $idpagemere
     * @return Response
     */
    public function deleteContactStructure(int $idstructure, int $idcontact, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idcontact', 'idpagemere'));

        //Récupérer l'objet Structure, l'objet contact et l'objet page
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        $contact = $this->outilsBox->findEntityById(Contact::class, $idcontact);
        $pageMere = $this->outilsBox->findEntityById(Page::class, $idpagemere);

        //Supprimer le contact de la structure
        $structure->removeContact($contact);

        //Supprimer le contact de la BDD
        $this->outilsBox->deleteEntityById(Contact::class, $idcontact);
        
        //Afficher un message de validation
        $this->addFlash('success', 'Le contact a bien été supprimé !');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);

        //Définir la page de redirection
        $this->outilsBox->defineRedirection($pageMere->getNomChemin());
        $this->outilsBox->addParamsRedirect($pageMere->getParams());

        //Afficher la redirection
        return $this->jobController();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** STRUCTURES - AJOUTER, MODIFIER et SUPPRIMER une FONCTION ******************************/

    /**
     * Ajouter une fonction à la structure
     *
     * @Route("/admin/structure/addfonction/{idstructure}/{idpagemere}", name="admin_structure_addfonction")
     * 
     * (Test n°1 - Assert 18)
     * 
     * @param integer $idstructure
     * @param integer $idpagemere
     * @return Response
     */
    public function addfonctionStructure(int $idstructure, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idpagemere'));

        //Récupérer l'objet Structure
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);

        //Gérer le formulaire
        $this->outilsBox->setFormElement(new Fonction());
        $this->outilsBox->setFormClassType(FonctionBaseType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_fonction_base.html.twig');
        $this->outilsBox->setFormTexteConfirmation("La fonction a bien été modifié !");
        $this->outilsBox->setFormActions(array(['name' => 'action_addfonctionStructure', 'params' => ['structure' => $structure]]));
        
        

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Modifier une fonction de la structure
     *
     * @Route("/admin/structure/editfonction/{idstructure}/{idfonction}/{idpagemere}", name="admin_structure_editfonction")
     * 
     * (Test n°1 - Assert 27)
     * 
     * @param integer $idstructure
     * @param integer $idfonction
     * @param integer $idpagemere
     * @return Response
     */
    public function editFonctionStructure(int $idstructure, int $idfonction, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idfonction', 'idpagemere'));

        //Récupérer l'objet Structure et l'objet Fonction
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        $fonction = $this->outilsBox->findEntityById(Fonction::class, $idfonction);

        //Gérer le formulaire
        $this->outilsBox->setFormElement($fonction);
        $this->outilsBox->setFormClassType(FonctionBaseType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_fonction_base.html.twig');
        $this->outilsBox->setFormTexteConfirmation("La fonction a bien été modifié !");
        
        

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);
        $this->outilsBox->addParamTwig('fonction', $fonction);

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Supprimer un contact de la structure
     *
     * @Route("/admin/structure/deletefonction/{idstructure}/{idfonction}/{idpagemere}", name="admin_structure_deletefonction")
     * 
     * (Test n°1 - Assert 28)
     * 
     * @param integer $idstructure
     * @param integer $idfonction
     * @param integer $idpagemere
     * @return Response
     */
    public function deleteFonctionStructure(int $idstructure, int $idfonction, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idfonction', 'idpagemere'));

        //Récupérer l'objet Structure, l'objet fonction et l'objet page mère'
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        $fonction = $this->outilsBox->findEntityById(Fonction::class, $idfonction);
        $pageMere = $this->outilsBox->findEntityById(Page::class, $idpagemere);

        //Supprimer la fonction de la structure
        $structure->removeFonction($fonction);

        //Supprimer la fonction de la BDD
        $this->outilsBox->deleteEntityById(Fonction::class, $idfonction);
        
        //Afficher un message de validation
        $this->addFlash('success', 'Le fonction a bien été supprimé !');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);

        //Définir la page de redirection
        $this->outilsBox->defineRedirection($pageMere->getNomChemin());
        $this->outilsBox->addParamsRedirect($pageMere->getParams());

        //Afficher la redirection
        return $this->jobController();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** STRUCTURES - AJOUTER et SUPPRIMER un HUMAIN d'une FONCTION ****************************/

    /**
     * Ajouter un humain à une fonction de la structure
     * 
     * @Route("/admin/structure/fonction/addhumain/{idstructure}/{idfonction}/{idpagemere}", name="admin_structure_fonction_addhumain")
     * 
     * (Test n°1 - Assert 19)
     * 
     * @param integer $idstructure
     * @param integer $idfonction
     * @param integer $idpagemere
     * @return Response
     */
    public function addHumainFonctionStructure(int $idstructure, int $idfonction, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idfonction', 'idpagemere'));

        //Récupérer l'objet Structure et l'objet Fonction
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        $fonction = $this->outilsBox->findEntityById(Fonction::class, $idfonction);

        //Gérer le formulaire
        $this->outilsBox->setFormElement($fonction);
        $this->outilsBox->setFormClassType(FonctionHumainType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_fonction_humain.html.twig');
        $this->outilsBox->setFormTexteConfirmation("La fonction a bien été modifié !");
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);
        $this->outilsBox->addParamTwig('fonction', $fonction);

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Supprimer un humain d'une fonction de la structure
     *
     * @Route("/admin/structure/fonction/deletehumain/{idstructure}/{idfonction}/{idpagemere}", name="admin_structure_fonction_deletehumain")
     * 
     * (Test n°1 - Assert 20)
     * 
     * @param integer $idstructure
     * @param integer $idfonction
     * @param integer $idpagemere
     * @return Response
     */
    public function deleteHumainFonctionStructure(int $idstructure, int $idfonction, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idfonction', 'idpagemere'));

        //Récupérer l'objet Structure, l'objet fonction et l'objet page mère'
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        $fonction = $this->outilsBox->findEntityById(Fonction::class, $idfonction);
        $pageMere = $this->outilsBox->findEntityById(Page::class, $idpagemere);

        //Supprimer l'humain de la fonction
        $fonction->setHumain(null);
        
        //Afficher un message de validation
        $this->addFlash('success', 'Le fonction a bien été supprimé !');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);

        //Définir la page de redirection
        $this->outilsBox->defineRedirection($pageMere->getNomChemin());
        $this->outilsBox->addParamsRedirect($pageMere->getParams());

        //Afficher la redirection
        return $this->jobController();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** SERVICES - VOIR, AJOUTER, GERER, MODIFIER et SUPPRIMER ********************************/

    /**
     * Afficher l'ensemble des Services
     *
     * @Route("/admin/structures/services", name="admin_structures_services")
     * 
     * (Test n°1 - Assert 1 - La page s'affiche)
     * 
     * @return Response
     */
    public function services(): Response
    {
        //Récupérer tous les services
        $services = $this->outilsBox->findAllEntity(Service::class);

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('service');

        //Définir le twig à afficher
        $this->outilsBox->defineTwig('symcom4/admin/structures/services.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('services', $services);

        //Afficher la page
        return $this->jobController();
    }

    /**
     * Créer un service
     *
     * @Route("/admin/structures/service/new", name="admin_structures_service_new")
     * 
     * (Test n°1 - Assert 5)
     * 
     * @return Response
     */
    public function newService():Response
    {
        //Gérer le formulaire
        $this->outilsBox->setFormElement(new Service());
        $this->outilsBox->setFormClassType(ServiceBaseType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_service_base.html.twig');
        $this->outilsBox->setFormPageResultat('admin_structures_service');
        $this->outilsBox->setFormTexteConfirmation("Le service <strong>###</strong> a bien été créé !");
        $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getStructure()->getNom();']);
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('service');
        
        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /** 
     * Gérer un service
     *
     * @Route("/admin/structures/service/{idservice}", name="admin_structures_service")
     * 
     * (Test n°1 - Assert 2 - Affichage d'un service
     * 
     * @param integer $idservice
     * @return Response
     */
    public function service(int $idservice): Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idservice'));

        //Récupérer l'objet Service
        $service = $this->outilsBox->findEntityById(Service::class, $idservice);

        //Préparer le titre et le menu rapide
        $this->initTwig('service');

        //Définir le twig à afficher
        $this->outilsBox->defineTwig('symcom4/admin/structures/service.html.twig'); 

        //Fournir les paramètres requis au Twig      
        $this->outilsBox->addParamTwig('service', $service);

        //Afficher la page
        return $this->jobController();
    }

    /**
     * Modifier des informations de base d'un service
     *
     * @Route("/admin/structures/service/edit/{idstructure}/{idpagemere}", name="admin_structures_service_edit")
     * 
     * (Test n°1 - Assert 3 - Modification des informations de base d'un service)
     * 
     * @param integer $idstructure
     * @param integer $idpagemere
     * @return Response
     */
    public function editService(int $idstructure, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idpagemere'));
        
        //Récupérer l'objet Structure
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        
        //Gérer le formulaire
        $this->outilsBox->setFormClassType(ServiceBaseType::class);
        $this->outilsBox->setFormElement($structure->getService());
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_service_base.html.twig');
        $this->outilsBox->setFormTexteConfirmation("Le service a bien été modifié !");
        
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Supprimer un service
     *
     * @Route("/admin/structures/service/delete/{idstructure}", name="admin_structures_service_delete")
     * 
     * (Test n°1 - Assert 4)
     * 
     * @param integer $idstructure
     * @return Response
     */
    public function deleteService(int $idstructure):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure'));

        //Supprimer le service de la BDD
        $this->outilsBox->deleteEntityById(Structure::class, $idstructure);
        
        //Afficher un message de validation
        $this->addFlash('success', 'Le service a bien été supprimé !');
       
        //Définir la page de redirection
        $this->outilsBox->defineRedirection('admin_structures_services');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('service');

        //Afficher la redirection
        return $this->jobController();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** ASSOCIATIONS - VOIR, AJOUTER, GERER, MODIFIER et SUPPRIMER ****************************/

    /**
     * Affiche l'ensemble des associations
     *
     * @Route("/admin/structures/associations", name="admin_structures_associations")
     * 
     * (Test n°2 - Assert 1)
     * 
     * @return Response
     */
    public function associations(): Response
    {
        //Récupérer toutes les associations
        $associations = $this->outilsBox->findAllEntity(Association::class);

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('association');

        //Définir le twig à afficher
        $this->outilsBox->defineTwig('symcom4/admin/structures/associations.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('associations', $associations);

        //Afficher la page
        return $this->jobController();
    }

    /**
     * Créer une association
     *
     * @Route("/admin/structures/association/new", name="admin_structures_association_new")
     * 
     * (Test n°2 - Assert 5)
     * 
     * @return Response
     */
    public function newAssociation():Response
    {
        //Gérer le formulaire
        $this->outilsBox->setFormElement(new Association());
        $this->outilsBox->setFormClassType(AssociationBaseType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_association_base.html.twig');
        $this->outilsBox->setFormPageResultat('admin_structures_association');
        $this->outilsBox->setFormTexteConfirmation("L'association' <strong>###</strong> a bien été créé !");
        $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getStructure()->getNom();']);
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('association');
        
        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Gérer une association
     * 
     * @Route("/admin/structures/association/{idassociation}", name="admin_structures_association")
     *
     * (Test n°2 - Assert 2)
     * @param integer $idassociation
     * @return Response
     */
    public function association(int $idassociation): Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idassociation'));

        //Récupérer l'objet Association
        $association = $this->outilsBox->findEntityById(Association::class, $idassociation);

        //Préparer le titre et le menu rapide
        $this->initTwig('association');

        //Définir le twig à afficher
        $this->outilsBox->defineTwig('symcom4/admin/structures/association.html.twig'); 

        //Fournir les paramètres requis au Twig      
        $this->outilsBox->addParamTwig('association', $association);

        //Afficher la page
        return $this->jobController();
    }

    /**
     * Modifier les informations de base d'une association
     * 
     * @Route("/admin/structures/association/edit/{idstructure}/{idpagemere}", name="admin_structures_association_edit")
     *
     * (Test n°2 - Assert 3)
     * @param integer $idstructure
     * @param integer $idpagemere
     * @return Response
     */
    public function editAssociation(int $idstructure, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idpagemere'));
        
        //Récupérer l'objet Structure
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        
        //Gérer le formulaire
        $this->outilsBox->setFormClassType(AssociationBaseType::class);
        $this->outilsBox->setFormElement($structure->getAssociation());
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_association_base.html.twig');
        $this->outilsBox->setFormTexteConfirmation("L'association a bien été modifiée !");
        
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Supprimer une association
     * 
     * @Route("/admin/structures/association/delete/{idstructure}", name="admin_structures_association_delete")
     *
     * (Test n°2 - Assert 4)
     * 
     * @param integer $idstructure
     * @return Response
     */
    public function deleteAssociation(int $idstructure):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure'));

        //Supprimer le service de la BDD
        $this->outilsBox->deleteEntityById(Structure::class, $idstructure);
        
        //Afficher un message de validation
        $this->addFlash('success', "L'association' a bien été supprimé !");
       
        //Définir la page de redirection
        $this->outilsBox->defineRedirection('admin_structures_associations');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('association');

        //Afficher la redirection
        return $this->jobController();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** TYPES ASSOCIATIONS - VOIR, AJOUTER, GERER, MODIFIER et SUPPRIMER **********************/

    /**
     * Afficher l'ensemble des types d'association
     *
     * @Route("/admin/structures/associations/types", name="admin_structures_associations_types")
     * 
     * @return Response
     */
    public function typeassociations(): Response
    {
        //Récupérer tous les types d'association
        $typeAssociations = $this->outilsBox->findAllEntity(TypeAssociation::class);

        //Obtenir le titre et le menu rapide
        $this->initTwig('association');

        //Définir le twig à afficher
        $this->outilsBox->defineTwig('symcom4/admin/structures/typeassociations.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('typeAssociations', $typeAssociations);

        //Afficher la page
        return $this->jobController();
    }

    /**
     * Créer un type d'association
     * 
     * @Route("/admin/structures/associations/type/new", name="admin_structures_associations_type_new")
     *
     * @return Response
     */
    public function newTypeAssociation():Response
    {
        //Gérer le formulaire
        $this->outilsBox->setFormElement(new TypeAssociation());
        $this->outilsBox->setFormClassType(TypeAssociationType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_typeassociation.html.twig');
        $this->outilsBox->setFormPageResultat('admin_structures_associations_types');
        $this->outilsBox->setFormTexteConfirmation("Le type d'association ### a bien été créé !");
        $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getNom();']);
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('association');
        
        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Modifier un type d'association
     * 
     * @Route("/admin/structures/associations/type/edit/{idtypeassociation}", name="admin_structures_associations_type_edit")
     *
     * @param integer $idtypeassociation
     * @return Response
     */
    public function editTypeAssociation(int $idtypeassociation):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idtypeassociation'));
        
        //Récupérer l'objet Structure
        $typeAssociation = $this->outilsBox->findEntityById(TypeAssociation::class, $idtypeassociation);
        
        //Gérer le formulaire
        $this->outilsBox->setFormElement($typeAssociation);
        $this->outilsBox->setFormClassType(TypeAssociationType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_typeassociation.html.twig');
        $this->outilsBox->setFormTexteConfirmation("Le type d'association ### a bien été modifié !");
        $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getNom();']);
        $this->outilsBox->setFormPageResultat('admin_structures_associations_types');
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('association');

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Supprimer un type d'association
     * 
     * @Route("/admin/structures/associations/type/delete/{idtypeassociation}", name="admin_structures_associations_type_delete")
     *
     * @param integer $idtypeassociation
     * @return Response
     */
    public function deleteTypeAssociation(int $idtypeassociation):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idtypeassociation'));

        //Supprimer le type d'association de la BDD
        $this->outilsBox->deleteEntityById(TypeAssociation::class, $idtypeassociation);
        
        //Afficher un message de validation
        $this->addFlash('success', "Le type d'association a bien été supprimé !");
       
        //Définir la page de redirection
        $this->outilsBox->defineRedirection('admin_structures_typeassociations');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('association');

        //Afficher la redirection
        return $this->jobController();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** ASSOCIATIONS - MODIFIER LE TYPE ASSOCIATION *******************************************/

    /**
     * Modifier le type de l'association
     * 
     * @Route("/admin/structures/association/edittype/{idstructure}/{idpagemere}", name="admin_structures_association_edittype")
     *
     * @param integer $idstructure
     * @param integer $idpagemere
     * @return Response
     */
    public function editAssociationType(int $idstructure, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idpagemere'));
        
        //Récupérer l'objet Structure
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        
        //Gérer le formulaire
        $this->outilsBox->setFormClassType(AssociationTypeType::class);
        $this->outilsBox->setFormElement($structure->getAssociation());
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_association_type.html.twig');
        $this->outilsBox->setFormTexteConfirmation("L'association a bien été modifié !");
        
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** ENTREPRISES - VOIR, AJOUTER, GERER, MODIFIER et SUPPRIMER *****************************/

    /**
     * Afficher l'ensemble des entreprises
     * 
     * @Route("/admin/structures/entreprises", name="admin_structures_entreprises")
     *
     * @return Response
     */
    public function entreprises(): Response
    {
        //Récupérer tous les services
        $entreprises = $this->outilsBox->findAllEntity(Entreprise::class);

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('entreprise');

        //Définir le twig à afficher
        $this->outilsBox->defineTwig('symcom4/admin/structures/entreprises.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('entreprises', $entreprises);

        //Afficher la page
        return $this->jobController();
    }

    /**
     * Créer une nouvelle entreprise
     * 
     * @Route("/admin/structures/entreprise/new", name="admin_structures_entreprise_new")
     *
     * @return Response
     */
    public function newEntreprise():Response
    {
        //Gérer le formulaire
        $this->outilsBox->setFormElement(new Entreprise());
        $this->outilsBox->setFormClassType(EntrepriseBaseType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_entreprise_base.html.twig');
        $this->outilsBox->setFormPageResultat('admin_structures_entreprise');
        $this->outilsBox->setFormTexteConfirmation("L'entreprise <strong>###</strong> a bien été créé !");
        $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getStructure()->getNom();']);
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('entreprise');
        
        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Gérer une entreprise
     * 
     * @Route("/admin/structures/entreprise/{identreprise}", name="admin_structures_entreprise")
     *
     * @param integer $identreprise
     * @return Response
     */
    public function Entreprise(int $identreprise): Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('identreprise'));

        //Récupérer l'objet Service
        $entreprise = $this->outilsBox->findEntityById(Entreprise::class, $identreprise);

        //Préparer le titre et le menu rapide
        $this->initTwig('entreprise');

        //Définir le twig à afficher
        $this->outilsBox->defineTwig('symcom4/admin/structures/entreprise.html.twig'); 

        //Fournir les paramètres requis au Twig      
        $this->outilsBox->addParamTwig('entreprise', $entreprise);

        //Afficher la page
        return $this->jobController();
    }

    /**
     * Modifier les informations de base d'une entreprise
     * 
     * @Route("/admin/structures/entreprise/edit/{idstructure}/{idpagemere}", name="admin_structures_entreprise_edit")
     *
     * @param integer $idstructure
     * @param integer $idpagemere
     * @return Response
     */
    public function editEntreprise(int $idstructure, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idpagemere'));
        
        //Récupérer l'objet Structure
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        
        //Gérer le formulaire
        $this->outilsBox->setFormClassType(EntrepriseBaseType::class);
        $this->outilsBox->setFormElement($structure->getEntreprise());
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_entreprise_base.html.twig');
        $this->outilsBox->setFormTexteConfirmation("L'entreprise a bien été modifié !");
        
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Supprimer une entreprise
     * 
     * @Route("/admin/structures/entreprise/delete/{idstructure}", name="admin_structures_entreprise_delete")
     *
     * @param integer $idstructure
     * @return Response
     */
    public function deleteEntreprise(int $idstructure):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure'));

        //Supprimer le service de la BDD
        $this->outilsBox->deleteEntityById(Structure::class, $idstructure);
        
        //Afficher un message de validation
        $this->addFlash('success', "L'entreprise a bien été supprimé !");
       
        //Définir la page de redirection
        $this->outilsBox->defineRedirection('admin_structures_entreprises');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('entreprise');

        //Afficher la redirection
        return $this->jobController();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** TYPES ENTREPRISES - VOIR, AJOUTER, GERER, MODIFIER et SUPPRIMER ***********************/

    /**
     * Afficher l'ensemble des types d'entreprise
     * 
     * @Route("/admin/structures/entreprises/types", name="admin_structures_entreprises_types")
     *
     * @return Response
     */
    public function typeentreprises(): Response
    {
        //Récupérer tous les types d'association
        $typeEntreprises = $this->outilsBox->findAllEntity(TypeEntreprise::class);

        //Obtenir le titre et le menu rapide
        $this->initTwig('entreprise');

        //Définir le twig à afficher
        $this->outilsBox->defineTwig('symcom4/admin/structures/typeentreprises.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('typeEntreprises', $typeEntreprises);

        //Afficher la page
        return $this->jobController();
    }

    /**
     * Créer un type d'entreprise
     * 
     * @Route("/admin/structures/entreprises/type/new", name="admin_structures_entreprises_type_new")
     *
     * @return Response
     */
    public function newTypeEntreprise():Response
    {
        //Gérer le formulaire
        $this->outilsBox->setFormElement(new TypeEntreprise());
        $this->outilsBox->setFormClassType(TypeEntrepriseType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_typeentreprise.html.twig');
        $this->outilsBox->setFormPageResultat('admin_structures_entreprises_types');
        $this->outilsBox->setFormTexteConfirmation("Le type d'entreprise ### a bien été créé !");
        $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getNom();']);
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('entreprise');
        
        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Modifier un type d'entreprise
     * 
     * @Route("/admin/structures/entreprises/type/edit/{idtypeentreprise}", name="admin_structures_entreprises_type_edit")
     *
     * @param integer $idtypeentreprise
     * @return Response
     */
    public function editTypeEntreprise(int $idtypeentreprise):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idtypeentreprise'));
        
        //Récupérer l'objet Structure
        $typeEntreprise = $this->outilsBox->findEntityById(TypeEntreprise::class, $idtypeentreprise);
        
        //Gérer le formulaire
        $this->outilsBox->setFormElement($typeEntreprise);
        $this->outilsBox->setFormClassType(TypeEntrepriseType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_typeentreprise.html.twig');
        $this->outilsBox->setFormTexteConfirmation("Le type d'entreprise ### a bien été modifié !");
        $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getNom();']);
        $this->outilsBox->setFormPageResultat('admin_structures_typeentreprises');
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('entreprise');

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Supprimer un type d'entreprise
     * 
     * @Route("/admin/structures/entreprises/type/delete/{idtypeentreprise}", name="admin_structures_entreprises_type_delete")
     *
     * @param integer $idtypeentreprise
     * @return Response
     */
    public function deleteTypeEntreprise(int $idtypeentreprise):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idtypeentreprise'));

        //Supprimer le type d'association de la BDD
        $this->outilsBox->deleteEntityById(TypeEntreprise::class, $idtypeentreprise);
        
        //Afficher un message de validation
        $this->addFlash('success', "Le type d'entreprise a bien été supprimé !");
       
        //Définir la page de redirection
        $this->outilsBox->defineRedirection('admin_structures_typeentreprises');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('entreprise');

        //Afficher la redirection
        return $this->jobController();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** ENTREPRISES - MODIFIER LE TYPE ENTREPRISE *********************************************/

    /**
     * Modifier le type de l'entreprise
     * 
     * @Route("/admin/structures/entreprise/edittype/{idstructure}/{idpagemere}", name="admin_structures_entreprise_edittype")
     *
     * @param integer $idstructure
     * @param integer $idpagemere
     * @return Response
     */
    public function editEntrepriseType(int $idstructure, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idstructure', 'idpagemere'));
        
        //Récupérer l'objet Structure
        $structure = $this->outilsBox->findEntityById(Structure::class, $idstructure);
        
        //Gérer le formulaire
        $this->outilsBox->setFormClassType(EntrepriseTypeType::class);
        $this->outilsBox->setFormElement($structure->getEntreprise());
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_entreprise_type.html.twig');
        $this->outilsBox->setFormTexteConfirmation("L'entreprise a bien été modifié !");
        
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS - VOIR **********************************************************************/

    /**
     * Afficher l'ensemble des fonctions
     *
     * @Route("/admin/fonctions", name="admin_fonctions")
     * 
     * @return Response
     */
    public function fonctions(): Response
    {
        //Récupérer toutes les fonctions
        $fonctions = $this->outilsBox->findAllEntity(Fonction::class);

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('fonction');

        //Définir le twig à afficher
        $this->outilsBox->defineTwig('symcom4/admin/fonctions/fonctions.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('fonctions', $fonctions);

        //Afficher la page
        return $this->jobController();
    }  
    
    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** TYPES FONCTIONS - VOIR, AJOUTER, GERER, MODIFIER et SUPPRIMER ***********************/

    /**
     * Afficher l'ensemble des types de fonctions
     * 
     * @Route("/admin/fonctions/types", name="admin_fonctions_types")
     *
     * @return Response
     */
    public function typefonctions(): Response
    {
        //Récupérer tous les types de fonction
        $typeFonctions = $this->outilsBox->findAllEntity(TypeFonction::class);

        //Obtenir le titre et le menu rapide
        $this->initTwig('fonction');

        //Définir le twig à afficher
        $this->outilsBox->defineTwig('symcom4/admin/fonctions/typefonctions.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('typeFonctions', $typeFonctions);

        //Afficher la page
        return $this->jobController();
    }

    /**
     * Créer un type de fonction
     * 
     * @Route("/admin/fonctions/type/new", name="admin_fonctions_type_new")
     *
     * @return Response
     */
    public function newTypeFonction():Response
    {
        //Gérer le formulaire
        $this->outilsBox->setFormElement(new TypeFonction());
        $this->outilsBox->setFormClassType(TypeFonctionType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_typefonction.html.twig');
        $this->outilsBox->setFormPageResultat('admin_fonctions_types');
        $this->outilsBox->setFormTexteConfirmation("Le type de fonction ### a bien été créé !");
        $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getTitre();']);
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('fonction');
        
        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Modifier un type de fonction
     * 
     * @Route("/admin/fonctions/type/edit/{idtypefonction}", name="admin_fonctions_type_edit")
     *
     * @param integer $idtypefonction
     * @return Response
     */
    public function editTypeFonction(int $idtypefonction):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idtypefonction'));
        
        //Récupérer l'objet TypeFonction
        $typeFonction = $this->outilsBox->findEntityById(TypeFonction::class, $idtypefonction);
        
        //Gérer le formulaire
        $this->outilsBox->setFormElement($typeFonction);
        $this->outilsBox->setFormClassType(TypeFonctionType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_typefonction.html.twig');
        $this->outilsBox->setFormTexteConfirmation("Le type de fonction ### a bien été modifiée !");
        $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getTitre();']);
        $this->outilsBox->setFormPageResultat('admin_fonctions_types');
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('fonction');

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Supprimer un type de fonction
     *
     * @Route("/admin/fonctions/type/delete/{idtypefonction}", name="admin_fonctions_type_delete")
     * 
     * @param integer $idtypefonction
     * @return Response
     */
    public function deleteTypeFonction(int $idtypefonction):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idtypefonction'));

        //Supprimer le type d'association de la BDD
        $this->outilsBox->deleteEntityById(TypeFonction::class, $idtypefonction);
        
        //Afficher un message de validation
        $this->addFlash('success', "Le type de fonction a bien été supprimé !");
       
        //Définir la page de redirection
        $this->outilsBox->defineRedirection('admin_fonctions_types');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('fonction');

        //Afficher la redirection
        return $this->jobController();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS - AJOUTER, EDITER et SUPPRIMER un CONTACT ***********************************/

    /**
     * Ajouter un contact à une fonction
     * 
     * @Route("/admin/structure/fonction/addcontact/{idfonction}/{idpagemere}/{type}", name="admin_structure_fonction_addcontact")
     *
     * (Test n°1 - Assert 21 - Mail)
     * (Test n°1 - Assert 22 - Téléphone)
     * 
     * @param integer $idfonction
     * @param integer $idpagemere
     * @param string $type
     * @param ContactService $contactService
     * @return Response
     */
    public function addcontactStructureFonction(int $idfonction, int $idpagemere, string $type, ContactService $contactService):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idfonction', 'idpagemere', 'type'));

        //Récupérer l'objet Fonction et l'objet Structure
        $fonction = $this->outilsBox->findEntityById(Fonction::class, $idfonction);
        $structure = $fonction->getStructure();

        //Gérer le formulaire
        $this->outilsBox->setFormElement($contactService->getElementFormulaire($type));
        $this->outilsBox->setFormClassType($contactService->getClassTypeFormulaire($type));
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_contact.html.twig');
        $this->outilsBox->setFormTexteConfirmation("Le contact a bien été modifié !");
        $this->outilsBox->setFormActions(array(['name' => 'action_addcontactStructureFonction', 'params' => ['fonction' => $fonction]]));
        
        

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('type', $type);
        $this->outilsBox->addParamTwig('structure', $structure);
        $this->outilsBox->addParamTwig('fonction', $fonction);

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Modifier le contact d'une fonction
     *
     * @Route("/admin/structure/fonction/editcontact/{idfonction}/{idcontact}/{idpagemere}", name="admin_structure_fonction_editcontact")
     * 
     * (Test n°1 - Assert 23 - Mail)
     * (Test n°1 - Assert 24 - Téléphone)
     * 
     * @param integer $idfonction
     * @param integer $idcontact
     * @param integer $idpagemere
     * @param ContactService $contactService
     * @return Response
     */
    public function editcontactStructureFonction(int $idfonction, int $idcontact, int $idpagemere, ContactService $contactService):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idfonction', 'idcontact', 'idpagemere'));

        //Récupérer l'objet Fonction, l'objet Structure et l'objet Contact
        $fonction = $this->outilsBox->findEntityById(Fonction::class, $idfonction);
        $structure = $fonction->getStructure();
        $contact = $this->outilsBox->findEntityById(Contact::class, $idcontact);
        $type = $contact->getType();

        //Gérer le formulaire
        $this->outilsBox->setFormElement($contactService->getElementFormulaire($type, $contact));
        $this->outilsBox->setFormClassType($contactService->getClassTypeFormulaire($type));
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_contact.html.twig');
        $this->outilsBox->setFormTexteConfirmation("Le contact a bien été modifié !");
        
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($type);

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);
        $this->outilsBox->addParamTwig('fonction', $fonction);
        $this->outilsBox->addParamTwig('type', $type);

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Supprimer un contact d'une fonction
     * 
     * @Route("/admin/structure/fonction/deletecontact/{idfonction}/{idcontact}/{idpagemere}", name="admin_structure_fonction_deletecontact")
     *
     * (Test n°1 - Assert 25 - Mail)
     * (Test n°1 - Assert 26 - Téléphone)
     * 
     * @param integer $idfonction
     * @param integer $idcontact
     * @param integer $idpagemere
     * @return Response
     */
    public function deleteContactStructureFonction(int $idfonction, int $idcontact, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idfonction', 'idcontact', 'idpagemere'));

        //Récupérer l'objet Fonction, l'objet Structure et l'objet Page mere
        $fonction = $this->outilsBox->findEntityById(Fonction::class, $idfonction);
        $contact = $this->outilsBox->findEntityById(Contact::class, $idcontact);
        $structure = $fonction->getStructure();
        $pageMere = $this->outilsBox->findEntityById(Page::class, $idpagemere);
        
        //Supprimer le contact de la fonction
        $fonction->removeContact($contact);

        //Supprimer le contact de la BDD
        $this->outilsBox->deleteEntityById(Contact::class, $idcontact);
        
        //Afficher un message de validation
        $this->addFlash('success', 'Le contact a bien été supprimé !');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('structure', $structure);
        $this->outilsBox->addParamTwig('fonction', $fonction);

        //Définir la page de redirection
        $this->outilsBox->defineRedirection($pageMere->getNomChemin());
        $this->outilsBox->addParamsRedirect($pageMere->getParams());

        //Afficher la redirection
        return $this->jobController();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** ACTIONS *******************************************************************************/
    
    /**
     * Action - Ajoute le lien à la structure
     *
     * @param Object $lien
     * @param Array $params
     * @return Object
     */
    public function action_addlienStructure(Object $lien, Array $params):Object
    {
        $lien->setStructure($params['structure']);
        return $lien;
    }

    /**
     * Action - Ajouter un contact à la structure
     *
     * @param Object $contactChild
     * @param Array $params
     * @return Object
     */
    public function action_addcontactStructure(Object $contactChild, Array $params):Object
    {
        $contact = $contactChild->getContact();
        $contact->addStructure($params['structure']);
        return $contactChild;
    }

    /**
     * Action - Ajouter une fonction à la structure
     *
     * @param Object $fonction
     * @param Array $params
     * @return Object
     */
    public function action_addfonctionStructure(Object $fonction, Array $params):Object
    {
        $fonction->setStructure($params['structure']);
        return $fonction;
    }

    /**
     * Action - Ajouter un contact à la fonction
     *
     * @param Object $contactChild
     * @param Array $params
     * @return Object
     */
    public function action_addcontactStructureFonction(Object $contactChild, Array $params):Object
    {
        $contact = $contactChild->getContact();
        $contact->addFonction($params['fonction']);
        return $contactChild;
    }
























    /*========================================================================================*/
    /** Les fonctions - Ajoute, édite et supprime un contact *********************************/

    




    




















    

    

    

    

    

    


    

    

    

    

    
    


    

    

    
    



    


    


    
    

    /*========================================================================================*/
    /** A garder pour la gestion des images sera peut etre utile !!! **************************************************************/
/*
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
*/
    
    
}
