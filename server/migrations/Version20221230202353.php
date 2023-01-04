<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221230202353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE task ADD published TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE task_meta DROP FOREIGN KEY FK_A441804E8DB60186');
        $this->addSql('ALTER TABLE task_meta CHANGE solved solved INT DEFAULT 0 NOT NULL, CHANGE complexity complexity INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE task_meta ADD CONSTRAINT FK_A441804E8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE task DROP published');
        $this->addSql('ALTER TABLE task_meta DROP FOREIGN KEY FK_A441804E8DB60186');
        $this->addSql('ALTER TABLE task_meta CHANGE solved solved INT DEFAULT NULL, CHANGE complexity complexity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task_meta ADD CONSTRAINT FK_A441804E8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
    }
}
