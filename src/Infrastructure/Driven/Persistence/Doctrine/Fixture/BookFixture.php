<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\Persistence\Doctrine\Fixture;

use App\Domain\Author\Author;
use App\Domain\Book\Book;
use App\Domain\Book\VO\BookId;
use App\Domain\Book\VO\Genre;
use App\Domain\Book\VO\Isbn;
use App\Domain\Book\VO\Title;
use App\Domain\Book\VO\Year;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class BookFixture extends TrackableFixture
{
    public static function ids(): array
    {
        return [
            '97812b1f-b19a-4185-ba14-e1784a5e4710',
            'da8eee98-3f8a-4f15-b516-b4037b5b84d5',
        ];
    }

    public static function isbns(): array
    {
        return [
            '978-6-7618-2031-6',
            '978-4-2460-2814-1',
        ];
    }

    public static function toGenerate(): int
    {
        return \count(self::ids());
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < \count(self::ids()); ++$i) {
            $id = BookId::fromString(self::ids()[$i]);

            $authors = new ArrayCollection();

            $authors->add($this->getReference(Author::class . '-' . $i, Author::class));

            if (1 === $i) {
                $authors->add($this->getReference(Author::class . '-2', Author::class));
            }

            $book = new Book(
                $id,
                new Isbn(self::isbns()[$i]),
                new Title($faker->title),
                $authors,
                new Year((int) date('Y')),
                new Genre('drama'),
            );

            $this->addReference(Book::class . '-' . $i, $book);

            $manager->persist($book);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [AuthorFixture::class];
    }
}
