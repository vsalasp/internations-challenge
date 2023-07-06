<?php

declare(strict_types=1);

use App\Application\Actions\User\AddUserAction;
use App\Application\Actions\User\DeleteUserAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\UserGroups\AddUserGroupAction;
use App\Application\Actions\UserGroups\ListUserGroupsAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->post('', AddUserAction::class);
        $group->get('/{id}', ViewUserAction::class);
        $group->delete('/{id}', DeleteUserAction::class);
    });

    $app->group('/groups', function (Group $group) {
        $group->get('', ListUserGroupsAction::class);
        $group->post('', AddUserGroupAction::class);
    });
};
