<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190311222656 extends AbstractMigration
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

        $this->addSql('CREATE TABLE comment_account (comment_id INT NOT NULL, account_id INT NOT NULL, INDEX IDX_8E4534B4F8697D13 (comment_id), INDEX IDX_8E4534B49B6B5FBA (account_id), PRIMARY KEY(comment_id, account_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_account ADD CONSTRAINT FK_8E4534B4F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_account ADD CONSTRAINT FK_8E4534B49B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CB4622EC2');
        $this->addSql('DROP INDEX IDX_9474526CB4622EC2 ON comment');
        $this->addSql('ALTER TABLE comment DROP liked_by_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE comment_account');
        $this->addSql('ALTER TABLE comment ADD liked_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB4622EC2 FOREIGN KEY (liked_by_id) REFERENCES account (id)');
        $this->addSql('CREATE INDEX IDX_9474526CB4622EC2 ON comment (liked_by_id)');
    }
}
