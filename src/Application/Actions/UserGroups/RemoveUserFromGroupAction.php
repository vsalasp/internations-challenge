<?php

namespace App\Application\Actions\UserGroups;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use App\Domain\UserGroups\UserGroup;
use App\Domain\UserGroups\UserGroupRepository;
use App\Domain\ValueObjects\ID;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class RemoveUserFromGroupAction extends UserGroupAction
{

    private UserRepository $userRepository;

    public function __construct(
        LoggerInterface $logger,
        UserGroupRepository $userGroupRepository,
        UserRepository $userRepository,
    ) {
        parent::__construct($logger, $userGroupRepository);
        $this->userRepository = $userRepository;
    }

    protected function action(): Response
    {
        $user = $this->userRepository->findUserOfId(
            new ID($this->resolveArg('userId'))
        );

        $group = $this->userGroupRepository->findUserGroupOfId(
            new ID($this->resolveArg('groupId'))
        );
        $this->validate($group, $user);

        $this->userGroupRepository->removeUserFromGroup($group, $user);

        return $this->respondWithData();
    }

    private function validate(UserGroup $group, User $user): void
    {
        $found = false;
        foreach ($group->getMembers()->getValue() as $member) {
            if ($member->getID()->getValue() === $user->getID()->getValue()) {
                $found = true;
            }
        }
        if (!$found) {
            throw new \InvalidArgumentException("User does not belong to group");
        }
    }
}
