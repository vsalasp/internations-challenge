<?php

namespace App\Application\Actions\UserGroups;

use Psr\Http\Message\ResponseInterface as Response;

class ListUserGroupsAction extends UserGroupAction
{

    protected function action(): Response
    {
        $groups = $this->userGroupRepository->findAll();
        return $this->respondWithData($groups);
    }
}
