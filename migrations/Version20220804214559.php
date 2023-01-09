<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220804214559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE piece ADD id INT AUTO_INCREMENT NOT NULL, CHANGE drawingn drawingn VARCHAR(50) DEFAULT NULL, CHANGE productionn productionn INT DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE piece MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE piece DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE piece DROP id, CHANGE drawingn drawingn VARCHAR(50) NOT NULL, CHANGE productionn productionn INT NOT NULL, CHANGE description description TEXT NOT NULL');
    }
}
