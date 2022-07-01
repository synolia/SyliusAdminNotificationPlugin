<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220701062415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added admin notification table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE synolia_admin_notifications (
          id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          message VARCHAR(255) NOT NULL,
          channel VARCHAR(255) NOT NULL,
          level_name VARCHAR(255) NOT NULL,
          context JSON NOT NULL,
          created_at DATETIME NOT NULL,
          updated_at DATETIME DEFAULT NULL,
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE synolia_admin_notifications');
    }
}
