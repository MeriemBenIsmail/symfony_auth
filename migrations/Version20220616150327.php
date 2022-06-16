<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220616150327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE leave_right (id INT AUTO_INCREMENT NOT NULL, employe_id INT DEFAULT NULL, leave_type_id INT DEFAULT NULL, balance DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, unit DOUBLE PRECISION NOT NULL, start_validity_date DATE NOT NULL, end_validity_date DATE NOT NULL, INDEX IDX_711E2CBE1B65292 (employe_id), INDEX IDX_711E2CBE8313F474 (leave_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE leave_type (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, annual TINYINT(1) NOT NULL, validity_duration DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request (id INT AUTO_INCREMENT NOT NULL, leave_type_id INT DEFAULT NULL, employe_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, motive VARCHAR(255) NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, INDEX IDX_3B978F9F8313F474 (leave_type_id), INDEX IDX_3B978F9F1B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE leave_right ADD CONSTRAINT FK_711E2CBE1B65292 FOREIGN KEY (employe_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE leave_right ADD CONSTRAINT FK_711E2CBE8313F474 FOREIGN KEY (leave_type_id) REFERENCES leave_type (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F8313F474 FOREIGN KEY (leave_type_id) REFERENCES leave_type (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F1B65292 FOREIGN KEY (employe_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE leave_right DROP FOREIGN KEY FK_711E2CBE8313F474');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F8313F474');
        $this->addSql('DROP TABLE leave_right');
        $this->addSql('DROP TABLE leave_type');
        $this->addSql('DROP TABLE request');
    }
}
