<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210114104633 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mill.duty (id CHAR(2) NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON TABLE mill.duty IS \'Список должностей\'');
        $this->addSql('CREATE TABLE mill.people_duty (people_id INT NOT NULL, duty_id CHAR(2) NOT NULL, PRIMARY KEY(people_id, duty_id))');
        $this->addSql('CREATE INDEX IDX_A779AC293147C936 ON mill.people_duty (people_id)');
        $this->addSql('CREATE INDEX IDX_A779AC293A1F9EC1 ON mill.people_duty (duty_id)');
        $this->addSql('ALTER TABLE mill.people_duty ADD CONSTRAINT FK_A779AC293147C936 FOREIGN KEY (people_id) REFERENCES mill.people (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mill.people_duty ADD CONSTRAINT FK_A779AC293A1F9EC1 FOREIGN KEY (duty_id) REFERENCES mill.duty (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE people_duty DROP CONSTRAINT FK_A779AC293A1F9EC1');
        $this->addSql('CREATE TABLE mill.thickness (nom SMALLINT NOT NULL, min SMALLINT NOT NULL, max SMALLINT NOT NULL, PRIMARY KEY(nom))');
        $this->addSql('COMMENT ON TABLE mill.thickness IS \'Справочник толщин\'');
        $this->addSql('COMMENT ON COLUMN mill.thickness.nom IS \'Номинальная толщина\'');
        $this->addSql('COMMENT ON COLUMN mill.thickness.min IS \'Минимальная толщина\'');
        $this->addSql('COMMENT ON COLUMN mill.thickness.max IS \'Максимальная толщина\'');
        $this->addSql('CREATE TABLE mill.width (nom SMALLINT NOT NULL, min SMALLINT NOT NULL, max SMALLINT NOT NULL, PRIMARY KEY(nom))');
        $this->addSql('COMMENT ON TABLE mill.width IS \'Справочник ширин\'');
        $this->addSql('COMMENT ON COLUMN mill.width.nom IS \'Номинальная ширина\'');
        $this->addSql('COMMENT ON COLUMN mill.width.min IS \'Минимальная ширина\'');
        $this->addSql('COMMENT ON COLUMN mill.width.max IS \'Максимальная ширина\'');
        $this->addSql('CREATE TABLE mill.board (drec TIMESTAMP(0) WITH TIME ZONE NOT NULL, nom_thickness SMALLINT NOT NULL, nom_width SMALLINT NOT NULL, nom_length SMALLINT NOT NULL, qual_list_id SMALLINT NOT NULL, species_id CHAR(2) NOT NULL, thickness SMALLINT NOT NULL, width SMALLINT NOT NULL, length SMALLINT NOT NULL, qualities CHAR(1) NOT NULL, pocket CHAR(1) NOT NULL, PRIMARY KEY(drec))');
        $this->addSql('CREATE INDEX idx_4fd10382f51c341b ON mill.board (nom_length)');
        $this->addSql('CREATE INDEX idx_4fd10382b2a1d860 ON mill.board (species_id)');
        $this->addSql('CREATE INDEX idx_4fd10382569eb619 ON mill.board (qual_list_id)');
        $this->addSql('CREATE INDEX idx_4fd10382239d68ef ON mill.board (nom_thickness)');
        $this->addSql('CREATE INDEX idx_4fd10382b07643d2 ON mill.board (nom_width)');
        $this->addSql('COMMENT ON COLUMN mill.board.drec IS \'Дата записи\'');
        $this->addSql('COMMENT ON COLUMN mill.board.nom_thickness IS \'Номинальная толщина\'');
        $this->addSql('COMMENT ON COLUMN mill.board.nom_width IS \'Номинальная ширина\'');
        $this->addSql('COMMENT ON COLUMN mill.board.nom_length IS \'Длина\'');
        $this->addSql('COMMENT ON COLUMN mill.board.thickness IS \'Пильная толщина доски, мм.\'');
        $this->addSql('COMMENT ON COLUMN mill.board.width IS \'Пильная ширина доски, мм.\'');
        $this->addSql('COMMENT ON COLUMN mill.board.length IS \'Пильная длина доски, мм.\'');
        $this->addSql('COMMENT ON COLUMN mill.board.qualities IS \'Два качества от операторов, по 4 бита\'');
        $this->addSql('COMMENT ON COLUMN mill.board.pocket IS \'Карман\'');
        $this->addSql('CREATE TABLE mill.quality_list (id SMALLINT NOT NULL, name VARCHAR(32) NOT NULL, def SMALLINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON TABLE mill.quality_list IS \'Списки качеств\'');
        $this->addSql('COMMENT ON COLUMN mill.quality_list.name IS \'Название списка\'');
        $this->addSql('COMMENT ON COLUMN mill.quality_list.def IS \'ID качества по-умолчанию\'');
        $this->addSql('ALTER TABLE mill.board ADD CONSTRAINT fk_4fd10382239d68ef FOREIGN KEY (nom_thickness) REFERENCES mill.thickness (nom) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mill.board ADD CONSTRAINT fk_4fd10382b07643d2 FOREIGN KEY (nom_width) REFERENCES mill.width (nom) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mill.board ADD CONSTRAINT fk_4fd10382f51c341b FOREIGN KEY (nom_length) REFERENCES mill.length (standard) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mill.board ADD CONSTRAINT fk_4fd10382569eb619 FOREIGN KEY (qual_list_id) REFERENCES mill.quality_list (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mill.board ADD CONSTRAINT fk_4fd10382b2a1d860 FOREIGN KEY (species_id) REFERENCES dic.species (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE mill.duty');
        $this->addSql('DROP TABLE people_duty');
        $this->addSql('ALTER TABLE mill.downtime ALTER drec TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE mill.downtime ALTER drec SET DEFAULT \'now()\'');
        $this->addSql('COMMENT ON COLUMN mill.downtime.drec IS NULL');
        $this->addSql('COMMENT ON COLUMN mill.downtime.finish IS NULL');
        $this->addSql('ALTER TABLE mill.shift ALTER start TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE mill.shift ALTER start SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE mill.event ALTER drec TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE mill.event ALTER drec SET DEFAULT \'now()\'');
        $this->addSql('COMMENT ON COLUMN mill.event.drec IS NULL');
        $this->addSql('ALTER TABLE mill.timber ALTER boards TYPE bnom');
        $this->addSql('ALTER TABLE mill.timber ALTER boards DROP DEFAULT');
    }
}
