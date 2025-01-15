<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250114213447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'books entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE books (id UUID NOT NULL, isbn VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, year INT NOT NULL, genre VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN books.id IS \'(DC2Type:bookId)\'');
        $this->addSql('CREATE TABLE books_authors (book_id UUID NOT NULL, author_id UUID NOT NULL, PRIMARY KEY(book_id, author_id))');
        $this->addSql('CREATE INDEX IDX_877EACC216A2B381 ON books_authors (book_id)');
        $this->addSql('CREATE INDEX IDX_877EACC2F675F31B ON books_authors (author_id)');
        $this->addSql('COMMENT ON COLUMN books_authors.book_id IS \'(DC2Type:bookId)\'');
        $this->addSql('COMMENT ON COLUMN books_authors.author_id IS \'(DC2Type:authorId)\'');
        $this->addSql('CREATE TABLE authors (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN authors.id IS \'(DC2Type:authorId)\'');
        $this->addSql('ALTER TABLE books_authors ADD CONSTRAINT FK_877EACC216A2B381 FOREIGN KEY (book_id) REFERENCES books (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE books_authors ADD CONSTRAINT FK_877EACC2F675F31B FOREIGN KEY (author_id) REFERENCES authors (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE books_authors DROP CONSTRAINT FK_877EACC216A2B381');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE books_authors');
        $this->addSql('DROP TABLE authors');
    }
}
