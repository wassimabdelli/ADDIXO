<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220804161538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD id INT AUTO_INCREMENT NOT NULL, DROP lname, DROP fname, DROP email, DROP description, DROP city, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user ADD lname VARCHAR(50) NOT NULL, ADD fname VARCHAR(50) NOT NULL, ADD email VARCHAR(50) NOT NULL, ADD description VARCHAR(50) NOT NULL, ADD city VARCHAR(50) NOT NULL, DROP id');
    }
}
