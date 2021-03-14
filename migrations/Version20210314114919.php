<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210314114919 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE planet (id INT AUTO_INCREMENT NOT NULL, player_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, ogame_id INT NOT NULL, coordinates VARCHAR(255) NOT NULL, position INT NOT NULL, system INT NOT NULL, galaxy INT NOT NULL, has_moon TINYINT(1) NOT NULL, moon_ogame_id INT DEFAULT NULL, moon_size INT DEFAULT NULL, moon_name VARCHAR(255) DEFAULT NULL, INDEX IDX_68136AA599E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE planet ADD CONSTRAINT FK_68136AA599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE planet');
    }
}
