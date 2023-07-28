<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230728123510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE temperature_measuring_activity RENAME TO temperature_measuring_activities');
        $this->addSql('ALTER TABLE temperature_measuring_activities RENAME INDEX idx_d5c6149edd62c21b TO IDX_7961A8C4DD62C21B');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE temperature_measuring_activities RENAME TO temperature_measuring_activity');
        $this->addSql('ALTER TABLE temperature_measuring_activities RENAME INDEX idx_7961a8c4dd62c21b TO IDX_D5C6149EDD62C21B');
    }
}
