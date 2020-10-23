<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200929081621 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mill.downtime (drec TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW() NOT NULL, cause_id INT DEFAULT NULL, place_id INT DEFAULT NULL, finish TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(drec))');
        $this->addSql('CREATE INDEX IDX_8B6D12BD66E2221E ON mill.downtime (cause_id)');
        $this->addSql('CREATE INDEX IDX_8B6D12BDDA6A219 ON mill.downtime (place_id)');
        $this->addSql('CREATE TABLE mill.downtime_cause (id INT NOT NULL, name VARCHAR(128) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON TABLE mill.downtime_cause IS \'Причины простоя\'');
        $this->addSql('COMMENT ON COLUMN mill.downtime_cause.name IS \'Название причины\'');
        $this->addSql('CREATE TABLE mill.downtime_place (id INT NOT NULL, name VARCHAR(128) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON TABLE mill.downtime_place IS \'Места простоя\'');
        $this->addSql('COMMENT ON COLUMN mill.downtime_place.name IS \'Название места\'');
        $this->addSql('ALTER TABLE mill.downtime ADD CONSTRAINT FK_8B6D12BD66E2221E FOREIGN KEY (cause_id) REFERENCES mill.downtime_cause (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mill.downtime ADD CONSTRAINT FK_8B6D12BDDA6A219 FOREIGN KEY (place_id) REFERENCES mill.downtime_place (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE SEQUENCE mill.downtime_cause_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mill.downtime_place_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mill.downtime DROP CONSTRAINT FK_8B6D12BD66E2221E');
        $this->addSql('ALTER TABLE mill.downtime DROP CONSTRAINT FK_8B6D12BDDA6A219');
        $this->addSql('DROP SEQUENCE mill.downtime_cause_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mill.downtime_place_id_seq CASCADE');
        $this->addSql('DROP TABLE mill.downtime');
        $this->addSql('DROP TABLE mill.downtime_cause');
        $this->addSql('DROP TABLE mill.downtime_place');
    }
}
