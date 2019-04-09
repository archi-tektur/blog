<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190409165447 extends AbstractMigration
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

        $this->addSql('ALTER TABLE article_account ADD CONSTRAINT FK_90E412139B6B5FBA FOREIGN KEY (account_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fos_user ADD api_partial_key VARCHAR(64) NOT NULL, CHANGE profile_image profile_image VARCHAR(128) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_account DROP FOREIGN KEY FK_90E412139B6B5FBA');
        $this->addSql('ALTER TABLE fos_user DROP api_partial_key, CHANGE profile_image profile_image VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
