<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230718082416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE weighing_activities (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', child_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', weight LONGTEXT NOT NULL, start_time LONGTEXT NOT NULL, end_time LONGTEXT DEFAULT NULL, break_duration LONGTEXT DEFAULT NULL, note LONGTEXT DEFAULT NULL, INDEX IDX_87FCA891DD62C21B (child_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE weighing_activities ADD CONSTRAINT FK_87FCA891DD62C21B FOREIGN KEY (child_id) REFERENCES children (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE weighing_activities DROP FOREIGN KEY FK_87FCA891DD62C21B');
        $this->addSql('DROP TABLE weighing_activities');
    }
}
