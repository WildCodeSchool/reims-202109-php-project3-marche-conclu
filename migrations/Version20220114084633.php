<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220114084633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE slot (id INT AUTO_INCREMENT NOT NULL, space_id INT NOT NULL, owner_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, slot_time DATE NOT NULL, INDEX IDX_AC0E206723575340 (space_id), INDEX IDX_AC0E20677E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE space (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name VARCHAR(100) NOT NULL, photos VARCHAR(500) DEFAULT NULL, surface INT NOT NULL, category VARCHAR(50) NOT NULL, capacity INT NOT NULL, location VARCHAR(500) NOT NULL, price INT NOT NULL, description LONGTEXT DEFAULT NULL, address VARCHAR(255) NOT NULL, INDEX IDX_2972C13A7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE space_disponibility (id INT AUTO_INCREMENT NOT NULL, space_id INT DEFAULT NULL, monday VARCHAR(100) NOT NULL, tuesday VARCHAR(100) NOT NULL, wednesday VARCHAR(100) NOT NULL, thursday VARCHAR(100) NOT NULL, friday VARCHAR(100) NOT NULL, saturday VARCHAR(100) NOT NULL, sunday VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_1BDB8F6123575340 (space_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, avatar VARCHAR(500) DEFAULT NULL, company VARCHAR(255) DEFAULT NULL, job VARCHAR(50) DEFAULT NULL, phone_number VARCHAR(15) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E206723575340 FOREIGN KEY (space_id) REFERENCES space (id)');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E20677E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE space ADD CONSTRAINT FK_2972C13A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE space_disponibility ADD CONSTRAINT FK_1BDB8F6123575340 FOREIGN KEY (space_id) REFERENCES space (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY FK_AC0E206723575340');
        $this->addSql('ALTER TABLE space_disponibility DROP FOREIGN KEY FK_1BDB8F6123575340');
        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY FK_AC0E20677E3C61F9');
        $this->addSql('ALTER TABLE space DROP FOREIGN KEY FK_2972C13A7E3C61F9');
        $this->addSql('DROP TABLE slot');
        $this->addSql('DROP TABLE space');
        $this->addSql('DROP TABLE space_disponibility');
        $this->addSql('DROP TABLE user');
    }
}
