<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190228201743 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE access_token DROP FOREIGN KEY FK_B6A2DD6819EB6921');
        $this->addSql('ALTER TABLE auth_code DROP FOREIGN KEY FK_5933D02C19EB6921');
        $this->addSql('ALTER TABLE refresh_token DROP FOREIGN KEY FK_C74F219519EB6921');
        $this->addSql('ALTER TABLE access_token DROP FOREIGN KEY FK_B6A2DD68A76ED395');
        $this->addSql('ALTER TABLE auth_code DROP FOREIGN KEY FK_5933D02CA76ED395');
        $this->addSql('ALTER TABLE refresh_token DROP FOREIGN KEY FK_C74F2195A76ED395');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(100) DEFAULT NULL, date_of_birth DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE access_token');
        $this->addSql('DROP TABLE auth_code');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('ALTER TABLE article CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE access_token (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, INDEX IDX_B6A2DD68A76ED395 (user_id), INDEX IDX_B6A2DD6819EB6921 (client_id), UNIQUE INDEX UNIQ_B6A2DD685F37A13B (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE auth_code (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, redirect_uri LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, INDEX IDX_5933D02CA76ED395 (user_id), INDEX IDX_5933D02C19EB6921 (client_id), UNIQUE INDEX UNIQ_5933D02C5F37A13B (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, random_id VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, redirect_uris LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', secret VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, allowed_grant_types LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, username_canonical VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, email_canonical VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, last_login DATETIME DEFAULT \'NULL\', confirmation_token VARCHAR(180) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, password_requested_at DATETIME DEFAULT \'NULL\', roles LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', first_name VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, last_name VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, birthdate DATE NOT NULL, UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE refresh_token (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, INDEX IDX_C74F2195A76ED395 (user_id), INDEX IDX_C74F219519EB6921 (client_id), UNIQUE INDEX UNIQ_C74F21955F37A13B (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE access_token ADD CONSTRAINT FK_B6A2DD6819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE access_token ADD CONSTRAINT FK_B6A2DD68A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE auth_code ADD CONSTRAINT FK_5933D02C19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE auth_code ADD CONSTRAINT FK_5933D02CA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE refresh_token ADD CONSTRAINT FK_C74F219519EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE refresh_token ADD CONSTRAINT FK_C74F2195A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE article CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
    }
}
