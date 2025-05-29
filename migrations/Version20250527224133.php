<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250527224133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, ticket_id INT NOT NULL, titre_ticket VARCHAR(255) DEFAULT NULL, type_ticket VARCHAR(255) DEFAULT NULL, description_ticket VARCHAR(1000) DEFAULT NULL, priorite_ticket VARCHAR(255) DEFAULT NULL, statut_ticket VARCHAR(255) DEFAULT NULL, date_creation DATETIME DEFAULT NULL, date_echeance DATETIME DEFAULT NULL, date_resolution DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE ticket
        SQL);
    }
}
