<?php

namespace App\Tests\Controller;

use App\Entity\TypeFonction;
use App\Service\OutilsService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OutilsControllerStructureTest extends WebTestCase
{
    
    private $config = array();

    /** Prérequis pour la réalisation des tests ===========================
     * 
     */

    
    public function setUp()
    {
        include 'configTest.php';
    }

    /**
     * Test n°1 - Scenario sur un service
     *
     * @return void
     */
    public function testScenarioService()
    {
        // Scénario - On débute sur la page Services (admin_structures_services)
            $client = static::createClient();
            $crawler = $client->request('GET', '/admin/structures/services');
            //Si le client n'est pas connecté, il est redirigé vers la page login
            if($client->getResponse()->getStatusCode() == 302)
            {
                $crawler = $client->followRedirect();
                //Le client rempli le formulaire de connexion et le valide
                $form = $crawler->selectButton('login_connexion')->form();
                $form['_username'] = 'j.carion';
                $form['_password'] = 'password';
                $client->submit($form);
                $crawler = $client->followRedirect();
            }
            /** Assert 1 - La page s'affiche */
                $this->assertSame(200, $client->getResponse()->getStatusCode());

        //Scénario - On va gérer le premier service disponible
            $link = $crawler->selectLink('Modifier')->link();
            $crawler = $client->click($link);

        //Scénario - On arrive sur la page d'un service (admin_structures_service)
            /** Assert 2 - Affichage d'un service */
                $this->assertSame(200, $client->getResponse()->getStatusCode());

        //Scénario - On va modifier les informations de base de la structure
            $link = $crawler->selectLink('Modifier')->link();
            $crawler = $client->click($link);
            //Le client rempli le formulaire
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['service_base[structure][nom]'] = 'Service de test modifié';
            $form['service_base[structure][presentation]'] = 'Texte de description modifié';
            $form['service_base[structure][local]'] = false;
            //Le client valide le formulaire
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 3 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
        //Scénario - On supprime le service (admin_structures_service_delete)
            $link = $crawler->filter('#delete_service')->selectLink('Supprimer')->link();
            $crawler = $client->click($link);
            $crawler = $client->followRedirect();
            /** Assert 4 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        //Scénario - On crée un nouveau service (admin_structures_service_new)
            $link = $crawler->selectLink('Ajouter un service')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['service_base[structure][nom]'] = 'Service test';
            $form['service_base[structure][presentation]'] = 'Mon texte de description de test';
            $form['service_base[structure][local]'] = true;
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 5 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        //Scénario - On ajoute un site internet (admin_structure_addlien)
            $link = $crawler->filter('#gestion_lien')->selectLink('Ajouter un site Internet')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['lien_base[url]'] = 'http://www.ville-guesnain.fr';
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 6 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
        //Scénario - On modifie un site internet (admin_structure_editlien)
            $link = $crawler->filter('#gestion_lien')->selectLink('Modifier')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['lien_base[url]'] = 'http://www.ville-guesnain2.fr';
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 7 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
        //Scénario - On supprime un site internet (admin_structure_deletelien)
            $link = $crawler->filter('#gestion_lien')->selectLink('Supprimer')->link();
            $crawler = $client->click($link);
            $crawler = $client->followRedirect();
            /** Assert 8 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
        //Scénario - On ajoute une adresse (admin_structure_addcontact)
            $link = $crawler->filter('#gestion_adresses')->selectLink('Ajouter une adresse')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['new_adresse[numero]'] = '13';
            $form['new_adresse[rue]'] = 'rue du Bois';
            $form['new_adresse[complement]'] = 'chemin du lac';
            $form['new_adresse[codePostal]'] = '59287';
            $form['new_adresse[ville]'] = 'Guesnain';
            $form['new_adresse[contact][prive]'] = false;
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 9 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
        //Scénario - On modifie une adresse (admin_structure_editcontact)
            $link = $crawler->filter('#gestion_adresses')->selectLink('Modifier')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['new_adresse[numero]'] = '14';
            $form['new_adresse[rue]'] = 'rue du Peuple';
            $form['new_adresse[complement]'] = null;
            $form['new_adresse[codePostal]'] = '59500';
            $form['new_adresse[ville]'] = 'Douai';
            $form['new_adresse[contact][prive]'] = true;
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 10 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        //Scénario - On supprime une adresse (admin_structure_deletecontact)
            $link = $crawler->filter('#gestion_adresses')->selectLink('Supprimer')->link();
            $crawler = $client->click($link);
            $crawler = $client->followRedirect();
            /** Assert 11 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
       //Scénario - On ajoute un numero de téléphone (admin_structure_addcontact)
            $link = $crawler->selectLink('Ajouter un numéro de téléphone')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['new_telephone[numero]'] = '00 00 00 00 00';
            $form['new_telephone[contact][prive]'] = false;
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 12 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        //Scénario - On modifie un numero de téléphone (admin_structure_editcontact)
            $link = $crawler->filter('#gestion_telephones')->selectLink('Modifier')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['new_telephone[numero]'] = '11 11 11 11 11';
            $form['new_telephone[contact][prive]'] = true;
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 13 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        //Scénario - On supprime un numéro de téléphone (admin_structure_deletecontact)
            $link = $crawler->filter('#gestion_telephones')->selectLink('Supprimer')->link();
            $crawler = $client->click($link);
            $crawler = $client->followRedirect();
            /** Assert 14 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
        //Scénario - On ajoute un mail (admin_structure_addcontact)
            $link = $crawler->selectLink('Ajouter une adresse e-mail')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['new_mail[adresse]'] = 'monmail@toto.fr';
            $form['new_mail[contact][prive]'] = false;
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 15 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
        //Scénario - On modifie un mail (admin_structure_editcontact)
            $link = $crawler->filter('#gestion_mails')->selectLink('Modifier')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['new_mail[adresse]'] = 'newmail@toto.fr';
            $form['new_mail[contact][prive]'] = true;
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 16 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
        //Scénario - On supprime un mail (admin_structure_deletecontact)
            $link = $crawler->filter('#gestion_mails')->selectLink('Supprimer')->link();
            $crawler = $client->click($link);
            $crawler = $client->followRedirect();
            /** Assert 17 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    
        //Scénario - On ajoute une fonction (admin_structure_addfonction)
            $link = $crawler->selectLink('Ajouter une fonction')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['fonction_base[typeFonction]'] = rand($this->config['idTypeFonctionStart'], $this->config['idTypeFonctionStart']+$this->config['nbrIdTypeFonction']-1);
            $form['fonction_base[secteur]'] = 'secteur de test';
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 18 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
        //Scénario - On ajoute un responsable à la fonction (admin_structure_fonction_addhumain)
            $link = $crawler->selectLink('Ajouter un responsable')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['fonction_humain[humain]'] = rand($this->config['idTypeFonctionStart'], $this->config['idHumainStart']+$this->config['nbrIdHumain']-1);
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 19 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        //Scénario - On supprime le responsable de la fonction (admin_structure_fonction_deletehumain)
            $link = $crawler->filter('#fonction_humain')->selectLink('Supprimer')->link();
            $crawler = $client->click($link);
            $crawler = $client->followRedirect();
            /** Assert 20 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    
        //Scénario - On ajoute un mail à la fonction (admin_structure_fonction_addcontact)
            $link = $crawler->filter('#gestion_fonction_mails')->selectLink('Ajouter')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['new_mail[adresse]'] = 'toto@free.fr';
            $form['new_mail[contact][prive]'] = false;
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 21 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
        //Scénario - On ajoute un numéro de téléphone à la fonction
            $link = $crawler->filter('#gestion_fonction_telephones')->selectLink('Ajouter')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['new_telephone[numero]'] = '00 01 02 03 04';
            $form['new_telephone[contact][prive]'] = false;
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 22 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    
        //Scénario - On modifie un mail à la fonction
            $link = $crawler->filter('#gestion_fonction_mails')->selectLink('Modifier')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['new_mail[adresse]'] = 'titi@free.fr';
            $form['new_mail[contact][prive]'] = true;
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 23 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    
        //Scénario - On modifie un numéro de téléphone à la fonction
            $link = $crawler->filter('#gestion_fonction_telephones')->selectLink('Modifier')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['new_telephone[numero]'] = '10 11 12 13 14';
            $form['new_telephone[contact][prive]'] = true;
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 24 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        //Scénario - On supprime un mail à la fonction
            $link = $crawler->filter('#gestion_fonction_mails')->selectLink('Supprimer')->link();
            $crawler = $client->click($link);
            $crawler = $client->followRedirect();
            /** Assert 25 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    
        //Scénario - On supprime un numéro de téléphone à la fonction
            $link = $crawler->filter('#gestion_fonction_telephones')->selectLink('Supprimer')->link();
            $crawler = $client->click($link);
            $crawler = $client->followRedirect();
            /** Assert 26 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        //Scénario - On modifie une fonction (admin_structure_editfonction)
            $link = $crawler->filter('#gestion_structure_fonctions')->selectLink('Modifier')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['fonction_base[typeFonction]'] = rand($this->config['idTypeFonctionStart'], $this->config['idTypeFonctionStart']+$this->config['nbrIdTypeFonction']-1);
            $form['fonction_base[secteur]'] = 'secteur n°2';
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 27 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        //Scénario - On supprime une fonction (admin_structure_deletefonction)
            $link = $crawler->filter('#gestion_structure_fonctions')->selectLink('Supprimer')->link();
            $crawler = $client->click($link);
            $crawler = $client->followRedirect();
            /** Assert 28 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    /**
     * Test n°2 - Scenario sur une association
     *
     * @return void
     */
    public function testScenarioAssociation()
    {
        // Scénario - On débute sur la page Associations (admin_structures_associations)
            $client = static::createClient();
            $crawler = $client->request('GET', '/admin/structures/associations');
            //Si le client n'est pas connecté, il est redirigé vers la page login
            if($client->getResponse()->getStatusCode() == 302)
            {
                $crawler = $client->followRedirect();
                //Le client rempli le formulaire de connexion et le valide
                $form = $crawler->selectButton('login_connexion')->form();
                $form['_username'] = 'j.carion';
                $form['_password'] = 'password';
                $client->submit($form);
                $crawler = $client->followRedirect();
            }
            /** Assert 1 - La page s'affiche */
                $this->assertSame(200, $client->getResponse()->getStatusCode());

        //Scénario - On va gérer la première association disponible
            $link = $crawler->selectLink('Modifier')->link();
            $crawler = $client->click($link);

        //Scénario - On arrive sur la page d'une association (admin_structures_association)
            /** Assert 2 - Affichage d'une association */
                $this->assertSame(200, $client->getResponse()->getStatusCode());

        //Scénario - On va modifier les informations de base de la structure
            $link = $crawler->selectLink('Modifier')->link();
            $crawler = $client->click($link);
            //Le client rempli le formulaire
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['association_base[structure][nom]'] = 'Association de test modifié';
            $form['association_base[sigle]'] = 'ABC';
            $form['association_base[structure][presentation]'] = 'Texte de description modifié';
            $form['association_base[structure][local]'] = false;
            //Le client valide le formulaire
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 3 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
        //Scénario - On supprime l'association (admin_structures_association_delete)
            $link = $crawler->filter('#delete_association')->selectLink('Supprimer')->link();
            $crawler = $client->click($link);
            $crawler = $client->followRedirect();
            /** Assert 4 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

        //Scénario - On crée une nouvelle association (admin_structures_association_new)
            $link = $crawler->selectLink('Ajouter une association')->link();
            $crawler = $client->click($link);
            $form = $crawler->selectButton('Enregistrer')->form();
            $form['association_base[structure][nom]'] = 'Service test';
            $form['association_base[sigle]'] = 'XYZ';
            $form['association_base[structure][presentation]'] = 'Mon texte de description de test';
            $form['association_base[structure][local]'] = true;
            $client->submit($form);
            $crawler = $client->followRedirect();
            /** Assert 5 - Le client reçoit bien un message de confirmation */
                $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Scénario - On ajoute un site internet (admin_structure_addlien)
    //         $link = $crawler->filter('#gestion_lien')->selectLink('Ajouter un site Internet')->link();
    //         $crawler = $client->click($link);
    //         $form = $crawler->selectButton('Enregistrer')->form();
    //         $form['lien_base[url]'] = 'http://www.ville-guesnain.fr';
    //         $client->submit($form);
    //         $crawler = $client->followRedirect();
    //         /** Assert 6 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
    //     //Scénario - On modifie un site internet (admin_structure_editlien)
    //         $link = $crawler->filter('#gestion_lien')->selectLink('Modifier')->link();
    //         $crawler = $client->click($link);
    //         $form = $crawler->selectButton('Enregistrer')->form();
    //         $form['lien_base[url]'] = 'http://www.ville-guesnain2.fr';
    //         $client->submit($form);
    //         $crawler = $client->followRedirect();
    //         /** Assert 7 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
    //     //Scénario - On supprime un site internet (admin_structure_deletelien)
    //         $link = $crawler->filter('#gestion_lien')->selectLink('Supprimer')->link();
    //         $crawler = $client->click($link);
    //         $crawler = $client->followRedirect();
    //         /** Assert 8 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
    //     //Scénario - On ajoute une adresse (admin_structure_addcontact)
    //         $link = $crawler->filter('#gestion_adresses')->selectLink('Ajouter une adresse')->link();
    //         $crawler = $client->click($link);
    //         $form = $crawler->selectButton('Enregistrer')->form();
    //         $form['new_adresse[numero]'] = '13';
    //         $form['new_adresse[rue]'] = 'rue du Bois';
    //         $form['new_adresse[complement]'] = 'chemin du lac';
    //         $form['new_adresse[codePostal]'] = '59287';
    //         $form['new_adresse[ville]'] = 'Guesnain';
    //         $form['new_adresse[contact][prive]'] = false;
    //         $client->submit($form);
    //         $crawler = $client->followRedirect();
    //         /** Assert 9 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
    //     //Scénario - On modifie une adresse (admin_structure_editcontact)
    //         $link = $crawler->filter('#gestion_adresses')->selectLink('Modifier')->link();
    //         $crawler = $client->click($link);
    //         $form = $crawler->selectButton('Enregistrer')->form();
    //         $form['new_adresse[numero]'] = '14';
    //         $form['new_adresse[rue]'] = 'rue du Peuple';
    //         $form['new_adresse[complement]'] = null;
    //         $form['new_adresse[codePostal]'] = '59500';
    //         $form['new_adresse[ville]'] = 'Douai';
    //         $form['new_adresse[contact][prive]'] = true;
    //         $client->submit($form);
    //         $crawler = $client->followRedirect();
    //         /** Assert 10 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Scénario - On supprime une adresse (admin_structure_deletecontact)
    //         $link = $crawler->filter('#gestion_adresses')->selectLink('Supprimer')->link();
    //         $crawler = $client->click($link);
    //         $crawler = $client->followRedirect();
    //         /** Assert 11 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
    //    //Scénario - On ajoute un numero de téléphone (admin_structure_addcontact)
    //         $link = $crawler->selectLink('Ajouter un numéro de téléphone')->link();
    //         $crawler = $client->click($link);
    //         $form = $crawler->selectButton('Enregistrer')->form();
    //         $form['new_telephone[numero]'] = '00 00 00 00 00';
    //         $form['new_telephone[contact][prive]'] = false;
    //         $client->submit($form);
    //         $crawler = $client->followRedirect();
    //         /** Assert 12 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Scénario - On modifie un numero de téléphone (admin_structure_editcontact)
    //         $link = $crawler->filter('#gestion_telephones')->selectLink('Modifier')->link();
    //         $crawler = $client->click($link);
    //         $form = $crawler->selectButton('Enregistrer')->form();
    //         $form['new_telephone[numero]'] = '11 11 11 11 11';
    //         $form['new_telephone[contact][prive]'] = true;
    //         $client->submit($form);
    //         $crawler = $client->followRedirect();
    //         /** Assert 13 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Scénario - On supprime un numéro de téléphone (admin_structure_deletecontact)
    //         $link = $crawler->filter('#gestion_telephones')->selectLink('Supprimer')->link();
    //         $crawler = $client->click($link);
    //         $crawler = $client->followRedirect();
    //         /** Assert 14 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
    //     //Scénario - On ajoute un mail (admin_structure_addcontact)
    //         $link = $crawler->selectLink('Ajouter une adresse e-mail')->link();
    //         $crawler = $client->click($link);
    //         $form = $crawler->selectButton('Enregistrer')->form();
    //         $form['new_mail[adresse]'] = 'monmail@toto.fr';
    //         $form['new_mail[contact][prive]'] = false;
    //         $client->submit($form);
    //         $crawler = $client->followRedirect();
    //         /** Assert 15 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
    //     //Scénario - On modifie un mail (admin_structure_editcontact)
    //         $link = $crawler->filter('#gestion_mails')->selectLink('Modifier')->link();
    //         $crawler = $client->click($link);
    //         $form = $crawler->selectButton('Enregistrer')->form();
    //         $form['new_mail[adresse]'] = 'newmail@toto.fr';
    //         $form['new_mail[contact][prive]'] = true;
    //         $client->submit($form);
    //         $crawler = $client->followRedirect();
    //         /** Assert 16 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
    //     //Scénario - On supprime un mail (admin_structure_deletecontact)
    //         $link = $crawler->filter('#gestion_mails')->selectLink('Supprimer')->link();
    //         $crawler = $client->click($link);
    //         $crawler = $client->followRedirect();
    //         /** Assert 17 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    
    //     //Scénario - On ajoute une fonction (admin_structure_addfonction)
    //         $link = $crawler->selectLink('Ajouter une fonction')->link();
    //         $crawler = $client->click($link);
    //         $form = $crawler->selectButton('Enregistrer')->form();
    //         $form['fonction_base[typeFonction]'] = rand($this->config['idTypeFonctionStart'], $this->config['idTypeFonctionStart']+$this->config['nbrIdTypeFonction']-1);
    //         $form['fonction_base[secteur]'] = 'secteur de test';
    //         $client->submit($form);
    //         $crawler = $client->followRedirect();
    //         /** Assert 18 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
    //     //Scénario - On ajoute un responsable à la fonction (admin_structure_fonction_addhumain)
    //         $link = $crawler->selectLink('Ajouter un responsable')->link();
    //         $crawler = $client->click($link);
    //         $form = $crawler->selectButton('Enregistrer')->form();
    //         $form['fonction_humain[humain]'] = rand($this->config['idTypeFonctionStart'], $this->config['idHumainStart']+$this->config['nbrIdHumain']-1);
    //         $client->submit($form);
    //         $crawler = $client->followRedirect();
    //         /** Assert 19 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Scénario - On supprime le responsable de la fonction (admin_structure_fonction_deletehumain)
    //         $link = $crawler->filter('#fonction_humain')->selectLink('Supprimer')->link();
    //         $crawler = $client->click($link);
    //         $crawler = $client->followRedirect();
    //         /** Assert 20 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    
    //     //Scénario - On ajoute un mail à la fonction (admin_structure_fonction_addcontact)
    //         $link = $crawler->filter('#gestion_fonction_mails')->selectLink('Ajouter')->link();
    //         $crawler = $client->click($link);
    //         $form = $crawler->selectButton('Enregistrer')->form();
    //         $form['new_mail[adresse]'] = 'toto@free.fr';
    //         $form['new_mail[contact][prive]'] = false;
    //         $client->submit($form);
    //         $crawler = $client->followRedirect();
    //         /** Assert 21 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        
    //     //Scénario - On ajoute un numéro de téléphone à la fonction
    //         $link = $crawler->filter('#gestion_fonction_telephones')->selectLink('Ajouter')->link();
    //         $crawler = $client->click($link);
    //         $form = $crawler->selectButton('Enregistrer')->form();
    //         $form['new_telephone[numero]'] = '00 01 02 03 04';
    //         $form['new_telephone[contact][prive]'] = false;
    //         $client->submit($form);
    //         $crawler = $client->followRedirect();
    //         /** Assert 22 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    
    //     //Scénario - On modifie un mail à la fonction
    //         $link = $crawler->filter('#gestion_fonction_mails')->selectLink('Modifier')->link();
    //         $crawler = $client->click($link);
    //         $form = $crawler->selectButton('Enregistrer')->form();
    //         $form['new_mail[adresse]'] = 'titi@free.fr';
    //         $form['new_mail[contact][prive]'] = true;
    //         $client->submit($form);
    //         $crawler = $client->followRedirect();
    //         /** Assert 23 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    
    //     //Scénario - On modifie un numéro de téléphone à la fonction
    //         $link = $crawler->filter('#gestion_fonction_telephones')->selectLink('Modifier')->link();
    //         $crawler = $client->click($link);
    //         $form = $crawler->selectButton('Enregistrer')->form();
    //         $form['new_telephone[numero]'] = '10 11 12 13 14';
    //         $form['new_telephone[contact][prive]'] = true;
    //         $client->submit($form);
    //         $crawler = $client->followRedirect();
    //         /** Assert 24 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Scénario - On supprime un mail à la fonction
    //         $link = $crawler->filter('#gestion_fonction_mails')->selectLink('Supprimer')->link();
    //         $crawler = $client->click($link);
    //         $crawler = $client->followRedirect();
    //         /** Assert 25 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    
    //     //Scénario - On supprime un numéro de téléphone à la fonction
    //         $link = $crawler->filter('#gestion_fonction_telephones')->selectLink('Supprimer')->link();
    //         $crawler = $client->click($link);
    //         $crawler = $client->followRedirect();
    //         /** Assert 26 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Scénario - On modifie une fonction (admin_structure_editfonction)
    //         $link = $crawler->filter('#gestion_structure_fonctions')->selectLink('Modifier')->link();
    //         $crawler = $client->click($link);
    //         $form = $crawler->selectButton('Enregistrer')->form();
    //         $form['fonction_base[typeFonction]'] = rand($this->config['idTypeFonctionStart'], $this->config['idTypeFonctionStart']+$this->config['nbrIdTypeFonction']-1);
    //         $form['fonction_base[secteur]'] = 'secteur n°2';
    //         $client->submit($form);
    //         $crawler = $client->followRedirect();
    //         /** Assert 27 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    //     //Scénario - On supprime une fonction (admin_structure_deletefonction)
    //         $link = $crawler->filter('#gestion_structure_fonctions')->selectLink('Supprimer')->link();
    //         $crawler = $client->click($link);
    //         $crawler = $client->followRedirect();
    //         /** Assert 28 - Le client reçoit bien un message de confirmation */
    //             $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }



    



































    // /** Test inutile (juste pour démo de fonctionnalités de test) */
    // public function testAdminStructuresServices()
    // {
    //     $client = static::createClient();
    //     $crawler = $client->request('GET', '/admin/structures/services');
    //     $this->assertSame(1, $crawler->filter('html:contains("Nombre de services")')->count());
    // }
}