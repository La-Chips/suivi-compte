<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211006144143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE root_folder_folder (root_folder_id INT NOT NULL, folder_id INT NOT NULL, INDEX IDX_52BA4B7D5F3EA365 (root_folder_id), INDEX IDX_52BA4B7D162CB942 (folder_id), PRIMARY KEY(root_folder_id, folder_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE root_folder_folder ADD CONSTRAINT FK_52BA4B7D5F3EA365 FOREIGN KEY (root_folder_id) REFERENCES root_folder (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE root_folder_folder ADD CONSTRAINT FK_52BA4B7D162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CD5F3EA365');
        $this->addSql('DROP INDEX IDX_ECA209CD5F3EA365 ON folder');
        $this->addSql('ALTER TABLE folder DROP root_folder_id');
        $this->addSql('ALTER TABLE root_folder DROP FOREIGN KEY FK_CE18E50C162CB942');
        $this->addSql('DROP INDEX UNIQ_CE18E50C162CB942 ON root_folder');
        $this->addSql('ALTER TABLE root_folder ADD root_folder_id INT DEFAULT NULL, DROP folder_id');
        $this->addSql('ALTER TABLE root_folder ADD CONSTRAINT FK_CE18E50C5F3EA365 FOREIGN KEY (root_folder_id) REFERENCES folder (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CE18E50C5F3EA365 ON root_folder (root_folder_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE root_folder_folder');
        $this->addSql('ALTER TABLE folder ADD root_folder_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE folder ADD CONSTRAINT FK_ECA209CD5F3EA365 FOREIGN KEY (root_folder_id) REFERENCES root_folder (id)');
        $this->addSql('CREATE INDEX IDX_ECA209CD5F3EA365 ON folder (root_folder_id)');
        $this->addSql('ALTER TABLE root_folder DROP FOREIGN KEY FK_CE18E50C5F3EA365');
        $this->addSql('DROP INDEX UNIQ_CE18E50C5F3EA365 ON root_folder');
        $this->addSql('ALTER TABLE root_folder ADD folder_id INT NOT NULL, DROP root_folder_id');
        $this->addSql('ALTER TABLE root_folder ADD CONSTRAINT FK_CE18E50C162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CE18E50C162CB942 ON root_folder (folder_id)');
    }
}
