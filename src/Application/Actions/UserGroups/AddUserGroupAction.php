<?php

namespace App\Application\Actions\UserGroups;

use App\Domain\UserGroups\UserGroup;
use App\Domain\ValueObjects\Name;
use App\Domain\ValueObjects\UserList;
use Psr\Http\Message\ResponseInterface as Response;

class AddUserGroupAction extends UserGroupAction
{
    protected function action(): Response
    {
        $body = $this->getFormData();
        $name = new Name($body['name']);
        $members = new UserList([]);

        $userGroup = $this->userGroupRepository->addUserGroup(new UserGroup(null, $name, $members));

        return $this->respondWithData($userGroup);
    }
}
