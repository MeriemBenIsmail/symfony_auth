<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613215510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_role_admin DROP FOREIGN KEY FK_B7EC43DC123FA025');
        $this->addSql('ALTER TABLE admin_role_permission DROP FOREIGN KEY FK_53AD1461123FA025');
        $this->addSql('ALTER TABLE admin_role_permission DROP FOREIGN KEY FK_53AD1461FED90CCA');
        $this->addSql('CREATE TABLE group_user (group_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A4C98D39FE54D947 (group_id), INDEX IDX_A4C98D39A76ED395 (user_id), PRIMARY KEY(group_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role_user (user_role_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_33CC29398E0E3CA6 (user_role_id), INDEX IDX_33CC2939A76ED395 (user_id), PRIMARY KEY(user_role_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT FK_A4C98D39FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT FK_A4C98D39A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role_user ADD CONSTRAINT FK_33CC29398E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role_user ADD CONSTRAINT FK_33CC2939A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE admin_role');
        $this->addSql('DROP TABLE admin_role_admin');
        $this->addSql('DROP TABLE admin_role_permission');
        $this->addSql('DROP TABLE group_admin');
        $this->addSql('DROP TABLE permission');
        $this->addSql('ALTER TABLE user ADD super TINYINT(1) NOT NULL, DROP is_super');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_role_user DROP FOREIGN KEY FK_33CC29398E0E3CA6');
        $this->addSql('CREATE TABLE admin_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE admin_role_admin (admin_role_id INT NOT NULL, admin_id INT NOT NULL, INDEX IDX_B7EC43DC642B8210 (admin_id), INDEX IDX_B7EC43DC123FA025 (admin_role_id), PRIMARY KEY(admin_role_id, admin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE admin_role_permission (admin_role_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_53AD1461FED90CCA (permission_id), INDEX IDX_53AD1461123FA025 (admin_role_id), PRIMARY KEY(admin_role_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE group_admin (group_id INT NOT NULL, admin_id INT NOT NULL, INDEX IDX_D8222611642B8210 (admin_id), INDEX IDX_D8222611FE54D947 (group_id), PRIMARY KEY(group_id, admin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE admin_role_admin ADD CONSTRAINT FK_B7EC43DC642B8210 FOREIGN KEY (admin_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE admin_role_admin ADD CONSTRAINT FK_B7EC43DC123FA025 FOREIGN KEY (admin_role_id) REFERENCES admin_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE admin_role_permission ADD CONSTRAINT FK_53AD1461FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE admin_role_permission ADD CONSTRAINT FK_53AD1461123FA025 FOREIGN KEY (admin_role_id) REFERENCES admin_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_admin ADD CONSTRAINT FK_D8222611FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_admin ADD CONSTRAINT FK_D8222611642B8210 FOREIGN KEY (admin_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE group_user');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE user_role_user');
        $this->addSql('ALTER TABLE `user` ADD is_super TINYINT(1) DEFAULT NULL, DROP super');
    }
}
