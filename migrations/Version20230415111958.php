<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230415111958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pumping_activities (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', pumping_parent_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', child_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', breast LONGTEXT NOT NULL, amount LONGTEXT DEFAULT NULL, start_time LONGTEXT NOT NULL, end_time LONGTEXT DEFAULT NULL, break_duration LONGTEXT DEFAULT NULL, note LONGTEXT DEFAULT NULL, INDEX IDX_3F57A1F9B1BD3A6B (pumping_parent_id), INDEX IDX_3F57A1F9DD62C21B (child_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pumping_activities ADD CONSTRAINT FK_3F57A1F9B1BD3A6B FOREIGN KEY (pumping_parent_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE pumping_activities ADD CONSTRAINT FK_3F57A1F9DD62C21B FOREIGN KEY (child_id) REFERENCES children (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pumping_activities DROP FOREIGN KEY FK_3F57A1F9B1BD3A6B');
        $this->addSql('ALTER TABLE pumping_activities DROP FOREIGN KEY FK_3F57A1F9DD62C21B');
        $this->addSql('DROP TABLE pumping_activities');
    }
}
