<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200505092052 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commande_drive_mediatheque DROP FOREIGN KEY FK_51E763D27D0729A9');
        $this->addSql('ALTER TABLE commande_drive_mediatheque DROP FOREIGN KEY FK_51E763D21C23654F');
        $this->addSql('ALTER TABLE membre_mediatheque_membre_mediatheque DROP FOREIGN KEY FK_E26FC9E5AB86E45D');
        $this->addSql('ALTER TABLE membre_mediatheque_membre_mediatheque DROP FOREIGN KEY FK_E26FC9E5B263B4D2');
        $this->addSql('CREATE TABLE mediatheque_drive_commande (id INT AUTO_INCREMENT NOT NULL, membre_id INT NOT NULL, creneau_id INT DEFAULT NULL, nbr_livre_choisi INT NOT NULL, nbr_cd_choisi INT NOT NULL, nbr_dvd_choisi INT NOT NULL, nbr_livre_surprise INT NOT NULL, nbr_cd_surprise INT NOT NULL, nbr_dvd_surprise INT NOT NULL, etat VARCHAR(255) NOT NULL, INDEX IDX_DEFA8FE76A99F74A (membre_id), INDEX IDX_DEFA8FE77D0729A9 (creneau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mediatheque_drive_creneau (id INT AUTO_INCREMENT NOT NULL, debut DATETIME NOT NULL, fin DATETIME NOT NULL, ouvert TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mediatheque_membre (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, num_carte VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E011BD0EFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mediatheque_membre_mediatheque_membre (mediatheque_membre_source INT NOT NULL, mediatheque_membre_target INT NOT NULL, INDEX IDX_FC41359DB2D1C0C (mediatheque_membre_source), INDEX IDX_FC41359C2C84C83 (mediatheque_membre_target), PRIMARY KEY(mediatheque_membre_source, mediatheque_membre_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mediatheque_drive_commande ADD CONSTRAINT FK_DEFA8FE76A99F74A FOREIGN KEY (membre_id) REFERENCES mediatheque_membre (id)');
        $this->addSql('ALTER TABLE mediatheque_drive_commande ADD CONSTRAINT FK_DEFA8FE77D0729A9 FOREIGN KEY (creneau_id) REFERENCES mediatheque_drive_creneau (id)');
        $this->addSql('ALTER TABLE mediatheque_membre ADD CONSTRAINT FK_E011BD0EFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE mediatheque_membre_mediatheque_membre ADD CONSTRAINT FK_FC41359DB2D1C0C FOREIGN KEY (mediatheque_membre_source) REFERENCES mediatheque_membre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mediatheque_membre_mediatheque_membre ADD CONSTRAINT FK_FC41359C2C84C83 FOREIGN KEY (mediatheque_membre_target) REFERENCES mediatheque_membre (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE commande_drive_mediatheque');
        $this->addSql('DROP TABLE creneau_drive_mediatheque');
        $this->addSql('DROP TABLE membre_mediatheque');
        $this->addSql('DROP TABLE membre_mediatheque_membre_mediatheque');
        $this->addSql('ALTER TABLE adresse CHANGE numero numero INT DEFAULT NULL, CHANGE rue rue VARCHAR(255) DEFAULT NULL, CHANGE complement complement VARCHAR(255) DEFAULT NULL, CHANGE code_postal code_postal INT DEFAULT NULL, CHANGE ville ville VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE association CHANGE sigle sigle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dossier CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fonction CHANGE humain_id humain_id INT DEFAULT NULL, CHANGE secteur secteur VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE horaire CHANGE lieu_id lieu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE humain CHANGE photo_id photo_id INT DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE date_naissance date_naissance DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE lien CHANGE page_id page_id INT DEFAULT NULL, CHANGE font_awesome font_awesome VARCHAR(255) DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT NULL, CHANGE color_boot color_boot VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE media CHANGE titre titre VARCHAR(255) DEFAULT NULL, CHANGE date date DATE DEFAULT NULL, CHANGE public public TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE page CHANGE titre titre VARCHAR(255) DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE structure CHANGE lien_id lien_id INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_association CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_entreprise CHANGE parent_id parent_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mediatheque_drive_commande DROP FOREIGN KEY FK_DEFA8FE77D0729A9');
        $this->addSql('ALTER TABLE mediatheque_drive_commande DROP FOREIGN KEY FK_DEFA8FE76A99F74A');
        $this->addSql('ALTER TABLE mediatheque_membre_mediatheque_membre DROP FOREIGN KEY FK_FC41359DB2D1C0C');
        $this->addSql('ALTER TABLE mediatheque_membre_mediatheque_membre DROP FOREIGN KEY FK_FC41359C2C84C83');
        $this->addSql('CREATE TABLE commande_drive_mediatheque (id INT AUTO_INCREMENT NOT NULL, membre_mediatheque_id INT NOT NULL, creneau_id INT DEFAULT NULL, nbr_livre_choisi INT NOT NULL, nbr_cd_choisi INT NOT NULL, nbr_dvd_choisi INT NOT NULL, nbr_livre_surprise INT NOT NULL, nbr_cd_surprise INT NOT NULL, nbr_dvd_surprise INT NOT NULL, etat VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_51E763D21C23654F (membre_mediatheque_id), INDEX IDX_51E763D27D0729A9 (creneau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE creneau_drive_mediatheque (id INT AUTO_INCREMENT NOT NULL, debut DATETIME NOT NULL, fin DATETIME NOT NULL, ouvert TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE membre_mediatheque (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, num_carte VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_387FC50BFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE membre_mediatheque_membre_mediatheque (membre_mediatheque_source INT NOT NULL, membre_mediatheque_target INT NOT NULL, INDEX IDX_E26FC9E5B263B4D2 (membre_mediatheque_target), INDEX IDX_E26FC9E5AB86E45D (membre_mediatheque_source), PRIMARY KEY(membre_mediatheque_source, membre_mediatheque_target)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande_drive_mediatheque ADD CONSTRAINT FK_51E763D21C23654F FOREIGN KEY (membre_mediatheque_id) REFERENCES membre_mediatheque (id)');
        $this->addSql('ALTER TABLE commande_drive_mediatheque ADD CONSTRAINT FK_51E763D27D0729A9 FOREIGN KEY (creneau_id) REFERENCES creneau_drive_mediatheque (id)');
        $this->addSql('ALTER TABLE membre_mediatheque ADD CONSTRAINT FK_387FC50BFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE membre_mediatheque_membre_mediatheque ADD CONSTRAINT FK_E26FC9E5AB86E45D FOREIGN KEY (membre_mediatheque_source) REFERENCES membre_mediatheque (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE membre_mediatheque_membre_mediatheque ADD CONSTRAINT FK_E26FC9E5B263B4D2 FOREIGN KEY (membre_mediatheque_target) REFERENCES membre_mediatheque (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE mediatheque_drive_commande');
        $this->addSql('DROP TABLE mediatheque_drive_creneau');
        $this->addSql('DROP TABLE mediatheque_membre');
        $this->addSql('DROP TABLE mediatheque_membre_mediatheque_membre');
        $this->addSql('ALTER TABLE adresse CHANGE numero numero INT DEFAULT NULL, CHANGE rue rue VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE complement complement VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE code_postal code_postal INT DEFAULT NULL, CHANGE ville ville VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE association CHANGE sigle sigle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE dossier CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fonction CHANGE humain_id humain_id INT DEFAULT NULL, CHANGE secteur secteur VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE horaire CHANGE lieu_id lieu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE humain CHANGE photo_id photo_id INT DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_naissance date_naissance DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE lien CHANGE page_id page_id INT DEFAULT NULL, CHANGE font_awesome font_awesome VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE url url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE color_boot color_boot VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE media CHANGE titre titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date date DATE DEFAULT \'NULL\', CHANGE public public TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE page CHANGE titre titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE url url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE structure CHANGE lien_id lien_id INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_association CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_entreprise CHANGE parent_id parent_id INT DEFAULT NULL');
    }
}
