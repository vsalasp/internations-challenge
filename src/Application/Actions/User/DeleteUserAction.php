<?php

namespace App\Application\Actions\User;

use App\Application\Actions\ActionPayload;
use App\Domain\ValueObjects\ID;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteUserAction extends UserAction
{

    protected function action(): Response
    {
        $userId = new ID($this->resolveArg('id'));

        $this->userRepository->deleteUser($userId);

        return $this->respond(new ActionPayload());
    }
}
