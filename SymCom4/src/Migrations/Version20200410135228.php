<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200410135228 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE association_type_association (association_id INT NOT NULL, type_association_id INT NOT NULL, INDEX IDX_773ECD84EFB9C8A5 (association_id), INDEX IDX_773ECD84B97AE131 (type_association_id), PRIMARY KEY(association_id, type_association_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE association_type_association ADD CONSTRAINT FK_773ECD84EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE association_type_association ADD CONSTRAINT FK_773ECD84B97AE131 FOREIGN KEY (type_association_id) REFERENCES type_association (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adresse CHANGE numero numero INT DEFAULT NULL, CHANGE rue rue VARCHAR(255) DEFAULT NULL, CHANGE complement complement VARCHAR(255) DEFAULT NULL, CHANGE code_postal code_postal INT DEFAULT NULL, CHANGE ville ville VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE association DROP FOREIGN KEY FK_FD8521CCC54C8C93');
        $this->addSql('DROP INDEX IDX_FD8521CCC54C8C93 ON association');
        $this->addSql('ALTER TABLE association DROP type_id, CHANGE sigle sigle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dossier CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fonction CHANGE secteur secteur VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE horaire CHANGE lieu_id lieu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE humain CHANGE photo_id photo_id INT DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lien CHANGE page_id page_id INT DEFAULT NULL, CHANGE font_awesome font_awesome VARCHAR(255) DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT NULL, CHANGE color_boot color_boot VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE media CHANGE titre titre VARCHAR(255) DEFAULT NULL, CHANGE date date DATE DEFAULT NULL, CHANGE public public TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE page CHANGE titre titre VARCHAR(255) DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE structure CHANGE lien_id lien_id INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_association ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_association ADD CONSTRAINT FK_B69CF65D727ACA70 FOREIGN KEY (parent_id) REFERENCES type_association (id)');
        $this->addSql('CREATE INDEX IDX_B69CF65D727ACA70 ON type_association (parent_id)');
        $this->addSql('ALTER TABLE type_entreprise ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_entreprise ADD CONSTRAINT FK_101A2C45727ACA70 FOREIGN KEY (parent_id) REFERENCES type_entreprise (id)');
        $this->addSql('CREATE INDEX IDX_101A2C45727ACA70 ON type_entreprise (parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE association_type_association');
        $this->addSql('ALTER TABLE adresse CHANGE numero numero INT DEFAULT NULL, CHANGE rue rue VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE complement complement VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE code_postal code_postal INT DEFAULT NULL, CHANGE ville ville VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE association ADD type_id INT NOT NULL, CHANGE sigle sigle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCC54C8C93 FOREIGN KEY (type_id) REFERENCES type_association (id)');
        $this->addSql('CREATE INDEX IDX_FD8521CCC54C8C93 ON association (type_id)');
        $this->addSql('ALTER TABLE dossier CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fonction CHANGE secteur secteur VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE horaire CHANGE lieu_id lieu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE humain CHANGE photo_id photo_id INT DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE lien CHANGE page_id page_id INT DEFAULT NULL, CHANGE font_awesome font_awesome VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE url url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE color_boot color_boot VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE media CHANGE titre titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date date DATE DEFAULT \'NULL\', CHANGE public public TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE page CHANGE titre titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE url url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE structure CHANGE lien_id lien_id INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_association DROP FOREIGN KEY FK_B69CF65D727ACA70');
        $this->addSql('DROP INDEX IDX_B69CF65D727ACA70 ON type_association');
        $this->addSql('ALTER TABLE type_association DROP parent_id');
        $this->addSql('ALTER TABLE type_entreprise DROP FOREIGN KEY FK_101A2C45727ACA70');
        $this->addSql('DROP INDEX IDX_101A2C45727ACA70 ON type_entreprise');
        $this->addSql('ALTER TABLE type_entreprise DROP parent_id');
    }
}
