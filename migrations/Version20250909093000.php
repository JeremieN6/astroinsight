<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250909093000 extends AbstractMigration
{
    public function getDescription(): string
    { return 'API cache table'; }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE api_cache_entry (id INT AUTO_INCREMENT NOT NULL, cache_key VARCHAR(190) NOT NULL, payload LONGTEXT NOT NULL, created_at DATETIME NOT NULL, expires_at DATETIME NOT NULL, UNIQUE INDEX uniq_api_cache_key (cache_key), INDEX idx_api_cache_expires (expires_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE api_cache_entry');
    }
}
