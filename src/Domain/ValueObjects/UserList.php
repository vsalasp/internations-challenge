<?php

namespace App\Domain\ValueObjects;

use App\Domain\User\User;
use InvalidArgumentException;
use JsonSerializable;

class UserList implements JsonSerializable
{
    /** @var User[]  */
    private array $users;

    public function __construct(array $users)
    {
        $this->users = $users;
        $this->validate();
    }

    public function getValue(): array
    {
        return $this->users;
    }

    private function validate(): void
    {
        foreach ($this->users as $user) {
            if (!$user instanceof User) {
                throw new InvalidArgumentException("Should be an array of users");
            }
        }
    }

    public function jsonSerialize(): array
    {
        return $this->users;
    }
}
