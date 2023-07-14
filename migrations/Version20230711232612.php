<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230711232612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
      
        $this->addSql('ALTER TABLE ligne ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne ADD CONSTRAINT FK_57F0DB83A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_57F0DB83A76ED395 ON ligne (user_id)');
        $this->addSql('ALTER TABLE statut CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CD79066886');
        $this->addSql('ALTER TABLE folder CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB83A76ED395');
        $this->addSql('DROP INDEX IDX_57F0DB83A76ED395 ON ligne');
        $this->addSql('ALTER TABLE ligne DROP user_id');
        $this->addSql('ALTER TABLE statut CHANGE id id INT NOT NULL');
    }
}
