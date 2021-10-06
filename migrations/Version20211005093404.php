<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211005093404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file ADD extention_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610CDB5EB65 FOREIGN KEY (extention_id) REFERENCES extention_icon (id)');
        $this->addSql('CREATE INDEX IDX_8C9F3610CDB5EB65 ON file (extention_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610CDB5EB65');
        $this->addSql('DROP INDEX IDX_8C9F3610CDB5EB65 ON file');
        $this->addSql('ALTER TABLE file DROP extention_id');
    }
}
