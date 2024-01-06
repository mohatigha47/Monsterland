<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240104171201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monstre ADD royaume_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE monstre ADD CONSTRAINT FK_A20EC7A5A3878AD1 FOREIGN KEY (royaume_id) REFERENCES royaume (id)');
        $this->addSql('CREATE INDEX IDX_A20EC7A5A3878AD1 ON monstre (royaume_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monstre DROP FOREIGN KEY FK_A20EC7A5A3878AD1');
        $this->addSql('DROP INDEX IDX_A20EC7A5A3878AD1 ON monstre');
        $this->addSql('ALTER TABLE monstre DROP royaume_id');
    }
}
