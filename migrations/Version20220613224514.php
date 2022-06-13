<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613224514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_role ADD user_role_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A38E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_role (id)');
        $this->addSql('CREATE INDEX IDX_2DE8C6A38E0E3CA6 ON user_role (user_role_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A38E0E3CA6');
        $this->addSql('DROP INDEX IDX_2DE8C6A38E0E3CA6 ON user_role');
        $this->addSql('ALTER TABLE user_role DROP user_role_id');
    }
}
