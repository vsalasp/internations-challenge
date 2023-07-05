<?php

namespace App\Domain\ValueObjects\Common;

class IntObject
{
    public function __construct(
        private int $value
    ) {
        $this->validate();
    }

    protected function validate(): void
    {
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): void
    {
        $this->value = $value;
    }
}
