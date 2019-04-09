<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190409164914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_account DROP FOREIGN KEY FK_90E412139B6B5FBA');
        $this->addSql('DROP TABLE account');
        $this->addSql('ALTER TABLE article_account DROP FOREIGN KEY FK_90E412139B6B5FBA');
        $this->addSql('ALTER TABLE article_account ADD CONSTRAINT FK_90E412139B6B5FBA FOREIGN KEY (account_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fos_user ADD api_partial_key VARCHAR(64) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, roles JSON NOT NULL, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, name VARCHAR(32) NOT NULL COLLATE utf8mb4_unicode_ci, surname VARCHAR(64) NOT NULL COLLATE utf8mb4_unicode_ci, profile_image VARCHAR(128) DEFAULT NULL COLLATE utf8mb4_unicode_ci, api_partial_key VARCHAR(64) NOT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_7D3656A45043502C (api_partial_key), UNIQUE INDEX UNIQ_7D3656A4E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_account DROP FOREIGN KEY FK_90E412139B6B5FBA');
        $this->addSql('ALTER TABLE article_account ADD CONSTRAINT FK_90E412139B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fos_user DROP api_partial_key');
    }
}
