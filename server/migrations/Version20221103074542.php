<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221103074542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_meta CHANGE author author VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE task_test CHANGE input_data input_data VARCHAR(255) DEFAULT NULL, CHANGE output_data output_data VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_meta CHANGE author author VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE task_test CHANGE input_data input_data LONGTEXT NOT NULL, CHANGE output_data output_data LONGTEXT NOT NULL');
    }
}
