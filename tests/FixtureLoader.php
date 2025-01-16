<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Bundle\FixturesBundle\Loader\SymfonyFixturesLoader;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Small;
use Symfony\Component\DependencyInjection\ContainerInterface;

#[CoversNothing]
#[Small]
final class FixtureLoader
{
    /** @var array<int,string> */
    private array $fixtureClasses = [];

    public function __construct(private ContainerInterface $container) {}

    public function load(string ...$fixtures): void
    {
        /** @var Registry $registry */
        $registry = $this->container->get('doctrine');
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $registry->getManager();
        $purger = new ORMPurger($entityManager);
        $loader = new SymfonyFixturesLoader();
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->setPurger($purger);

        foreach ($fixtures as $fixtureClass) {
            $this->loadFixtureClass($fixtureClass, $loader);
        }

        $purger->purge();
        $executor->execute($loader->getFixtures());
    }

    private function loadFixtureClass(string $fixtureClass, SymfonyFixturesLoader $loader): void
    {
        if (\in_array($fixtureClass, $this->fixtureClasses, true)) {
            return;
        }

        /** @var FixtureInterface $fixture */
        $fixture = $this->container->get($fixtureClass);

        if ($fixture instanceof DependentFixtureInterface) {
            $this->loadFixtureAndDependencies($fixture, $loader);
        }

        $this->fixtureClasses[] = $fixtureClass;
        $loader->addFixture($fixture);
    }

    private function loadFixtureAndDependencies(DependentFixtureInterface $fixture, SymfonyFixturesLoader $loader): void
    {
        foreach ($fixture->getDependencies() as $dependencyClass) {
            if (!\in_array($dependencyClass, $this->fixtureClasses, true)) {
                $this->loadFixtureClass($dependencyClass, $loader);
            }
        }
    }
}
