<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210210103019 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE mill.downtime_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mill.downtime_location_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE mill.downtime_group (id INT NOT NULL, text VARCHAR(128) NOT NULL, enabled BOOLEAN DEFAULT \'true\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON TABLE mill.downtime_group IS \'Группы причин простоя\'');
        $this->addSql('COMMENT ON COLUMN mill.downtime_group.text IS \'Название причины\'');
        $this->addSql('COMMENT ON COLUMN mill.downtime_group.enabled IS \'Используется\'');

        $this->addSql('CREATE TABLE mill.downtime_location (id INT NOT NULL, text VARCHAR(128) NOT NULL, enabled BOOLEAN DEFAULT \'true\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON TABLE mill.downtime_location IS \'Локации простоя\'');
        $this->addSql('COMMENT ON COLUMN mill.downtime_location.text IS \'Название причины\'');
        $this->addSql('COMMENT ON COLUMN mill.downtime_location.enabled IS \'Используется\'');
        $this->addSql('COMMENT ON COLUMN mill.downtime.drec IS \'Время начала простоя\'');
        $this->addSql('COMMENT ON COLUMN mill.downtime.finish IS \'Время окончания простоя\'');

        $this->addSql('ALTER TABLE mill.downtime_cause ADD "group_id" INT NOT NULL');
        $this->addSql('ALTER TABLE mill.downtime_cause ADD enabled BOOLEAN DEFAULT \'true\' NOT NULL');
        $this->addSql('COMMENT ON COLUMN mill.downtime_cause.enabled IS \'Используется\'');
        $this->addSql('ALTER TABLE mill.downtime_cause ADD CONSTRAINT FK_3CEF78836DC044C5 FOREIGN KEY ("group_id") REFERENCES mill.downtime_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3CEF78836DC044C5 ON mill.downtime_cause ("group_id")');

        $this->addSql('ALTER TABLE mill.downtime_place ADD location_id INT NOT NULL');
        $this->addSql('ALTER TABLE mill.downtime_place ADD enabled BOOLEAN DEFAULT \'true\' NOT NULL');
        $this->addSql('COMMENT ON COLUMN mill.downtime_place.enabled IS \'Используется\'');
        $this->addSql('ALTER TABLE mill.downtime_place ADD CONSTRAINT FK_B82854F164D218E FOREIGN KEY (location_id) REFERENCES mill.downtime_location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B82854F164D218E ON mill.downtime_place (location_id)');

        $this->addSql('COMMENT ON COLUMN mill.event.drec IS \'Начало события\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your need
    }
}
