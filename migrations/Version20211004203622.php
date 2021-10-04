<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211004203622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE root_folder (id INT AUTO_INCREMENT NOT NULL, root_folder_id INT NOT NULL, folder_id INT NOT NULL, UNIQUE INDEX UNIQ_CE18E50C5F3EA365 (root_folder_id), UNIQUE INDEX UNIQ_CE18E50C162CB942 (folder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE root_folder ADD CONSTRAINT FK_CE18E50C5F3EA365 FOREIGN KEY (root_folder_id) REFERENCES folder (id)');
        $this->addSql('ALTER TABLE root_folder ADD CONSTRAINT FK_CE18E50C162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id)');
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CD162CB942');
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CD5F3EA365');
        $this->addSql('DROP INDEX IDX_ECA209CD162CB942 ON folder');
        $this->addSql('DROP INDEX IDX_ECA209CD5F3EA365 ON folder');
        $this->addSql('ALTER TABLE folder DROP folder_id, DROP root_folder_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE root_folder');
        $this->addSql('ALTER TABLE folder ADD folder_id INT DEFAULT NULL, ADD root_folder_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE folder ADD CONSTRAINT FK_ECA209CD162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id)');
        $this->addSql('ALTER TABLE folder ADD CONSTRAINT FK_ECA209CD5F3EA365 FOREIGN KEY (root_folder_id) REFERENCES folder (id)');
        $this->addSql('CREATE INDEX IDX_ECA209CD162CB942 ON folder (folder_id)');
        $this->addSql('CREATE INDEX IDX_ECA209CD5F3EA365 ON folder (root_folder_id)');
    }
}
