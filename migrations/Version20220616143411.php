<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220616143411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE child_choice DROP FOREIGN KEY FK_A8FE9ECE998666D1');
        $this->addSql('DROP TABLE child_choice');
        $this->addSql('DROP TABLE choice');
        $this->addSql('DROP TABLE hello');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A0905086');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499032262A');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499032262A FOREIGN KEY (contact_urgence_id) REFERENCES contact_urgence (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE child_choice (id INT AUTO_INCREMENT NOT NULL, choice_id INT DEFAULT NULL, alive TINYINT(1) NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_A8FE9ECE998666D1 (choice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE choice (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE hello (id INT AUTO_INCREMENT NOT NULL, created_at DATE NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, expires_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE child_choice ADD CONSTRAINT FK_A8FE9ECE998666D1 FOREIGN KEY (choice_id) REFERENCES choice (id)');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6499032262A');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649A0905086');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6499032262A FOREIGN KEY (contact_urgence_id) REFERENCES contact_urgence (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
    }
}
