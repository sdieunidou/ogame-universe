<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324133424 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spy CHANGE total_ship total_ship BIGINT DEFAULT NULL, CHANGE total_defense total_defense BIGINT DEFAULT NULL, CHANGE total_ship_score total_ship_score BIGINT DEFAULT NULL, CHANGE total_defense_score total_defense_score BIGINT DEFAULT NULL, CHANGE metal metal BIGINT DEFAULT NULL, CHANGE crystal crystal BIGINT DEFAULT NULL, CHANGE deuterium deuterium BIGINT DEFAULT NULL, CHANGE energy energy BIGINT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spy CHANGE total_ship total_ship INT DEFAULT NULL, CHANGE total_defense total_defense INT DEFAULT NULL, CHANGE total_ship_score total_ship_score INT DEFAULT NULL, CHANGE total_defense_score total_defense_score INT DEFAULT NULL, CHANGE metal metal INT DEFAULT NULL, CHANGE crystal crystal INT DEFAULT NULL, CHANGE deuterium deuterium INT DEFAULT NULL, CHANGE energy energy INT DEFAULT NULL');
    }
}
