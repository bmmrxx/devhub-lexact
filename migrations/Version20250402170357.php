<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250402170357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add visibility column to project table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project ADD visibility VARCHAR(20) DEFAULT \'INTERN\' NOT NULL');

        // Optioneel: converteer bestaande boolean naar enum waarden
        $this->addSql("UPDATE project SET visibility = CASE 
            WHEN visibility = 1 THEN 'ADMIN'
            ELSE 'INTERN' END");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project DROP visibility');
    }
}