<?php

namespace App\Application\Actions\User;

use App\Domain\User\User;
use App\Domain\ValueObjects\Common\BoolObject;
use App\Domain\ValueObjects\Name;
use Psr\Http\Message\ResponseInterface as Response;

class AddUserAction extends UserAction
{
    protected function action(): Response
    {
        $body = $this->getFormData();
        $name = new Name($body['name']);

        $user = $this->userRepository->addUser(new User(null, $name));

        return $this->respondWithData($user);
    }
}
