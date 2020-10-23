<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201022075401 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE mill.length (
                value SMALLINT NOT NULL, 
                PRIMARY KEY(value))'
        );

        $this->addSql('COMMENT ON TABLE mill.length IS \'Справочник длин\'');
        $this->addSql('COMMENT ON COLUMN mill.length.value IS \'Длина\'');

        $this->addSql(
            'CREATE TABLE mill.quality_list (
                id SMALLINT NOT NULL,
                name VARCHAR(32) NOT NULL,
                def SMALLINT DEFAULT NULL,
                PRIMARY KEY(id))'
        );
        $this->addSql('CREATE SEQUENCE mill.quality_list_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        
        $this->addSql('COMMENT ON TABLE mill.quality_list IS \'Списки качеств\'');
        $this->addSql('COMMENT ON COLUMN mill.quality_list.name IS \'Название списка\'');
        $this->addSql('COMMENT ON COLUMN mill.quality_list.def IS \'ID качества по-умолчанию\'');

        $this->addSql(
            'CREATE TABLE mill.thickness (
                nom SMALLINT NOT NULL,
                min SMALLINT NOT NULL,
                max SMALLINT NOT NULL,
                CHECK ( min <= max ), 
                PRIMARY KEY(nom))'
        );
        $this->addSql('COMMENT ON TABLE mill.thickness IS \'Справочник толщин\'');
        $this->addSql('COMMENT ON COLUMN mill.thickness.nom IS \'Номинальная толщина\'');
        $this->addSql('COMMENT ON COLUMN mill.thickness.min IS \'Минимальная толщина\'');
        $this->addSql('COMMENT ON COLUMN mill.thickness.max IS \'Максимальная толщина\'');

        $this->addSql(
            'CREATE TABLE mill.width (nom SMALLINT NOT NULL,
                min SMALLINT NOT NULL,
                max SMALLINT NOT NULL,
                CHECK ( min <= max ), 
                PRIMARY KEY(nom))');

        $this->addSql('COMMENT ON TABLE mill.width IS \'Справочник ширин\'');
        $this->addSql('COMMENT ON COLUMN mill.width.nom IS \'Номинальная ширина\'');
        $this->addSql('COMMENT ON COLUMN mill.width.min IS \'Минимальная ширина\'');
        $this->addSql('COMMENT ON COLUMN mill.width.max IS \'Максимальная ширина\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');

        $this->addSql('DROP TABLE mill.length');
        $this->addSql('DROP TABLE mill.quality_list');
        $this->addSql('DROP TABLE mill.thickness');
        $this->addSql('DROP TABLE mill.width');
    }
}
