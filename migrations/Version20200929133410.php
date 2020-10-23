<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200929133410 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE mill.people_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        
        $this->addSql('CREATE TABLE mill.action_operator (id SMALLINT NOT NULL, name VARCHAR(128) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON TABLE mill.action_operator IS \'Действия оператора\'');
        $this->addSql('CREATE TABLE mill.people (id INT NOT NULL, fam VARCHAR(30) NOT NULL, nam VARCHAR(30) DEFAULT NULL, pat VARCHAR(30) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON TABLE mill.people IS \'Люди\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE mill.action_operator_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mill.people_id_seq CASCADE');
        $this->addSql('DROP TABLE mill.action_operator');
        $this->addSql('DROP TABLE mill.people');
    }
}
