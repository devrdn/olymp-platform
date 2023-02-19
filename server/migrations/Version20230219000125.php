<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230219000125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_solution (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, task_id INT NOT NULL, uploaded_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7FBECDDBA76ED395 (user_id), INDEX IDX_7FBECDDB8DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_solution ADD CONSTRAINT FK_7FBECDDBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_solution ADD CONSTRAINT FK_7FBECDDB8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE contest_task DROP FOREIGN KEY FK_9915CBEE1CD0F0DE');
        $this->addSql('ALTER TABLE contest_task DROP FOREIGN KEY FK_9915CBEE8DB60186');
        $this->addSql('DROP TABLE contest');
        $this->addSql('DROP TABLE contest_task');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contest (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, author VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE contest_task (id INT AUTO_INCREMENT NOT NULL, contest_id INT NOT NULL, task_id INT NOT NULL, INDEX IDX_9915CBEE8DB60186 (task_id), INDEX IDX_9915CBEE1CD0F0DE (contest_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE contest_task ADD CONSTRAINT FK_9915CBEE1CD0F0DE FOREIGN KEY (contest_id) REFERENCES contest (id)');
        $this->addSql('ALTER TABLE contest_task ADD CONSTRAINT FK_9915CBEE8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE user_solution DROP FOREIGN KEY FK_7FBECDDBA76ED395');
        $this->addSql('ALTER TABLE user_solution DROP FOREIGN KEY FK_7FBECDDB8DB60186');
        $this->addSql('DROP TABLE user_solution');
    }
}
