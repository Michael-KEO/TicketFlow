<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530110538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire ADD ticket_id INT NOT NULL, ADD auteur_id INT NOT NULL, ADD contenu LONGTEXT NOT NULL, ADD date_creation DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_67F068BC700047D2 ON commentaire (ticket_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_67F068BC60BB6FE6 ON commentaire (auteur_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC700047D2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC60BB6FE6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_67F068BC700047D2 ON commentaire
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_67F068BC60BB6FE6 ON commentaire
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire DROP ticket_id, DROP auteur_id, DROP contenu, DROP date_creation
        SQL);
    }
}
