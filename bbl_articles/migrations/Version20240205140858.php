<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240205140858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, date_adhesion DATE NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naiss DATE NOT NULL, adresse_postale VARCHAR(255) NOT NULL, num_tel INT NOT NULL, photo VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D7F0840037');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D7F0840037 FOREIGN KEY (emprunteur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239FF6F3098');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239FF6F3098 FOREIGN KEY (reservateur_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D7F0840037');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239FF6F3098');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D7F0840037');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D7F0840037 FOREIGN KEY (emprunteur_id) REFERENCES adherent (id)');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239FF6F3098');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239FF6F3098 FOREIGN KEY (reservateur_id) REFERENCES adherent (id)');
    }
}
