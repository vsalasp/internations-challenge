<?php

namespace App\Application\Actions\UserGroups;

use App\Domain\User\UserRepository;
use App\Domain\UserGroups\UserGroupRepository;
use App\Domain\ValueObjects\ID;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class AssignMemberToGroupAction extends UserGroupAction
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
        $body = $this->getFormData();

        $user = $this->userRepository->findUserOfId(
            new ID($body['userId'])
        );

        $group = $this->userGroupRepository->findUserGroupOfId(
            new ID($this->resolveArg('id'))
        );

        foreach ($group->getMembers()->getValue() as $member) {
            if ($member->getID()->getValue() === $user->getID()->getValue()) {
                throw new \InvalidArgumentException("User already in the group");
            }
        }

        $this->userGroupRepository->addMemberToUserGroup($group, $user);

        return $this->respondWithData();
    }
}
