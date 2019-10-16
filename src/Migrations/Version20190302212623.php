<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190302212623 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE auth_tokens (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_8AF9B66CA76ED395 (user_id), UNIQUE INDEX auth_tokens_value_unique (value), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE auth_tokens ADD CONSTRAINT FK_8AF9B66CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE first_name first_name VARCHAR(100) DEFAULT NULL, CHANGE last_name last_name VARCHAR(100) DEFAULT NULL, CHANGE date_of_birth date_of_birth DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE auth_tokens');
        $this->addSql('ALTER TABLE article CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE first_name first_name VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_name last_name VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE date_of_birth date_of_birth DATETIME DEFAULT \'NULL\'');
    }
}
