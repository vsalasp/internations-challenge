<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\ValueObjects\Common\BoolObject;
use App\Domain\ValueObjects\ID;
use App\Domain\ValueObjects\Name;
use JsonSerializable;

class User implements JsonSerializable
{
    private ?ID $id;

    private Name $name;

    private BoolObject $isAdmin;

    public function __construct(?ID $id, Name $name, BoolObject $isAdmin)
    {
        $this->id = $id;
        $this->name = $name;
        $this->isAdmin = $isAdmin;
    }

    public function getID(): ID
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getIsAdmin(): BoolObject
    {
        return $this->isAdmin;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->getValue(),
            'name' => $this->name->getValue(),
            'isAdmin' => $this->isAdmin->isValue(),
        ];
    }
}
