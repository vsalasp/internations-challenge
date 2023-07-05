<?php

namespace App\Domain\ValueObjects\Common;

class StringObject
{
    public function __construct(
        private string $value
    ) {
        $this->validate();
    }

    protected function validate(): void
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
