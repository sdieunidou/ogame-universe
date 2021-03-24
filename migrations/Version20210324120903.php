<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324120903 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lock_keys');
        $this->addSql('ALTER TABLE spy DROP FOREIGN KEY FK_AF10BF7599E6F5DF');
        $this->addSql('ALTER TABLE spy DROP FOREIGN KEY FK_AF10BF75A25E9820');
        $this->addSql('ALTER TABLE spy ADD CONSTRAINT FK_AF10BF7599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE spy ADD CONSTRAINT FK_AF10BF75A25E9820 FOREIGN KEY (planet_id) REFERENCES planet (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lock_keys (key_id VARCHAR(64) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, key_token VARCHAR(44) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, key_expiration INT UNSIGNED NOT NULL, PRIMARY KEY(key_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE spy DROP FOREIGN KEY FK_AF10BF7599E6F5DF');
        $this->addSql('ALTER TABLE spy DROP FOREIGN KEY FK_AF10BF75A25E9820');
        $this->addSql('ALTER TABLE spy ADD CONSTRAINT FK_AF10BF7599E6F5DF FOREIGN KEY (player_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE spy ADD CONSTRAINT FK_AF10BF75A25E9820 FOREIGN KEY (planet_id) REFERENCES server (id)');
    }
}
