<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615165626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD contact_urgence_id INT DEFAULT NULL, ADD poste_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499032262A FOREIGN KEY (contact_urgence_id) REFERENCES contact_urgence (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6499032262A ON user (contact_urgence_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649A0905086 ON user (poste_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6499032262A');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649A0905086');
        $this->addSql('DROP INDEX IDX_8D93D6499032262A ON `user`');
        $this->addSql('DROP INDEX IDX_8D93D649A0905086 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP contact_urgence_id, DROP poste_id');
    }
}
