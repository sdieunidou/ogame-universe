<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210314120047 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planet ADD server_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE planet ADD CONSTRAINT FK_68136AA51844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('CREATE INDEX IDX_68136AA51844E6B7 ON planet (server_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planet DROP FOREIGN KEY FK_68136AA51844E6B7');
        $this->addSql('DROP INDEX IDX_68136AA51844E6B7 ON planet');
        $this->addSql('ALTER TABLE planet DROP server_id');
    }
}
