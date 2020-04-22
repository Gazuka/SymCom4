<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OutilsControllerStructureTest extends WebTestCase
{
    /** Prérequis pour la réalisation des tests ===========================
     * 
     *  > Un service doit exister
     *  > Une structure doit exister
     *  > Une page doit exister
     *  > Un lien doit exister et appartenir à la structure
     */
    private $idservice = 16;
    private $idstructure = 16;
    private $idpagemere = 1;
    private $idlien = ?;
    private $idcontact = ?;

    public function setUp()
    {

    }

    /** N°1 - Test d'affichage de la page */
    public function testChemin_admin_structure_service()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/structures/service/'.$this->idservice);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /** N°2 - Test d'affichage de la page */
    public function testChemin_admin_structures_service_edit()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/structures/service/edit/'.$this->idstructure.'/'.$this->idpagemere);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /** N°3 - Test d'affichage de la page */
    public function testChemin_admin_structure_addlien()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/structure/addlien/'.$this->idstructure.'/'.$this->idpagemere);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /** N°4 - Test d'affichage de la page */
    public function testChemin_admin_structure_editlien()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/structures/lien/edit/'.$this->idstructure.'/'.$this->idlien.'/'.$this->idpage);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /** N°5 - Test d'affichage de la page */
    public function testChemin_admin_structure_deletelien()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/structures/lien/delete/'.$this->idstructure.'/'.$this->idlien.'/'.$this->idpage);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /** N°6 - Test d'affichage de la page */
    public function testChemin_admin_structure_addcontact()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/structure/addcontact/'.$this->idstructure.'/'.$this->idpage.'/adresse');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $client->request('GET', '/admin/structure/addcontact/'.$this->idstructure.'/'.$this->idpage.'/mail');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $client->request('GET', '/admin/structure/addcontact/'.$this->idstructure.'/'.$this->idpage.'/telephone');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /** N°7 - Test d'affichage de la page */
    public function testChemin_admin_structure_editcontact()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/structure/editcontact/'.$this->idstructure.'/'.$this->idcontact.'/'.$this->idpage);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /** N°8 - Test d'affichage de la page */
    public function testChemin_admin_structure_deletecontact()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/structure/deletecontact/'.$this->idstructure.'/'.$this->idcontact.'/'.$this->idpage);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }



    // /** Test d'affichage de la page services */
    // public function testAdminStructuresServicesIsUp()
    // {
    //     $client = static::createClient();
    //     $client->request('GET', '/admin/structures/services');
    //     $this->assertSame(200, $client->getResponse()->getStatusCode());
    // }









    // /** test d'affichage de la page associations */
    // public function testAdminStructuresAssociationsIsUp()
    // {
    //     $client = static::createClient();
    //     $client->request('GET', '/admin/structures/associations');
    //     $this->assertSame(200, $client->getResponse()->getStatusCode());
    // }
    // /** test d'affichage de la page entreprises */
    // public function testAdminStructuresEntreprisesIsUp()
    // {
    //     $client = static::createClient();
    //     $client->request('GET', '/admin/structures/entreprises');
    //     $this->assertSame(200, $client->getResponse()->getStatusCode());
    // }

    // /** TEST DES FONCTIONS DE : SERVICES */

    // // AJOUTER D'UN NOUVEAU SERVICE
    // public function testAddNewService()
    // {
    //     //Création d'un client afin de réaliser les tests
    //     $client = static::createClient();
    //     //Le client arrive sur la page ci-dessous
    //     $crawler = $client->request('GET', '/admin/structures/services');
    //     //Le client voit un lien
    //     $link = $crawler->selectLink('Ajouter un service')->link();
    //     //Le client clic sur le lien
    //     $crawler = $client->click($link);
    //     //Le client rempli le formulaire
    //     $form = $crawler->selectButton('Enregistrer')->form();
    //     $form['service_base[structure][nom]'] = 'John Doe';
    //     $form['service_base[structure][presentation]'] = 'Mon texte de description';
    //     $form['service_base[structure][local]'] = true;
    //     //Le client valide le formulaire
    //     $client->submit($form);
    //     //Le client est redirigé
    //     $crawler = $client->followRedirect();
    //     //Permet de voir la réponse vu par le client
    //     //echo $client->getResponse()->getContent();
    //     //Test si le client à bien un message de success
    //     $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    // }
    // // MODIFIER LA BASE D'UN SERVICE
    // public function testModifierNomService()
    // {
    //     $client = static::createClient();
    //     $crawler = $client->request('GET', '/admin/structures/services');
    //     $link = $crawler->selectLink('Modifier')->link();
    //     $crawler = $client->click($link);
    //     $link = $crawler->selectLink('Modifier')->link();
    //     $crawler = $client->click($link);
    //     //Le client rempli le formulaire
    //     $form = $crawler->selectButton('Enregistrer')->form();
    //     $form['service_base[structure][nom]'] = 'John Doe 2';
    //     $form['service_base[structure][presentation]'] = 'Mon texte de description 2';
    //     $form['service_base[structure][local]'] = false;
    //     //Le client valide le formulaire
    //     $client->submit($form);
    //     $crawler = $client->followRedirect();
    //     $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    // }
    // // SUPPRIMER UN SERVICE
    // public function testSupprimerService()
    // {
    //     $client = static::createClient();
    //     $crawler = $client->request('GET', '/admin/structures/services');
    //     $link = $crawler->selectLink('Modifier')->link();
    //     $crawler = $client->click($link);
    //     $link = $crawler->selectLink('Supprimer : John Doe 2')->link();
    //     $crawler = $client->click($link);
    //     $crawler = $client->followRedirect();
    //     $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    // }
    // // AJOUTER, MODIFIER ET SUPPRIMER UN MAIL DU SERVICE
    // public function testServiceGestionMails()
    // {
    //     //Création du client
    //     $client = static::createClient();
    //     //Il démarre sur la page des services
    //     $crawler = $client->request('GET', '/admin/structures/services');
    //     //Il clic sur un lien pour modifier un service
    //     $link = $crawler->selectLink('Modifier')->link();
    //     $crawler = $client->click($link);
    //     //Il clic sur ajouter un mail
    //     $link = $crawler->selectLink('Ajouter une adresse e-mail')->link();
    //     $crawler = $client->click($link);
    //     //Il rempli le formulaire avec un nouveau mail
    //     $form = $crawler->selectButton('Enregistrer')->form();
    //     $form['new_mail[adresse]'] = 'monmail@toto.fr';
    //     $form['new_mail[contact][prive]'] = false;
    //     $client->submit($form);
    //     $crawler = $client->followRedirect();
    //     //On vérifi le message de success pour le NOUVEAU mail
    //     $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Le clien clic maintenant pour modifier un mail
    //     $crawler->filter('#gestion_mails')->selectLink('Modifier')->link();
    //     $crawler = $client->click($link);
    //     //Il rempli donc ce nouveau formulaire
    //     $form = $crawler->selectButton('Enregistrer')->form();
    //     $form['new_mail[adresse]'] = 'newmail@toto.fr';
    //     $form['new_mail[contact][prive]'] = true;
    //     $client->submit($form);
    //     $crawler = $client->followRedirect();
    //     //On vérifi le message de success pour la MODIFICATION du mail
    //     $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Le clien clic maintenant pour supprimer un mail
    //     $link = $crawler->filter('#gestion_mails')->selectLink('Supprimer')->link();
    //     $crawler = $client->click($link);
    //     $crawler = $client->followRedirect();
    //     //On vérifi le message de success pour la SUPPRESSION du mail
    //     $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    // }
    // // AJOUTER, MODIFIER ET SUPPRIMER UN NUMERO DE TELEPHONE DU SERVICE
    // public function testServiceGestionTelephones()
    // {
    //     //Création du client
    //     $client = static::createClient();
    //     //Il démarre sur la page des services
    //     $crawler = $client->request('GET', '/admin/structures/services');
    //     //Il clic sur un lien pour modifier un service
    //     $link = $crawler->selectLink('Modifier')->link();
    //     $crawler = $client->click($link);
    //     //Il clic sur ajouter un numéro de téléphone
    //     $link = $crawler->selectLink('Ajouter un numéro de téléphone')->link();
    //     $crawler = $client->click($link);
    //     //Il rempli le formulaire avec un nouveau numéro de téléphone
    //     $form = $crawler->selectButton('Enregistrer')->form();
    //     $form['new_telephone[numero]'] = '00 00 00 00 00';
    //     $form['new_telephone[contact][prive]'] = false;
    //     $client->submit($form);
    //     $crawler = $client->followRedirect();
    //     //On vérifi le message de success pour le NOUVEAU numéro de téléphone
    //     $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Le clien clic maintenant pour modifier un numéro de téléphone
    //     $crawler->filter('#gestion_telephones')->selectLink('Modifier')->link();
    //     $crawler = $client->click($link);
    //     //Il rempli donc ce nouveau formulaire
    //     $form = $crawler->selectButton('Enregistrer')->form();
    //     $form['new_telephone[numero]'] = '11 11 11 11 11';
    //     $form['new_telephone[contact][prive]'] = true;
    //     $client->submit($form);
    //     $crawler = $client->followRedirect();
    //     //On vérifi le message de success pour la MODIFICATION du numéro de téléphone
    //     $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Le clien clic maintenant pour supprimer un numéro de téléphone
    //     $link = $crawler->filter('#gestion_telephones')->selectLink('Supprimer')->link();
    //     $crawler = $client->click($link);
    //     $crawler = $client->followRedirect();
    //     //On vérifi le message de success pour la SUPPRESSION du numéro de téléphone
    //     $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    // }
    // // AJOUTER, MODIFIER ET SUPPRIMER UNE ADRESSE DU SERVICE
    // public function testServiceGestionAdresses()
    // {
    //     //Création du client
    //     $client = static::createClient();
    //     //Il démarre sur la page des services
    //     $crawler = $client->request('GET', '/admin/structures/services');
    //     //Il clic sur un lien pour modifier un service
    //     $link = $crawler->selectLink('Modifier')->link();
    //     $crawler = $client->click($link);
    //     //Il clic sur ajouter une adresse
    //     $link = $crawler->selectLink('Ajouter une adresse')->link();
    //     $crawler = $client->click($link);
    //     //Il rempli le formulaire avec une nouvelle adresse
    //     $form = $crawler->selectButton('Enregistrer')->form();
    //     $form['new_adresse[numero]'] = '13';
    //     $form['new_adresse[rue]'] = 'rue du Bois';
    //     $form['new_adresse[complement]'] = 'chemin du lac';
    //     $form['new_adresse[codePostal]'] = '59287';
    //     $form['new_adresse[ville]'] = 'Guesnain';
    //     $form['new_adresse[contact][prive]'] = false;
    //     $client->submit($form);
    //     $crawler = $client->followRedirect();
    //     //On vérifi le message de success pour la NOUVELLE adresse
    //     $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Le clien clic maintenant pour modifier une adresse
    //     $crawler->filter('#gestion_adresses')->selectLink('Modifier')->link();
    //     $crawler = $client->click($link);
    //     //Il rempli donc ce nouveau formulaire
    //     $form = $crawler->selectButton('Enregistrer')->form();
    //     $form['new_adresse[numero]'] = '14';
    //     $form['new_adresse[rue]'] = 'rue du Peuple';
    //     $form['new_adresse[complement]'] = null;
    //     $form['new_adresse[codePostal]'] = '59500';
    //     $form['new_adresse[ville]'] = 'Douai';
    //     $form['new_adresse[contact][prive]'] = true;
    //     $client->submit($form);
    //     $crawler = $client->followRedirect();
    //     //On vérifi le message de success pour la MODIFICATION de l'adresse
    //     $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Le clien clic maintenant pour supprimer une adresse
    //     $link = $crawler->filter('#gestion_adresses')->selectLink('Supprimer')->link();
    //     $crawler = $client->click($link);
    //     $crawler = $client->followRedirect();
    //     //On vérifi le message de success pour la SUPPRESSION d'une adresse'
    //     $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    // }
    // // AJOUTER, MODIFIER ET SUPPRIMER UN LIEN DU SERVICE
    // public function testServiceGestionLien()
    // {
    //     //Création du client
    //     $client = static::createClient();
    //     //Il démarre sur la page des services
    //     $crawler = $client->request('GET', '/admin/structures/services');
    //     //Il clic sur un lien pour modifier un service
    //     $link = $crawler->selectLink('Modifier')->link();
    //     $crawler = $client->click($link);
    //     //Il clic sur ajouter une adresse
    //     $link = $crawler->selectLink('Ajouter un site Internet')->link();
    //     $crawler = $client->click($link);
    //     //Il rempli le formulaire avec une nouvelle adresse
    //     $form = $crawler->selectButton('Enregistrer')->form();
    //     $form['lien_base[url]'] = 'www.monsite.fr';
    //     $client->submit($form);
    //     $crawler = $client->followRedirect();
    //     //On vérifi le message de success pour le NOUVEAU LIEN
    //     $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Le clien clic maintenant pour modifier le lien
    //     $crawler->filter('#gestion_lien')->selectLink('Modifier')->link();
    //     $crawler = $client->click($link);
    //     //Il rempli donc ce nouveau formulaire
    //     $form = $crawler->selectButton('Enregistrer')->form();
    //     $form['lien_base[url]'] = 'www.monsite2.fr';
    //     $client->submit($form);
    //     $crawler = $client->followRedirect();
    //     //On vérifi le message de success pour la MODIFICATION du lien
    //     $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Le clien clic maintenant pour supprimer le lien
    //     $link = $crawler->filter('#gestion_lien')->selectLink('Supprimer')->link();
    //     $crawler = $client->click($link);
    //     $crawler = $client->followRedirect();
    //     //On vérifi le message de success pour la SUPPRESSION d'un lien'
    //     $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    // }












    // /** Test inutile (juste pour démo de fonctionnalités de test) */
    // public function testAdminStructuresServices()
    // {
    //     $client = static::createClient();
    //     $crawler = $client->request('GET', '/admin/structures/services');
    //     $this->assertSame(1, $crawler->filter('html:contains("Nombre de services")')->count());
    // }
}