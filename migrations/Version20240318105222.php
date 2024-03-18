<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240318105222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments ADD id_article_id INT DEFAULT NULL, ADD comment VARCHAR(255) NOT NULL, ADD date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AD71E064B FOREIGN KEY (id_article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AD71E064B ON comments (id_article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AD71E064B');
        $this->addSql('DROP INDEX IDX_5F9E962AD71E064B ON comments');
        $this->addSql('ALTER TABLE comments DROP id_article_id, DROP comment, DROP date');
    }
}
