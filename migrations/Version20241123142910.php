<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241123142910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF47A44D41 FOREIGN KEY (typeformation_id) REFERENCES type_formation (id)');
        $this->addSql('CREATE INDEX IDX_404021BF47A44D41 ON formation (typeformation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF47A44D41');
        $this->addSql('DROP INDEX IDX_404021BF47A44D41 ON formation');
    }
}
