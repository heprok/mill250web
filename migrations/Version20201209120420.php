<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201209120420 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE mill.quality_list_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE mill.postav_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mill.timber_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER INDEX mill.idx_8b6d12bd66e2221e RENAME TO IDX_7FDC324A66E2221E');
        $this->addSql('ALTER INDEX mill.idx_8b6d12bdda6a219 RENAME TO IDX_7FDC324ADA6A219');
        $this->addSql('ALTER INDEX mill.idx_2c2922628cde5729 RENAME TO IDX_3C2BDAD08CDE5729');
        $this->addSql('ALTER INDEX mill.idx_2c2922625f8a7f73 RENAME TO IDX_3C2BDAD05F8A7F73');
        $this->addSql('ALTER INDEX mill.idx_b28c13803147c936 RENAME TO IDX_A28EEB323147C936');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE mill.postav_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mill.timber_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE mill.quality_list_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
    }
}
