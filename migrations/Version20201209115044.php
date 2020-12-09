<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201209115044 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('COMMENT ON COLUMN mill.action_operator.name IS \'Название действия\'');
        $this->addSql('ALTER TABLE mill.downtime_cause RENAME COLUMN name TO text');
        $this->addSql('ALTER TABLE mill.downtime_place RENAME COLUMN name TO text');

        $this->addSql('COMMENT ON COLUMN mill.length.minimum IS \'Минимальная граница диапзаона не включая, мм\'');
        $this->addSql('COMMENT ON COLUMN mill.length.maximum IS \'Максимальная граница диапзаона не включая, мм\'');

        $this->addSql('COMMENT ON COLUMN mill.people.fam IS \'Фамилия\'');
        $this->addSql('COMMENT ON COLUMN mill.people.nam IS \'Имя\'');
        $this->addSql('COMMENT ON COLUMN mill.people.pat IS \'Отчество\'');
        
        $this->addSql('COMMENT ON COLUMN mill.shift.start IS \'Время начала смены\'');
        $this->addSql('COMMENT ON COLUMN mill.shift.stop IS \'Окончание смены\'');


    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE mill.downtime_cause RENAME COLUMN text TO name');
        $this->addSql('ALTER TABLE mill.downtime_place RENAME COLUMN text TO name');
    }
}
