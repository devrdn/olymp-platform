<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221206100536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, description LONGTEXT NOT NULL, time_limit INT NOT NULL, memory_limit INT NOT NULL, example_input LONGTEXT DEFAULT NULL, example_output LONGTEXT DEFAULT NULL, restriction VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_meta (id INT AUTO_INCREMENT NOT NULL, task_id INT NOT NULL, author VARCHAR(255) NOT NULL, solved INT DEFAULT NULL, complexity INT DEFAULT NULL, source VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_A441804E8DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_test (id INT AUTO_INCREMENT NOT NULL, task_id INT NOT NULL, input_data VARCHAR(255) DEFAULT NULL, output_data VARCHAR(255) DEFAULT NULL, INDEX IDX_ABCCEA778DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task_meta ADD CONSTRAINT FK_A441804E8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE task_test ADD CONSTRAINT FK_ABCCEA778DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_meta DROP FOREIGN KEY FK_A441804E8DB60186');
        $this->addSql('ALTER TABLE task_test DROP FOREIGN KEY FK_ABCCEA778DB60186');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_meta');
        $this->addSql('DROP TABLE task_test');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
