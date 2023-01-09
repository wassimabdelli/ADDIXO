<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220805183909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pieces (id INT AUTO_INCREMENT NOT NULL, prductnumber INT NOT NULL, societe VARCHAR(50) DEFAULT NULL, date DATE NOT NULL, utilisateur VARCHAR(50) NOT NULL, drawingnumber VARCHAR(50) DEFAULT NULL, productionnum INT DEFAULT NULL, typekeylist INT NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, fname VARCHAR(50) NOT NULL, lname VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, password VARCHAR(50) NOT NULL, email VARCHAR(50) DEFAULT NULL, ville VARCHAR(50) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pieces');
        $this->addSql('DROP TABLE utilisateur');
    }
}
