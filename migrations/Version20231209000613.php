<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231209000613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE schedule_expense CHANGE start_date start_date DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CD79066886');
        $this->addSql('ALTER TABLE folder CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB83F6203804');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB83BCF5E72D');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB83A76ED395');
        $this->addSql('DROP INDEX IDX_57F0DB83A76ED395 ON ligne');
        $this->addSql('ALTER TABLE particulier DROP FOREIGN KEY FK_6CC4D4F3BF396750');
        $this->addSql('ALTER TABLE profesionnel DROP FOREIGN KEY FK_42680596BF396750');
        $this->addSql('ALTER TABLE schedule_expense CHANGE start_date start_date DATE DEFAULT NULL');
    }
}
