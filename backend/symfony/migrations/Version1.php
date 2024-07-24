<?php
declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version1 extends AbstractMigration
{
public function getDescription(): string
{
return 'Create users table';
}

public function up(Schema $schema): void
{
$this->addSql('CREATE TABLE users (
userid INT AUTO_INCREMENT NOT NULL,
email VARCHAR(150) NOT NULL,
name VARCHAR(50) NOT NULL,
phone VARCHAR(50) NOT NULL,
created_by INT NOT NULL,
created_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
updated_by INT NOT NULL,
updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
PRIMARY KEY(userid)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
$this->addSql('CREATE UNIQUE INDEX IDX_users_email ON users (email)');
$this->addSql('CREATE INDEX IDX_users_created_by ON users(created_by)');
$this->addSql('CREATE INDEX IDX_users_updated_by ON users(updated_by)');
}

public function down(Schema $schema): void
{
$this->addSql('DROP TABLE users');
}
}