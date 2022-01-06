<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220105131458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE slot (id INT AUTO_INCREMENT NOT NULL, space_id INT NOT NULL, owner_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, slot_time DATE NOT NULL, INDEX IDX_AC0E206723575340 (space_id), INDEX IDX_AC0E20677E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE space (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name VARCHAR(100) NOT NULL, photos VARCHAR(500) NOT NULL, surface VARCHAR(10) NOT NULL, category VARCHAR(50) NOT NULL, capacity INT NOT NULL, location VARCHAR(500) NOT NULL, INDEX IDX_2972C13A7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, avatar VARCHAR(500) DEFAULT NULL, company VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E206723575340 FOREIGN KEY (space_id) REFERENCES space (id)');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E20677E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE space ADD CONSTRAINT FK_2972C13A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY FK_AC0E206723575340');
        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY FK_AC0E20677E3C61F9');
        $this->addSql('ALTER TABLE space DROP FOREIGN KEY FK_2972C13A7E3C61F9');
        $this->addSql('DROP TABLE slot');
        $this->addSql('DROP TABLE space');
        $this->addSql('DROP TABLE user');
    }
}
