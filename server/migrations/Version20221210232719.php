<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221210232719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_meta DROP FOREIGN KEY FK_A441804E8DB60186');
        $this->addSql('ALTER TABLE task_meta CHANGE solved solved INT DEFAULT 0, CHANGE complexity complexity INT DEFAULT 0');
        $this->addSql('ALTER TABLE task_meta ADD CONSTRAINT FK_A441804E8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_meta DROP FOREIGN KEY FK_A441804E8DB60186');
        $this->addSql('ALTER TABLE task_meta CHANGE solved solved INT DEFAULT NULL, CHANGE complexity complexity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task_meta ADD CONSTRAINT FK_A441804E8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
    }
}
