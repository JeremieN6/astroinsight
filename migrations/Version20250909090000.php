<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250909090000 extends AbstractMigration
{
    public function getDescription(): string
    { return 'Create daily_horoscope table for cached interpreted daily horoscopes'; }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE daily_horoscope (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date DATE NOT NULL, summary LONGTEXT DEFAULT NULL, insights JSON DEFAULT NULL, next_transit JSON DEFAULT NULL, raw_data JSON DEFAULT NULL, scores JSON DEFAULT NULL, generated_at DATETIME DEFAULT NULL, final TINYINT(1) NOT NULL, UNIQUE INDEX uniq_daily_user_date (user_id, date), INDEX idx_daily_user_date (user_id, date), INDEX IDX_DAILY_USER (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE daily_horoscope ADD CONSTRAINT FK_DAILY_USER FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE daily_horoscope');
    }
}
