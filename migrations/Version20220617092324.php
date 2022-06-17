<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220617092324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demand ADD employe_id INT DEFAULT NULL, DROP employe');
        $this->addSql('ALTER TABLE demand ADD CONSTRAINT FK_428D79731B65292 FOREIGN KEY (employe_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_428D79731B65292 ON demand (employe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demand DROP FOREIGN KEY FK_428D79731B65292');
        $this->addSql('DROP INDEX IDX_428D79731B65292 ON demand');
        $this->addSql('ALTER TABLE demand ADD employe VARCHAR(255) NOT NULL, DROP employe_id');
    }
}
