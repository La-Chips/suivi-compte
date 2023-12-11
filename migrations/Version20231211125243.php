<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231211125243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE schedule_expense ADD repeatable TINYINT(1) NOT NULL, CHANGE schedule_repeat_id schedule_repeat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL, CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE schedule_expense DROP repeatable, CHANGE schedule_repeat_id schedule_repeat_id INT NOT NULL');
        $this->addSql('ALTER TABLE profesionnel DROP FOREIGN KEY FK_42680596BF396750');
        $this->addSql('ALTER TABLE particulier DROP FOREIGN KEY FK_6CC4D4F3BF396750');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB83F6203804');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB83BCF5E72D');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB83A76ED395');
        $this->addSql('DROP INDEX IDX_57F0DB83A76ED395 ON ligne');
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CD79066886');
        $this->addSql('ALTER TABLE folder CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE etapes DROP FOREIGN KEY FK_E3443E17F6203804');
    }
}
