<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200414090630 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE responsable');
        $this->addSql('ALTER TABLE adresse CHANGE numero numero INT DEFAULT NULL, CHANGE rue rue VARCHAR(255) DEFAULT NULL, CHANGE complement complement VARCHAR(255) DEFAULT NULL, CHANGE code_postal code_postal INT DEFAULT NULL, CHANGE ville ville VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE association CHANGE sigle sigle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dossier CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fonction ADD humain_id INT DEFAULT NULL, CHANGE secteur secteur VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fonction ADD CONSTRAINT FK_900D5BD1A10D012 FOREIGN KEY (humain_id) REFERENCES humain (id)');
        $this->addSql('CREATE INDEX IDX_900D5BD1A10D012 ON fonction (humain_id)');
        $this->addSql('ALTER TABLE horaire CHANGE lieu_id lieu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE humain CHANGE photo_id photo_id INT DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL');
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

        $this->addSql('CREATE TABLE responsable (id INT AUTO_INCREMENT NOT NULL, fonction_id INT NOT NULL, humain_id INT NOT NULL, INDEX IDX_52520D0757889920 (fonction_id), INDEX IDX_52520D071A10D012 (humain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE responsable ADD CONSTRAINT FK_52520D071A10D012 FOREIGN KEY (humain_id) REFERENCES humain (id)');
        $this->addSql('ALTER TABLE responsable ADD CONSTRAINT FK_52520D0757889920 FOREIGN KEY (fonction_id) REFERENCES fonction (id)');
        $this->addSql('ALTER TABLE adresse CHANGE numero numero INT DEFAULT NULL, CHANGE rue rue VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE complement complement VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE code_postal code_postal INT DEFAULT NULL, CHANGE ville ville VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE association CHANGE sigle sigle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE dossier CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fonction DROP FOREIGN KEY FK_900D5BD1A10D012');
        $this->addSql('DROP INDEX IDX_900D5BD1A10D012 ON fonction');
        $this->addSql('ALTER TABLE fonction DROP humain_id, CHANGE secteur secteur VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE horaire CHANGE lieu_id lieu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE humain CHANGE photo_id photo_id INT DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE lien CHANGE page_id page_id INT DEFAULT NULL, CHANGE font_awesome font_awesome VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE url url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE color_boot color_boot VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE media CHANGE titre titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date date DATE DEFAULT \'NULL\', CHANGE public public TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE page CHANGE titre titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE url url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE structure CHANGE lien_id lien_id INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_association CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_entreprise CHANGE parent_id parent_id INT DEFAULT NULL');
    }
}
