<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211004135909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, mail VARCHAR(255) NOT NULL, rue VARCHAR(255) DEFAULT NULL, cp VARCHAR(5) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, pays VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE particulier (id INT NOT NULL, telephone VARCHAR(20) DEFAULT NULL, civilite VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profesionnel (id INT NOT NULL, raison_social VARCHAR(255) NOT NULL, contact VARCHAR(255) DEFAULT NULL, mail_2 VARCHAR(255) DEFAULT NULL, tel_1 VARCHAR(20) DEFAULT NULL, tel_2 VARCHAR(20) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, quota DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE particulier ADD CONSTRAINT FK_6CC4D4F3BF396750 FOREIGN KEY (id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profesionnel ADD CONSTRAINT FK_42680596BF396750 FOREIGN KEY (id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ligne CHANGE date_insert date_insert DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE particulier DROP FOREIGN KEY FK_6CC4D4F3BF396750');
        $this->addSql('ALTER TABLE profesionnel DROP FOREIGN KEY FK_42680596BF396750');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE particulier');
        $this->addSql('DROP TABLE profesionnel');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE ligne CHANGE date_insert date_insert DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
