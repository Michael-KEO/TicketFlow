<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250528001253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD client_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD CONSTRAINT FK_50159CA919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_50159CA919EB6921 ON projet (client_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket ADD projet_id INT NOT NULL, ADD rapporteur_id INT NOT NULL, ADD developpeur_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA32AF5D182 FOREIGN KEY (rapporteur_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA384E66085 FOREIGN KEY (developpeur_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_97A0ADA3C18272 ON ticket (projet_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_97A0ADA32AF5D182 ON ticket (rapporteur_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_97A0ADA384E66085 ON ticket (developpeur_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP FOREIGN KEY FK_50159CA919EB6921
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_50159CA919EB6921 ON projet
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP client_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3C18272
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA32AF5D182
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA384E66085
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_97A0ADA3C18272 ON ticket
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_97A0ADA32AF5D182 ON ticket
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_97A0ADA384E66085 ON ticket
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket DROP projet_id, DROP rapporteur_id, DROP developpeur_id
        SQL);
    }
}
