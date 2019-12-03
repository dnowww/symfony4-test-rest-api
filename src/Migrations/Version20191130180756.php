<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191130180756 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE tbl_article_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tbl_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tbl_article (id INT NOT NULL, title VARCHAR(255) NOT NULL, text TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX text ON tbl_article (text)');
        $this->addSql('CREATE INDEX title ON tbl_article (title)');
        $this->addSql('CREATE TABLE tbl_article_category (article_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(article_id, category_id))');
        $this->addSql('CREATE INDEX IDX_597A6FD87294869C ON tbl_article_category (article_id)');
        $this->addSql('CREATE INDEX IDX_597A6FD812469DE2 ON tbl_article_category (category_id)');
        $this->addSql('CREATE TABLE tbl_category (id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX created_at ON tbl_category (created_at)');
        $this->addSql('CREATE INDEX name ON tbl_category (name)');
        $this->addSql('CREATE TABLE ext_log_entries (id INT NOT NULL, action VARCHAR(8) NOT NULL, logged_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data TEXT DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX log_class_lookup_idx ON ext_log_entries (object_class)');
        $this->addSql('CREATE INDEX log_date_lookup_idx ON ext_log_entries (logged_at)');
        $this->addSql('CREATE INDEX log_user_lookup_idx ON ext_log_entries (username)');
        $this->addSql('CREATE INDEX log_version_lookup_idx ON ext_log_entries (object_id, object_class, version)');
        $this->addSql('COMMENT ON COLUMN ext_log_entries.data IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE tbl_article_category ADD CONSTRAINT FK_597A6FD87294869C FOREIGN KEY (article_id) REFERENCES tbl_article (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tbl_article_category ADD CONSTRAINT FK_597A6FD812469DE2 FOREIGN KEY (category_id) REFERENCES tbl_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tbl_article_category DROP CONSTRAINT FK_597A6FD87294869C');
        $this->addSql('ALTER TABLE tbl_article_category DROP CONSTRAINT FK_597A6FD812469DE2');
        $this->addSql('DROP SEQUENCE tbl_article_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tbl_category_id_seq CASCADE');
        $this->addSql('DROP TABLE tbl_article');
        $this->addSql('DROP TABLE tbl_article_category');
        $this->addSql('DROP TABLE tbl_category');
        $this->addSql('DROP TABLE ext_log_entries');
    }
}
