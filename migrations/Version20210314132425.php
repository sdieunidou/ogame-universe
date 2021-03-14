<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210314132425 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alliance (id INT AUTO_INCREMENT NOT NULL, server_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, tag VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, ogame_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_6CBA583F1844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planet (id INT AUTO_INCREMENT NOT NULL, player_id INT DEFAULT NULL, server_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, ogame_id INT NOT NULL, coordinates VARCHAR(255) NOT NULL, position INT NOT NULL, system INT NOT NULL, galaxy INT NOT NULL, has_moon TINYINT(1) NOT NULL, moon_ogame_id INT DEFAULT NULL, moon_size INT DEFAULT NULL, moon_name VARCHAR(255) DEFAULT NULL, INDEX IDX_68136AA599E6F5DF (player_id), INDEX IDX_68136AA51844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, server_id INT DEFAULT NULL, alliance_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, ogame_id INT NOT NULL, score INT NOT NULL, score_at24_h INT NOT NULL, date_of_score_at24_h DATETIME NOT NULL, economy_score INT NOT NULL, research_score INT NOT NULL, military_score INT NOT NULL, military_built_score INT NOT NULL, military_destroyed_score INT NOT NULL, military_lost_score INT NOT NULL, military_honor_score INT NOT NULL, military_ships_score INT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_98197A651844E6B7 (server_id), INDEX IDX_98197A6510A0EA3F (alliance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, server_id INT NOT NULL, language VARCHAR(255) NOT NULL, latest_player_update DATETIME DEFAULT NULL, latest_alliance_update DATETIME DEFAULT NULL, latest_universe_update DATETIME DEFAULT NULL, latest_ranking_update DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alliance ADD CONSTRAINT FK_6CBA583F1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE planet ADD CONSTRAINT FK_68136AA599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE planet ADD CONSTRAINT FK_68136AA51844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A651844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6510A0EA3F FOREIGN KEY (alliance_id) REFERENCES alliance (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A6510A0EA3F');
        $this->addSql('ALTER TABLE planet DROP FOREIGN KEY FK_68136AA599E6F5DF');
        $this->addSql('ALTER TABLE alliance DROP FOREIGN KEY FK_6CBA583F1844E6B7');
        $this->addSql('ALTER TABLE planet DROP FOREIGN KEY FK_68136AA51844E6B7');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A651844E6B7');
        $this->addSql('DROP TABLE alliance');
        $this->addSql('DROP TABLE planet');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE server');
    }
}
