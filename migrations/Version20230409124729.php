<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230409124729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE children (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', parental_unit_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name LONGTEXT DEFAULT NULL, gender LONGTEXT DEFAULT NULL, birth_day LONGTEXT DEFAULT NULL, birth_weight LONGTEXT DEFAULT NULL, birth_height LONGTEXT DEFAULT NULL, INDEX IDX_A197B1BA32F8B562 (parental_unit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feeding_activities (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', child_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', type LONGTEXT NOT NULL, amount LONGTEXT NOT NULL, bottle_content_type LONGTEXT DEFAULT NULL, start_time LONGTEXT NOT NULL, end_time LONGTEXT NOT NULL, break_duration LONGTEXT DEFAULT NULL, note LONGTEXT DEFAULT NULL, INDEX IDX_B5D4BD7ADD62C21B (child_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parental_units (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name LONGTEXT DEFAULT NULL, share_code BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', parental_unit_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', selected_child_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', roles JSON NOT NULL, name LONGTEXT DEFAULT NULL, INDEX IDX_1483A5E932F8B562 (parental_unit_id), INDEX IDX_1483A5E9CA3D3826 (selected_child_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE children ADD CONSTRAINT FK_A197B1BA32F8B562 FOREIGN KEY (parental_unit_id) REFERENCES parental_units (id)');
        $this->addSql('ALTER TABLE feeding_activities ADD CONSTRAINT FK_B5D4BD7ADD62C21B FOREIGN KEY (child_id) REFERENCES children (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E932F8B562 FOREIGN KEY (parental_unit_id) REFERENCES parental_units (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9CA3D3826 FOREIGN KEY (selected_child_id) REFERENCES children (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE children DROP FOREIGN KEY FK_A197B1BA32F8B562');
        $this->addSql('ALTER TABLE feeding_activities DROP FOREIGN KEY FK_B5D4BD7ADD62C21B');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E932F8B562');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9CA3D3826');
        $this->addSql('DROP TABLE children');
        $this->addSql('DROP TABLE feeding_activities');
        $this->addSql('DROP TABLE parental_units');
        $this->addSql('DROP TABLE users');
    }
}
