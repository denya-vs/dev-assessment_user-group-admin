<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create groups table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE `groups` (
            groupid SMALLINT AUTO_INCREMENT NOT NULL, 
            groupname VARCHAR(100) NOT NULL, 
            created_by INT NOT NULL, 
            created_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_by INT NOT NULL,
            updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, 
            PRIMARY KEY(groupid)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE UNIQUE INDEX groupname ON `groups` (groupname)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE groups');
    }
}