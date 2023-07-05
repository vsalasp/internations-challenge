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

    public function __construct(?ID $id, Name $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getID(): ID
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->getValue(),
            'name' => $this->name->getValue(),
        ];
    }
}
