<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220330170232 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE lavapaikka_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE lavapaikka (id INT NOT NULL, kaytava INT NOT NULL, vali INT NOT NULL, taso INT NOT NULL, reuna VARCHAR(255) NOT NULL, usable BOOLEAN NOT NULL, usage INT DEFAULT NULL, sisalto VARCHAR(511) DEFAULT NULL, updated DATE DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE lavapaikka_id_seq CASCADE');
        $this->addSql('DROP TABLE lavapaikka');
    }
}
