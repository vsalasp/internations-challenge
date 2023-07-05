<?php

namespace App\Domain\ValueObjects;

use App\Domain\ValueObjects\Common\StringObject;

class Name extends StringObject
{
    protected function validate(): void
    {
        if (empty($this->getValue())) {
            throw new \InvalidArgumentException("Name should not be an empty string.");
        }
    }
}
