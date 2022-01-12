<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220111135201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE space_disponibility (id INT AUTO_INCREMENT NOT NULL, space_id INT DEFAULT NULL, monday VARCHAR(100) NOT NULL, tuesday VARCHAR(100) NOT NULL, wednesday VARCHAR(100) NOT NULL, thursday VARCHAR(100) NOT NULL, friday VARCHAR(100) NOT NULL, saturday VARCHAR(100) NOT NULL, sunday VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_1BDB8F6123575340 (space_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE space_disponibility ADD CONSTRAINT FK_1BDB8F6123575340 FOREIGN KEY (space_id) REFERENCES space (id)');
        $this->addSql('ALTER TABLE space DROP disponibility');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE space_disponibility');
        $this->addSql('ALTER TABLE space ADD disponibility JSON NOT NULL');
    }
}
