<?php

namespace App\Domain\UserGroups;

use App\Domain\User\User;
use App\Domain\ValueObjects\ID;
use App\Domain\ValueObjects\Name;
use App\Domain\ValueObjects\UserList;
use JsonSerializable;

class UserGroup implements JsonSerializable
{
    private ?ID $id;

    private Name $name;

    private UserList $members;

    public function __construct(?ID $id, Name $name, UserList $members)
    {
        $this->id = $id;
        $this->name = $name;
        $this->members = $members;
    }

    public function getMembers(): UserList
    {
        return $this->members;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getId(): ?ID
    {
        return $this->id;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->getValue(),
            'name' => $this->name->getValue(),
            'members' => $this->members,
        ];
    }
}
