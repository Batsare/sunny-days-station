<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190313213029 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE auth_tokens CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD profile_picture VARCHAR(255) NOT NULL, ADD image_size INT NOT NULL, ADD updated_at DATETIME NOT NULL, CHANGE roles roles JSON NOT NULL, CHANGE first_name first_name VARCHAR(100) DEFAULT NULL, CHANGE last_name last_name VARCHAR(100) DEFAULT NULL, CHANGE date_of_birth date_of_birth DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE auth_tokens CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP profile_picture, DROP image_size, DROP updated_at, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE first_name first_name VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_name last_name VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE date_of_birth date_of_birth DATETIME DEFAULT \'NULL\'');
    }
}
