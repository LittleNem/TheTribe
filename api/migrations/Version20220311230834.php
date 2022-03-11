<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220311230834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character (id INT NOT NULL, owned_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, rank INT DEFAULT 1 NOT NULL, skill_points INT DEFAULT 12 NOT NULL, health INT DEFAULT 10 NOT NULL, attack INT DEFAULT 0 NOT NULL, defense INT DEFAULT 0 NOT NULL, magik INT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP, delay TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_937AB0345E70BCD7 ON character (owned_by_id)');
        $this->addSql('COMMENT ON COLUMN character.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN character.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN character.delay IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE character_fight (character_id INT NOT NULL, fight_id INT NOT NULL, PRIMARY KEY(character_id, fight_id))');
        $this->addSql('CREATE INDEX IDX_DF69A3341136BE75 ON character_fight (character_id)');
        $this->addSql('CREATE INDEX IDX_DF69A334AC6657E4 ON character_fight (fight_id)');
        $this->addSql('CREATE TABLE fight (id INT NOT NULL, winner_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_21AA44565DFCD4B8 ON fight (winner_id)');
        $this->addSql('COMMENT ON COLUMN fight.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN fight.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE history (id INT NOT NULL, fight_id INT NOT NULL, character_id INT NOT NULL, round INT NOT NULL, dice_value INT NOT NULL, damage INT NOT NULL, opponent_health_value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_27BA704BAC6657E4 ON history (fight_id)');
        $this->addSql('CREATE INDEX IDX_27BA704B1136BE75 ON history (character_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB0345E70BCD7 FOREIGN KEY (owned_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_fight ADD CONSTRAINT FK_DF69A3341136BE75 FOREIGN KEY (character_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_fight ADD CONSTRAINT FK_DF69A334AC6657E4 FOREIGN KEY (fight_id) REFERENCES fight (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA44565DFCD4B8 FOREIGN KEY (winner_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704BAC6657E4 FOREIGN KEY (fight_id) REFERENCES fight (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B1136BE75 FOREIGN KEY (character_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE character_fight DROP CONSTRAINT FK_DF69A3341136BE75');
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT FK_21AA44565DFCD4B8');
        $this->addSql('ALTER TABLE history DROP CONSTRAINT FK_27BA704B1136BE75');
        $this->addSql('ALTER TABLE character_fight DROP CONSTRAINT FK_DF69A334AC6657E4');
        $this->addSql('ALTER TABLE history DROP CONSTRAINT FK_27BA704BAC6657E4');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB0345E70BCD7');
        $this->addSql('DROP TABLE character');
        $this->addSql('DROP TABLE character_fight');
        $this->addSql('DROP TABLE fight');
        $this->addSql('DROP TABLE history');
        $this->addSql('DROP TABLE "user"');
    }
}
