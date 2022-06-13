<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613190235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_role_admin (admin_role_id INT NOT NULL, admin_id INT NOT NULL, INDEX IDX_B7EC43DC123FA025 (admin_role_id), INDEX IDX_B7EC43DC642B8210 (admin_id), PRIMARY KEY(admin_role_id, admin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin_role_admin ADD CONSTRAINT FK_B7EC43DC123FA025 FOREIGN KEY (admin_role_id) REFERENCES admin_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE admin_role_admin ADD CONSTRAINT FK_B7EC43DC642B8210 FOREIGN KEY (admin_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_role_admin DROP FOREIGN KEY FK_B7EC43DC123FA025');
        $this->addSql('DROP TABLE admin_role');
        $this->addSql('DROP TABLE admin_role_admin');
        $this->addSql('DROP TABLE permission');
    }
}
