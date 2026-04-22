<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to add user relationship to tasks table
 */
final class Version20260422000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user foreign key relationship to tasks table';
    }

    public function up(Schema $schema): void
    {
        // Add user_id foreign key column to tasks table
        $this->addSql('ALTER TABLE tasks ADD COLUMN user_id INT NULL DEFAULT NULL');
        
        // Add foreign key constraint
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // Remove foreign key and column
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597A76ED395');
        $this->addSql('ALTER TABLE tasks DROP COLUMN user_id');
    }
}
