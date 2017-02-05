<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170131005833 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE event_status (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD event_status_id INT DEFAULT NULL, DROP status');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7ED623E80 FOREIGN KEY (event_status_id) REFERENCES event_status (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7ED623E80 ON event (event_status_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7ED623E80');
        $this->addSql('DROP TABLE event_status');
        $this->addSql('DROP INDEX IDX_3BAE0AA7ED623E80 ON event');
        $this->addSql('ALTER TABLE event ADD status VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP event_status_id');
    }
}
