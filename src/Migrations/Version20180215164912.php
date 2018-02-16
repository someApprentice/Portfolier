<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180215164912 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE portfolio_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE stock_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE portfolio (id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A9ED1062A76ED395 ON portfolio (user_id)');
        $this->addSql('CREATE TABLE stock (id INT NOT NULL, portfolio_id INT NOT NULL, symbol VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4B365660B96B5643 ON stock (portfolio_id)');
        $this->addSql('ALTER TABLE portfolio ADD CONSTRAINT FK_A9ED1062A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660B96B5643 FOREIGN KEY (portfolio_id) REFERENCES portfolio (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE stock DROP CONSTRAINT FK_4B365660B96B5643');
        $this->addSql('DROP SEQUENCE portfolio_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE stock_id_seq CASCADE');
        $this->addSql('DROP TABLE portfolio');
        $this->addSql('DROP TABLE stock');
    }
}
