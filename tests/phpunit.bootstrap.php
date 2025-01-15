<?php

declare(strict_types=1);

passthru('ENV=test');

passthru(sprintf(
    'APP_ENV=test php "%s/../bin/console" doctrine:database:drop --if-exists --force',
    __DIR__
));

passthru(sprintf(
    'APP_ENV=test php "%s/../bin/console" doctrine:database:create --if-not-exists',
    __DIR__
));

passthru(sprintf(
    'APP_ENV=test php "%s/../bin/console" doctrine:migrations:migrate --no-interaction',
    __DIR__
));
/*
passthru(sprintf(
    'APP_ENV=test php -d memory_limit=-1 "%s/../bin/console" doctrine:fixtures:load --group=DevDummyData --no-interaction',
    __DIR__
));
*/
require __DIR__ . '/bootstrap.php';
