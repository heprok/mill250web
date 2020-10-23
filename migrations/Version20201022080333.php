<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201022080333 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE mill.board (
                drec TIMESTAMP WITH TIME ZONE,
                nom_thickness SMALLINT NOT NULL,
                nom_width SMALLINT NOT NULL,
                nom_length SMALLINT NOT NULL,
                qual_list_id SMALLINT NOT NULL,
                species_id CHAR(2) NOT NULL,
                thickness SMALLINT NOT NULL,
                width SMALLINT NOT NULL,
                length SMALLINT NOT NULL,
                qualities CHAR NOT NULL,
                pocket CHAR NOT NULL,
                PRIMARY KEY(drec))'
        );
        $this->addSql('CREATE INDEX IDX_4FD10382239D68EF ON mill.board (nom_thickness)');
        $this->addSql('CREATE INDEX IDX_4FD10382B07643D2 ON mill.board (nom_width)');
        $this->addSql('CREATE INDEX IDX_4FD10382F51C341B ON mill.board (nom_length)');
        $this->addSql('CREATE INDEX IDX_4FD10382569EB619 ON mill.board (qual_list_id)');
        $this->addSql('CREATE INDEX IDX_4FD10382B2A1D860 ON mill.board (species_id)');

        $this->addSql('COMMENT ON COLUMN mill.board.drec IS \'Дата записи\'');
        $this->addSql('COMMENT ON COLUMN mill.board.nom_thickness IS \'Номинальная толщина\'');
        $this->addSql('COMMENT ON COLUMN mill.board.nom_width IS \'Номинальная ширина\'');
        $this->addSql('COMMENT ON COLUMN mill.board.nom_length IS \'Длина\'');
        $this->addSql('COMMENT ON COLUMN mill.board.thickness IS \'Пильная толщина доски, мм.\'');
        $this->addSql('COMMENT ON COLUMN mill.board.width IS \'Пильная ширина доски, мм.\'');
        $this->addSql('COMMENT ON COLUMN mill.board.length IS \'Пильная длина доски, мм.\'');
        $this->addSql('COMMENT ON COLUMN mill.board.qualities IS \'Два качества от операторов, по 4 бита\'');
        $this->addSql('COMMENT ON COLUMN mill.board.pocket IS \'Карман\'');

        $this->addSql('ALTER TABLE mill.board ADD CONSTRAINT FK_4FD10382239D68EF FOREIGN KEY (nom_thickness) REFERENCES mill.thickness (nom) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mill.board ADD CONSTRAINT FK_4FD10382B07643D2 FOREIGN KEY (nom_width) REFERENCES mill.width (nom) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mill.board ADD CONSTRAINT FK_4FD10382F51C341B FOREIGN KEY (nom_length) REFERENCES mill.length (value) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mill.board ADD CONSTRAINT FK_4FD10382569EB619 FOREIGN KEY (qual_list_id) REFERENCES mill.quality_list (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mill.board ADD CONSTRAINT FK_4FD10382B2A1D860 FOREIGN KEY (species_id) REFERENCES dic.species (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE mill.board');
    }
}
