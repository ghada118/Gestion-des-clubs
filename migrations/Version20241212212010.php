<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241212212010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE club_etudiant (club_id INT NOT NULL, etudiant_id INT NOT NULL, INDEX IDX_72C5884A61190A32 (club_id), INDEX IDX_72C5884ADDEAB1A3 (etudiant_id), PRIMARY KEY(club_id, etudiant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, UNIQUE INDEX UNIQ_717E22E3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE club_etudiant ADD CONSTRAINT FK_72C5884A61190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE club_etudiant ADD CONSTRAINT FK_72C5884ADDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE club ADD type_id INT DEFAULT NULL, ADD name VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD image VARCHAR(255) DEFAULT NULL, ADD date_creation DATETIME NOT NULL, ADD places_disponibles INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE3872C54C8C93 FOREIGN KEY (type_id) REFERENCES type_club (id)');
        $this->addSql('CREATE INDEX IDX_B8EE3872C54C8C93 ON club (type_id)');
        $this->addSql('ALTER TABLE demande_inscription ADD etudiant_id INT NOT NULL, ADD club_id INT NOT NULL, ADD statut VARCHAR(255) NOT NULL, ADD date_demande DATETIME NOT NULL');
        $this->addSql('ALTER TABLE demande_inscription ADD CONSTRAINT FK_FFB7A9A2DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE demande_inscription ADD CONSTRAINT FK_FFB7A9A261190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('CREATE INDEX IDX_FFB7A9A2DDEAB1A3 ON demande_inscription (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_FFB7A9A261190A32 ON demande_inscription (club_id)');
        $this->addSql('ALTER TABLE type_club ADD categorie_club VARCHAR(255) NOT NULL, ADD description_categ_club VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_inscription DROP FOREIGN KEY FK_FFB7A9A2DDEAB1A3');
        $this->addSql('ALTER TABLE club_etudiant DROP FOREIGN KEY FK_72C5884A61190A32');
        $this->addSql('ALTER TABLE club_etudiant DROP FOREIGN KEY FK_72C5884ADDEAB1A3');
        $this->addSql('DROP TABLE club_etudiant');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE3872C54C8C93');
        $this->addSql('DROP INDEX IDX_B8EE3872C54C8C93 ON club');
        $this->addSql('ALTER TABLE club DROP type_id, DROP name, DROP description, DROP image, DROP date_creation, DROP places_disponibles');
        $this->addSql('ALTER TABLE demande_inscription DROP FOREIGN KEY FK_FFB7A9A261190A32');
        $this->addSql('DROP INDEX IDX_FFB7A9A2DDEAB1A3 ON demande_inscription');
        $this->addSql('DROP INDEX IDX_FFB7A9A261190A32 ON demande_inscription');
        $this->addSql('ALTER TABLE demande_inscription DROP etudiant_id, DROP club_id, DROP statut, DROP date_demande');
        $this->addSql('ALTER TABLE type_club DROP categorie_club, DROP description_categ_club');
    }
}
