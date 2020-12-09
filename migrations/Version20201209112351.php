<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201209112351 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TYPE mill.bnom AS ( thickness smallint, width smallint)');
        $this->addSql('COMMENT ON COLUMN mill.bnom.thickness IS \'Толщина, мм\'');
        $this->addSql('COMMENT ON COLUMN mill.bnom.width IS \'Ширина, мм\'');

        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE mill.postav (
                id INT NOT NULL, 
                drec TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                comm TEXT DEFAULT NULL, 
                postav JSON NOT NULL, 
                enabled BOOLEAN DEFAULT \'true\' NOT NULL, 
                PRIMARY KEY(id))');

        $this->addSql('COMMENT ON TABLE mill.postav IS \'Таблица поставов в формате JSON\'');
        $this->addSql('COMMENT ON COLUMN mill.postav.drec IS \'Время создания\'');
        $this->addSql('COMMENT ON COLUMN mill.postav.comm IS \'Примечание\'');

        $this->addSql('CREATE TABLE mill.timber (
            id BIGINT NOT NULL, 
            postav_id INT DEFAULT NULL, 
            species_id CHAR(2) NOT NULL, 
            scid INT NOT NULL, drec TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            top DOUBLE PRECISION NOT NULL, 
            butt DOUBLE PRECISION NOT NULL, 
            top_taper DOUBLE PRECISION NOT NULL, 
            butt_taper DOUBLE PRECISION NOT NULL, 
            length INT NOT NULL, 
            sweep DOUBLE PRECISION NOT NULL, 
            diam DOUBLE PRECISION NOT NULL, 
            boards mill.bnom[] NOT NULL, 
            PRIMARY KEY(id))');

        $this->addSql('CREATE INDEX IDX_5FAB256521545D92 ON mill.timber (postav_id)');
        $this->addSql('CREATE INDEX IDX_5FAB2565B2A1D860 ON mill.timber (species_id)');

        $this->addSql('COMMENT ON TABLE mill.timber IS \'Брёвна\'');
        $this->addSql('COMMENT ON COLUMN mill.timber.scid IS \'Сканер ID\'');
        $this->addSql('COMMENT ON COLUMN mill.timber.drec IS \'Время записи\'');
        $this->addSql('COMMENT ON COLUMN mill.timber.top IS \'Диаметр вершины, мм\'');
        $this->addSql('COMMENT ON COLUMN mill.timber.butt IS \'Диаметр комля, мм\'');
        $this->addSql('COMMENT ON COLUMN mill.timber.top_taper IS \'Сбег вершины, мм/м2\'');
        $this->addSql('COMMENT ON COLUMN mill.timber.butt_taper IS \'Сбег комля, мм\'');
        $this->addSql('COMMENT ON COLUMN mill.timber.length IS \'Длина бревна, мм\'');
        $this->addSql('COMMENT ON COLUMN mill.timber.sweep IS \'Кривизна, %\'');
        $this->addSql('COMMENT ON COLUMN mill.timber.diam IS \'Учётный диаметр по ГОСТ, см\'');

        $this->addSql('ALTER TABLE mill.timber ADD CONSTRAINT FK_5FAB256521545D92 FOREIGN KEY (postav_id) REFERENCES mill.postav (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mill.timber ADD CONSTRAINT FK_5FAB2565B2A1D860 FOREIGN KEY (species_id) REFERENCES dic.species (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE mill.postav');
        $this->addSql('DROP TABLE mill.timber');
    }
}
