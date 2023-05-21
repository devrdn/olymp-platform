<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212221905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contest_task (id INT AUTO_INCREMENT NOT NULL, contest_id INT NOT NULL, task_id INT NOT NULL, INDEX IDX_9915CBEE1CD0F0DE (contest_id), INDEX IDX_9915CBEE8DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contest_task ADD CONSTRAINT FK_9915CBEE1CD0F0DE FOREIGN KEY (contest_id) REFERENCES contest (id)');
        $this->addSql('ALTER TABLE contest_task ADD CONSTRAINT FK_9915CBEE8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contest_task DROP FOREIGN KEY FK_9915CBEE1CD0F0DE');
        $this->addSql('ALTER TABLE contest_task DROP FOREIGN KEY FK_9915CBEE8DB60186');
        $this->addSql('DROP TABLE contest_task');
    }
}
