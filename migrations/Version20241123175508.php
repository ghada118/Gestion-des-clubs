<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241123175508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation ADD type_formation_id INT NOT NULL');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFD543922B FOREIGN KEY (type_formation_id) REFERENCES type_formation (id)');
        $this->addSql('CREATE INDEX IDX_404021BFD543922B ON formation (type_formation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFD543922B');
        $this->addSql('DROP INDEX IDX_404021BFD543922B ON formation');
        $this->addSql('ALTER TABLE formation DROP type_formation_id');
    }
}
