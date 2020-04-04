<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200402160934 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, contact_id INT NOT NULL, numero INT DEFAULT NULL, rue VARCHAR(255) DEFAULT NULL, complement VARCHAR(255) DEFAULT NULL, code_postal INT DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C35F0816E7A1254A (contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE association (id INT AUTO_INCREMENT NOT NULL, structure_id INT NOT NULL, type_id INT NOT NULL, UNIQUE INDEX UNIQ_FD8521CC2534008B (structure_id), INDEX IDX_FD8521CCC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, prive TINYINT(1) NOT NULL, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, structure_id INT NOT NULL, UNIQUE INDEX UNIQ_D19FA602534008B (structure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise_type_entreprise (entreprise_id INT NOT NULL, type_entreprise_id INT NOT NULL, INDEX IDX_F49CB27DA4AEAFEA (entreprise_id), INDEX IDX_F49CB27DD44318DF (type_entreprise_id), PRIMARY KEY(entreprise_id, type_entreprise_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fonction (id INT AUTO_INCREMENT NOT NULL, structure_id INT NOT NULL, titre VARCHAR(255) NOT NULL, titre_feminin VARCHAR(255) NOT NULL, secteur VARCHAR(255) DEFAULT NULL, INDEX IDX_900D5BD2534008B (structure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fonction_contact (fonction_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_81328AA057889920 (fonction_id), INDEX IDX_81328AA0E7A1254A (contact_id), PRIMARY KEY(fonction_id, contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, association_id INT NOT NULL, type_id INT NOT NULL, nom VARCHAR(255) NOT NULL, descriptif LONGTEXT DEFAULT NULL, INDEX IDX_4B98C21EFB9C8A5 (association_id), INDEX IDX_4B98C21C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_contact (groupe_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_8A0501767A45358C (groupe_id), INDEX IDX_8A050176E7A1254A (contact_id), PRIMARY KEY(groupe_id, contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE horaire (id INT AUTO_INCREMENT NOT NULL, lieu_id INT DEFAULT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, ferie TINYINT(1) NOT NULL, vacances TINYINT(1) NOT NULL, INDEX IDX_BBC83DB66AB213CC (lieu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE horaire_jour (horaire_id INT NOT NULL, jour_id INT NOT NULL, INDEX IDX_8279C49D58C54515 (horaire_id), INDEX IDX_8279C49D220C6AD0 (jour_id), PRIMARY KEY(horaire_id, jour_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE humain (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) DEFAULT NULL, date_naissance DATE DEFAULT NULL, sexe TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE humain_contact (humain_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_A18AEF11A10D012 (humain_id), INDEX IDX_A18AEF1E7A1254A (contact_id), PRIMARY KEY(humain_id, contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jour (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieu (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, descriptif LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieu_contact (lieu_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_ADE6DED96AB213CC (lieu_id), INDEX IDX_ADE6DED9E7A1254A (contact_id), PRIMARY KEY(lieu_id, contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mail (id INT AUTO_INCREMENT NOT NULL, contact_id INT NOT NULL, adresse VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5126AC48E7A1254A (contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE periode (id INT AUTO_INCREMENT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, titre VARCHAR(255) NOT NULL, ferie TINYINT(1) NOT NULL, vacances TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsable (id INT AUTO_INCREMENT NOT NULL, fonction_id INT NOT NULL, humain_id INT NOT NULL, INDEX IDX_52520D0757889920 (fonction_id), INDEX IDX_52520D071A10D012 (humain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, structure_id INT NOT NULL, UNIQUE INDEX UNIQ_E19D9AD22534008B (structure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, presentation LONGTEXT DEFAULT NULL, local TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure_contact (structure_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_97C096092534008B (structure_id), INDEX IDX_97C09609E7A1254A (contact_id), PRIMARY KEY(structure_id, contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE telephone (id INT AUTO_INCREMENT NOT NULL, contact_id INT NOT NULL, numero VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_450FF010E7A1254A (contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_association (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_entreprise (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CC2534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCC54C8C93 FOREIGN KEY (type_id) REFERENCES type_association (id)');
        $this->addSql('ALTER TABLE entreprise ADD CONSTRAINT FK_D19FA602534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE entreprise_type_entreprise ADD CONSTRAINT FK_F49CB27DA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entreprise_type_entreprise ADD CONSTRAINT FK_F49CB27DD44318DF FOREIGN KEY (type_entreprise_id) REFERENCES type_entreprise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fonction ADD CONSTRAINT FK_900D5BD2534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE fonction_contact ADD CONSTRAINT FK_81328AA057889920 FOREIGN KEY (fonction_id) REFERENCES fonction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fonction_contact ADD CONSTRAINT FK_81328AA0E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C21EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C21C54C8C93 FOREIGN KEY (type_id) REFERENCES type_association (id)');
        $this->addSql('ALTER TABLE groupe_contact ADD CONSTRAINT FK_8A0501767A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_contact ADD CONSTRAINT FK_8A050176E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE horaire ADD CONSTRAINT FK_BBC83DB66AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)');
        $this->addSql('ALTER TABLE horaire_jour ADD CONSTRAINT FK_8279C49D58C54515 FOREIGN KEY (horaire_id) REFERENCES horaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE horaire_jour ADD CONSTRAINT FK_8279C49D220C6AD0 FOREIGN KEY (jour_id) REFERENCES jour (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE humain_contact ADD CONSTRAINT FK_A18AEF11A10D012 FOREIGN KEY (humain_id) REFERENCES humain (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE humain_contact ADD CONSTRAINT FK_A18AEF1E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lieu_contact ADD CONSTRAINT FK_ADE6DED96AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lieu_contact ADD CONSTRAINT FK_ADE6DED9E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mail ADD CONSTRAINT FK_5126AC48E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE responsable ADD CONSTRAINT FK_52520D0757889920 FOREIGN KEY (fonction_id) REFERENCES fonction (id)');
        $this->addSql('ALTER TABLE responsable ADD CONSTRAINT FK_52520D071A10D012 FOREIGN KEY (humain_id) REFERENCES humain (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD22534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE structure_contact ADD CONSTRAINT FK_97C096092534008B FOREIGN KEY (structure_id) REFERENCES structure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE structure_contact ADD CONSTRAINT FK_97C09609E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE telephone ADD CONSTRAINT FK_450FF010E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C21EFB9C8A5');
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F0816E7A1254A');
        $this->addSql('ALTER TABLE fonction_contact DROP FOREIGN KEY FK_81328AA0E7A1254A');
        $this->addSql('ALTER TABLE groupe_contact DROP FOREIGN KEY FK_8A050176E7A1254A');
        $this->addSql('ALTER TABLE humain_contact DROP FOREIGN KEY FK_A18AEF1E7A1254A');
        $this->addSql('ALTER TABLE lieu_contact DROP FOREIGN KEY FK_ADE6DED9E7A1254A');
        $this->addSql('ALTER TABLE mail DROP FOREIGN KEY FK_5126AC48E7A1254A');
        $this->addSql('ALTER TABLE structure_contact DROP FOREIGN KEY FK_97C09609E7A1254A');
        $this->addSql('ALTER TABLE telephone DROP FOREIGN KEY FK_450FF010E7A1254A');
        $this->addSql('ALTER TABLE entreprise_type_entreprise DROP FOREIGN KEY FK_F49CB27DA4AEAFEA');
        $this->addSql('ALTER TABLE fonction_contact DROP FOREIGN KEY FK_81328AA057889920');
        $this->addSql('ALTER TABLE responsable DROP FOREIGN KEY FK_52520D0757889920');
        $this->addSql('ALTER TABLE groupe_contact DROP FOREIGN KEY FK_8A0501767A45358C');
        $this->addSql('ALTER TABLE horaire_jour DROP FOREIGN KEY FK_8279C49D58C54515');
        $this->addSql('ALTER TABLE humain_contact DROP FOREIGN KEY FK_A18AEF11A10D012');
        $this->addSql('ALTER TABLE responsable DROP FOREIGN KEY FK_52520D071A10D012');
        $this->addSql('ALTER TABLE horaire_jour DROP FOREIGN KEY FK_8279C49D220C6AD0');
        $this->addSql('ALTER TABLE horaire DROP FOREIGN KEY FK_BBC83DB66AB213CC');
        $this->addSql('ALTER TABLE lieu_contact DROP FOREIGN KEY FK_ADE6DED96AB213CC');
        $this->addSql('ALTER TABLE association DROP FOREIGN KEY FK_FD8521CC2534008B');
        $this->addSql('ALTER TABLE entreprise DROP FOREIGN KEY FK_D19FA602534008B');
        $this->addSql('ALTER TABLE fonction DROP FOREIGN KEY FK_900D5BD2534008B');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD22534008B');
        $this->addSql('ALTER TABLE structure_contact DROP FOREIGN KEY FK_97C096092534008B');
        $this->addSql('ALTER TABLE association DROP FOREIGN KEY FK_FD8521CCC54C8C93');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C21C54C8C93');
        $this->addSql('ALTER TABLE entreprise_type_entreprise DROP FOREIGN KEY FK_F49CB27DD44318DF');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE association');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE entreprise_type_entreprise');
        $this->addSql('DROP TABLE fonction');
        $this->addSql('DROP TABLE fonction_contact');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupe_contact');
        $this->addSql('DROP TABLE horaire');
        $this->addSql('DROP TABLE horaire_jour');
        $this->addSql('DROP TABLE humain');
        $this->addSql('DROP TABLE humain_contact');
        $this->addSql('DROP TABLE jour');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('DROP TABLE lieu_contact');
        $this->addSql('DROP TABLE mail');
        $this->addSql('DROP TABLE periode');
        $this->addSql('DROP TABLE responsable');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE structure');
        $this->addSql('DROP TABLE structure_contact');
        $this->addSql('DROP TABLE telephone');
        $this->addSql('DROP TABLE type_association');
        $this->addSql('DROP TABLE type_entreprise');
    }
}
