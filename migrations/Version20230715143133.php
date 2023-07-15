<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230715143133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shared_in_progress_activities (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', parental_unit_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', activity_type VARCHAR(180) NOT NULL, config LONGTEXT NOT NULL, INDEX IDX_B3D979C932F8B562 (parental_unit_id), INDEX IDX_B3D979C98F1A8CBB (activity_type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shared_in_progress_activities ADD CONSTRAINT FK_B3D979C932F8B562 FOREIGN KEY (parental_unit_id) REFERENCES parental_units (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shared_in_progress_activities DROP FOREIGN KEY FK_B3D979C932F8B562');
        $this->addSql('DROP TABLE shared_in_progress_activities');
    }
}
