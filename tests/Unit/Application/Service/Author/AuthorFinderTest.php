<?php

declare(strict_types=1);

namespace App\Tests\Application\Service\Author;

use App\Application\Repository\Author\AuthorReadRepository;
use App\Application\Service\Author\AuthorFinder;
use App\Domain\Author\Author;
use App\Domain\Author\VO\AuthorId;
use App\Domain\Shared\VO\Name;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(AuthorFinder::class)]
class AuthorFinderTest extends TestCase
{
    private MockObject $authorReadRepositoryMock;

    private AuthorFinder $authorFinder;

    protected function setUp(): void
    {
        // Mock the AuthorReadRepository
        $this->authorReadRepositoryMock = $this->createMock(AuthorReadRepository::class);

        // Inject the mocked repository into AuthorFinder
        $this->authorFinder = new AuthorFinder($this->authorReadRepositoryMock);
    }

    public function testFindAuthorSuccess(): void
    {
        // Arrange: Create a mock AuthorId and Author
        $authorId = AuthorId::fromString('123');
        $author = new Author($authorId, new Name('John Doe'), new ArrayCollection());

        // Mock the repository to return the Author when get() is called
        $this->authorReadRepositoryMock
            ->expects(static::once())
            ->method('get')
            ->with($authorId)
            ->willReturn($author);

        // Act: Call the findAuthor method
        $result = $this->authorFinder->findAuthor('123');

        // Assert: Ensure the correct author is returned
        static::assertSame($author, $result);
    }

    public function testFindAuthorThrowsException(): void
    {
        // Arrange: Mock the repository to throw an exception
        $this->authorReadRepositoryMock
            ->expects(static::once())
            ->method('get')
            ->willThrowException(new \Exception('Author not found'));

        // Act: Call the findAuthor method
        $result = $this->authorFinder->findAuthor('123');

        // Assert: Ensure null is returned when an exception is thrown
        static::assertNull($result);
    }
}
