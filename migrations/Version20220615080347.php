<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615080347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poste DROP FOREIGN KEY FK_7C890FAB86AF3011');
        $this->addSql('ALTER TABLE poste ADD CONSTRAINT FK_7C890FAB86AF3011 FOREIGN KEY (superieur_id) REFERENCES poste (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poste DROP FOREIGN KEY FK_7C890FAB86AF3011');
        $this->addSql('ALTER TABLE poste ADD CONSTRAINT FK_7C890FAB86AF3011 FOREIGN KEY (superieur_id) REFERENCES poste (id)');
    }
}
