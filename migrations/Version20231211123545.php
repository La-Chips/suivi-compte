<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231211123545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bank_account (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_53A23E0AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule_expense (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, schedule_repeat_id INT NOT NULL, bank_account_id INT NOT NULL, label VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, start_date DATE NOT NULL, INDEX IDX_A050A11712469DE2 (category_id), INDEX IDX_A050A11720F1484 (schedule_repeat_id), INDEX IDX_A050A11712CB990C (bank_account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule_repeat (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, day_duration INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE schedule_expense ADD CONSTRAINT FK_A050A11712469DE2 FOREIGN KEY (category_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE schedule_expense ADD CONSTRAINT FK_A050A11720F1484 FOREIGN KEY (schedule_repeat_id) REFERENCES schedule_repeat (id)');
        $this->addSql('ALTER TABLE schedule_expense ADD CONSTRAINT FK_A050A11712CB990C FOREIGN KEY (bank_account_id) REFERENCES bank_account (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0AA76ED395');
        $this->addSql('ALTER TABLE schedule_expense DROP FOREIGN KEY FK_A050A11712469DE2');
        $this->addSql('ALTER TABLE schedule_expense DROP FOREIGN KEY FK_A050A11720F1484');
        $this->addSql('ALTER TABLE schedule_expense DROP FOREIGN KEY FK_A050A11712CB990C');
        $this->addSql('DROP TABLE bank_account');
        $this->addSql('DROP TABLE schedule_expense');
        $this->addSql('DROP TABLE schedule_repeat');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL');
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
