<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211030040042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE applicant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', location VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_applicant (id INT AUTO_INCREMENT NOT NULL, job_id INT NOT NULL, applicant_id INT NOT NULL, INDEX IDX_D1DFF08BE04EA9 (job_id), INDEX IDX_D1DFF0897139001 (applicant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_applicant ADD CONSTRAINT FK_D1DFF08BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE job_applicant ADD CONSTRAINT FK_D1DFF0897139001 FOREIGN KEY (applicant_id) REFERENCES applicant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_applicant DROP FOREIGN KEY FK_D1DFF0897139001');
        $this->addSql('ALTER TABLE job_applicant DROP FOREIGN KEY FK_D1DFF08BE04EA9');
        $this->addSql('DROP TABLE applicant');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE job_applicant');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
