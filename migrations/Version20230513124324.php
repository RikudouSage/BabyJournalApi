<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230513124324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE parental_unit_settings (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', parental_unit_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', setting VARCHAR(180) NOT NULL, value LONGTEXT DEFAULT NULL, INDEX IDX_78DE2BEA32F8B562 (parental_unit_id), INDEX IDX_78DE2BEA9F74B898 (setting), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE parental_unit_settings ADD CONSTRAINT FK_78DE2BEA32F8B562 FOREIGN KEY (parental_unit_id) REFERENCES parental_units (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE parental_unit_settings DROP FOREIGN KEY FK_78DE2BEA32F8B562');
        $this->addSql('DROP TABLE parental_unit_settings');
    }
}
