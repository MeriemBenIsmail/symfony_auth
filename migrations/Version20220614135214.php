<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220614135214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact_urgence (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, lien VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_user_role (group_id INT NOT NULL, user_role_id INT NOT NULL, INDEX IDX_ED5B9A42FE54D947 (group_id), INDEX IDX_ED5B9A428E0E3CA6 (user_role_id), PRIMARY KEY(group_id, user_role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_user_role ADD CONSTRAINT FK_ED5B9A42FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_user_role ADD CONSTRAINT FK_ED5B9A428E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A38E0E3CA6');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A38E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_role (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contact_urgence');
        $this->addSql('DROP TABLE group_user_role');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A38E0E3CA6');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A38E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_role (id)');
    }
}
