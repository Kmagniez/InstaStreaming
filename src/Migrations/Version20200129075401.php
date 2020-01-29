<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200129075401 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admin_movie (admin_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_BD740995642B8210 (admin_id), INDEX IDX_BD7409958F93B6FC (movie_id), PRIMARY KEY(admin_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin_movie ADD CONSTRAINT FK_BD740995642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE admin_movie ADD CONSTRAINT FK_BD7409958F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE admin_movie');
    }
}
