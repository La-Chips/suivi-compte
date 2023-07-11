<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230711220123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE TABLE ligne_user (ligne_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_979BEACB5A438E76 (ligne_id), INDEX IDX_979BEACBA76ED395 (user_id), PRIMARY KEY(ligne_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL, CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE root_folder (id INT AUTO_INCREMENT NOT NULL, root_folder_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_CE18E50C5F3EA365 (root_folder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE root_folder_folder (root_folder_id INT NOT NULL, folder_id INT NOT NULL, INDEX IDX_52BA4B7D162CB942 (folder_id), INDEX IDX_52BA4B7D5F3EA365 (root_folder_id), PRIMARY KEY(root_folder_id, folder_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE root_folder ADD CONSTRAINT FK_CE18E50C5F3EA365 FOREIGN KEY (root_folder_id) REFERENCES folder (id)');
        $this->addSql('ALTER TABLE root_folder_folder ADD CONSTRAINT FK_52BA4B7D5F3EA365 FOREIGN KEY (root_folder_id) REFERENCES root_folder (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE root_folder_folder ADD CONSTRAINT FK_52BA4B7D162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ligne_user DROP FOREIGN KEY FK_979BEACB5A438E76');
        $this->addSql('ALTER TABLE ligne_user DROP FOREIGN KEY FK_979BEACBA76ED395');
        $this->addSql('DROP TABLE ligne_user');
        $this->addSql('ALTER TABLE etapes DROP FOREIGN KEY FK_E3443E17F6203804');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610162CB942');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610CDB5EB65');
        $this->addSql('ALTER TABLE filter DROP FOREIGN KEY FK_7FC45F1DBCF5E72D');
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CD79066886');
        $this->addSql('ALTER TABLE folder CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB83F6203804');
        $this->addSql('ALTER TABLE ligne DROP FOREIGN KEY FK_57F0DB83BCF5E72D');
        $this->addSql('ALTER TABLE particulier DROP FOREIGN KEY FK_6CC4D4F3BF396750');
        $this->addSql('ALTER TABLE profesionnel DROP FOREIGN KEY FK_42680596BF396750');
        $this->addSql('ALTER TABLE statut CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE username username INT NOT NULL, CHANGE roles roles LONGTEXT NOT NULL');
    }
}
