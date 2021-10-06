<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211006144529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE folder ADD root_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE folder ADD CONSTRAINT FK_ECA209CD79066886 FOREIGN KEY (root_id) REFERENCES folder (id)');
        $this->addSql('CREATE INDEX IDX_ECA209CD79066886 ON folder (root_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CD79066886');
        $this->addSql('DROP INDEX IDX_ECA209CD79066886 ON folder');
        $this->addSql('ALTER TABLE folder DROP root_id');
    }
}
