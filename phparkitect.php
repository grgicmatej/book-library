<?php

declare(strict_types=1);

use Arkitect\ClassSet;
use Arkitect\CLI\Config;
use Arkitect\Expression\ForClasses\DependsOnlyOnTheseNamespaces;
use Arkitect\Expression\ForClasses\HaveNameMatching;
use Arkitect\Expression\ForClasses\IsNotAbstract;
use Arkitect\Expression\ForClasses\MatchOneOfTheseNames;
use Arkitect\Expression\ForClasses\NotHaveDependencyOutsideNamespace;
use Arkitect\Expression\ForClasses\NotHaveNameMatching;
use Arkitect\Expression\ForClasses\ResideInOneOfTheseNamespaces;
use Arkitect\Rules\Rule;

return static function (Config $config): void {
    $allowedVendors = ['Symfony\Component\Uid\Uuid', 'Assert', 'Doctrine\Common\Collections', 'DateTimeImmutable', 'DateTimeInterface', 'DateTime', 'Exception', 'Doctrine\ORM\EntityManagerInterface', 'Doctrine\ORM\EntityNotFoundException'];
    $srcClassSet = ClassSet::fromDir(__DIR__ . '/src');

    $rules = [];

    $rules[] = Rule::allClasses()
        ->except('App\Domain\*\Exception\*')
        ->that(new ResideInOneOfTheseNamespaces('App\Domain'))
        ->should(new NotHaveDependencyOutsideNamespace(
            'App\Domain',
            $allowedVendors,
        ))
        ->because('we want protect our domain');

    $rules[] = Rule::allClasses()
        ->except('App\Application\Filter\*')
        ->that(new ResideInOneOfTheseNamespaces('App\Application'))
        ->should(new DependsOnlyOnTheseNamespaces(
            'App\Domain',
            'App\Application',
            ...$allowedVendors,
        ))
        ->because('we want protect our application');

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces(
            'App\Infrastructure\Driving\Http\Mobile\v1\Endpoint',
        ))
        ->should(new HaveNameMatching('*Controller'))
        ->because('we want uniform naming for controllers');

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App'))
        ->should(new NotHaveNameMatching('*Manager'))
        ->because('*Manager is too vague in naming classes');

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App'))
        ->should(new NotHaveNameMatching('*Helper'))
        ->because('*Helper is too vague in naming classes');

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Infrastructure\Driving\Http\Mobile\v1\Endpoint'))
        ->should(new IsNotAbstract())
        ->because('we want to avoid abstract classes into our UI layer');

    $rules[] = Rule::allClasses()
        ->that(new HaveNameMatching('*Handler'))
        ->should(new ResideInOneOfTheseNamespaces('App\Application', 'App\Infrastructure\Driven'))
        ->because('we want to be sure that all Handlers are in a specific namespace');

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces(
            'App\Infrastructure\Driving\Http\Mobile\v1\Endpoint',
        ))
        ->should(new MatchOneOfTheseNames(['Create*', 'List*', 'Update*', 'Delete*', 'Get*']))
        ->because('we want uniform naming for controllers');
    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces(
            'App\Application\Repository',
        ))
        ->should(new MatchOneOfTheseNames(['*ReadRepository*', '*WriteRepository*']))
        ->because('we want uniform naming for Repositories');
    $config
        ->add($srcClassSet, ...$rules);
};
