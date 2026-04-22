<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to make user_id NOT NULL in tasks table
 */
final class Version20260422000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make user_id NOT NULL in tasks table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tasks MODIFY user_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tasks MODIFY user_id INT NULL');
    }
}
