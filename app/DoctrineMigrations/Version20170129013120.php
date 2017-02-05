<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170129013120 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_vote (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, user_id INT DEFAULT NULL, voteYes TINYINT(1) DEFAULT NULL, INDEX IDX_2091C9AD71F7E88B (event_id), INDEX IDX_2091C9ADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_vote ADD CONSTRAINT FK_2091C9AD71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE user_vote ADD CONSTRAINT FK_2091C9ADA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE event ADD user_votes VARCHAR(255) DEFAULT NULL, ADD voting_start DATETIME NOT NULL, ADD voting_end DATETIME NOT NULL, ADD vote_total INT DEFAULT NULL, DROP score, DROP totalVotes, DROP votingStart, DROP votingEnd');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_vote');
        $this->addSql('ALTER TABLE event ADD totalVotes INT DEFAULT NULL, ADD votingStart DATETIME NOT NULL, ADD votingEnd DATETIME NOT NULL, DROP user_votes, DROP voting_start, DROP voting_end, CHANGE vote_total score INT DEFAULT NULL');
    }
}
