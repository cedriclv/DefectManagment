<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201185656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE defect ADD reason_id INT NOT NULL');
        $this->addSql('ALTER TABLE defect ADD CONSTRAINT FK_3A9C388759BB1592 FOREIGN KEY (reason_id) REFERENCES reason (id)');
        $this->addSql('CREATE INDEX IDX_3A9C388759BB1592 ON defect (reason_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE defect DROP FOREIGN KEY FK_3A9C388759BB1592');
        $this->addSql('DROP INDEX IDX_3A9C388759BB1592 ON defect');
        $this->addSql('ALTER TABLE defect DROP reason_id');
    }
}
