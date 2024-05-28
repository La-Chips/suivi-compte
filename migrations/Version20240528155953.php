<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528155953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, quantite INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank_account (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, initial_value DOUBLE PRECISION DEFAULT NULL, INDEX IDX_53A23E0AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, INDEX IDX_497DD634A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etapes (id INT AUTO_INCREMENT NOT NULL, statut_id INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_E3443E17F6203804 (statut_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filter (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, user_id INT DEFAULT NULL, keyword VARCHAR(255) NOT NULL, amount DOUBLE PRECISION DEFAULT NULL, INDEX IDX_7FC45F1DBCF5E72D (categorie_id), INDEX IDX_7FC45F1DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne (id INT AUTO_INCREMENT NOT NULL, statut_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, user_id INT DEFAULT NULL, bank_account_id INT DEFAULT NULL, date DATETIME NOT NULL, libelle LONGTEXT NOT NULL, libelle_2 LONGTEXT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, date_insert DATETIME NOT NULL, origine INT NOT NULL, closed TINYINT(1) NOT NULL, INDEX IDX_57F0DB83F6203804 (statut_id), INDEX IDX_57F0DB83BCF5E72D (categorie_id), INDEX IDX_57F0DB83A76ED395 (user_id), INDEX IDX_57F0DB8312CB990C (bank_account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_user (ligne_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_979BEACB5A438E76 (ligne_id), INDEX IDX_979BEACBA76ED395 (user_id), PRIMARY KEY(ligne_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule_expense (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, schedule_repeat_id INT DEFAULT NULL, bank_account_id INT NOT NULL, label VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, start_date DATE NOT NULL, repeatable TINYINT(1) NOT NULL, INDEX IDX_A050A11712469DE2 (category_id), INDEX IDX_A050A11720F1484 (schedule_repeat_id), INDEX IDX_A050A11712CB990C (bank_account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule_repeat (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, day_duration INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD634A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE etapes ADD CONSTRAINT FK_E3443E17F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE filter ADD CONSTRAINT FK_7FC45F1DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE filter ADD CONSTRAINT FK_7FC45F1DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ligne ADD CONSTRAINT FK_57F0DB83F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE ligne ADD CONSTRAINT FK_57F0DB83BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE ligne ADD CONSTRAINT FK_57F0DB83A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ligne ADD CONSTRAINT FK_57F0DB8312CB990C FOREIGN KEY (bank_account_id) REFERENCES bank_account (id)');
        $this->addSql('ALTER TABLE ligne_user ADD CONSTRAINT FK_979BEACB5A438E76 FOREIGN KEY (ligne_id) REFERENCES ligne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ligne_user ADD CONSTRAINT FK_979BEACBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE schedule_expense ADD CONSTRAINT FK_A050A11712469DE2 FOREIGN KEY (category_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE schedule_expense ADD CONSTRAINT FK_A050A11720F1484 FOREIGN KEY (schedule_repeat_id) REFERENCES schedule_repeat (id)');
        $this->addSql('ALTER TABLE schedule_expense ADD CONSTRAINT FK_A050A11712CB990C FOREIGN KEY (bank_account_id) REFERENCES bank_account (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0AA76ED395');
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634A76ED395');
        $this->addSql('ALTER TABLE etapes DROP FOREIGN KEY FK_E3443E17F6203804');
        $this->addSql('ALTER TABLE filter DROP FOREIGN KEY FK_7FC45F1DBCF5E72D');
        $this->addSql('ALTER TABLE filter DROP FOREIGN KEY FK_7FC45F1DA76ED395');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB83F6203804');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB83BCF5E72D');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB83A76ED395');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB8312CB990C');
        $this->addSql('ALTER TABLE ligne_user DROP FOREIGN KEY FK_979BEACB5A438E76');
        $this->addSql('ALTER TABLE ligne_user DROP FOREIGN KEY FK_979BEACBA76ED395');
        $this->addSql('ALTER TABLE schedule_expense DROP FOREIGN KEY FK_A050A11712469DE2');
        $this->addSql('ALTER TABLE schedule_expense DROP FOREIGN KEY FK_A050A11720F1484');
        $this->addSql('ALTER TABLE schedule_expense DROP FOREIGN KEY FK_A050A11712CB990C');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE bank_account');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE etapes');
        $this->addSql('DROP TABLE filter');
        $this->addSql('DROP TABLE ligne');
        $this->addSql('DROP TABLE ligne_user');
        $this->addSql('DROP TABLE schedule_expense');
        $this->addSql('DROP TABLE schedule_repeat');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE user');
    }
}
