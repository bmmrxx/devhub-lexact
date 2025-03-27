<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327140630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_project (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_77BECEE4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_project ADD CONSTRAINT FK_77BECEE4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE visibility_file_user DROP FOREIGN KEY FK_6DFF551393CB796C');
        $this->addSql('ALTER TABLE visibility_file_user DROP FOREIGN KEY FK_6DFF5513A76ED395');
        $this->addSql('ALTER TABLE intern_upload DROP FOREIGN KEY FK_5692405FA76ED395');
        $this->addSql('ALTER TABLE code_snippet DROP FOREIGN KEY FK_AEBBC849A76ED395');
        $this->addSql('ALTER TABLE project_file DROP FOREIGN KEY FK_B50EFE08166D1F9C');
        $this->addSql('ALTER TABLE project_file DROP FOREIGN KEY FK_B50EFE0893CB796C');
        $this->addSql('DROP TABLE visibility_file_user');
        $this->addSql('DROP TABLE intern_upload');
        $this->addSql('DROP TABLE code_snippet');
        $this->addSql('DROP TABLE project_file');
        $this->addSql('ALTER TABLE file ADD project_id INT UNSIGNED NOT NULL, ADD file_path LONGTEXT NOT NULL, ADD file_type VARCHAR(50) NOT NULL, CHANGE uploaded_at created_at DATE NOT NULL');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_8C9F3610166D1F9C ON file (project_id)');
        $this->addSql('ALTER TABLE note ADD catagory JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE visibility_file_user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, file_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_6DFF551393CB796C (file_id), INDEX IDX_6DFF5513A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE intern_upload (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATETIME NOT NULL, category VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_5692405FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE code_snippet (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, code LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, language VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_AEBBC849A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE project_file (file_id INT UNSIGNED NOT NULL, project_id INT UNSIGNED NOT NULL, INDEX IDX_B50EFE08166D1F9C (project_id), INDEX IDX_B50EFE0893CB796C (file_id), PRIMARY KEY(file_id, project_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE visibility_file_user ADD CONSTRAINT FK_6DFF551393CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE visibility_file_user ADD CONSTRAINT FK_6DFF5513A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE intern_upload ADD CONSTRAINT FK_5692405FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE code_snippet ADD CONSTRAINT FK_AEBBC849A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project_file ADD CONSTRAINT FK_B50EFE08166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_file ADD CONSTRAINT FK_B50EFE0893CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_project DROP FOREIGN KEY FK_77BECEE4A76ED395');
        $this->addSql('DROP TABLE user_project');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610166D1F9C');
        $this->addSql('DROP INDEX IDX_8C9F3610166D1F9C ON file');
        $this->addSql('ALTER TABLE file DROP project_id, DROP file_path, DROP file_type, CHANGE created_at uploaded_at DATE NOT NULL');
        $this->addSql('ALTER TABLE note DROP catagory');
    }
}
