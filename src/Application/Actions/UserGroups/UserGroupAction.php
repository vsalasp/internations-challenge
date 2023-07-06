<?php

declare(strict_types=1);

namespace App\Application\Actions\UserGroups;

use App\Application\Actions\Action;
use App\Domain\UserGroups\UserGroupRepository;
use Psr\Log\LoggerInterface;

abstract class UserGroupAction extends Action
{
    protected UserGroupRepository $userGroupRepository;

    public function __construct(LoggerInterface $logger, UserGroupRepository $userGroupRepository)
    {
        parent::__construct($logger);
        $this->userGroupRepository = $userGroupRepository;
    }
}
