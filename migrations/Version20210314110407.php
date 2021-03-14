<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210314110407 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alliance ADD server_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE alliance ADD CONSTRAINT FK_6CBA583F1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('CREATE INDEX IDX_6CBA583F1844E6B7 ON alliance (server_id)');
        $this->addSql('ALTER TABLE player ADD server_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A651844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('CREATE INDEX IDX_98197A651844E6B7 ON player (server_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alliance DROP FOREIGN KEY FK_6CBA583F1844E6B7');
        $this->addSql('DROP INDEX IDX_6CBA583F1844E6B7 ON alliance');
        $this->addSql('ALTER TABLE alliance DROP server_id');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A651844E6B7');
        $this->addSql('DROP INDEX IDX_98197A651844E6B7 ON player');
        $this->addSql('ALTER TABLE player DROP server_id');
    }
}
