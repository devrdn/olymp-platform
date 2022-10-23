<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221023122816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_test (id INT AUTO_INCREMENT NOT NULL, task_id INT NOT NULL, input_data VARCHAR(255) DEFAULT NULL, output_data VARCHAR(255) DEFAULT NULL, INDEX IDX_ABCCEA778DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task_test ADD CONSTRAINT FK_ABCCEA778DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE task CHANGE name name LONGTEXT NOT NULL, CHANGE example_input example_input LONGTEXT DEFAULT NULL, CHANGE example_output example_output LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_test DROP FOREIGN KEY FK_ABCCEA778DB60186');
        $this->addSql('DROP TABLE task_test');
        $this->addSql('ALTER TABLE task CHANGE name name TEXT NOT NULL, CHANGE example_input example_input TEXT DEFAULT NULL, CHANGE example_output example_output TEXT DEFAULT NULL');
    }
}
