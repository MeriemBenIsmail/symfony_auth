<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613201006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin_role_permission (admin_role_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_53AD1461123FA025 (admin_role_id), INDEX IDX_53AD1461FED90CCA (permission_id), PRIMARY KEY(admin_role_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin_role_permission ADD CONSTRAINT FK_53AD1461123FA025 FOREIGN KEY (admin_role_id) REFERENCES admin_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE admin_role_permission ADD CONSTRAINT FK_53AD1461FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE admin_role_permission');
    }
}
