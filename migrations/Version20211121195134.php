<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211121195134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company_owner (id INTEGER NOT NULL, company_id INTEGER DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88914419979B1AD6 ON company_owner (company_id)');
        $this->addSql('DROP INDEX IDX_288A3A4E979B1AD6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__job_offer AS SELECT id, company_id, title FROM job_offer');
        $this->addSql('DROP TABLE job_offer');
        $this->addSql('CREATE TABLE job_offer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, company_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_288A3A4E979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO job_offer (id, company_id, title) SELECT id, company_id, title FROM __temp__job_offer');
        $this->addSql('DROP TABLE __temp__job_offer');
        $this->addSql('CREATE INDEX IDX_288A3A4E979B1AD6 ON job_offer (company_id)');
        $this->addSql('DROP INDEX IDX_82F422B297139001');
        $this->addSql('DROP INDEX IDX_82F422B23481D195');
        $this->addSql('CREATE TEMPORARY TABLE __temp__job_offer_applicant AS SELECT job_offer_id, applicant_id FROM job_offer_applicant');
        $this->addSql('DROP TABLE job_offer_applicant');
        $this->addSql('CREATE TABLE job_offer_applicant (job_offer_id INTEGER NOT NULL, applicant_id INTEGER NOT NULL, PRIMARY KEY(job_offer_id, applicant_id), CONSTRAINT FK_82F422B23481D195 FOREIGN KEY (job_offer_id) REFERENCES job_offer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_82F422B297139001 FOREIGN KEY (applicant_id) REFERENCES applicant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO job_offer_applicant (job_offer_id, applicant_id) SELECT job_offer_id, applicant_id FROM __temp__job_offer_applicant');
        $this->addSql('DROP TABLE __temp__job_offer_applicant');
        $this->addSql('CREATE INDEX IDX_82F422B297139001 ON job_offer_applicant (applicant_id)');
        $this->addSql('CREATE INDEX IDX_82F422B23481D195 ON job_offer_applicant (job_offer_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL COLLATE BINARY, roles CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        , password VARCHAR(255) NOT NULL COLLATE BINARY, type DEFAULT \'APP\')');
        $this->addSql('INSERT INTO user (id, email, roles, password) SELECT id, email, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE company_owner');
        $this->addSql('DROP INDEX IDX_288A3A4E979B1AD6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__job_offer AS SELECT id, company_id, title FROM job_offer');
        $this->addSql('DROP TABLE job_offer');
        $this->addSql('CREATE TABLE job_offer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, company_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO job_offer (id, company_id, title) SELECT id, company_id, title FROM __temp__job_offer');
        $this->addSql('DROP TABLE __temp__job_offer');
        $this->addSql('CREATE INDEX IDX_288A3A4E979B1AD6 ON job_offer (company_id)');
        $this->addSql('DROP INDEX IDX_82F422B23481D195');
        $this->addSql('DROP INDEX IDX_82F422B297139001');
        $this->addSql('CREATE TEMPORARY TABLE __temp__job_offer_applicant AS SELECT job_offer_id, applicant_id FROM job_offer_applicant');
        $this->addSql('DROP TABLE job_offer_applicant');
        $this->addSql('CREATE TABLE job_offer_applicant (job_offer_id INTEGER NOT NULL, applicant_id INTEGER NOT NULL, PRIMARY KEY(job_offer_id, applicant_id))');
        $this->addSql('INSERT INTO job_offer_applicant (job_offer_id, applicant_id) SELECT job_offer_id, applicant_id FROM __temp__job_offer_applicant');
        $this->addSql('DROP TABLE __temp__job_offer_applicant');
        $this->addSql('CREATE INDEX IDX_82F422B23481D195 ON job_offer_applicant (job_offer_id)');
        $this->addSql('CREATE INDEX IDX_82F422B297139001 ON job_offer_applicant (applicant_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password) SELECT id, email, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
