<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200407134047 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE type_fonction (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, titre_feminin VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresse CHANGE numero numero INT DEFAULT NULL, CHANGE rue rue VARCHAR(255) DEFAULT NULL, CHANGE complement complement VARCHAR(255) DEFAULT NULL, CHANGE code_postal code_postal INT DEFAULT NULL, CHANGE ville ville VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE association CHANGE sigle sigle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dossier CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fonction ADD type_fonction_id INT NOT NULL, DROP titre, DROP titre_feminin, CHANGE secteur secteur VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fonction ADD CONSTRAINT FK_900D5BD1C914EB1 FOREIGN KEY (type_fonction_id) REFERENCES type_fonction (id)');
        $this->addSql('CREATE INDEX IDX_900D5BD1C914EB1 ON fonction (type_fonction_id)');
        $this->addSql('ALTER TABLE horaire CHANGE lieu_id lieu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE humain CHANGE photo_id photo_id INT DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lien CHANGE page_id page_id INT DEFAULT NULL, CHANGE font_awesome font_awesome VARCHAR(255) DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT NULL, CHANGE color_boot color_boot VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE media CHANGE titre titre VARCHAR(255) DEFAULT NULL, CHANGE date date DATE DEFAULT NULL, CHANGE public public TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE structure CHANGE lien_id lien_id INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fonction DROP FOREIGN KEY FK_900D5BD1C914EB1');
        $this->addSql('DROP TABLE type_fonction');
        $this->addSql('ALTER TABLE adresse CHANGE numero numero INT DEFAULT NULL, CHANGE rue rue VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE complement complement VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE code_postal code_postal INT DEFAULT NULL, CHANGE ville ville VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE association CHANGE sigle sigle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE dossier CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_900D5BD1C914EB1 ON fonction');
        $this->addSql('ALTER TABLE fonction ADD titre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD titre_feminin VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP type_fonction_id, CHANGE secteur secteur VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE horaire CHANGE lieu_id lieu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE humain CHANGE photo_id photo_id INT DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE lien CHANGE page_id page_id INT DEFAULT NULL, CHANGE font_awesome font_awesome VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE url url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE color_boot color_boot VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE media CHANGE titre titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date date DATE DEFAULT \'NULL\', CHANGE public public TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE structure CHANGE lien_id lien_id INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL');
    }
}
