<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200404150707 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE adresse CHANGE numero numero INT DEFAULT NULL, CHANGE rue rue VARCHAR(255) DEFAULT NULL, CHANGE complement complement VARCHAR(255) DEFAULT NULL, CHANGE code_postal code_postal INT DEFAULT NULL, CHANGE ville ville VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE association CHANGE sigle sigle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dossier CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fonction CHANGE secteur secteur VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE horaire CHANGE lieu_id lieu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE humain CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE date_naissance date_naissance DATE DEFAULT NULL, CHANGE sexe sexe TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE lien CHANGE page_id page_id INT DEFAULT NULL, CHANGE font_awesome font_awesome VARCHAR(255) DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT NULL, CHANGE color_boot color_boot VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE media CHANGE titre titre VARCHAR(255) DEFAULT NULL, CHANGE date date DATE DEFAULT NULL, CHANGE public public TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EAF6BD1646');
        $this->addSql('DROP INDEX UNIQ_6F0137EAF6BD1646 ON structure');
        $this->addSql('ALTER TABLE structure ADD lien_id INT DEFAULT NULL, DROP site_id, CHANGE illustration_id illustration_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EAEDAAC352 FOREIGN KEY (lien_id) REFERENCES lien (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6F0137EAEDAAC352 ON structure (lien_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE adresse CHANGE numero numero INT DEFAULT NULL, CHANGE rue rue VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE complement complement VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE code_postal code_postal INT DEFAULT NULL, CHANGE ville ville VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE association CHANGE sigle sigle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE dossier CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fonction CHANGE secteur secteur VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE horaire CHANGE lieu_id lieu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE humain CHANGE prenom prenom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_naissance date_naissance DATE DEFAULT \'NULL\', CHANGE sexe sexe TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE lien CHANGE page_id page_id INT DEFAULT NULL, CHANGE font_awesome font_awesome VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE url url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE color_boot color_boot VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE media CHANGE titre titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date date DATE DEFAULT \'NULL\', CHANGE public public TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EAEDAAC352');
        $this->addSql('DROP INDEX UNIQ_6F0137EAEDAAC352 ON structure');
        $this->addSql('ALTER TABLE structure ADD site_id INT DEFAULT NULL, DROP lien_id, CHANGE illustration_id illustration_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EAF6BD1646 FOREIGN KEY (site_id) REFERENCES lien (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6F0137EAF6BD1646 ON structure (site_id)');
    }
}
