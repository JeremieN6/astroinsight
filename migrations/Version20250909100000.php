<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250909100000 extends AbstractMigration
{
    public function getDescription(): string
    { return 'Geo cache entry table'; }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE geo_cache_entry (id INT AUTO_INCREMENT NOT NULL, hash VARCHAR(64) NOT NULL, place_original VARCHAR(160) NOT NULL, lat DOUBLE PRECISION NOT NULL, lon DOUBLE PRECISION NOT NULL, timezone VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX uniq_geo_hash (hash), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE geo_cache_entry');
    }
}
