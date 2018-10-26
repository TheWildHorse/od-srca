<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181025064036 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, wish_id INT DEFAULT NULL, realize_wish_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, INDEX IDX_1483A5E942B83698 (wish_id), INDEX IDX_1483A5E99EDA47 (realize_wish_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishes (id INT AUTO_INCREMENT NOT NULL, wish VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E942B83698 FOREIGN KEY (wish_id) REFERENCES wishes (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E99EDA47 FOREIGN KEY (realize_wish_id) REFERENCES wishes (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E942B83698');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E99EDA47');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE wishes');
    }
}
