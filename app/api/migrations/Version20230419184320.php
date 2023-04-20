<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230419184320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create company table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, siren_number VARCHAR(255) NOT NULL, registration_city VARCHAR(255) NOT NULL, registration_date DATE NOT NULL, capital DOUBLE PRECISION NOT NULL, created_date DATETIME NOT NULL, last_update_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_4FBF094FD07E39B8 (siren_number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE company');
    }
}
