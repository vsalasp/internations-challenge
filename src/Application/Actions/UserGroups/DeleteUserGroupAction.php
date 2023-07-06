<?php

namespace App\Application\Actions\UserGroups;

use App\Domain\ValueObjects\ID;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteUserGroupAction extends UserGroupAction
{

    protected function action(): Response
    {
        $userGroup = $this->userGroupRepository->findUserGroupOfId(
            new ID($this->resolveArg('id'))
        );

        if (!empty($userGroup->getMembers()->getValue())) {
            throw new \InvalidArgumentException("The user group is not empty");
        }

        $this->userGroupRepository->deleteUserGroup($userGroup);

        return $this->respondWithData();
    }
}
