<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220111132205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE space ADD description LONGTEXT DEFAULT NULL, ADD disponibility JSON NOT NULL, DROP price, CHANGE surface surface VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE user ADD job VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE space ADD price INT NOT NULL, DROP description, DROP disponibility, CHANGE surface surface INT NOT NULL');
        $this->addSql('ALTER TABLE user DROP job');
    }
}
