<?php

declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Domain\UserGroups\UserGroupRepository;
use App\Infrastructure\Persistence\User\MySQLUserRepository;
use App\Infrastructure\Persistence\UserGroup\MySQLUserGroupRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(MySQLUserRepository::class),
        UserGroupRepository::class => \DI\autowire(MySQLUserGroupRepository::class),
    ]);
};
