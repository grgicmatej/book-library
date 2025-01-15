<?php

declare(strict_types=1);

namespace App\Infrastructure\Driven\Persistence\Doctrine\Fixture;

use App\Domain\Author\Author;
use App\Domain\Author\VO\AuthorId;
use App\Domain\Shared\VO\Name;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class AuthorFixture extends Fixture
{
    public static function ids(): array
    {
        return [
            '3d29d4d8-3dd0-478e-98ca-ab32ee133de8',
            '84ed24be-4f52-42ce-93c3-c9842a6cd0bb',
            'ce596544-849c-4ba6-981e-a34407d9bff2',
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < \count(self::ids()); ++$i) {
            $id = AuthorId::fromString(self::ids()[$i]);

            $author = new Author(
                $id,
                new Name($faker->name),
                new ArrayCollection(),
            );

            $this->addReference(Author::class . '-' . $i, $author);

            $manager->persist($author);
        }

        $manager->flush();
    }
}
