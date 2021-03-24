<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324132731 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE spy (id INT AUTO_INCREMENT NOT NULL, server_id INT DEFAULT NULL, player_id INT DEFAULT NULL, planet_id INT DEFAULT NULL, api_key VARCHAR(255) NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', player_ogame_id INT DEFAULT NULL, player_name VARCHAR(255) DEFAULT NULL, player_class INT DEFAULT NULL, is_moon TINYINT(1) DEFAULT NULL, activity INT DEFAULT NULL, coordinates VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, system INT DEFAULT NULL, galaxy INT DEFAULT NULL, total_ship INT DEFAULT NULL, total_defense INT DEFAULT NULL, total_ship_score INT DEFAULT NULL, total_defense_score INT DEFAULT NULL, loot_percentage INT DEFAULT NULL, spy_at DATETIME DEFAULT NULL, metal INT DEFAULT NULL, crystal INT DEFAULT NULL, deuterium INT DEFAULT NULL, energy INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_AF10BF751844E6B7 (server_id), INDEX IDX_AF10BF7599E6F5DF (player_id), INDEX IDX_AF10BF75A25E9820 (planet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE spy ADD CONSTRAINT FK_AF10BF751844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE spy ADD CONSTRAINT FK_AF10BF7599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE spy ADD CONSTRAINT FK_AF10BF75A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE spy');
    }
}
