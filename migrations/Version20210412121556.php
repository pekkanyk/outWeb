<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210412121556 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE outlet_tuote (out_id INT NOT NULL, pid INT NOT NULL, name VARCHAR(255) NOT NULL, out_price DOUBLE PRECISION NOT NULL, nor_price DOUBLE PRECISION NOT NULL, price_updated_date DATE NOT NULL, updated BOOLEAN NOT NULL, poistotuote BOOLEAN NOT NULL, dumppituote BOOLEAN NOT NULL, alennus DOUBLE PRECISION NOT NULL, warranty INT NOT NULL, condition VARCHAR(255) NOT NULL, deleted DATE DEFAULT NULL, kampanja BOOLEAN NOT NULL, kamploppuu DATE DEFAULT NULL, first_seen DATE NOT NULL, varastossa INT DEFAULT NULL, on_varasto BOOLEAN NOT NULL, koko VARCHAR(255) DEFAULT NULL, pid_luotu DATE DEFAULT NULL, PRIMARY KEY(out_id))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE outlet_tuote');
    }
}
