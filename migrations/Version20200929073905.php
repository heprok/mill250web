<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200929073905 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Ошибки';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA mill');
        $this->addSql('CREATE TABLE mill.error (id SMALLINT NOT NULL, text VARCHAR(128) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON TABLE mill.error IS \'Ошибки\'');
        $this->addSql('COMMENT ON COLUMN mill.error.id IS \'Код ошибки\'');
        $this->addSql('COMMENT ON COLUMN mill.error.text IS \'Текст ошибки\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE mill.error');
    }
}
