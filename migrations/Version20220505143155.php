<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505143155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added notifications table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE synolia_admin_notifications (
          id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          message VARCHAR(255) NOT NULL,
          channel VARCHAR(255) NOT NULL,
          level_name VARCHAR(255) NOT NULL,
          context JSON NOT NULL,
          created_at DATETIME NOT NULL,
          updated_at DATETIME DEFAULT NULL,
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE synolia_admin_notifications');
    }
}
