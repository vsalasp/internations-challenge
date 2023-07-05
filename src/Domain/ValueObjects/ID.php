<?php

namespace App\Domain\ValueObjects;

use App\Domain\ValueObjects\Common\IntObject;
use InvalidArgumentException;

class ID extends IntObject
{
    protected function validate(): void
    {
        if (
            empty($this->getValue())
        ) {
            throw new InvalidArgumentException("Invalid ID");
        }
    }
}
