<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615172808 extends AbstractMigration
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
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A38E0E3CA6');
        $this->addSql('DROP INDEX IDX_2DE8C6A38E0E3CA6 ON user_role');
        $this->addSql('ALTER TABLE user_role CHANGE user_role_id parent_role_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A44B56EA FOREIGN KEY (parent_role_id) REFERENCES user_role (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_2DE8C6A3A44B56EA ON user_role (parent_role_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poste DROP FOREIGN KEY FK_7C890FAB86AF3011');
        $this->addSql('ALTER TABLE poste ADD CONSTRAINT FK_7C890FAB86AF3011 FOREIGN KEY (superieur_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A44B56EA');
        $this->addSql('DROP INDEX IDX_2DE8C6A3A44B56EA ON user_role');
        $this->addSql('ALTER TABLE user_role CHANGE parent_role_id user_role_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A38E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_role (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_2DE8C6A38E0E3CA6 ON user_role (user_role_id)');
    }
}
