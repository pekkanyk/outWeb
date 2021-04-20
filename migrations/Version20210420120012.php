<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210420120012 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pid_info (pid INT NOT NULL, width INT DEFAULT NULL, depth INT DEFAULT NULL, weight INT DEFAULT NULL, height INT DEFAULT NULL, volume INT DEFAULT NULL, PRIMARY KEY(pid))');
        $this->addSql('ALTER TABLE outlet_tuote ALTER out_price DROP NOT NULL');
        $this->addSql('ALTER TABLE outlet_tuote ALTER price_updated_date DROP NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE pid_info');
        $this->addSql('ALTER TABLE outlet_tuote ALTER out_price SET NOT NULL');
        $this->addSql('ALTER TABLE outlet_tuote ALTER price_updated_date SET NOT NULL');
    }
}
