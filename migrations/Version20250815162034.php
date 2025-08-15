<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250815162034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agency (agency_id INT AUTO_INCREMENT NOT NULL, city VARCHAR(100) NOT NULL, PRIMARY KEY(agency_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journey (journey_id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, departure_agency_id INT NOT NULL, arrival_agency_id INT NOT NULL, departure_date DATETIME NOT NULL, arrival_date DATETIME NOT NULL, total_seats INT NOT NULL, available_seats INT NOT NULL, INDEX IDX_C816C6A2A76ED395 (user_id), INDEX IDX_C816C6A2D88C8AC7 (departure_agency_id), INDEX IDX_C816C6A279EF755E (arrival_agency_id), PRIMARY KEY(journey_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (user_id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(50) NOT NULL, first_name VARCHAR(50) NOT NULL, phone VARCHAR(10) NOT NULL, email VARCHAR(150) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE journey ADD CONSTRAINT FK_C816C6A2A76ED395 FOREIGN KEY (user_id) REFERENCES user (user_id)');
        $this->addSql('ALTER TABLE journey ADD CONSTRAINT FK_C816C6A2D88C8AC7 FOREIGN KEY (departure_agency_id) REFERENCES agency (agency_id)');
        $this->addSql('ALTER TABLE journey ADD CONSTRAINT FK_C816C6A279EF755E FOREIGN KEY (arrival_agency_id) REFERENCES agency (agency_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE journey DROP FOREIGN KEY FK_C816C6A2A76ED395');
        $this->addSql('ALTER TABLE journey DROP FOREIGN KEY FK_C816C6A2D88C8AC7');
        $this->addSql('ALTER TABLE journey DROP FOREIGN KEY FK_C816C6A279EF755E');
        $this->addSql('DROP TABLE agency');
        $this->addSql('DROP TABLE journey');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
