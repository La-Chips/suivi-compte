<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230714183000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        // drop this fk FK_E3443E17F6203804
        $this->addSql('ALTER TABLE etapes DROP FOREIGN KEY FK_E3443E17F6203804');

        $this->addSql('ALTER TABLE statut CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE filter DROP amount');
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CD79066886');
        $this->addSql('ALTER TABLE folder CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE statut CHANGE id id INT NOT NULL');
    }
}
