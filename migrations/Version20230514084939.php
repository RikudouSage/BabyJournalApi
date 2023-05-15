<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230514084939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE oauth_access_token (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', client_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', expiry_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', identifier VARCHAR(180) NOT NULL, revoked TINYINT(1) NOT NULL, INDEX IDX_F7FA86A419EB6921 (client_id), INDEX IDX_F7FA86A4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth_access_token_oauth_scope (oauth_access_token_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', oauth_scope_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_C84CCF84888114B4 (oauth_access_token_id), INDEX IDX_C84CCF844857DA2D (oauth_scope_id), PRIMARY KEY(oauth_access_token_id, oauth_scope_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth_auth_code (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', client_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', redirect_uri VARCHAR(180) DEFAULT NULL, identifier VARCHAR(180) NOT NULL, expiry_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', revoked TINYINT(1) NOT NULL, INDEX IDX_4D12F0E0A76ED395 (user_id), INDEX IDX_4D12F0E019EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth_auth_code_oauth_scope (oauth_auth_code_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', oauth_scope_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_14C1DC3D28A68528 (oauth_auth_code_id), INDEX IDX_14C1DC3D4857DA2D (oauth_scope_id), PRIMARY KEY(oauth_auth_code_id, oauth_scope_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth_authorized_user_scope (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', scope_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', client_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_899E16907E3C61F9 (owner_id), INDEX IDX_899E1690682B5931 (scope_id), INDEX IDX_899E169019EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth_client (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', identifier VARCHAR(180) NOT NULL, name VARCHAR(180) NOT NULL, confidential TINYINT(1) NOT NULL, secret VARCHAR(180) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth_refresh_token (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', access_token_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', identifier VARCHAR(180) NOT NULL, expiry_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', revoked TINYINT(1) NOT NULL, INDEX IDX_55DCF7552CCB2688 (access_token_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth_scope (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', identifier VARCHAR(180) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_oauth_client (user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', oauth_client_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_2DBF5B4FA76ED395 (user_id), INDEX IDX_2DBF5B4FDCA49ED (oauth_client_id), PRIMARY KEY(user_id, oauth_client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE oauth_access_token ADD CONSTRAINT FK_F7FA86A419EB6921 FOREIGN KEY (client_id) REFERENCES oauth_client (id)');
        $this->addSql('ALTER TABLE oauth_access_token ADD CONSTRAINT FK_F7FA86A4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE oauth_access_token_oauth_scope ADD CONSTRAINT FK_C84CCF84888114B4 FOREIGN KEY (oauth_access_token_id) REFERENCES oauth_access_token (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oauth_access_token_oauth_scope ADD CONSTRAINT FK_C84CCF844857DA2D FOREIGN KEY (oauth_scope_id) REFERENCES oauth_scope (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oauth_auth_code ADD CONSTRAINT FK_4D12F0E0A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE oauth_auth_code ADD CONSTRAINT FK_4D12F0E019EB6921 FOREIGN KEY (client_id) REFERENCES oauth_client (id)');
        $this->addSql('ALTER TABLE oauth_auth_code_oauth_scope ADD CONSTRAINT FK_14C1DC3D28A68528 FOREIGN KEY (oauth_auth_code_id) REFERENCES oauth_auth_code (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oauth_auth_code_oauth_scope ADD CONSTRAINT FK_14C1DC3D4857DA2D FOREIGN KEY (oauth_scope_id) REFERENCES oauth_scope (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oauth_authorized_user_scope ADD CONSTRAINT FK_899E16907E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE oauth_authorized_user_scope ADD CONSTRAINT FK_899E1690682B5931 FOREIGN KEY (scope_id) REFERENCES oauth_scope (id)');
        $this->addSql('ALTER TABLE oauth_authorized_user_scope ADD CONSTRAINT FK_899E169019EB6921 FOREIGN KEY (client_id) REFERENCES oauth_client (id)');
        $this->addSql('ALTER TABLE oauth_refresh_token ADD CONSTRAINT FK_55DCF7552CCB2688 FOREIGN KEY (access_token_id) REFERENCES oauth_access_token (id)');
        $this->addSql('ALTER TABLE user_oauth_client ADD CONSTRAINT FK_2DBF5B4FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_oauth_client ADD CONSTRAINT FK_2DBF5B4FDCA49ED FOREIGN KEY (oauth_client_id) REFERENCES oauth_client (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE oauth_access_token DROP FOREIGN KEY FK_F7FA86A419EB6921');
        $this->addSql('ALTER TABLE oauth_access_token DROP FOREIGN KEY FK_F7FA86A4A76ED395');
        $this->addSql('ALTER TABLE oauth_access_token_oauth_scope DROP FOREIGN KEY FK_C84CCF84888114B4');
        $this->addSql('ALTER TABLE oauth_access_token_oauth_scope DROP FOREIGN KEY FK_C84CCF844857DA2D');
        $this->addSql('ALTER TABLE oauth_auth_code DROP FOREIGN KEY FK_4D12F0E0A76ED395');
        $this->addSql('ALTER TABLE oauth_auth_code DROP FOREIGN KEY FK_4D12F0E019EB6921');
        $this->addSql('ALTER TABLE oauth_auth_code_oauth_scope DROP FOREIGN KEY FK_14C1DC3D28A68528');
        $this->addSql('ALTER TABLE oauth_auth_code_oauth_scope DROP FOREIGN KEY FK_14C1DC3D4857DA2D');
        $this->addSql('ALTER TABLE oauth_authorized_user_scope DROP FOREIGN KEY FK_899E16907E3C61F9');
        $this->addSql('ALTER TABLE oauth_authorized_user_scope DROP FOREIGN KEY FK_899E1690682B5931');
        $this->addSql('ALTER TABLE oauth_authorized_user_scope DROP FOREIGN KEY FK_899E169019EB6921');
        $this->addSql('ALTER TABLE oauth_refresh_token DROP FOREIGN KEY FK_55DCF7552CCB2688');
        $this->addSql('ALTER TABLE user_oauth_client DROP FOREIGN KEY FK_2DBF5B4FA76ED395');
        $this->addSql('ALTER TABLE user_oauth_client DROP FOREIGN KEY FK_2DBF5B4FDCA49ED');
        $this->addSql('DROP TABLE oauth_access_token');
        $this->addSql('DROP TABLE oauth_access_token_oauth_scope');
        $this->addSql('DROP TABLE oauth_auth_code');
        $this->addSql('DROP TABLE oauth_auth_code_oauth_scope');
        $this->addSql('DROP TABLE oauth_authorized_user_scope');
        $this->addSql('DROP TABLE oauth_client');
        $this->addSql('DROP TABLE oauth_refresh_token');
        $this->addSql('DROP TABLE oauth_scope');
        $this->addSql('DROP TABLE user_oauth_client');
    }
}
